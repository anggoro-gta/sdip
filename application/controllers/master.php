<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}  class master extends CI_Controller
{
    //TODO: Semua method di masterx harus di-merge kesini

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user');
        $this->load->model('m_master');
        $this->load->model('m_rekap');
        $this->load->model('m_survei');
    }

    // get koordinat dari kecamatan
    public function get_kecamatan_koordinat()
    {
        $data['type']="FeatureCollection";
        $data['features']=array();
        $kecamatan =  $this->m_master->get_unit(null, "kecamatan", (isset($_POST["id_parent"]) ? $_POST["id_parent"] : null));
        foreach ($kecamatan as $kec) {
            $data['features'][] = array( 
                "type"=>"Feature", 
                "geometry"=>array( 
                    "type"=>"Polygon", 
                    "coordinates"=>$kec->koordinat ? json_decode($kec->koordinat) : [] ), 
                    "properties"=>array( 
                        "id_unit"=>$kec->id_unit, 
                        "nama"=>$kec->nama, 
                        "kategori"=>$kec->kategori, 
                        "last_update"=>$kec->last_update 
                        ) 
                    );
        }
        echo json_encode($data);
    }

    public function get_desa_koordinat()
    {
        $data['type']="FeatureCollection";
        $data['features']=array();
        $desa =  $this->m_master->get_unit(null, "desa", (isset($_POST["id_parent"]) ? $_POST["id_parent"] : null));
        foreach ($desa as $des) {
            $data['features'][] = array( "type"=>"Feature", "geometry"=>array( "type"=>"Polygon", "coordinates"=>$des->koordinat ? json_decode($des->koordinat) : [] ), "properties"=>array( "id_unit"=>$des->id_unit, "nama"=>$des->nama, "kategori"=>$des->kategori, "last_update"=>$des->last_update, ) );
        }
        echo json_encode($data);
    }
    public function get_select2_unit()
    {
        $query = isset($_GET['q']) ? $_GET['q']:"";
        $result =  $this->m_master->get_select2_unit($query, (isset($_GET['kategori']) ? $_GET['kategori'] : ""), (isset($_GET["id_parent"]) ? $_GET["id_parent"] : null));
        $result = $result?$result:array();
        echo json_encode($result?$result:array());
    }
    public function get_tree_unit()
    {
        $page = isset($_POST['page']) ?  $_POST['page'] : 1;
        $limit = isset($_POST['rowPerPage']) ?  $_POST['rowPerPage'] : 10;
        $offset = ($page-1)*$limit;
        $data =  $this->m_master->get_unit_parent("0");
        if ($data == null) {
            $data = array();
        }
        $splice = array_slice($data, $offset, $limit);
        foreach ($splice as $key => $value) {
            $value->child=$this->get_unit_child($value->id_unit);
        }
        $result = array("jumlah"=>count($data), "data"=>$splice);
        echo json_encode($result);
    }
    public function get_unit_child($parent_id)
    {
        $result =  $this->m_master->get_unit_parent($parent_id);
        if ($result) {
            foreach ($result as $key => $value) {
                $value->child = $this->get_unit_child($value->id_unit);
            }
        }
        return $result;
    }
    public function get_elemen_home()
    { /* UNIT */ $unit =  $this->m_master->get_unit($_POST['id_unit']);
        $rekapDesa = array();
        $rekapSkPD = array();
        if ($unit->kategori=="kabupaten" || $unit->kategori=="kota") { /* SKPD */ $skpd =  $this->m_master->get_unit(null, "skpd");
            foreach ($skpd as $sk) {
                array_push($rekapSkPD, $sk->id_unit);
            }
            $kecamatan =  $this->m_master->get_unit_parent($_POST['id_unit']);
            foreach ($kecamatan as $kec) {
                $desa =  $this->m_master->get_unit_parent($kec->id_unit);
                if ($desa) {
                    foreach ($desa as $des) {
                        array_push($rekapDesa, $des->id_unit);
                    }
                }
            }
        } elseif ($unit->kategori=="kecamatan") {
            $desa =  $this->m_master->get_unit_parent($_POST['id_unit']);
            foreach ($desa as $des) {
                array_push($rekapDesa, $des->id_unit);
            }
        } elseif ($unit->kategori=="desa") {
            array_push($rekapDesa, $_POST['id_unit']);
        } /* looping elemen */ $report = array();
        $elemen =  $this->m_master->get_elemen(null, null, null, null, "1");
        $rekap_unit = "'".implode("','", $rekapDesa)."'";
        $rekap_skpd = "'".implode("','", $rekapSkPD)."'";
        if ($elemen==null) {
            $elemen = array();
        }
        foreach ($elemen as $value) {
            if ($value->has_unit_elemen==="1" || ($value->has_unit_elemen==="0" && $unit->kategori=="kabupaten")) {
                $splitElemenUsur = explode(".", $value->id_elemen);
                $unsur =  $this->m_master->get_elemen($splitElemenUsur[0].".".$splitElemenUsur[1]);
                $value->unsur = $unsur->keterangan;
                $value->unsur_singkat = $unsur->singkat_keterangan;
                $value->id_unsur = $unsur->id_elemen;
                $value->warna = $unsur->warna;
                $value->jumlah = $this->get_rekap_jumlah($_POST['periode'], $value, $unit, $rekap_unit, $rekap_skpd);
                array_push($report, $value);
            }
        }
        echo json_encode($report);
    }
    private function get_rekap_jumlah($periode, $elemen, $unit, $rekap_unit, $rekap_skpd)
    {
        $child =  $this->m_rekap->get_elemen_parent($elemen->id_elemen);
        $result_jumlah = 0;
        if ($child==null) {
            if ($elemen->has_unit_elemen==="1") {
                $jumlah =  $this->m_rekap->get_rekap_jumlah($_POST['periode'], $elemen->id_elemen, $rekap_unit);
                $result_jumlah += ($jumlah==null ? 0 : $jumlah->jumlah);
            }
            if ($elemen->has_unit_elemen==="0" && $unit->kategori=="kabupaten") {
                $jumlah =  $this->m_rekap->get_rekap_jumlah($_POST['periode'], $elemen->id_elemen, null, $rekap_skpd);
                $result_jumlah += ($jumlah==null ? 0 : $jumlah->jumlah);
            }
        } else {
            foreach ($child as $key => $value) {
                $result_jumlah += $this->get_rekap_jumlah($periode, $value, $unit, $rekap_unit, $rekap_skpd);
            }
        }
        return $result_jumlah;
    }
    public function get_tree_elemen()
    {
        $page = isset($_POST['page']) ?  $_POST['page'] : 1;
        $limit = isset($_POST['rowPerPage']) ?  $_POST['rowPerPage'] : 10;
        $offset = ($page-1)*$limit;
        $data =  $this->m_master->get_elemen_parent("0", null, null);
        if ($data == null) {
            $data = array();
        }
        $splice = array_slice($data, $offset, $limit);
        foreach ($splice as $key => $value) {
            $value->child=$this->get_elemen_child($value->id_elemen);
        }
        $result = array("jumlah"=>count($data), "data"=>$splice);
        echo json_encode($result);
    }
    public function get_elemen_child($id_parent)
    {
        $result =  $this->m_master->get_elemen_parent($id_parent, null, null);
        if ($result) {
            foreach ($result as $key => $value) {
                $value->child = $this->get_elemen_child($value->id_elemen);
            }
        }
        return $result;
    }
    public function get_select2_elemen()
    {
        $query = isset($_GET['q']) ? $_GET['q']:"";
        $result =  $this->m_master->get_select2_elemen($query, isset($_GET['kategori']) ? $_GET['kategori'] : null, isset($_GET['id_parent']) ? $_GET['id_parent'] : null);
        $result = $result?$result:array();
        foreach ($result as $item) {
            $item->next_urut =   $this->m_master->get_next_urut_elemen($item->id);
        }
        echo json_encode($result);
    }
    /* USER */
    public function users()
    {
        $data["smenu"]='master/users';
        $data['styles'] = array( "assets/smc/plugins/select2/select2.min.css" );
        $data['scripts'] = array( "assets/smc/plugins/select2/select2.min.js", "assets/smc/treeTable.js", "assets/smc/pagination.js", "assets/smc/setting/users.js" );
        $this->load->view('setting/users.php', $data);
    }
    public function get_users()
    {
        $page = isset($_POST['page']) ?  $_POST['page'] : 1;
        $limit = isset($_POST['rowPerPage']) ?  $_POST['rowPerPage'] : 10;
        $offset = ($page-1)*$limit;
        $data =  $this->m_master->get_user();
        if ($data == null) {
            $data = array();
        }
        $splice = array_slice($data, $offset, $limit);
        $result = array("jumlah"=>count($data), "data"=>$splice);
        echo json_encode($result);
    }
    public function simpan_user()
    {
        $this->load->model('info');
        $id_user = "";
        $id_user = $this->info->get_next_id("smc_user", "id_user");
        if (isset($_POST['id_user'])) {
            $id_user = $_POST['id_user'];
        }
        $data = array( "id_role"=>$_POST['id_role'], "id_unit_kerja"=> $_POST['kategori_unit']=="desa" ? $_POST['id_unit'] : $_POST['id_unit_kerja'], "nama"=>$_POST['nama'], "email"=>$_POST['email'], "user_name"=>$_POST['email'], "no_ktp"=>$_POST['no_ktp'], "no_hp"=>$_POST['no_hp'], "no_telp"=>$_POST['no_telp'], "alamat"=>$_POST['alamat'], "is_aktif"=>isset($_POST['is_aktif'])?1:0, 'last_update'=>date('Y-m-d H:i:s') );
        if (!file_exists($_FILES['file_foto']['tmp_name']) || !is_uploaded_file($_FILES['file_foto']['tmp_name'])) {
            $this->simpan_db_user($data, $id_user);
        } else {
            $target_dir = "assets/user/".$id_user."/";
            $target_name = date("YmdHis");
            $imageFileType = end((explode(".", $_FILES["file_foto"]["name"])));
            $target_file = $target_dir . $target_name. "." . $imageFileType;
            $uploadOk = 1;
            $message = "";
            if ($_FILES["file_foto"]["size"] > 500000) {
                $message .="Sorry, your file is too large.";
                $uploadOk = 0;
            }
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $message .= "Sorry, your file is ".$imageFileType." not allowed only JPG, JPEG, PNG & GIF files.";
                $uploadOk = 0;
            }
            if ($uploadOk === 0) {
                echo json_encode(array("status"=>$uploadOk, "message"=>$message));
                $this->simpan_db_user($data, $id_user);
            } else {
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                if (move_uploaded_file($_FILES["file_foto"]["tmp_name"], $target_file)) {
                    $data["foto"] = $target_file;
                    echo json_encode(array("status"=>$uploadOk, "path"=>$target_file));
                    $this->simpan_db_user($data, $id_user);
                } else {
                    echo json_encode(array("status"=>0, "message"=>"Sorry, there was an error uploading your file."));
                    $this->simpan_db_user($data, $id_user);
                }
            }
        }
    }
    private function simpan_db_user($data, $id_user)
    {
        $this->load->model('user');
        if (isset($_POST['id_user'])) {
            $user =  $this->m_master->get_user($_POST['id_user']);
            if ($_POST['password'] != $user->password && $muser->verify($_POST['password'], $user->password) != $user->password) {
                $data['password'] = $this->user->generateHash($_POST['password']);
            }
            $this->m_master->update("smc_user", $data, array("id_user"=>$id_user));
        } else {
            $data["id_user"] = $id_user;
            $data['password'] = $this->user->generateHash($_POST['password']);
            $this->m_master->replace("smc_user", $data);
        }
    }
}
