<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}  

class M_params extends CI_Model
{
    public function get_kemiskinan_paramlist($params=null){
        $id_parameter = isset($params->id_parameter)?$params->id_parameter:null;
        $id_related_field = isset($params->id_related_field)?$params->id_related_field:null;
        $show_only_parent = isset($params->show_only_parent)?$params->show_only_parent:false;        
        $id_parent = isset($params->id_parent)?$params->id_parent:false;

        $sql = "SELECT * FROM smc_kemiskinan_parameter WHERE 1 AND nama_parameter IS NOT NULL";
        
        if ($id_parameter){
            $sql .= " and id_parameter='$id_parameter'";
            return $this->db->query($sql)->row();
        }

        if ($id_related_field){
            $sql .= " AND id_related_field='$id_related_field'";
        }
        
        if ($show_only_parent){            
            $sql .= " AND id_parent IS NULL";
        }

        if ($id_parent){            
            $sql .= " AND id_parent = '$id_parent'";
        }

        // echo $sql;

        return $this->db->query($sql)->result();    
    }
}