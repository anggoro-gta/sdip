<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class survei extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('info');
        $this->load->model('m_survei');
        // $this->info->cek_auth('3');
    }

    public function get_survei($additional_id_fields = null)
    {
        $page = isset($_POST['page']) ?  $_POST['page'] : 1;
        $limit = isset($_POST['rowPerPage']) ?  $_POST['rowPerPage'] : 10;
        $offset = ($page - 1) * $limit;

        $count_survei = $this->m_survei->count_survei($_POST['search'], $_POST['unit'], $_POST['elemen']);
        $data =  $this->m_survei->get_survei(null, $_POST['search'], $_POST['unit'], $_POST['elemen'], null, null, $offset, $limit);

        $result = array(
            "jumlah" => $count_survei,
            "data" => $data == null ? array() : $data
        );

        echo json_encode($result);
    }
}
