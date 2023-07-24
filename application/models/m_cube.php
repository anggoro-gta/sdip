<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}  

class M_cube extends CI_Model
{
    public function get_cube_list($params=null){
        $cube_key = isset($params->cube_key)?$params->cube_key:null;

        $sql = "SELECT * FROM smc_cube WHERE 1";

        return $this->db->query($sql)->result();    
    }

    public function get_cube_data($params){
        $id_parameter = isset($params->id_parameter)?$params->id_parameter:null;
        $desil = isset($params->desil)?$params->desil:null;        
        $periode = isset($params->periode)?$params->periode:null;
        $id_kecamatan = isset($params->id_kecamatan)?$params->id_kecamatan:null;

        $aggregation_level = isset($params->aggregation_level)?$params->aggregation_level:null;

        $sql = "SELECT sc.id_cube, sc.cube_key, su.nama AS nama_kecamatan, su_desa.nama AS nama_desa, 
                scd.periode, scd.id_kecamatan, scd.id_desa, scd.total FROM smc_cube sc
                JOIN smc_cube_data scd ON scd.id_cube = sc.id_cube
                JOIN smc_kemiskinan_parameter skp ON skp.id_parameter = sc.id_parameter
                LEFT JOIN smc_unit su ON scd.id_kecamatan = su.id_unit
                LEFT JOIN smc_unit su_desa ON scd.id_desa = su_desa.id_unit
                WHERE 1";

        if(is_null($id_parameter)){
            $sql .= " AND sc.id_parameter IS NULL";
        }else{
            $sql .= " AND sc.id_parameter='$id_parameter'";
        }

        if ($desil){
            $sql .= " AND sc.cube_key LIKE '%$desil'";
        }

        if($periode){
            $sql .= " AND periode=$periode";
        }

        if($id_kecamatan){
            $sql .= " AND id_kecamatan='$id_kecamatan'";
        }

        switch ($aggregation_level) {
            case 'kecamatan':
                $sql .= " AND id_desa IS NULL AND id_kecamatan IS NOT NULL";
                break;
            
            case 'periode':
                $sql .= " AND id_desa IS NULL AND id_kecamatan IS NULL AND periode IS NOT NULL";
                break;

            case 'desa':
                $sql .= " AND id_desa IS NOT NULL";
                break;    

            case 'all':
                # semua dimensi cube di agregasi
                $sql .= " AND periode IS NULL AND id_desa IS NULL AND id_kecamatan IS NULL";
                break;

            default:                
                break;
        }
        
        $sql .= " ORDER BY sc.id_cube";

        // echo $sql; exit;
        return $this->db->query($sql)->result();
    }
}