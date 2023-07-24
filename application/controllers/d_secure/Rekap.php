<?php class Rekap extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_summary');
        // $this->info->cek_auth('3');
    }

    public function create_rekap_kemiskinan(){
        $result = $this->m_summary->create_rekap_kemiskinan();

        echo json_encode($result);
    }
}