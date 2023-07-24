<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}  class m_master extends CI_Model
{
    public function get_unit_kecamatan($unit=null)
    {
        $sql = "select smc_unit.* from smc_unit where kategori='kecamatan'";
        if ($unit!=null) {
            $sql .= " and nama='$unit' ";
            return $this->db->query($sql)->row();
        }
        return $this->db->query($sql)->result();
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
            return $this->db->query($sql)->row();
        }
        $sql.=" order by smc_unit.kategori, smc_unit.nama asc";
        return $this->db->query($sql)->result();
    }

    public function get_select2_unit($query=null, $kategori=null, $id_parent=null)
    {
        $sql = "select nama as text, kategori, id_unit as id from smc_unit where LOWER(nama) like '%$query%'";
        if ($kategori) {
            $sql .= " and kategori='$kategori'";
        }
        if ($id_parent) {
            $sql .= " and id_parent='$id_parent'";
        }
        // $sql .= " limit 10";
        return $this->db->query($sql)->result();
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
    public function insert($nama_table, $data)
    {
        $this->db->insert($nama_table, $data);
        return $this->db->affected_rows() > 0? TRUE: FALSE;
    }
    public function replace($nama_table, $data)
    {
        $this->db->replace($nama_table, $data);
        return $this->db->affected_rows() > 0? TRUE: FALSE;
    }
    public function update($nama_table, $data, $where)
    {
        $this->db->update($nama_table, $data, $where);
        return $this->db->affected_rows() > 0? TRUE: FALSE;
    }
    public function delete_unit($id_unit)
    {
        $sql = "delete from smc_unit where smc_unit.id_unit='$id_unit'";
        $this->db->query($sql);
    }
    public function delete_elemen($id_elemen)
    {
        $sql = "delete from smc_elemen where smc_elemen.id_elemen='$id_elemen'";
        $this->db->query($sql);
    }
    public function get_elemen($id_elemen=null, $keterangan=null, $kategori=null, $id_parent=null, $is_home=null)
    {
        $sql = "select * from smc_elemen where is_aktif='1' ";
        if ($id_elemen!=null) {
            $sql .= " and smc_elemen.id_elemen='$id_elemen'";
            return $this->db->query($sql)->row();
        }
        if ($keterangan!=null) {
            $sql .= " and smc_elemen.keterangan='$keterangan'";
            return $this->db->query($sql)->row();
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
        $sql = "select smc_elemen.*, parent.keterangan as nama_parent from smc_elemen left join smc_elemen as parent on parent.id_elemen=smc_elemen.id_parent and parent.is_aktif='1' where smc_elemen.is_aktif='1' ";
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
    public function get_select2_elemen($query, $kategori=null, $id_parent=null)
    {
        $sql = "select keterangan as text, singkat_keterangan, kategori, id_elemen as id from smc_elemen where is_aktif='1' AND LOWER(keterangan) like '%$query%'";
        if ($kategori) {
            $sql .= " and smc_elemen.kategori='$kategori'";
        }
        if ($id_parent!=null) {
            $sql .= " and smc_elemen.id_parent='$id_parent'";
        }
        $sql .= " limit 10";
        return $this->db->query($sql)->result();
    }
    public function get_next_urut_elemen($id_parent)
    {
        $sql = "select max(urut) as urut from smc_elemen where is_aktif='1' AND id_parent='$id_parent'";
        $result = $this->db->query($sql)->row();
        return $result?$result->urut+1:1;
    }
    public function get_ternak($id_ternak=null, $unsur=null, $unsurIn)
    {
        $sql = "select * from smc_ternak where 1";
        if ($id_ternak!=null) {
            $sql .= " and id_ternak='$id_ternak'";
            return $this->db->query($sql)->row();
        }
        if ($unsur!=null) {
            $sql .= " and unsur='$unsur'";
        }
        if ($unsurIn!=null) {
            $sql .= " and unsur in ($unsurIn)";
        }
        return $this->db->query($sql)->result();
    }
    public function get_user($id_user=null)
    {
        $sql = "SELECT smc_user.*, smc_unit.nama as nama_unit, smc_unit.kategori as kategori_unit, smc_unit.id_parent as id_parent_unit, parent_unit.nama as nama_parent_unit, (SELECT group_concat(keterangan) FROM smc_conf_role WHERE smc_conf_role.id_role IN (smc_user.id_role )) as `role` FROM smc_user LEFT join smc_unit on smc_unit.id_unit=smc_user.id_unit_kerja LEFT join smc_unit as parent_unit on parent_unit.id_unit=smc_unit.id_parent WHERE 1";
        if ($id_user!=null) {
            $sql .= " and smc_user.id_user='$id_user'";
            return $this->db->query($sql)->row();
        }
        return $this->db->query($sql)->result();
    }
    public function get_kemiskinan($no_kk=null)
    {
        $sql = "select id_survey, nomor_id_bdt as nomor_kk, nama_kepala_rumah_tangga, alamat_rumah from kemiskinan_tmp";
        if ($no_kk!=null) {
            $sql .= " where nomor_id_bdt='$no_kk' ";
            return $this->db->query($sql)->row();
        }
        return $this->db->query($sql)->result();
    }
    public function get_survey_ID()
    {
        $sql = "SELECT concat(DATE_FORMAT(CURDATE(), '%Y%m'),RIGHT(concat( '000000' , CAST(IFNULL(MAX(CAST(right(id_survey,6) AS unsigned)), 0) + 1 AS unsigned)),6)) as 'data' FROM kemiskinan_tmp WHERE left(id_survey,6) = DATE_FORMAT(CURDATE(), '%Y%m')";
        $result = $this->db->query($sql)->row();
        return $result->data;
    }
}
