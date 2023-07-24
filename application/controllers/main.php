<?php class main extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user');
    }
    public function index()
    {
        redirect();
    }
}
