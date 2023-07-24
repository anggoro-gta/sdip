<?php

class ntp extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('info');
        $this->load->model('m_master');
        $this->load->model('m_ntp');
        $this->load->model('m_ntp_komoditas');
        $this->info->cek_auth('3');
    }

    public function get_ntp_records()
    {
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $limit = isset($_POST['rowPerPage']) ? $_POST['rowPerPage'] : 10;
        $search = isset($_POST['search']) ? $_POST['search'] : null;
        $unit = isset($_POST['unit']) ? $_POST['unit'] : null;
        $elemen = isset($_POST['elemen']) ? $_POST['elemen'] : null;

        $offset = ($page - 1) * $limit;

        $count_survei = $this->m_ntp->count_ntp_records($search, $unit, $elemen);
        $data = $this->m_ntp->get_ntp_records(null, $search, $unit, $elemen, null, null, $offset, $limit);

        $result = array(
            "jumlah" => $count_survei,
            "data" => $data == null ? array() : $data
        );

        echo json_encode($result);
    }

    public function get_ntp_komoditas_value(){
        $dataInput = json_decode(file_get_contents('php://input'), true);

        if (empty($dataInput)) return null;

        $id_ntp_record = isset($dataInput['id_ntp_record']) ? $dataInput['id_ntp_record']: 0;

        $result = $this->m_ntp_komoditas->get_ntp_komoditas_value(null, $id_ntp_record);
        echo json_encode($result);
    }

    public function simpan_ntp()
    {
        $dataInput = json_decode(file_get_contents('php://input'), true);

        if (!isset($dataInput['keterangan_elemen']) || $dataInput['keterangan_elemen'] == null) {
            echo 'Data is not processed!';
            exit();
        }

        $data = array(
            "id_elemen" => isset($dataInput['id_elemen']) ? $dataInput['id_elemen'] : null,
            "id_unit" => $dataInput['id_unit'],
            "id_desa" => isset($dataInput['id_desa']) ? $dataInput['id_desa'] : null,
            "tgl_survey" => $dataInput['tgl_survey'],
            "nama_responden" => $dataInput['nama_responden'],
            "jumlah_anggota_kk" => $dataInput['jumlah_anggota_kk'],
            "jenis_kelamin" => $dataInput['jenis_kelamin'],
            "usia" => $dataInput['usia'],
            "pendidikan" => $dataInput['pendidikan'],
            "telp" => $dataInput['telp'],
            "lat" => isset($dataInput['lat']) ? $dataInput['lat'] : "",
            "lon" => isset($dataInput['lon']) ? $dataInput['lon'] : "",
            "id_user" => $this->session->userdata('smc_user_id')
        );

        if (isset($dataInput['id_ntp_record'])) {
            // update ntp record
            $data["id_ntp_record"] = $dataInput['id_ntp_record'];
            $status = $this->m_master->update("smc_ntp_records", $data, array("id_ntp_record" => $data['id_ntp_record']));
        } else {
            // tambahkan data ke table ntp record
            $data["id_ntp_record"] = $this->info->get_next_id("smc_ntp_records", "id_ntp_record");
            $status = $this->m_master->replace("smc_ntp_records", $data);
        }

        // simpan perubahan pada komoditas
        $status &= $this->_saveKomoditasValueChanges($dataInput['pemasukanChanges'], $data["id_ntp_record"]);
        $status &= $this->_saveKomoditasValueChanges($dataInput['pengeluaranChanges'], $data["id_ntp_record"]);

        echo json_encode(array(
            "id_ntp_record" => $data["id_ntp_record"],
            "status" => $status
        ));
    }

    private function _saveKomoditasValueChanges($komoditasChanges, $id_ntp_record)
    {
        if (empty($komoditasChanges)) return true; // nothing to be updated on database

        $status = true;
        foreach ($komoditasChanges as $id_ntp_komoditas => $komoditasValues) {
            $ntp_komoditas_value = $this->m_ntp_komoditas->get_ntp_komoditas_value(null, $id_ntp_record, $id_ntp_komoditas);

            $data = array(
                "id_ntp_record" => $id_ntp_record,
                "id_ntp_komoditas" => $id_ntp_komoditas
            );
            if (isset($komoditasValues['kualitas'])) $data["kualitas"] = $komoditasValues['kualitas'];
            if (isset($komoditasValues['satuan'])) $data["satuan"] = $komoditasValues['satuan'];
            if (isset($komoditasValues['jumlah'])) $data["jumlah"] = $komoditasValues['jumlah'];

            if ($ntp_komoditas_value) {
                $data["id_ntp_komoditas_value"] = $ntp_komoditas_value->id_ntp_komoditas_value;
                $status &= $this->m_master->update("smc_ntp_komoditas_value", $data, array("id_ntp_komoditas_value" => $data['id_ntp_komoditas_value']));
            } else {
                $status &= $this->m_master->replace("smc_ntp_komoditas_value", $data);
            }
        }
        return $status;
    }

    public function hapus_ntp()
    {
        $id_ntp_record = isset($_POST['id_ntp_record']) ? $_POST['id_ntp_record'] : 0;
        $this->m_ntp->hapus_ntp_pendapatan($id_ntp_record);
        $this->m_ntp->hapus_ntp_pengeluaran($id_ntp_record);
        $this->m_ntp->hapus_ntp($id_ntp_record);
    }
}