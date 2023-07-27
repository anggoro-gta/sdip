<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}  class m_unit extends CI_Model
{
    public function get_unit($id_unit=null, $kategori=null, $id_parent=null)
    {
        $sql = "select * from smc_unit where 1";
        if ($id_unit!=null) {
            $sql .= " and smc_unit.id_unit='$id_unit'";
            return $this->db->query($sql)->row();
        }
        if ($kategori!=null) {
            $sql .= " and smc_unit.kategori='$kategori'";
        }
        if ($id_parent!=null) {
            $sql .= " and smc_unit.id_parent='$id_parent'";
        }
        return $this->db->query($sql)->result();
    }
	
	//mencari kecamatan hanya Kab. Kediri
    public function get_unit_kec_kab()
    {
        $query = $this->db->query("SELECT id_unit,nama FROM smc_unit WHERE kategori = 'kecamatan' AND nama NOT IN ('Mojoroto','Kota','Pesantren')");
        $result = $query->result_array();

        return $result;
    }
}