<?php class auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user');
    }
    public function logon()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            if ($authenticatedUser = $this->user->authenticate($_POST['username'], $_POST['password'])) {
                $sess = array( 
                    'smc_level' => $authenticatedUser->id_role, 
                    'smc_user_id' => $authenticatedUser->id_user, 
                    'smc_nama' => $authenticatedUser->nama, 
                    'smc_uname' => $authenticatedUser->user_name, 
                    'smc_email' => $authenticatedUser->email, 
                    'smc_role' => strtolower($authenticatedUser->role), 
                    'smc_id_unit_kerja' => $authenticatedUser->id_unit_kerja, 
                    'smc_kategori_unit'=>$authenticatedUser->kategori_unit, 
                    'smc_nama_unit_kerja'=>$authenticatedUser->nama_unit, 
                    'smc_id_parent_unit'=>$authenticatedUser->id_parent_unit, 
                    'smc_nama_parent_unit'=>$authenticatedUser->nama_parent_unit, 
                    'smc_avatar' => $authenticatedUser->foto
                );
                $this->session->set_userdata($sess);
                redirect('apps');
            } else {
                $data['msglogin'] = '<div class="alert alert-block alert-danger fade in">Invalid login. Enter username and password correctly.</div>';
                $this->load->view('auth.php', $data);
            }
        }
    }
    public function index()
    {
        if (isset($_POST['b_submit'])) {
            $this->logon();
            exit();
        }
        $data['msglogin'] = "";
        $this->load->view('auth.php', $data);
    }
    public function logoff($username=null)
    {
        $this->session->sess_destroy();
        redirect();
    }
}
