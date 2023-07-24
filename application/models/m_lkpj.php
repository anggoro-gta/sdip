<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}  class m_lkpj extends CI_Model
{
    // mengambil data LKPJ
    public function get_lkpj($id_rekap=null, $unit=null, $elemen=null)
    {
        $sql = "SELECT sr.*, SUBSTR(md5(sr.id_rekap), 10, 10) as uri_code, 
        su.nama as nama_unit, se.keterangan as keterangan_elemen 
            FROM smc_rekap sr 
                left join smc_unit su on sr.id_unit=su.id_unit
                left join smc_elemen se on sr.id_elemen=se.id_elemen where 1";

        if ($id_rekap) {
            $sql .= " and (sr.id_rekap='$id_rekap' or SUBSTR(md5(sr.id_rekap), 10, 10)='$id_rekap')";
            return $this->db->query($sql)->row();
        }
        if ($unit) {
            $sql .= " and sr.id_unit='$unit'";
        }
        if ($elemen) {
            $sql .= " and sr.id_elemen='$elemen'";
        }
        
        $sql .= " order by sr.last_update desc";
        
        return $this->db->query($sql)->result();
    }

    // mengambil data elemen dari LKPJ
    public function get_elemen_lkpj($key){
        $sql = "SELECT * FROM `smc_elemen` WHERE id_parent='2.01' AND LOWER(keterangan) like '%$key%'"; 

        return $this->db->query($sql)->result();
    }
}