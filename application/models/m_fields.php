<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}  class m_fields extends CI_Model
{
    // mengambil list field dari tabel smc_field
    public function get_field_list($id_elemen_parent=null, $parent_keyfield=null, $visible=TRUE){
        $sql = "SELECT * FROM `smc_field` WHERE is_survei=$visible";

        if ($parent_keyfield != null){
            $sql .= " AND id_parent = (SELECT id_field FROM smc_field WHERE key_field='$parent_keyfield')";
        }

        if ($id_elemen_parent != null){
            $sql .= " AND id_parent IN (SELECT id_field FROM smc_field WHERE id_elemen='$id_elemen_parent')";
        }

        return $this->db->query($sql)->result();
    }
    public function get_field_from_parent($element_survei_detail){
        $sql = "select smc_field.* from smc_field join smc_field as parent on parent.id_field = smc_field.id_parent WHERE parent.key_field = '$element_survei_detail'";
        return $this->db->query($sql)->result();
    }
}