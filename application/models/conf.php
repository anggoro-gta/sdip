<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}  class conf extends CI_Model
{
    public function get_next_id($nama_table, $primary_key)
    {
        $sql = "SELECT concat('" . date("Ym") . "',RIGHT(concat( '000000' , CAST(IFNULL(MAX(CAST(right($primary_key,6) AS unsigned)), 0) + 1 AS unsigned)),6)) as `data` FROM $nama_table";
        $sql .= " WHERE LEFT($primary_key,6) = '" . date("Ym") . "' ";
        $data = $this->db->query($sql);
        $dt = $data->row_array();
        $strresult = $dt->data;
        return $strresult;
    }
    public function get_unit($id_unit=null, $kategori=null, $id_parent=null, $nama=null)
    {
        $sql = "select * from smc_unit where 1";
        if ($id_unit!=null) {
            $sql .= " and smc_unit.id_unit='$id_unit'";
        }
        if ($kategori!=null) {
            $sql .= " and smc_unit.kategori='$kategori'";
        }
        if ($id_parent!=null) {
            $sql .= " and smc_unit.id_parent='$id_parent'";
        }
        if ($nama!=null) {
            $sql .= " and smc_unit.nama='$nama'";
        }
        $data = $this->db->query($sql);
        if ($nama!=null || $id_unit!=null) {
            return $data->row();
        } else {
            return $data;
        }
    }
    public function get_unit_parent($id_parent=null, $nama=null)
    {
        $sql = "select smc_unit.*, parent.nama as nama_parent from smc_unit left join smc_unit as parent on parent.id_unit=smc_unit.id_parent where 1";
        if ($id_parent!=null) {
            $sql .= " and smc_unit.id_parent='$id_parent'";
        } elseif ($id_parent==0) {
            $sql .= " and (smc_unit.id_parent='' or smc_unit.id_parent is null or smc_unit.id_parent='0')";
        }
        if ($nama!=null) {
            $sql .= " and smc_unit.nama like '%$nama%'";
        }
        $sql.=" order by smc_unit.nama asc";
        $data = $this->db->query($sql);
        if ($nama!=null) {
            return $data->row();
        } else {
            return $data;
        }
    }
    public function get_elemen($id_elemen=null, $keterangan=null, $kategori=null, $id_parent=null, $is_home=null)
    {
        $sql = "select * from smc_elemen where is_aktif='1' ";
        if ($id_elemen!=null) {
            $sql .= " and smc_elemen.id_elemen='$id_elemen'";
            $data = $this->db->query($sql);
            return $data->row();
        }
        if ($keterangan!=null) {
            $sql .= " and smc_elemen.keterangan='$keterangan'";
            $data = $this->db->query($sql);
            return $data->row();
        }
        if ($kategori!=null) {
            $sql .= " and smc_elemen.kategori='$kategori'";
        }
        if ($id_parent!=null) {
            $sql .= " and smc_elemen.id_parent='$id_parent'";
        }
        if ($is_home!=null) {
            $sql .= " and smc_elemen.is_home='$is_home'";
        }
        $sql .= " order by left(smc_elemen.id_elemen,5) desc";
        return $this->db->query($sql)->result();
    }
    public function get_elemen_parent($id_parent=null, $keterangan=null)
    {
        $sql = "select smc_elemen.*, parent.keterangan as nama_parent from smc_elemen left join smc_elemen as parent on parent.id_elemen=smc_elemen.id_parent where 1";
        if ($id_parent!=null) {
            $sql .= " and smc_elemen.id_parent='$id_parent'";
        } elseif ($id_parent==0) {
            $sql .= " and (smc_elemen.id_parent='' or smc_elemen.id_parent is null or smc_elemen.id_parent='0')";
        }
        if ($keterangan!=null) {
            $sql .= " and smc_elemen.keterangan like '%$keterangan%'";
            $data = $this->db->query($sql);
            return $data->row();
        }
        $sql.=" order by smc_elemen.urut asc";
        return $this->db->query($sql)->result();
    }
    public function get_unsur_home()
    {
        $sql = "SELECT DISTINCT unsur.keterangan, unsur.singkat_keterangan, unsur.warna, unsur.id_elemen FROM smc_elemen 
            INNER JOIN smc_elemen AS unsur ON LEFT(smc_elemen.id_elemen,4)=unsur.id_elemen 
            WHERE smc_elemen.is_home='1' ORDER BY unsur.id_elemen DESC";
        return $this->db->query($sql)->result();
    }
    public function get_rekap($periode, $id_elemen, $unit=null, $skpd=null)
    {
        $sql = "select smc_rekap.*, smc_unit.nama as nama_unit, skpd.nama as nama_skpd from smc_rekap left join smc_unit on smc_unit.id_unit=smc_rekap.id_unit left join smc_unit as skpd on skpd.id_unit=smc_rekap.id_skpd where smc_rekap.periode='$periode' and smc_rekap.id_elemen='$id_elemen'";
        if ($unit!=null) {
            $sql .= " and smc_rekap.id_unit='$unit'";
        }
        if ($skpd!=null) {
            $sql .= " and smc_rekap.id_skpd='$skpd'";
        }
        $data = $this->db->query($sql);
        return $data->row();
    }
    public function get_rekap_jumlah($periode, $id_elemen, $unit_group=null, $skpd_group=null)
    {
        $sql = "select smc_rekap.jumlah from smc_rekap where smc_rekap.periode='$periode' and smc_rekap.id_elemen='$id_elemen'";
        if ($unit_group!=null) {
            $sql .= " and smc_rekap.id_unit in ($unit_group)";
        }
        if ($skpd_group!=null) {
            $sql .= " and smc_rekap.id_skpd in ($skpd_group)";
        }
        $result = $this->db->query($sql)->result();
        $jumlah = 0;
        foreach ($result as $key => $value) {
            $jumlah += $value->jumlah;
        }
        return (object) array('jumlah' => $jumlah);
    }
}
