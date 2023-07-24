<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}  class summary extends CI_Controller
{
    public function __construct()
    {
        parent::__construct(); 
        $this->load->model('m_summary');  
        $this->load->model('m_cube');  
        $this->load->model('m_ntp');
    }

//    public function create_summary_survei(){
//        $result = $this->m_summary->create_summary_survei_count("jumlah_kk_miskin", "1.5.7");
//
//        echo json_encode($result);
//    }

    private function getRegionColor($value, $min, $max, $color_list = null){
        if (is_null($color_list)) $color_list = ['#00FF00', '#EEEE00', '#CC0000'];
        $step = ($max-$min)/3;
        $range = range($min, $max, $step);
        $range[0] = $range[0]-0.1;
        
        $range_i = -1;
        for ($i=0; $i < count($range)-1; $i++) {
            if ($value > $range[$i] && $value <= $range[$i+1]){
                $range_i = $i;
            }           
        }
        
        return $i>0? $color_list[$range_i]: "#000000";
    }

    public function get_kecamatan_region_color(){
        $id_parameter=isset($_POST['id_parameter'])? $_POST['id_parameter']: null;
        $periode=isset($_POST['periode'])? $_POST['periode']: null;
        $id_kecamatan=isset($_POST['id_kecamatan'])? $_POST['id_kecamatan']: null;

        $desil = array("desil1","desil2","desil3","desil4");
        $result_desil = array();
        foreach ($desil as $d) {
            $params = (object)array(
                "id_parameter" => $id_parameter,
                "desil" => $d,
                "aggregation_level" => "kecamatan",
                "periode" => $periode
            );
            $result_desil[$d] = $this->m_cube->get_cube_data($params);
        }

        $list_unit = array();
        foreach ($result_desil as $d) {
            foreach($d as $obj){
                array_push($list_unit, (object)array(
                    "id_kecamatan" => $obj->id_kecamatan,
                    "nama_kecamatan" => $obj->nama_kecamatan
                ));
            } 
        }
        $list_unique_unit = array_unique($list_unit, SORT_REGULAR);

        $list_total_value = array();
        foreach ($list_unique_unit as $unit) {
            $sum_desil = 0;
            foreach ($desil as $d) {
                $idx_data_unit_desil = array_search($unit->id_kecamatan, array_column($result_desil[$d], 'id_kecamatan'));
                $nilai_desil = $result_desil[$d][$idx_data_unit_desil]->total;
                $sum_desil += $nilai_desil;
            }
            array_push($list_total_value, $sum_desil);
            $unit->total = $sum_desil;
        }
        
        // color range untuk nilai total 
        if (!empty($list_total_value)){
            $color_list = ['#00FF00', '#EEEE00', '#CC0000'];
            $min = min($list_total_value);
            $max = max($list_total_value);
            foreach ($list_unique_unit as $ob) {
                $ob->region_color= $this->getRegionColor($ob->total, $min, $max, $color_list);
            }       
        }

        $selected_unit_summary = null;
        foreach($list_unique_unit as $u) {
            if ($u->id_kecamatan == $id_kecamatan) {
                $selected_unit_summary = $u;
                break;
            }
        }
        
        echo json_encode($selected_unit_summary);
    }

    public function get_summary_survei(){
        $id_parameter=isset($_POST['id_parameter'])? $_POST['id_parameter']: null;
        $periode=isset($_POST['periode'])? $_POST['periode']: null;
        $id_kecamatan=isset($_POST['id_kecamatan'])? $_POST['id_kecamatan']: null;
        $kecamatan_color=isset($_POST['kecamatan_color'])? $_POST['kecamatan_color']: null;

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
                "id_parameter" => $id_parameter,
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

        // color range untuk nilai total 
        if (!empty($list_total_value)){
            $color_list = ['#00FF00', '#EEEE00', '#CC0000'];
            $min = min($list_total_value);
            $max = max($list_total_value);
            foreach ($data_row as $unit_data) {
                if ($aggregation_level == 'kecamatan')
                    $unit_data->region_color= $this->getRegionColor($unit_data->total_value, $min, $max, $color_list);
                else if ($aggregation_level == 'desa'){
                    $color_list = [$kecamatan_color, luminance($kecamatan_color, 0.25), luminance($kecamatan_color, 0.5)];                    
                    $unit_data->region_color= numberToColor($unit_data->total_value, $min, $max, $color_list);
                }
            }            
            // legends
            $step = ($max-$min)/3;
            $range = range($min, $max, $step);
            $range[0] = $range[0]-0.1;
            $legends = array();
            for ($i=0; $i < count($range)-1; $i++) {
                array_push($legends, array(
                    "label" => sprintf("Total KRT diantara %d ~ %d", $range[$i], $range[$i+1]),
                    "color" => $color_list[$i]
                ));          
            }
            if ($aggregation_level == "kecamatan")
                $result['legends'] = $legends;
            
            $result['metadata'] = array(
                "min_value" => $min,
                "max_value" => $max
            );
        }
        
        $result['aggregation_level'] = $aggregation_level;
        $result['tahun'] = $periode;
        
        echo json_encode($result, JSON_PRETTY_PRINT);
    }

    // NTP
    public function get_ntp_summary(){
        $periode=isset($_POST['periode'])? $_POST['periode']: null;
        $id_elemen=isset($_POST['id_elemen'])? $_POST['id_elemen']: null;

        $result['data'] = $this->m_ntp->get_ntp_rekap($periode, $id_elemen);

        echo json_encode($result, JSON_PRETTY_PRINT);
    }
}