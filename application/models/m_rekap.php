<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}  class m_rekap extends CI_Model
{
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
            return $this->db->query($sql)->row();
        }
        $sql.=" order by smc_elemen.urut asc";
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
        return $this->db->query($sql)->row();
    }
    public function get_rekap_jumlah($periode, $id_elemen, $unit_group=null, $skpd_group=null)
    {
        $sql = "select smc_rekap.jumlah from smc_rekap where smc_rekap.periode='$periode' and smc_rekap.id_elemen='$id_elemen'";
        if ($unit_group!=null) {
            $sql .= " and smc_rekap.id_unit in ($unit_group)";
        }
        if ($skpd_group!=null) {
            // $sql .= " and smc_rekap.id_skpd in ($skpd_group)";
        }
        $result = $this->db->query($sql)->result();
        $jumlah = 0;
        foreach ($result as $key => $value) {
            $jumlah += $value->jumlah;
        }
        return (object) array('jumlah' => $jumlah);
    }
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
}
