<?php

class ntp extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('info');
        $this->load->model('m_ntp_komoditas');
        $this->load->model('m_master');
        $this->info->cek_auth('3');
    }

    private function _buildKomoditasTreeArray($komoditasAllRecords){
        $komoditasParent = array_filter($komoditasAllRecords, function($komoditas){
            return $komoditas->id_parent == null;
        });
        $komoditasAndChildren = array();
        foreach ($komoditasParent as $kmd){
            $id_ntp_komoditas = $kmd->id_ntp_komoditas;
            $kmd->child = array_values(array_filter($komoditasAllRecords, function($komoditas) use($id_ntp_komoditas){
                return $komoditas->id_parent == $id_ntp_komoditas;
            }));
            array_push($komoditasAndChildren, $kmd);
        }
        return $komoditasAndChildren;
    }

    public function index()
    {
        $data['scripts'] = array(
            "assets/smc/select2/js/select2.full.min.js",
            "assets/smc/pagination.js",
            "assets/smc/treeTable.js",
            "assets/kediri/js/manage/manage_ntp.js"
        );
        $data['scripts_post'] = array("https://maps.googleapis.com/maps/api/js?key=AIzaSyD0xRNnRByY45XTL3vL0M34ywwn5L2eF2k&callback=initMap" );
        $data['styles'] = array("assets/smc/select2/css/select2.min.css");

        $komoditasPemasukan = $this->m_ntp_komoditas->get_ntp_komoditas(null, "pemasukan");
        $komoditasPengeluaran = $this->m_ntp_komoditas->get_ntp_komoditas(null, "pengeluaran");

        $data['index_komoditas'] = array(
            "pemasukan" => $this->_buildKomoditasTreeArray($komoditasPemasukan),
            "pengeluaran" => $this->_buildKomoditasTreeArray($komoditasPengeluaran)
        );

        $this->load->view('ntp/index.php', $data);
    }
}