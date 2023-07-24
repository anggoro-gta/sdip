<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}  class survei extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('info');
        $this->load->model('m_survei');
        $this->load->model('m_master');
        $this->info->cek_auth('3');
    }

    public function index()
    {
        $data['scripts'] = array( 
            "assets/smc/select2/js/select2.full.min.js", 
            "assets/smc/pagination.js",
            "assets/kediri/js/manage/manage_survei.js"
        );
        $data['scripts_post'] = array("https://maps.googleapis.com/maps/api/js?key=AIzaSyD0xRNnRByY45XTL3vL0M34ywwn5L2eF2k&callback=initMap" );
        $data['styles'] = array("assets/smc/select2/css/select2.min.css");
        $this->load->view('survei/survei.php', $data);
    }   

    // menyimpan data survei
    public function simpan_survei()
    {
        if (!isset($_POST['keterangan_elemen']) || $_POST['keterangan_elemen'] == null) {
            echo 'Data is not processed!'; 
            exit();
        }
        
        $id_elemen = isset($_POST['id_elemen'])?$_POST['id_elemen']:null;

        $data = array( 
            "id_unit"=>$_POST['id_unit'], 
            "id_elemen"=>$_POST['id_elemen'], 
            "id_skpd"=>$_POST['id_skpd'], 
            "tgl_survey"=>$_POST['tgl_survey'], 
            "nama_surveyor"=>$_POST['nama_surveyor'],
            "nama_ppl"=>$_POST['nama_ppl'], 
            "nama_narasumber"=>$_POST['nama_narasumber'],             
            "lat"=>isset($_POST['lat'])?$_POST['lat']:"", 
            "lon"=>isset($_POST['lon'])?$_POST['lon']:"", 
            "telp"=>$_POST['telp'], 
            "is_potensial"=>isset($_POST['is_potensial'])?"1":"0", 
            "is_valid"=>isset($_POST['is_valid'])?"1":"0",
            "catatan"=>$_POST['catatan'], 
            "id_user"=>$this->session->userdata('smc_user_id')
        );

        if (isset($_POST['id_desa'])) {
            $data["id_desa"]= $_POST['id_desa'];
        }

        $status = FALSE;
        if (isset($_POST['id_survey'])) {
            $data["id_survey"] = $_POST['id_survey'];

            // update survey
            $status = $this->m_master->update("smc_survey", $data, array("id_survey"=>$_POST['id_survey']));

            // update survey field value
            $field_values = Globalsdb::get_survey_field_value($_POST['id_survey']);
            foreach($field_values as $fv){
                $post_val = isset($_POST[$fv->key_field])? $_POST[$fv->key_field]: null;
                
                if ($post_val != $fv->value){                    
                    $status &= $this->m_master->update("smc_survey_field_value", array("value"=>$post_val), array("id_survey_field"=> $fv->id_survey_field));          
                }
            }
        } else { 
            // tambahkan data ke table survey
            $data["id_survey"] = $this->info->get_next_id("smc_survey", "id_survey");
            $status = $this->m_master->replace("smc_survey", $data);

            // tambahkan data ke table smc_survey_field_value
            $fields = Globalsdb::get_fields($id_elemen);

            $survey_field_data = array();
            $i=1;
            foreach($fields as $f){
                $value = $_POST[$f->key_field];
                $id_field = $f->id_field;
                $field_value = array(
                    'id_survey_field' => sprintf("%s%s", $data["id_survey"], substr($id_field, strrpos($id_field, '.'))),
                    'id_survey' => $data["id_survey"] ,
                    'id_field' => $id_field ,
                    'value' => isset($value)? $value: null
                );                
                $i++;
                
                $status &= $this->m_master->replace("smc_survey_field_value", $field_value);
            }
        }

        echo json_encode(array(
            "id_survey"=>$data["id_survey"],
            "status"=>$status
        ));
    }
    
    public function hapus_survei()
    {
        $this->m_survei->hapus_survey($_POST['id_survey']);
        $this->m_survei->hapus_detail_survey(null, $_POST['id_survey']);
    }

    /* Detail Survey */
    public function detail($id_survey=null)
    {
        if ($id_survey==null) {
            redirect("survei");
            exit();
        }

        $survei_data =  $this->m_survei->get_survei($id_survey);
        if ($survei_data==null) {
            redirect("survei");
            exit();
        }

        $survei_data->field_values = Globalsdb::get_survey_field_value($survei_data->id_survey);
        $data['survei']= $survei_data;
        $data['scripts'] = array( 
            "assets/smc/select2/js/select2.full.min.js", 
            "assets/kediri/js/survei/detail.js", 
            "assets/smc/pagination.js"
        );
        $data['styles'] = array( "assets/smc/select2/css/select2.min.css" );
        
        $this->load->view('survei/detail.php', $data);
    }
    
    public function get_detail_survei()
    {
        $id_survey = isset($_POST['id_survey'])? $_POST['id_survey']: 0;
        $search = isset($_POST['search'])? $_POST['search']: null;
        $page = isset($_POST['page']) ?  $_POST['page'] : 1;
        $limit = isset($_POST['rowPerPage']) ?  $_POST['rowPerPage'] : 10;
        $offset = ($page-1)*$limit;
        
        $count = $this->m_survei->count_detail_survei($id_survey, $search);
        // print_r($count);
        $data =  $this->m_survei->get_detail_survei($id_survey, $search, $offset, $limit);
        if ($data == null) {
            $data = array();
        }
        
        // $dataAsli = array();
        // foreach($data as $d){
        // 	$field_value_list = $this->m_survei->get_survei_detail_field_value($d->id_survey_detail);
        // 	if (!empty($field_value_list)){
        // 		foreach($field_value_list as $f){             
        // 			$d->{$f->key_field}= $f->value;
        // 		}
        // 		array_push($dataAsli, $d);
        // 	}
        // }
        // $data = $dataAsli;


        foreach($data as $d){
            $field_value_list = $this->m_survei->get_survei_detail_field_value($d->id_survey_detail);
          // var_dump($field_value_list);
            if (empty($field_value_list))continue;
            foreach($field_value_list as $f){             
                $d->{$f->key_field}= $f->value;
            }
        }
        $result = array("jumlah"=> $count, "data"=> $data);
        echo json_encode($result);
    }

    public function simpan_detail_survei()
    {
        if (!isset($_POST['keterangan_elemen']) || $_POST['keterangan_elemen'] == null) {
            echo 'Data is not processed!'; 
            exit();
        }
        
        $keterangan_elemen = strtolower($_POST['keterangan_elemen']);
        
        $data = array( 
            "id_survey"=> $_POST['id_survey'], 
            "id_user"=> $this->session->userdata('smc_user_id')
        );       
        
        if (isset($_POST['id_elemen'])) {
            $data['id_elemen'] = $_POST['id_elemen'];
        }

        if (isset($_POST['id_survey_detail'])) {
            $this->m_master->update("smc_survey_detail", $data, array("id_survey_detail"=>$_POST['id_survey_detail']));

            // update survey detail field value
            $field_values = Globalsdb::get_survey_detail_field_value($_POST['id_survey_detail']);
            
            foreach($field_values as $fv){
                $post_val = isset($_POST[$fv->key_field])? $_POST[$fv->key_field]: null;
                
                if ($post_val != $fv->value){                    
                    $this->m_master->update("smc_survey_detail_field_value", 
                        array("value"=>$post_val), 
                        array("id_survey_detail_field"=> $fv->id_survey_detail_field));          
                }
            }
        } else {
            // tambahkan data ke survey_detail
            $data["id_survey_detail"] = $this->info->get_next_id("smc_survey_detail", "id_survey_detail");            
            $this->m_master->replace("smc_survey_detail", $data);

            // tambahkan data ke smc_survey_detail_field_value
            $survei_detail_elemen = array();
            $fields = Globalsdb::get_detail_fields($keterangan_elemen); 
            //var_dump($fields);
            foreach($fields as $f){
                //var_dump($f->key_field);
                $value = isset($_POST[$f->key_field])?$_POST[$f->key_field]:null;
                
                $survei_detail_data = array(
                    'id_survey_detail_field' => $this->info->get_next_id("smc_survey_detail_field_value", "id_survey_detail_field"),
                    'id_survey_detail' => $data["id_survey_detail"],
                    'id_field' => $f->id_field,
                    'value' => $value,
                    'created_date' => date("Y-m-d H:i:s"),
                    'last_update' => date("Y-m-d H:i:s")
                );
                var_dump($survei_detail_data);
                $this->m_master->replace("smc_survey_detail_field_value", $survei_detail_data);
            }
        }
    }
    public function hapus_detail_survei()
    {
        $this->m_survei->hapus_detail_survey($_POST['id_detail']);
    } 
    
    /* rekap */ 
    public function rekap()
    {
        $data['scripts'] = array( 
            "assets/smc/select2/js/select2.full.min.js", 
            "assets/smc/chartjs/Chart.min.js", 
            "assets/smc/treeTable.js", 
            "assets/smc/survei/rekap.js" 
        );
        $data['scripts_post'] = array( "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js", "https://maps.googleapis.com/maps/api/js?key=AIzaSyD0xRNnRByY45XTL3vL0M34ywwn5L2eF2k&callback=initMap" );
        $data['styles'] = array( "assets/smc/select2/css/select2.min.css" );
        $data['unsurs'] =  $this->m_survei->get_elemen_survei();
        $this->load->view('survei/rekap.php', $data);
    }
    public function get_lokasi_kelompok()
    {
        $kelompok =  $this->m_survei->get_survei(null, null, null, $_POST['unsur'], $_POST['periode']);
        echo json_encode($kelompok);
    }
    public function get_rekap()
    {
        $rekap = array(); /* PERTANIAN */ $rekap["pertanian"] = array();
        $rekap["pertanian"][] = array( "id"=>"1", "elemen"=>"Luas Lahan", "satuan"=>"Ha", "tahun"=> $this->get_rekap_per_5th("pertanian", $_POST['kecamatan'], "Luas Lahan") );
        $rekap["pertanian"][] = array( "id"=>"2", "elemen"=>"Pola Tanam", "tahun"=> $this->get_rekap_per_5th(), "child"=>array( array( "id"=>"2.1", "elemen"=>"Pertanian ditanam padi", "tahun"=> $this->get_rekap_per_5th(), "child"=>array( array( "id"=>"2.1.1", "elemen"=>"1x Setahun", "satuan"=>"Ha", "tahun"=> $this->get_rekap_per_5th("pertanian", $_POST['kecamatan'], "Padi 1x Setahun"), ), array( "id"=>"2.1.2", "elemen"=>"2x Setahun", "satuan"=>"Ha", "tahun"=> $this->get_rekap_per_5th("pertanian", $_POST['kecamatan'], "Padi 2x Setahun"), ), array( "id"=>"2.1.3", "elemen"=>"3x Setahun", "satuan"=>"Ha", "tahun"=> $this->get_rekap_per_5th("pertanian", $_POST['kecamatan'], "Padi 3x Setahun"), ) ), ), array( "id"=>"2.2", "elemen"=>"Pertanian ditanam non-padi", "tahun"=> $this->get_rekap_per_5th(), "child"=>array( array( "id"=>"2.2.1", "elemen"=>"1x Setahun", "satuan"=>"Ha", "tahun"=> $this->get_rekap_per_5th("pertanian", $_POST['kecamatan'], "Nonpadi 1x Setahun"), ), array( "id"=>"2.2.2", "elemen"=>"2x Setahun", "satuan"=>"Ha", "tahun"=> $this->get_rekap_per_5th("pertanian", $_POST['kecamatan'], "Nonpadi 2x Setahun"), ), array( "id"=>"2.2.3", "elemen"=>"3x Setahun", "satuan"=>"Ha", "tahun"=> $this->get_rekap_per_5th("pertanian", $_POST['kecamatan'], "Nonpadi 3x Setahun"), ) ), ) ), );
        $rekap["pertanian"][] = array( "id"=>"3", "elemen"=>"Jumlah Produksi", "satuan"=>"Ton", "tahun"=> $this->get_rekap_per_5th("pertanian", $_POST['kecamatan'], "Jumlah Produksi"), );
        $jenis_potensi_utama = explode(",", $this->m_survei->get_jenis_potensi_utama("pertanian", $_POST['kecamatan'])->value);
        $child = array();
        foreach ($jenis_potensi_utama as $key => $value) {
            if ($value=="") {
                continue;
            }
            $child[] = array( "id"=>"4.".($key+1), "elemen"=>$value, "satuan"=>"Ton", "tahun"=> $this->get_rekap_per_5th("pertanian", $_POST['kecamatan'], "Potensi Utama", $value) );
        }
        $rekap["pertanian"][] = array( "id"=>"4", "elemen"=>"Potensi Utama", "tahun"=> $this->get_rekap_per_5th(), "child"=>$child ); /* PERKEBUNAN */ $rekap["perkebunan"] = array();
        $rekap["perkebunan"][] = array( "id"=>"1", "elemen"=>"Luas Lahan", "satuan"=>"Ha", "tahun"=> $this->get_rekap_per_5th("perkebunan", $_POST['kecamatan'], "Luas Lahan") );
        $rekap["perkebunan"][] = array( "id"=>"2", "elemen"=>"Jumlah Produksi", "satuan"=>"Ton", "tahun"=> $this->get_rekap_per_5th("perkebunan", $_POST['kecamatan'], "Jumlah Produksi") ); /* PETERNAKAN */ $rekap["peternakan"] = array();
        $child = array();
        $jenis_ternak =  $this->m_survei->get_jenis_ternak("peternakan", $_POST['kecamatan']);
        foreach ($jenis_ternak as $key => $value) {
            if ($value->ternak) {
                $child[] = array( "id"=>"1.".($key+1), "elemen"=>$value->ternak, "satuan"=>"Ekor", "tahun"=> $this->get_rekap_per_5th("peternakan", $_POST['kecamatan'], "Jumlah Ternak", $value->ternak) );
            }
        }
        $rekap["peternakan"][] = array( "id"=>"1", "elemen"=>"Jumlah Produksi", "tahun"=> $this->get_rekap_per_5th(), "child"=>$child ); /* PERINAKAN */ $rekap["perikanan"] = array();
        $kategori_produksi =  $this->m_survei->get_kategori_produksi("perikanan", $_POST['kecamatan']);
        $child = array();
        if ($kategori_produksi) {
            foreach ($kategori_produksi as $key => $value) {
                $jenis_perikanan =  $this->m_survei->get_jenis_ternak("perikanan", $_POST['kecamatan'], $value->value);
                $innerChild = array();
                if ($jenis_perikanan) {
                    foreach ($jenis_perikanan as $innerKey => $innerValue) {
                        if ($innerValue->ternak) {
                            $innerChild[] = array( "id"=>"1.".($key+1).".".($innerKey+1), "elemen"=>$innerValue->ternak, "satuan"=>$value->value=="benih"?"Ekor":"Ton", "tahun"=> $this->get_rekap_per_5th("perikanan", $_POST['kecamatan'], "Jumlah Ternak", $innerValue->ternak, $value->value) );
                        }
                    }
                }
                $child[]= array( "id"=>"1.".($key+1), "elemen"=>$value->value, "satuan"=>"Ton", "tahun"=> $this->get_rekap_per_5th(), "child"=>$innerChild );
            }
        } else {
            $jenis_perikanan =  $this->m_survei->get_jenis_ternak("perikanan", $_POST['kecamatan']);
            if ($jenis_perikanan) {
                foreach ($jenis_perikanan as $key => $value) {
                    if ($value->ternak) {
                        $child[] = array( "id"=>"1.".($key+1), "elemen"=>$value->ternak, "satuan"=>"Ton", "tahun"=> $this->get_rekap_per_5th("perikanan", $_POST['kecamatan'], "Jumlah Ternak", $value->ternak) );
                    }
                }
            }
        }
        $rekap["perikanan"][] = array( "id"=>"1", "elemen"=>"Jumlah Produksi", "tahun"=> $this->get_rekap_per_5th(), "child"=>$child );
        echo json_encode($rekap);
    }
    public function get_rekap_per_5th($unsur=null, $id_kecamatan=null, $param=null, $subparam=null, $subsubparam=null, $tahun=null)
    {
        if ($tahun==null) {
            $tahun=date("Y");
        }
        if ($unsur==null || $id_kecamatan==null || $param==null || $param=="") {
            $result = array();
            for ($i=$tahun;$i>$tahun-5;$i--) {
                $result[$i] = null;
            }
            return $result;
        }
        $result = array();
        for ($i=$tahun;$i>$tahun-5;$i--) {
            switch ($param) { case 'Luas Lahan': $result[$i] =  $this->m_survei->get_luas_lahan($i, $unsur, $id_kecamatan)->value; break; case 'Padi 1x Setahun': $result[$i] =  $this->m_survei->get_tanam_1x_setahun($i, $unsur, $id_kecamatan)->value; break; case 'Padi 2x Setahun': $result[$i] =  $this->m_survei->get_tanam_2x_setahun($i, $unsur, $id_kecamatan)->value; break; case 'Padi 3x Setahun': $result[$i] =  $this->m_survei->get_tanam_3x_setahun($i, $unsur, $id_kecamatan)->value; break; case 'Nonpadi 1x Setahun': $result[$i] =  $this->m_survei->get_tanam_1x_setahun($i, $unsur, $id_kecamatan, "nonpadi")->value; break; case 'Nonpadi 2x Setahun': $result[$i] =  $this->m_survei->get_tanam_2x_setahun($i, $unsur, $id_kecamatan, "nonpadi")->value; break; case 'Nonpadi 3x Setahun': $result[$i] =  $this->m_survei->get_tanam_3x_setahun($i, $unsur, $id_kecamatan, "nonpadi")->value; break; case 'Jumlah Produksi': $result[$i] =  $this->m_survei->get_jumlah_produksi($i, $unsur, $id_kecamatan)->value; break; case 'Potensi Utama': $result[$i] =  $this->m_survei->get_jumlah_potensi_utama($i, $unsur, $id_kecamatan, $subparam)->value; break; case 'Jumlah Ternak': $result[$i] =  $this->m_survei->get_jumlah_ternak($i, $unsur, $id_kecamatan, $subparam, $subsubparam)->value; break; default: $result[$i] = null; break; }
        }
        return $result;
    }
    public function ekspor_survey($type='1')
    {
        require_once(APPPATH.'libraries/PHPExcel/PHPExcel.php');
        $unit = null;
        $elemen = null;
        if ($_POST['id_unit']) {
            $unit =  $this->m_master->get_unit($_POST['id_unit']);
        }
        if ($_POST['id_elemen']) {
            $elemen =  $this->m_master->get_elemen($_POST['id_elemen']);
        }
        $data =  $this->m_survei->get_survei(null, $_POST['keyword'], $_POST['id_unit'], $_POST['id_elemen']);
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("DATA KEDIRI") ->setLastModifiedBy("DATA KEDIRI") ->setTitle("DATA SURVEY") ->setSubject("DATA SURVEY") ->setDescription("DATA SURVEY") ->setKeywords("DATA SURVEY") ->setCategory("DATA SURVEY");
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->mergeCells('A1:N1');
        $sheet->mergeCells('A2:N2');
        $sheet->mergeCells('A3:N3');
        $sheet->setCellValue("A2", "DATA SURVEY KAB.KEDIRI");
        $keterangan = "";
        if ($_POST['id_unit']) {
            $keterangan .= "Kecamatan : ".$unit->nama;
        }
        if ($_POST['id_elemen']) {
            if ($_POST['id_unit']) {
                $keterangan .= " , ";
            }
            $keterangan .= "Unsur : ".$elemen->keterangan;
        }
        $sheet->setCellValue("A3", $keterangan);
        $sheet->setCellValue('A5', "No");
        $sheet->setCellValue('B5', "Unsur");
        $sheet->setCellValue('C5', "Kecamatan");
        $sheet->setCellValue('D5', "Desa");
        $sheet->setCellValue('E5', "SKPD");
        $sheet->setCellValue('F5', "Tgl Survei");
        $sheet->setCellValue('G5', "Nama Surveyor");
        $sheet->setCellValue('H5', "Nama PPL");
        $sheet->setCellValue('I5', "Nama NaraSumber");
        $sheet->setCellValue('J5', "No Telp");
        $sheet->setCellValue('K5', "Nama Gapotan");
        $sheet->setCellValue('L5', "Nama Kelompok Tani");
        $sheet->setCellValue('M5', "Alamat Kelompok Tani");
        $sheet->setCellValue('N5', "Lokasi");
        $row_start = 6;
        $row_end = $row_start;
        foreach ($data as $key=>$value) {
            $sheet->setCellValue("A".$row_end, ($key+1));
            $sheet->setCellValue("B".$row_end, $value->keterangan_elemen);
            $sheet->setCellValue("C".$row_end, $value->nama_unit);
            $sheet->setCellValue("D".$row_end, $value->nama_desa);
            $sheet->setCellValue("E".$row_end, $value->nama_skpd);
            $sheet->setCellValue("F".$row_end, $value->tgl_survey);
            $sheet->setCellValue("G".$row_end, $value->nama_surveyor);
            $sheet->setCellValue("H".$row_end, $value->nama_ppl);
            $sheet->setCellValue("I".$row_end, $value->nama_narasumber);
            $sheet->setCellValue("J".$row_end, $value->telp);
            $sheet->setCellValue("K".$row_end, $value->nama_gapoktan);
            $sheet->setCellValue("L".$row_end, $value->nama_kelompok_tani);
            $sheet->setCellValue("M".$row_end, $value->alamat_kelompok_tani);
            $sheet->setCellValue("N".$row_end, $value->lat.",".$value->lon);
            $row_end++;
        }
        foreach (range('A', 'N') as $columnID) {
            $sheet->getColumnDimension($columnID) ->setAutoSize(true);
        }
        $font_title = array( 'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, ), 'font'  => array( 'bold'  => true, 'size'  => 10, ), );
        $cell_style_title = array( 'borders' => array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_MEDIUM, ), 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_MEDIUM, ) ), 'fill' => array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array( 'rgb' => "#333", ) ) );
        $cell_style_content = array( 'font'  => array( 'size'  => 10, ), 'borders' => array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ) ), );
        $table_style = array( 'borders' => array( 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_MEDIUM, ) ), );
        $out_area = array( 'fill' => array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array( 'argb' => PHPExcel_Style_Color::COLOR_WHITE, ) ) );
        $sheet->getDefaultStyle()->applyFromArray($out_area);
        $sheet->getStyle("A2:N3")->applyFromArray($font_title);
        $sheet->getStyle("A5:N5")->applyFromArray($cell_style_title);
        $sheet->getStyle("A6:N".($row_end-1))->applyFromArray($cell_style_content);
        $sheet->getStyle("A5:N".($row_end-1))->applyFromArray($table_style);
        $sheet->setAutoFilter('A5:N5');
        $sheet->freezePane('A6');
        $objPHPExcel->setActiveSheetIndex(0);
        if ($type==="1") {
            $type_writer = 'Excel2007';
            $type_file = '.xlsx';
            $content_type = 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        } else {
            $type_writer = 'Excel5';
            $type_file = '.xls';
            $content_type = 'Content-Type: application/vnd.ms-excel';
        }
        header($content_type);
        header('Content-Disposition: attachment;filename="Data_Survei_'.$keterangan.$type_file.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $type_writer);
        if (!$objWriter->save('php://output')) {
            return false;
        } else {
            return true;
        }
    }
    public function ekspor_survey_detail($type='1')
    {
        if (!isset($_POST['id_survey'])) {
            return true;
        }
        require_once(APPPATH.'libraries/PHPExcel/PHPExcel.php');
        $survei =  $this->m_survei->get_survei($_POST['id_survey']);
        $data =  $this->m_survei->get_detail_survei($_POST['id_survey']);
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("DATA KEDIRI") ->setLastModifiedBy("DATA KEDIRI") ->setTitle("DATA SURVEY") ->setSubject("DATA DETAIL SURVEY") ->setDescription("DATA DETAIL SURVEY") ->setKeywords("DATA DETAIL SURVEY") ->setCategory("DATA DETAIL SURVEY");
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        if (strpos(strtolower($survei->keterangan_elemen), 'pertanian') !== false || strpos(strtolower($survei->keterangan_elemen), 'perkebunan') !== false) {
            $endColumn = 'I';
        } else {
            $endColumn = 'G';
        }
        $sheet->mergeCells('A1:'.$endColumn.'1');
        $sheet->mergeCells('A2:'.$endColumn.'2');
        $sheet->setCellValue("A2", "DATA DETAIL SURVEY KABUPATEN KEDIRI");
        $sheet->mergeCells('A3:'.$endColumn.'3');
        $sheet->setCellValue("A3", "Unsur : ".$survei->keterangan_elemen);
        $sheet->mergeCells('A4:'.$endColumn.'4');
        $sheet->setCellValue("A4", ($survei->nama_skpd?"SKPD : ".$survei->nama_skpd : "") . " | ". ($survei->nama_unit?"KECAMATAN : ".$survei->nama_unit : "") . "|" . ($survei->nama_desa?"DESA : ".$survei->nama_desa : ""));
        $sheet->mergeCells('A5:'.$endColumn.'6');
        $sheet->setCellValue("A5", "Nama PPL : ".$survei->nama_ppl);
        $sheet->mergeCells('A7:'.$endColumn.'7');
        $sheet->setCellValue("A7", "Nama Narasumber : ".$survei->nama_narasumber);
        $sheet->mergeCells('A8:'.$endColumn.'8');
        $sheet->setCellValue("A8", "Nama Kelompok Tani : ".$survei->nama_kelompok_tani. "|" . "Nama GAPOKTAN : ".$survei->nama_gapoktan);
        $sheet->setCellValue('A10', "No");
        $sheet->setCellValue('B10', "Nama");
        if (strpos(strtolower($survei->keterangan_elemen), 'pertanian') !== false || strpos(strtolower($survei->keterangan_elemen), 'perkebunan') !== false) {
            $sheet->setCellValue('C10', "Luas Lahan");
            $sheet->setCellValue('D10', "Pola Tanam 1");
            $sheet->setCellValue('E10', "Pola Tanam 2");
            $sheet->setCellValue('F10', "Pola Tanam 3");
            $sheet->setCellValue('G10', "Jumlah Produksi");
            $sheet->setCellValue('H10', "Potensi Utama");
            $sheet->setCellValue('I10', "Produk Olahan");
        } else {
            $sheet->setCellValue('C10', "Jenis Ternak");
            $sheet->setCellValue('D10', "Kategori Ternak");
            $sheet->setCellValue('E10', "Jumlah Ternak");
            $sheet->setCellValue('F10', "Potensi Utama");
            $sheet->setCellValue('G10', "Produk Olahan");
        }
        $row_start = 11;
        $row_end = $row_start;
        foreach ($data as $key=>$value) {
            $sheet->setCellValue("A".$row_end, ($key+1));
            $sheet->setCellValue("B".$row_end, $value->nama);
            if (strpos(strtolower($survei->keterangan_elemen), 'pertanian') !== false || strpos(strtolower($survei->keterangan_elemen), 'perkebunan') !== false) {
                $sheet->setCellValue("C".$row_end, $value->luas_lahan);
                $sheet->setCellValue("D".$row_end, $value->pola_tanam_1);
                $sheet->setCellValue("E".$row_end, $value->pola_tanam_2);
                $sheet->setCellValue("F".$row_end, $value->pola_tanam_3);
                $sheet->setCellValue("G".$row_end, $value->jumlah_produksi);
                $sheet->setCellValue("H".$row_end, $value->potensi_utama);
                $sheet->setCellValue("I".$row_end, $value->produk_olahan);
            } else {
                $sheet->setCellValue("C".$row_end, $value->jenis_ternak);
                $sheet->setCellValue("D".$row_end, $value->kategori_produk);
                $sheet->setCellValue("E".$row_end, $value->jumlah_ternak);
                $sheet->setCellValue("F".$row_end, $value->potensi_utama);
                $sheet->setCellValue("G".$row_end, $value->produk_olahan);
            }
            $row_end++;
        }
        foreach (range('A', $endColumn) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $font_title = array( 'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, ), 'font'  => array( 'bold'  => true, 'size'  => 10, ), );
        $cell_style_title = array( 'borders' => array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_MEDIUM, ), 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_MEDIUM, ) ), 'fill' => array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array( 'rgb' => "#333", ) ) );
        $cell_style_content = array( 'font'  => array( 'size'  => 10, ), 'borders' => array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ) ), );
        $table_style = array( 'borders' => array( 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_MEDIUM, ) ), );
        $out_area = array( 'fill' => array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array( 'argb' => PHPExcel_Style_Color::COLOR_WHITE, ) ) );
        $sheet->getDefaultStyle()->applyFromArray($out_area);
        $sheet->getStyle("A2:".$endColumn."9")->applyFromArray($font_title);
        $sheet->getStyle("A10:".$endColumn."10")->applyFromArray($cell_style_title);
        $sheet->getStyle("A11:".$endColumn."".($row_end-1))->applyFromArray($cell_style_content);
        $sheet->getStyle("A10:".$endColumn."".($row_end-1))->applyFromArray($table_style);
        $sheet->setAutoFilter("A10:".$endColumn."10");
        $sheet->freezePane('A10');
        $objPHPExcel->setActiveSheetIndex(0);
        if ($type==="1") {
            $type_writer = 'Excel2007';
            $type_file = '.xlsx';
            $content_type = 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        } else {
            $type_writer = 'Excel5';
            $type_file = '.xls';
            $content_type = 'Content-Type: application/vnd.ms-excel';
        }
        header($content_type);
        header('Content-Disposition: attachment;filename="Data_Detail_Survei_'.$survei->id_survey.$type_file.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $type_writer);
        if (!$objWriter->save('php://output')) {
            return false;
        } else {
            return true;
        }
    }

    // HTML provider
    public function get_thead_survey_detail(){
        $html="";
        $keterangan_unsur = strtolower(isset($_POST['keterangan_unsur'])? $_POST['keterangan_unsur']:"");
        $template = '<thead>
            <tr>
                <td rowspan="2" style="text-align: center;vertical-align: middle;">Nama</td>
                <td rowspan="2" style="text-align: center;vertical-align: middle;">Jumlah Anggota</td>';
        if ($keterangan_unsur=="pertanian" || $keterangan_unsur=="perkebunan"){
            $template .= '<td rowspan="2" style="text-align: center;vertical-align: middle;">Luas Lahan</td>
                    <td colspan="3" style="text-align: center;">Pola Tanam</td>
                    <td rowspan="2" style="text-align: center;vertical-align: middle;">Jumlah Produksi</td>
                    <td rowspan="2" style="text-align: center;vertical-align: middle;">Potensi Utama</td>
                    <td rowspan="2" style="text-align: center;vertical-align: middle;">Produk Olahan</td>
                    <td rowspan="2" style="vertical-align: middle;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">1</td>
                        <td style="text-align: center;">2</td>
                        <td style="text-align: center;">3</td>
                    </tr>';
        } else if ($keterangan_unsur=="peternakan" || $keterangan_unsur=="perikanan"){
            $template .= '<td rowspan="2" style="text-align: center;vertical-align: middle;">Jenis Ternak</td>
                    <td rowspan="2" style="text-align: center;vertical-align: middle;">Kategori Ternak</td>
                    <td rowspan="2" style="text-align: center;vertical-align: middle;">Jumlah Ternak</td>
                    <td rowspan="2" style="text-align: center;vertical-align: middle;">Potensi Utama</td>
                    <td rowspan="2" style="text-align: center;vertical-align: middle;">Produk Olahan</td>
                    <td rowspan="2" style="vertical-align: middle;"></td>
                    </tr>';
        }
        
        $template .= '</thead>';

        echo preg_replace("/\s+|\n+|\r/", ' ', $template);

    }
}
