<?php class profil extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();        
        $this->load->model('info');
        $this->load->model('m_master');
    }

    public function save_profil_kabupaten(){
        $data = array( 
            "id_user"=>$this->session->userdata('smc_user_id'),
            "kategori_konten"=>"profil_kabupaten",
            "keterangan"=>isset($_POST['konten'])?$_POST['konten']:null,
            "is_publish"=>1
        );

        if (isset($_POST['id_konten'])) {
            $status = $this->m_master->update("smc_konten", $data, array("id_konten"=>$_POST['id_konten']));
        } else { 
            $data["id_konten"] = $this->info->get_next_id("smc_konten", "id_konten");

            // tambahkan data ke table survey
            $status =  $this->m_master->replace("smc_konten", $data);
        }
        $return_val = array(
            "status" => $status? "success":"failed"
        );
        echo json_encode($return_val);
    }
}