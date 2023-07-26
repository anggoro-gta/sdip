<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class info extends CI_Model
{
    public function get_konten($id = null, $kategori = null, $limit = null)
    {
        $sql = "SELECT *, mid(md5(id_konten),9,8) as id FROM smc_konten WHERE is_publish='1' ";
        if ($id && $id != '-') {
            $sql .= " AND (mid(md5(id_konten),9,8)='$id' OR id_konten='$id' ) ";
        }
        if ($kategori && $kategori != '-') {
            $sql .= " AND kategori_konten='$kategori' ";
        }
        if ($limit && $limit != '-') {
            $sql .= " LIMIT 0, $limit ";
        }
        if ($id && $id != '-') {
            return $data->row();
        } else {
            return $this->db->query($sql);
        }
    }
    public function get_next_id($nama_table, $primary_key, $str = null, $length = null)
    {
        $sql = "SELECT concat('" . $str . date("Ym") . "',RIGHT(concat( '000000' , CAST(IFNULL(MAX(CAST(right(" . $primary_key . ",6) AS unsigned)), 0) + 1 AS unsigned)),6)) as `data` FROM " . $nama_table;
        if ($str) {
            $sql .= " WHERE left(" . $primary_key . "," . ($length + 6) . ") = '" . $str . date("Ym") . "' ";
        } else {
            $sql .= " WHERE left(" . $primary_key . ",6) = '" . date("Ym") . "' ";
        }
        $dt = $this->db->query($sql)->row();
        $strresult = $dt->data;
        return $strresult;
    }
    public function get_elemen($kat = null, $parent = null, $id = null)
    {
        $sql = "SELECT *, mid(md5(id_elemen),10,9) as id FROM smc_elemen WHERE is_aktif=1 ";
        if ($kat && $kat != '-') {
            $sql .= " AND kategori='$kat' ";
        }
        if ($parent && $parent != '-') {
            $sql .= " AND (id_parent='$parent' OR mid(md5(id_parent),10,9)='$parent')";
        }
        if ($id && $id != '-') {
            $sql .= " AND (mid(md5(id_elemen),10,9)='$id'  OR id_elemen='$id' )";
        }
        $sql .= " ORDER BY urut ASC, id_elemen ASC";
        return $this->db->query($sql);
    }
    public function get_elemen_data($id = null, $kat = null)
    {
        $sql = "SELECT smc_elemen.* FROM smc_elemen WHERE 1 ";
        if ($id && $id != '-') {
            $sql .= " AND (mid(md5(smc_elemen.id_elemen),10,9) LIKE '%$id%'  OR smc_elemen.id_elemen LIKE '$id%' )";
        }
        if ($kat && $kat != '-') {
            $sql .= " AND kategori='$kat' ";
        }
        $sql .= " LIMIT 0,5 ";
        return $this->db->query($sql);
    }
    public function get_elemen_data_detail($id = null, $kat = null)
    {
        $sql = "SELECT smc_rekap.*, smc_elemen.keterangan FROM smc_rekap INNER JOIN smc_elemen ON smc_rekap.id_elemen = smc_elemen.id_elemen WHERE 1 ";
        if ($id && $id != '-') {
            $sql .= " AND (mid(md5(smc_rekap.id_elemen),10,9) LIKE '%$id%'  OR smc_rekap.id_elemen LIKE '$id%' )";
        }
        if ($kat && $kat != '-') {
            $sql .= " AND kategori='$kat' ";
        }
        return $this->db->query($sql);
    }
    public function cek_auth($key)
    {
        if (!$this->session->userdata('smc_level')) {
            redirect();
        } else {
            $level = $this->session->userdata('smc_level');
            if ($key != $level && $level != '1') {
                redirect('');
            }
        }
    }
}
