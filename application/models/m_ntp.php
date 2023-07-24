<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class m_ntp extends CI_Model
{
    public function count_ntp_records($search = null, $unit = null, $elemen = null)
    {
        $sql = "SELECT COUNT(id_ntp_record) as jumlah FROM smc_ntp_records snr WHERE 1";

        if ($search) {
            $sql .= " and (snr.nama_responden like '%$search%')";
        }
        if ($unit) {
            $sql .= " and snr.id_unit='$unit'";
        }
        if ($elemen) {
            $sql .= " and snr.id_elemen='$elemen'";
        }

        return $this->db->query($sql)->row()->jumlah;
    }

    public function get_ntp_records($id_ntp_record = null, $search = null, $unit = null, $elemen = null,
                                    $tahun = null, $fieldList = null, $limit_offset = null, $limit_count = null, $lat_is_notempty_null = false)
    {
        $fieldString = $fieldList ? join(", ", $fieldList) :
            "snr.*, smc_unit.nama as nama_unit, desa.nama as nama_desa, smc_elemen.keterangan as keterangan_elemen, 
	        smc_elemen.singkat_keterangan as singkat_keterangan_elemen";
        $sql = "SELECT $fieldString, SUBSTR(md5(snr.id_ntp_record), 10, 10) AS uri_code FROM smc_ntp_records snr 
                LEFT JOIN smc_unit ON snr.id_unit=smc_unit.id_unit 
                LEFT JOIN smc_unit AS desa ON snr.id_desa=desa.id_unit 
                LEFT JOIN smc_elemen ON snr.id_elemen=smc_elemen.id_elemen WHERE 1";

        if ($id_ntp_record) {
            $sql .= " and (snr.id_ntp_record='$id_ntp_record' or SUBSTR(md5(snr.id_ntp_record), 10, 10)='$id_ntp_record')";
            return $this->db->query($sql)->row();
        }
        if ($search) {
            $sql .= " and (snr.nama_responden like '%$search%')";
        }
        if ($unit) {
            $sql .= " and snr.id_unit='$unit'";
        }
        if ($elemen) {
            $sql .= " and snr.id_elemen='$elemen'";
        }
        if ($tahun) {
            $sql .= " and year(snr.tgl_survey)='$tahun'";
        }

        if ($lat_is_notempty_null) {
            $sql .= " and snr.lat IS NOT NULL AND snr.lat <> ''";
        }

        $sql .= " order by snr.last_update desc";

        if ($limit_offset !== null && $limit_count !== null) {
            $sql .= " LIMIT $limit_offset, $limit_count";
        }

        return $this->db->query($sql)->result();
    }

    public function get_distinct_ntp_element(){
        $sql = "select distinct id_elemen from smc_ntp_rekap";
        return $this->db->query($sql)->result();
    }

    public function get_distinct_ntp_year(){
        $sql = "select distinct year(tgl_data) as tahun from smc_ntp_rekap ";
        return $this->db->query($sql)->result();
    }

    public function hapus_ntp($id_ntp_record)
    {
        $sql = "delete from smc_ntp_records where id_ntp_record='$id_ntp_record'";
        return $this->db->query($sql);
    }

    public function hapus_ntp_pengeluaran($id_ntp_record)
    {
        $sql = "delete from smc_ntp_pengeluaran where id_ntp_record='$id_ntp_record'";
        return $this->db->query($sql);
    }

    public function hapus_ntp_pendapatan($id_ntp_record)
    {
        $sql = "delete from smc_ntp_pendapatan where id_ntp_record='$id_ntp_record'";

        return $this->db->query($sql);
    }

    public function get_ntp_rekap($tahun, $id_elemen=null, $elemen_keterangan=null, $limit_offset = null, $limit_count = null)
    {
        $sql = "SELECT * from smc_ntp_rekap snr
                join smc_elemen se on se.id_elemen = snr.id_elemen
                where 1";

        if ($tahun){
            $sql .= " and year(tgl_data) = $tahun";
        }

        if ($id_elemen) {
            $sql .= " and snr.id_elemen = '$id_elemen'";
        }

        if ($elemen_keterangan) {
            $sql .= " and lower(se.singkat_keterangan) = '$elemen_keterangan'";
        }

        if ($limit_offset !== null && $limit_count !== null) {
            $sql .= " LIMIT $limit_offset, $limit_count";
        }

        return $this->db->query($sql)->result();
    }
}