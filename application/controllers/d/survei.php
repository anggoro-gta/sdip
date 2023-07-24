<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}  class survei extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_survei');
        $this->load->model('m_master');        
        $this->load->model('m_cube');        
        $this->load->model('m_unit');        
        $this->load->model('m_params');        
    }

    public function get_lokasi_kelompok_simple(){
        $this->get_lokasi_kelompok(array(
            "smc_survey.id_survey",
            "smc_survey.lat",
            "smc_survey.lon",
            "smc_survey.id_elemen"
        ));
    }

    // get lokasi kelompok tanpa login
    public function get_lokasi_kelompok($fieldList=null)
    {
        $unsur = isset($_POST['unsur'])? $_POST['unsur']:"";
        $periode = isset($_POST['periode'])? $_POST['periode']:"";
        $survei_list =  $this->m_survei->get_survei(null, null, null, $unsur, $periode, $fieldList,null,null,true); 

        echo json_encode($survei_list);
    }

    public function get_info_kelompok(){
        $id_survey = isset($_POST['id_survey'])? $_POST['id_survey']: null;
        $id_elemen = isset($_POST['id_elemen'])? $_POST['id_elemen']: null;
        
        $survey = $this->get_survei_with_field_value($id_survey);

        $content = "";
        $fields = Globalsdb::get_fields($id_elemen);
        foreach($fields as $f){
            if ($survey->{$f->key_field}){
                $content .= sprintf("<b>%s</b>: %s<br/>", $f->nama_field, $survey->{$f->key_field});
            }            
        }
        
        $header = "";        
        
        $survey->info = array("header"=> $header, "content"=> $content);

        echo json_encode($survey);
    }

    public function get_survei_with_field_value($id_survey)
    {
        $survei = $this->m_survei->get_survei($id_survey);        
        
        $field_values = Globalsdb::get_survey_field_value($survei->id_survey);
        
        foreach($field_values as $fv){
            $survei->{$fv->key_field}= $fv->value;   
        }

        return $survei;
    }

    public function ekspor_survey_kemiskinan($type='1')
    {
        if (empty($_POST)) exit(0);    
        
        // GET DATA (START)
        
        $periode=isset($_POST['periode'])? $_POST['periode']: null;
        $id_kecamatan=isset($_POST['id_kecamatan'])? $_POST['id_kecamatan']: null;
        $id_parameter=isset($_POST['id_parameter'])? $_POST['id_parameter']: null;
        $id_sub_parameter=isset($_POST['id_sub_parameter'])? $_POST['id_sub_parameter']: null;
        
        $kecamatan = $this->m_unit->get_unit($id_kecamatan);
        $parameter = $this->m_params->get_kemiskinan_paramlist((object)array('id_parameter' => $id_parameter));
        $sub_parameter = $this->m_params->get_kemiskinan_paramlist((object)array('id_parameter' => $id_sub_parameter));
        
        $aggregation_level="kecamatan";        
        
        if ($id_kecamatan=="id_all") {
            $id_kecamatan = null;            
        } else if ($id_kecamatan AND $id_kecamatan != "id_all"){
            $aggregation_level="desa";
        }

        $desil = array("desil1","desil2","desil3","desil4");
        $result_desil = array();
        foreach ($desil as $d) {
            $params = (object)array(
                "id_parameter" => $id_sub_parameter,
                "desil" => $d,
                "aggregation_level" => $aggregation_level,
                "periode" => $periode,
                "id_kecamatan" => $id_kecamatan
            );
            $result_desil[$d] = $this->m_cube->get_cube_data($params);
        }
        
        $list_unit = array();
        foreach ($result_desil as $d) {
            foreach($d as $obj){
                if ($aggregation_level == "kecamatan"){
                    $new_obj = (object)array(
                        "id_kecamatan" => $obj->id_kecamatan,
                        "nama_kecamatan" => $obj->nama_kecamatan
                    );
                }else if ($aggregation_level == "desa"){
                    $new_obj = (object)array(
                        "id_desa" => $obj->id_desa,
                        "nama_desa" => $obj->nama_desa
                    );
                }
                array_push($list_unit, $new_obj);
            } 
        }
        $list_unique_unit = array_unique($list_unit, SORT_REGULAR);  
        
        switch ($aggregation_level) {
            case 'kecamatan':
                $header = array("Kecamatan");
                break;
            case 'desa':
                $header = array("Desa");
                break;
            default:
                $header = array("-");
                break;
        }
        $header = array_merge($header, $desil);
        array_push($header, 'Total');

        $data_row = array();
        $list_total_value = array();
        foreach ($list_unique_unit as $unit) {
            $dt = array(
                "id_unit" => $aggregation_level == "kecamatan"? $unit->id_kecamatan : $unit->id_desa,
                "nama_unit" => $aggregation_level == "kecamatan"? $unit->nama_kecamatan : $unit->nama_desa
            );
            $data_val = array();
            $sum_desil = 0;
            foreach ($desil as $d) {
                if ($aggregation_level == "kecamatan"){
                    $idx_data_unit_desil = array_search($unit->id_kecamatan, array_column($result_desil[$d], 'id_kecamatan'));
                }else if ($aggregation_level == "desa"){
                    $idx_data_unit_desil = array_search($unit->id_desa, array_column($result_desil[$d], 'id_desa'));                   
                }
                $nilai_desil = $result_desil[$d][$idx_data_unit_desil]->total;
                $sum_desil += $nilai_desil;
                array_push($data_val, $nilai_desil);
            }         
            $sum_desil = strval($sum_desil);
            array_push($list_total_value, $sum_desil);
            $dt["value"] = $data_val;  
            $dt["total_value"] = $sum_desil; 
            array_push($data_row, (object)$dt);
        }
        
        $result = array(
            "header" => $header,
            "data_row" => $data_row
        );

        // GET DATA (END)

        require_once(APPPATH.'libraries/PHPExcel/PHPExcel.php');
        $unit = null;
        $elemen = null;
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("DATA KEDIRI")
        ->setLastModifiedBy("DATA KEDIRI")->setTitle("DATA SURVEY")
        ->setSubject("DATA SURVEY")->setDescription("DATA SURVEY")
        ->setKeywords("DATA SURVEY")->setCategory("DATA SURVEY");

        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue("A1", "REKAP SURVEI KEMISKINAN");
        $keterangan = "Kemiskinan";
        
        $sheet->setCellValue("A3", "Periode: ".$periode);
        $sheet->setCellValue("A4", "Kecamatan: ".(is_null($id_kecamatan)?"Semua Kecamatan":$kecamatan->nama));
        $sheet->setCellValue("A5", sprintf("Parameter: %s (%s)",$parameter->nama_parameter,$sub_parameter->nama_parameter));

        $columns = range('A','F');

        foreach ($columns as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        for ($i=0; $i < count($header); $i++) { 
            $sheet->setCellValue($columns[$i]."7", $header[$i]);
        }

        $row_number = 8;
        foreach($data_row as $d){
            $sheet->setCellValue("A".$row_number, $d->nama_unit);
            for ($i=0; $i < count($d->value); $i++) { 
                $sheet->setCellValue($columns[$i+1].$row_number, $d->value[$i]);
            }
            $sheet->setCellValue($columns[count($d->value)+1].$row_number, $d->total_value);
            $row_number++;
        }
        
        $font_title = array( 'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, ), 'font'  => array( 'bold'  => true, 'size'  => 10, ), );
        $cell_style_title = array( 'borders' => array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_MEDIUM, ), 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_MEDIUM, ) ), 'fill' => array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array( 'rgb' => "#AAD4FF", ) ) );
        $cell_style_content = array( 'font'  => array( 'size'  => 10, ), 'borders' => array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ) ), );
        $table_style = array( 'borders' => array( 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_MEDIUM, ) ), );
        // $out_area = array( 'fill' => array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array( 'argb' => PHPExcel_Style_Color::COLOR_WHITE, ) ) );
        // $sheet->getDefaultStyle()->applyFromArray($out_area);
        $sheet->getStyle("A1:F1")->applyFromArray($font_title);
        $sheet->getStyle("A7:F7")->applyFromArray($font_title);
        // $sheet->getStyle("A7:F7")->applyFromArray($cell_style_title);
        $sheet->getStyle("A7:F".($row_number-1))->applyFromArray($cell_style_content);
        $sheet->getStyle("A7:F".($row_number-1))->applyFromArray($table_style);
        // $sheet->setAutoFilter('A5:N5');
        // $sheet->freezePane('A6');

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
}