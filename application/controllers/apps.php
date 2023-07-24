<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
} class apps extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('info');
        $this->load->model('conf');
        $this->load->model('user');
        $this->info->cek_auth('3');
    }
    public function index()
    {
        $data['scripts'] = array( "assets/smc/plugins/chartjs/Chart.min.js", "assets/smc/plugins/mixitup/mixitup.min.js", "assets/smc/plugins/slimScroll/jquery.slimscroll.min.js", "assets/smc/js/treeTable.js", "assets/smc/js/dashboard.js" );
        $data['scripts_post'] = array( "https://maps.googleapis.com/maps/api/js?key=AIzaSyD0xRNnRByY45XTL3vL0M34ywwn5L2eF2k&callback=initMap" );
        $data['unit'] =  $this->conf->get_unit(null, null, null, "Kabupaten Kediri");
        $data["unsur_profil"] =  $this->conf->get_unsur_home();
        $data['post'] = '';
        $this->load->view('home.php', $data);
    }
    public function profil()
    {
        $data['scripts'] = array( "assets/smc/js/profil.js" );
        $data['user'] =  $this->user->get_user($this->session->userdata('smc_user_id'));
        $this->load->view('profil.php', $data);
    }
    public function profil_update()
    {
        $data = array($_POST['name']=>$_POST['value']);
        $where = array("id_user"=>$this->session->userdata('smc_user_id'));
        $this->user->update_user($data, $where);
        if ($_POST['name']=="email") {
            $this->user->update_user(array("user_name"=>$_POST['value']), $where);
        }
        if ($_POST['name']=="nama") {
        }
        echo $_POST['value'];
    }
    public function profil_foto()
    {
        $target_dir = "upload/user/".$this->session->userdata('smc_user_id')."/";
        $target_dir_db = "user/".$this->session->userdata('smc_user_id')."/";
        $target_name = date("YmdHis");
        $imageFileType = end((explode(".", $_FILES["file_foto"]["name"])));
        $target_file = $target_dir . $target_name. "." . $imageFileType;
        $target_file_db = $target_dir_db. $target_name. "." . $imageFileType;
        $uploadOk = 1;
        $message = "";
        if ($_FILES["file_foto"]["size"] > 500000) {
            $message .="Sorry, your file is too large.";
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $message .= "Sorry, your file is ".$imageFileType." not allowed only JPG, JPEG, PNG & GIF files.";
            $uploadOk = 0;
        }
        if ($uploadOk === 0) {
            echo json_encode(array("status"=>$uploadOk, "message"=>$message));
        } else {
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            if (move_uploaded_file($_FILES["file_foto"]["tmp_name"], $target_file)) {
                $data = array("foto"=>$target_file_db);
                $where = array("id_user"=>$this->session->userdata('smc_user_id'));
                $this->user->update_user($data, $where);
                echo json_encode(array("status"=>$uploadOk, "path"=>$target_file));
            } else {
                echo json_encode(array("status"=>0, "message"=>"Sorry, there was an error uploading your file."));
            }
        }
    }
}
