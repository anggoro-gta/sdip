<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}  class rekap extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('info');
        $this->load->model('m_rekap');
        $this->load->model('m_master');
        $this->info->cek_auth('3');
    }
    public function index()
    {
        $data['scripts'] = array( 
            "assets/smc/select2/js/select2.full.min.js", 
            "assets/smc/chartjs/Chart.min.js", 
            "assets/smc/treeTable.js", 
            "assets/smc/rekap/index.js" );
        $data['styles'] = array( "assets/smc/select2/css/select2.min.css" );
        $this->load->view('rekap/index.php', $data);
    }
    public function get_tree_elemen()
    {
        $data =  $this->m_rekap->get_elemen_parent($_POST['id_parent']);
        $resultData = array();
        if ($data) {
            $resultData = $this->get_elemen_tree_child($_POST['id_parent'], $_POST['unit'], $_POST['kategori'], isset($_POST['sub_unit'])?$_POST['sub_unit']:null);
        }
        $tahun_rekap = array();
        for ($i=date("Y");$i>date("Y")-3;$i--) {
            array_push($tahun_rekap, $i);
        }
        $result = array( "jumlah"=>count($resultData), "data"=>$resultData, "tahun"=>$tahun_rekap );
        echo json_encode($result);
    }
    public function get_elemen_tree_child($id_parent, $unit, $kategori=null, $sub_unit=null)
    {
        $result =  $this->m_rekap->get_elemen_parent($id_parent);
        $return = array();
        if ($result!=null) {
            foreach ($result as $key => $value) {
                $value->child = $this->get_elemen_tree_child($value->id_elemen, $unit, $kategori, $sub_unit);
                if ($value->child!=null && $value->has_unit_elemen==="1") {
                    $value->has_unit_elemen = "0";
                }
                if ($value->child==null) { /* Daun */ if ($kategori == "kabupaten") { /* Ambil 3 tahun terakhir dari masing2 kecamatan */ if ($value->has_unit_elemen==="1") {
                    $value->child = array();
                    $kecamatan =  $this->m_rekap->get_unit(null, "kecamatan");
                    foreach ($kecamatan as $key => $kec) {
                        if ($unit==null || $unit === $kec->id_unit) {
                            $element_kecamatan = array( 'tahun' => array(), 'id_elemen' => $value->id_elemen . "." . ($key + 1), 'keterangan' => "Kecamatan " . $kec->nama, 'elemen'=> $value->keterangan, 'singkat' => $value->id_elemen . "." . ($key + 1), 'kategori' => 'elemen', 'urut' => ($key + 1), 'id_parent' => $value->id_elemen, 'satuan' => $value->satuan );
                            $desa =  $this->m_rekap->get_unit(null, null, $kec->id_unit);
                            $query_in_desa = "'',";
                            if ($desa) {
                                foreach ($desa as $des) {
                                    $query_in_desa .= "'" . $des->id_unit . "',";
                                }
                            }
                            $query_in_desa = substr($query_in_desa, 0, strlen($query_in_desa) - 1);
                            for ($i = date("Y"); $i > date("Y") - 3; $i--) {
                                $element_kecamatan['tahun'][$i] =  $this->m_rekap->get_rekap_jumlah($i, $value->id_elemen, $query_in_desa);
                            }
                            array_push($value->child, $element_kecamatan);
                        }
                    }
                    array_push($return, $value);
                }
                } elseif ($kategori === "kecamatan") {
                    if ($value->has_unit_elemen==="1") { /* Ambil 3 Tahun dari terakhir masing2 desa */ $value->child = array();
                        $desa =  $this->m_rekap->get_unit(null, null, $unit);
                        $urut = 1;
                        foreach ($desa as $key=>$des) {
                            if ($sub_unit==null || $sub_unit === $des->id_unit) {
                                $element_desa = array( 'tahun' => array(), 'id_elemen' => $value->id_elemen . "." . ($key + 1), 'id_unit' => $des->id_unit, 'keterangan' => "Desa " . $des->nama, 'elemen'=> $value->keterangan, 'singkat' => $urut, 'kategori' => 'elemen', 'urut' => $urut, 'id_parent' => $value->id_elemen, 'satuan' => $value->satuan );
                                for ($i = date("Y"); $i > date("Y") - 3; $i--) {
                                    $element_desa['tahun'][$i] =  $this->m_rekap->get_rekap($i, $value->id_elemen, $des->id_unit);
                                }
                                $urut++;
                                array_push($value->child, $element_desa);
                            }
                        }
                        array_push($return, $value);
                    }
                } elseif ($kategori==="skpd") { /* Ambil 3 tahun terakhir dari masing2 kecamatan */ if ($value->has_unit_elemen==="1") {
                    $value->child = array();
                    $kecamatan =  $this->m_rekap->get_unit(null, "kecamatan");
                    foreach ($kecamatan as $key => $kec) {
                        $element_kecamatan = array( 'tahun' => array(), 'id_elemen' => $value->id_elemen . "." . ($key + 1), 'keterangan' => "Kecamatan " . $kec->nama, 'elemen'=> $value->keterangan, 'singkat' => $value->id_elemen . "." . ($key + 1), 'kategori' => 'elemen', 'urut' => ($key + 1), 'id_parent' => $value->id_elemen, 'satuan' => $value->satuan );
                        $desa =  $this->m_rekap->get_unit(null, null, $kec->id_unit);
                        $query_in_desa = "'',";
                        if ($desa) {
                            foreach ($desa as $des) {
                                $query_in_desa .= "'" . $des->id_unit . "',";
                            }
                        }
                        $query_in_desa = substr($query_in_desa, 0, strlen($query_in_desa) - 1);
                        for ($i = date("Y"); $i > date("Y") - 3; $i--) {
                            $element_kecamatan['tahun'][$i] =  $this->m_rekap->get_rekap_jumlah($i, $value->id_elemen, $query_in_desa);
                        }
                        array_push($value->child, $element_kecamatan);
                    }
                    array_push($return, $value);
                } elseif ($value->has_unit_elemen==="0") { /* Ambil 3 Tahun terakhir */ $value->tahun = array();
                    for ($i=date("Y");$i>date("Y")-3;$i--) {
                        $value->tahun[$i] =  $this->m_rekap->get_rekap($i, $value->id_elemen, null, $unit);
                    }
                    array_push($return, $value);
                }
                }
                } else { /* cabang ada DAUN-NYA */ array_push($return, $value);
                }
            }
            if (count($return)==0) {
                return null;
            } else {
                return $return;
            }
        }
        return $result;
    }
    public function simpan_rekap()
    {
        
        $data = array( 
            "id_unit"=>$_POST['id_unit'], 
            "id_elemen"=>$_POST['id_elemen'],
            "periode"=>$_POST['periode'], 
            "jumlah"=>$_POST['jumlah'], 
            "jenis_data"=>$_POST['jenis_data'], 
            // 'id_user'=>$this->coms->authenticatedUser->id, 
            'last_update'=>date('Y-m-d H:i:s'), 
            'is_valid'=>(isset($_POST['is_valid']) ? "1" : "0") );
        $id_skpd = '';
        if (isset($_POST['id_skpd'])) {
            $id_skpd = $_POST['id_skpd'];
                $data['id_skpd'] = $_POST['id_skpd'];
            }
        if (isset($_POST['satuan'])) {
            $data['satuan'] = $_POST['satuan'];
        }
        if (!empty($_POST['id_rekap'])) {
            $this->m_master->update("smc_rekap", $data, array("id_rekap"=>$_POST['id_rekap']));
        } else {
            $data["id_rekap"] = $_POST['periode']."@".$_POST['id_unit']."@".$_POST['id_elemen']."@".($id_skpd==""?"0":$id_skpd);
            $data['created_date'] = date('Y-m-d H:i:s');
            $this->m_master->replace("smc_rekap", $data);
        }
    }
}
