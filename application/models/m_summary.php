<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}  class m_summary extends CI_Model
{
    public function create_summary_rekap(){
        $sql = "";

        $query_select = "SELECT table2.periode, table2.id_kecamatan, table2.id_desa, SUM(table2.jumlah) as total FROM
                        (SELECT su.id_unit as id_desa, parent.id_unit as id_kecamatan, sr.periode, sr.jumlah FROM `smc_rekap` sr
                        LEFT JOIN smc_unit su on su.id_unit = sr.id_unit
                        LEFT JOIN smc_unit as parent on parent.id_unit=su.id_parent
                        WHERE sr.id_elemen='1.12.01.1.1') AS table2
                        GROUP BY table2.periode, table2.id_kecamatan, table2.id_desa WITH ROLLUP";
    }

    public function create_summary_survei_count($cube_key, $id_field=""){
        $x = "AND s.id_desa IS NOT NULL"; // beberapa survei tidak memiliki id_desa, bisa dihapus jika sudah tidak diperlukan

        $query_select = "SELECT '$cube_key' as cube_key, table2.periode, table2.id_kecamatan, table2.id_desa, SUM(table2.hitung) as total FROM
                            (SELECT LEFT(s.id_survey,4) as periode, parent.id_unit as id_kecamatan, s.id_desa, COUNT(sfv.id_survey_field) AS hitung FROM `smc_survey_field_value` sfv
                            INNER JOIN smc_survey s ON s.id_survey = sfv.id_survey
                            LEFT JOIN smc_unit su on su.id_unit = s.id_desa
                            LEFT JOIN smc_unit as parent on parent.id_unit=su.id_parent
                            WHERE sfv.id_field='$id_field' $x
                            GROUP BY s.id_desa) AS table2
                        GROUP BY table2.periode, table2.id_kecamatan, table2.id_desa WITH ROLLUP";

        $sql = "INSERT INTO smc_cube_desa (cube_key, periode, id_kecamatan, id_desa, total) $query_select";

        $this->db->query($sql);
        return $this->db->affected_rows();        
    }

    public function get_next_id($nama_table, $primary_key, $str=null, $length=null)
    {
        $sql="SELECT concat('".$str.date("Ym")."',RIGHT(concat( '000000' , CAST(IFNULL(MAX(CAST(right(".$primary_key.",6) AS unsigned)), 0) + 1 AS unsigned)),6)) as `data` FROM ".$nama_table;
        if ($str) {
            $sql.= " WHERE left(".$primary_key.",".($length+6).") = '".$str.date("Ym")."' ";
        } else {
            $sql.= " WHERE left(".$primary_key.",6) = '".date("Ym")."' ";
        }
        $dt = $this->db->query($sql)->row();
        $strresult = $dt->data;
        return $strresult;
    }

    public function create_rekap_kemiskinan($params=null){
        $sql_get_list_desil_dan_status = "SELECT child.*, parent.nama AS parent_name, parent.keyname AS parent_keyname FROM smc_status parent
        JOIN (SELECT DISTINCT(id_desil), id_status, id_parent, nama AS child_name, keyname AS child_keyname FROM smc_desil, smc_status WHERE id_parent IS NOT NULL) child
        ON child.id_parent = parent.id_status";

        $list_desil_dan_status = $this->db->query($sql_get_list_desil_dan_status)->result();

        $status = 1;
        foreach ($list_desil_dan_status as $o) {
            // insert into smc_cube
            $data["id_cube"] = $this->get_next_id("smc_cube", "id_cube");
            $data["cube_key"] = sprintf("%s#%s#desil%s", $o->parent_keyname, $o->child_keyname, $o->id_desil);
            $data["cube_name"] = sprintf("%s (%s) - Desil%s", $o->parent_name, is_null($o->child_name)?'Kosong':$o->child_name, $o->id_desil);
            $data["id_parameter"] = $o->id_status;
            
            $status &= $this->db->replace("smc_cube", $data);

            if ($status){
                // insert into smc_cube_data
                $sql_insert = "INSERT INTO smc_cube_data (id_cube, periode, id_kecamatan, id_desa, total)
                SELECT '".$data["id_cube"]."' as id_cube, s.id_periode, p.id_parent AS id_kecamatan, 
                s.id_unit AS id_desa, SUM(s.jumlah) AS total
                FROM smc_aggregate_desil s
                INNER JOIN smc_unit p ON s.id_unit = p.id_unit
                WHERE s.id_status = '$o->id_status' AND id_desil = $o->id_desil
                GROUP BY s.id_periode, id_kecamatan, id_desa WITH ROLLUP";

                $this->db->query($sql_insert);
            }
        }

        return $status;
    }
}