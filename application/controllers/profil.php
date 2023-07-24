<?php class profil extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();             
        $this->load->model('info');    
    }

    public function index()
    {
        redirect('profil/manage');
    }

    public function manage()
    {
        $data['scripts'] = array( 
            "assets/smc/plugins/ckeditor/ckeditor.js",
            "assets/kediri/js/manage/manage_profil.js",
        );
        $data['styles'] = array();
        $data['posts'] =  $this->info->get_konten('', 'profil_kabupaten')->result();
        $this->load->view('admin/manage_profil.php', $data);
    }
}