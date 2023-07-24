<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class m_ntp_komoditas extends CI_Model
{
    public const PEMASUKAN = "pemasukan";
    public const PENGELUARAN = "pengeluaran";

    public function get_ntp_komoditas($id_ntp_komoditas=null, $kelompok=null, $id_parent=null, $search=null,
                                      $limit_offset=null, $limit_count = null)
    {
        $sql = "SELECT * FROM smc_ntp_komoditas WHERE 1";

        if ($id_ntp_komoditas) {
            $sql .= " and (id_ntp_komoditas.='$id_ntp_komoditas' or SUBSTR(md5(id_ntp_record), 10, 10)='$id_ntp_komoditas')";
            return $this->db->query($sql)->row();
        }
        if ($kelompok) {
            $sql .= " and kelompok='$kelompok'";
        }
        if ($id_parent) {
            $sql .= " and id_parent='$id_parent'";
        }
        if ($search) {
            $sql .= " and (nama_komoditas like '%$search%')";
        }

        $sql .= " order by id_ntp_komoditas ASC";

        if ($limit_offset !==null && $limit_count !==null){
            $sql .= " LIMIT $limit_offset, $limit_count";
        }

        return $this->db->query($sql)->result();
    }

    public function get_ntp_komoditas_value($id_ntp_komoditas_value=null, $id_ntp_record=null, $id_ntp_komoditas=null,
                                       $limit_offset=null, $limit_count = null){

        $sql = "SELECT * FROM smc_ntp_komoditas_value WHERE 1";

        if ($id_ntp_komoditas_value) {
            $sql .= " and (id_ntp_komoditas_value.='$id_ntp_komoditas_value' or SUBSTR(md5(id_ntp_komoditas_value), 10, 10)='$id_ntp_komoditas_value')";
        }

        if ($id_ntp_record ) {
            $sql .= " AND id_ntp_record='$id_ntp_record'";
        }

        if ($id_ntp_komoditas){
            $sql .= " AND id_ntp_komoditas='$id_ntp_komoditas'";
        }

        if ($limit_offset !==null && $limit_count !==null){
            $sql .= " LIMIT $limit_offset, $limit_count";
        }

        $query = $this->db->query($sql);
        return $query->num_rows() > 1 ? $this->db->query($sql)->result(): $this->db->query($sql)->row();
    }
}