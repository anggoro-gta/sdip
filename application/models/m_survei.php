<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}  class m_survei extends CI_Model
{
    // menghitung data survei
    public function count_survei($search=null, $unit=null, $elemen=null){
       $sql = "SELECT COUNT(id_survey) as jumlah FROM smc_survey WHERE 1";

        if ($search) {
            $sql .= " and (smc_survey.nama_surveyor like '%$search%' or smc_survey.nama_ppl like '%$search%' or smc_survey.nama_narasumber like '%$search%')";
        }
        if ($unit) {
            $sql .= " and smc_survey.id_unit='$unit'";
        }
        if ($elemen) {
            $sql .= " and smc_survey.id_elemen='$elemen'";
        }

        return $this->db->query($sql)->row()->jumlah;
    }

    // mengambil data survei
    public function get_survei($id_survey=null, $search=null, $unit=null, $elemen=null, 
    $tahun=null, $fieldList=null, $limit_offset=null, $limit_count = null, $lat_is_notempty_null=false)
    {
        $fieldString = $fieldList? join(", ",$fieldList): 
            "smc_survey.*, smc_unit.nama as nama_unit, desa.nama as nama_desa, skpd.nama as nama_skpd, 
            smc_elemen.keterangan as keterangan_elemen, smc_elemen.singkat_keterangan as singkat_keterangan_elemen";
        $sql = "SELECT $fieldString, SUBSTR(md5(smc_survey.id_survey), 10, 10) AS uri_code FROM smc_survey 
                LEFT JOIN smc_unit ON smc_survey.id_unit=smc_unit.id_unit 
                LEFT JOIN smc_unit AS desa ON smc_survey.id_desa=desa.id_unit 
                LEFT JOIN smc_unit AS skpd ON smc_survey.id_skpd=skpd.id_unit 
                LEFT JOIN smc_elemen ON smc_survey.id_elemen=smc_elemen.id_elemen WHERE 1";

        if ($id_survey) {
            $sql .= " and (smc_survey.id_survey='$id_survey' or SUBSTR(md5(smc_survey.id_survey), 10, 10)='$id_survey')";
            return $this->db->query($sql)->row();
        }
        if ($search) {
            $sql .= " and (smc_survey.nama_surveyor like '%$search%' or smc_survey.nama_ppl like '%$search%' or smc_survey.nama_narasumber like '%$search%')";
        }
        if ($unit) {
            $sql .= " and smc_survey.id_unit='$unit'";
        }
        if ($elemen) {
            $sql .= " and smc_survey.id_elemen='$elemen'";
        }
        if ($tahun) {
            $sql .= " and year(smc_survey.tgl_survey)='$tahun'";
        }        

        if ($lat_is_notempty_null){
            $sql .= " and smc_survey.lat IS NOT NULL AND smc_survey.lat <> ''";
        }

        $sql .= " order by smc_survey.last_update desc";

        if ($limit_offset !==null && $limit_count !==null){
            $sql .= " LIMIT $limit_offset, $limit_count";
        }
        
        return $this->db->query($sql)->result();
    }

    // mengambil data survei field
    public function get_survei_field_value($id_survey=null){
        $sql = "SELECT sfv.`id_survey_field`, sfv.`id_survey`, sfv.`id_field`, sfv.`value`, 
                    sf.key_field, sf.nama_field 
            FROM `smc_survey_field_value` sfv 
            LEFT JOIN smc_field sf ON sf.id_field = sfv.id_field 
            WHERE 1";

        if ($id_survey){
            // $sql .= " AND (sfv.id_survey='$id_survey' or SUBSTR(md5(sfv.id_survey), 10, 10)='$id_survey')";
            $sql .= " AND (sfv.id_survey='$id_survey')";
        }

        return $this->db->query($sql)->result();
    }

    // mengambil data survei field value
    public function get_survei_detail_field_value($id_survey_detail=null){
        $sql = "SELECT sdfv.`id_survey_detail_field`, sdfv.`id_survey_detail`, sdfv.`id_field`, sdfv.`value`, 
                    sf.key_field, sf.nama_field 
            FROM `smc_survey_detail_field_value` sdfv 
            LEFT JOIN smc_field sf ON sf.id_field = sdfv.id_field 
            WHERE 1";

        if ($id_survey_detail){
            $sql .= " AND (sdfv.id_survey_detail='$id_survey_detail')";
        }

        return $this->db->query($sql)->result();
    }

    public function get_elemen_survei($id_elemen_list=null)
    {
        $sql = "select distinct ss.id_elemen, se.keterangan 
            from smc_survey ss
                inner join smc_elemen se on se.id_elemen=ss.id_elemen
            WHERE 1";

        if ($id_elemen_list){
            $tmp = array();
            foreach ($id_elemen_list as $id_elemen){
                array_push($tmp, "se.id_elemen='$id_elemen'");
            }
            $elemen_query = implode(" OR ", $tmp);
            $sql .= "AND ($elemen_query)";
        }

        return $this->db->query($sql)->result();
    }

    public function hapus_survey($id_survey)
    {
        $sql = "delete from smc_survey where id_survey='$id_survey'";
        return $this->db->query($sql)->result();
    }

    public function count_detail_survei($id_survey, $search=null){
        $sql = "SELECT COUNT(*) as jumlah FROM smc_survey_detail WHERE smc_survey_detail.id_survey='$id_survey'";

        if ($search) {
            $sql .= " AND (smc_survey_detail.nama LIKE '%$search%' OR smc_survey_detail.potensi_utama LIKE '%$search%' OR smc_survey_detail.produk_olahan like '%$search%')";
        }

        return $this->db->query($sql)->row()->jumlah;
    }

    public function get_detail_survei($id_survey, $search=null, $limit_offset=null, $limit_count = null)
    {
        $sql = "select * from smc_survey_detail where smc_survey_detail.id_survey='$id_survey'";

        if ($search) {
            $sql .= " and (smc_survey_detail.nama like '%$search%' or smc_survey_detail.potensi_utama like '%$search%' or smc_survey_detail.produk_olahan like '%$search%')";
        }

        if ($limit_offset !==null && $limit_count !==null){
            $sql .= " LIMIT $limit_offset, $limit_count";
        }
        
        return $this->db->query($sql)->result();
    }

    public function hapus_detail_survey($id_detail=null, $id_survey=null)
    {
        $sql = "delete from smc_survey_detail";
        if ($id_detail) {
            $sql .= " where id_detail='$id_detail'";
            return $this->db->query($sql)->result();
        }
        if ($id_detail) {
            $sql .= " where id_survey='$id_survey'";
            return $this->db->query($sql)->result();
        }
    }

    public function get_luas_lahan($tahun, $unsur, $kecamatan)
    {
        $sql = "select sum(smc_survey_detail.luas_lahan) as value from smc_survey inner join smc_survey_detail on smc_survey.id_survey=smc_survey_detail.id_survey inner join smc_elemen on smc_elemen.id_elemen=smc_survey.id_elemen where year(smc_survey.created_date)='$tahun' and smc_survey.id_unit='$kecamatan' and lower(smc_elemen.keterangan) like '%$unsur%'";
        return $this->db->query($sql)->row();
    }

    public function get_tanam_3x_setahun($tahun, $unsur, $kecamatan, $kategori='padi')
    {
        $sql = "select sum(smc_survey_detail.luas_lahan) as value from smc_survey inner join smc_survey_detail on smc_survey.id_survey=smc_survey_detail.id_survey inner join smc_elemen on smc_elemen.id_elemen=smc_survey.id_elemen where year(smc_survey.created_date)='$tahun' and smc_survey.id_unit='$kecamatan' and lower(smc_elemen.keterangan) like '%$unsur%'";
        if ($kategori=='padi') {
            $sql .= " and TRIM(LOWER(smc_survey_detail.pola_tanam_1))='padi' and TRIM(LOWER(smc_survey_detail.pola_tanam_2))='padi' and TRIM(LOWER(smc_survey_detail.pola_tanam_3))='padi'";
        } else {
            $sql .= " and TRIM(LOWER(smc_survey_detail.pola_tanam_1))<>'padi' and TRIM(LOWER(smc_survey_detail.pola_tanam_2))<>'padi' and TRIM(LOWER(smc_survey_detail.pola_tanam_3))<>'padi'";
        }
        return $this->db->query($sql)->row();
    }

    public function get_tanam_2x_setahun($tahun, $unsur, $kecamatan, $kategori='padi')
    {
        $sql = "select sum(smc_survey_detail.luas_lahan) as value from smc_survey inner join smc_survey_detail on smc_survey.id_survey=smc_survey_detail.id_survey inner join smc_elemen on smc_elemen.id_elemen=smc_survey.id_elemen where year(smc_survey.created_date)='$tahun' and smc_survey.id_unit='$kecamatan' and lower(smc_elemen.keterangan) like '%$unsur%'";
        if ($kategori=='padi') {
            $sql .= " and (TRIM(LOWER(smc_survey_detail.pola_tanam_1))='padi' and TRIM(LOWER(smc_survey_detail.pola_tanam_2))='padi' or TRIM(LOWER(smc_survey_detail.pola_tanam_1))='padi' and TRIM(LOWER(smc_survey_detail.pola_tanam_3))='padi' or TRIM(LOWER(smc_survey_detail.pola_tanam_2))='padi' and TRIM(LOWER(smc_survey_detail.pola_tanam_3))='padi')";
        } else {
            $sql .= " and (TRIM(LOWER(smc_survey_detail.pola_tanam_1))<>'padi' and TRIM(LOWER(smc_survey_detail.pola_tanam_2))<>'padi' or TRIM(LOWER(smc_survey_detail.pola_tanam_1))<>'padi' and TRIM(LOWER(smc_survey_detail.pola_tanam_3))<>'padi' or TRIM(LOWER(smc_survey_detail.pola_tanam_2))<>'padi' and TRIM(LOWER(smc_survey_detail.pola_tanam_3))<>'padi')";
        }
        return $this->db->query($sql)->row();
    }

    public function get_tanam_1x_setahun($tahun, $unsur, $kecamatan, $kategori='padi')
    {
        $sql = "select sum(smc_survey_detail.luas_lahan) as value from smc_survey inner join smc_survey_detail on smc_survey.id_survey=smc_survey_detail.id_survey inner join smc_elemen on smc_elemen.id_elemen=smc_survey.id_elemen where year(smc_survey.created_date)='$tahun' and smc_survey.id_unit='$kecamatan' and lower(smc_elemen.keterangan) like '%$unsur%'";
        if ($kategori=='padi') {
            $sql .= " and (TRIM(LOWER(smc_survey_detail.pola_tanam_1))='padi' or TRIM(LOWER(smc_survey_detail.pola_tanam_2))='padi' or TRIM(LOWER(smc_survey_detail.pola_tanam_3))='padi')";
        } else {
            $sql .= " and (TRIM(LOWER(smc_survey_detail.pola_tanam_1))<>'padi' or TRIM(LOWER(smc_survey_detail.pola_tanam_2))<>'padi' or TRIM(LOWER(smc_survey_detail.pola_tanam_3))<>'padi')";
        }
        return $this->db->query($sql)->row();
    }

    public function get_jumlah_produksi($tahun, $unsur, $kecamatan)
    {
        $sql = "select sum(smc_survey_detail.jumlah_produksi) as value from smc_survey inner join smc_survey_detail on smc_survey.id_survey=smc_survey_detail.id_survey inner join smc_elemen on smc_elemen.id_elemen=smc_survey.id_elemen where year(smc_survey.created_date)='$tahun' and smc_survey.id_unit='$kecamatan' and lower(smc_elemen.keterangan) like '%$unsur%'";
        return $this->db->query($sql)->row();
    }

    public function get_jenis_potensi_utama($unsur, $kecamatan)
    {
        $sql = "select GROUP_CONCAT(DISTINCT trim(both ', ' from if(smc_survey_detail.potensi_utama='',null,lower(smc_survey_detail.potensi_utama))) ORDER BY smc_survey_detail.jumlah_produksi ASC SEPARATOR ',') as value from smc_survey inner join smc_survey_detail on smc_survey.id_survey=smc_survey_detail.id_survey inner join smc_elemen on smc_elemen.id_elemen=smc_survey.id_elemen where smc_survey.id_unit='$kecamatan' and lower(smc_elemen.keterangan) like '%$unsur%'";
        return $this->db->query($sql)->row();
    }

    public function get_jumlah_potensi_utama($tahun, $unsur, $kecamatan, $potensi_utama)
    {
        $sql = "select sum(smc_survey_detail.jumlah_produksi) as value from smc_survey inner join smc_survey_detail on smc_survey.id_survey=smc_survey_detail.id_survey inner join smc_elemen on smc_elemen.id_elemen=smc_survey.id_elemen where year(smc_survey.created_date)='$tahun' and smc_survey.id_unit='$kecamatan' and lower(smc_elemen.keterangan) like '%$unsur%' and smc_survey_detail.potensi_utama like '%$potensi_utama%'";
        return $this->db->query($sql)->row();
    }

    public function get_kategori_produksi($unsur, $kecamatan)
    {
        $sql = "select DISTINCT lower(smc_survey_detail.kategori_produk) as value from smc_survey inner join smc_survey_detail on smc_survey.id_survey=smc_survey_detail.id_survey inner join smc_elemen on smc_elemen.id_elemen=smc_survey.id_elemen where smc_survey_detail.kategori_produk is not null and smc_survey_detail.kategori_produk<>'' and smc_survey.id_unit='$kecamatan' and lower(smc_elemen.keterangan) like '%$unsur%'";
        return $this->db->query($sql)->result();
    }

    public function get_jenis_ternak($unsur, $kecamatan=null, $kategori_produk=null)
    {
        $sql = "select DISTINCT trim(lower(smc_survey_detail.jenis_ternak)) as ternak from smc_survey_detail inner join smc_survey on smc_survey.id_survey=smc_survey_detail.id_survey inner join smc_elemen on smc_elemen.id_elemen=smc_survey.id_elemen left join smc_ternak on smc_ternak.nama=smc_survey_detail.jenis_ternak where lower(smc_elemen.keterangan) like '%$unsur%'";
        if ($kategori_produk!=null) {
            $sql .= " and smc_survey_detail.kategori_produk='$kategori_produk'";
        }
        return $this->db->query($sql)->result();
    }

    public function get_jumlah_ternak($tahun, $unsur, $kecamatan, $ternak=null, $kategori_produksi=null)
    {
        $sql = "select sum(smc_survey_detail.jumlah_ternak) as value from smc_survey inner join smc_survey_detail on smc_survey.id_survey=smc_survey_detail.id_survey inner join smc_elemen on smc_elemen.id_elemen=smc_survey.id_elemen where year(smc_survey.created_date)='$tahun' and smc_survey.id_unit='$kecamatan' and lower(smc_elemen.keterangan) like '%$unsur%'";
        if ($ternak!=null) {
            $sql .= " and trim(lower(smc_survey_detail.jenis_ternak))='$ternak'";
        }
        if ($kategori_produksi!=null) {
            $sql .= " and smc_survey_detail.kategori_produk='$kategori_produksi'";
        }
        return $this->db->query($sql)->row();
    }
	
	//mencari tahun distinct dari survey UMKM
    public function get_distinct_year_survey()
    {
        $query = $this->db->query("select distinct year(tgl_survey) as tahun from smc_survey");
        $row3 = $query->result_array();

        return $row3;
    }

    //mencari rekap survey berdasarkan bidang survey UMKM
    public function get_rekap_survey_bidang_umkm()
    {
        $query = $this->db->query("SELECT smc_survey.id_survey, smc_survey.id_unit, smc_survey.tgl_survey, YEAR(smc_survey.tgl_survey) as tahun_survey, smc_survey_field_value.id_survey_field,UPPER (smc_survey_field_value.value) as bidang
        FROM smc_survey
        LEFT JOIN smc_survey_field_value
        ON smc_survey.id_survey = smc_survey_field_value.id_survey
        WHERE smc_survey_field_value.id_field IN ('1.9.31','1.10.31','1.11.31','1.12.31','1.13.31','1.14.31','1.15.31')");

        $result = $query->result_array();

        return $result;
    }
}
