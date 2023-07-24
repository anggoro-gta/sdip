<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class m_elemen extends CI_Model
{
    public function get_elemen($id_elemen_list=null)
    {
        $sql = "select * FROM smc_elemen WHERE 1 ";

        if ($id_elemen_list){
            $tmp = array();
            foreach ($id_elemen_list as $id_elemen){
                array_push($tmp, "id_elemen='$id_elemen'");
            }
            $elemen_query = implode(" OR ", $tmp);
            $sql .= "AND ($elemen_query)";
        }

        return $this->db->query($sql)->result();
    }
}