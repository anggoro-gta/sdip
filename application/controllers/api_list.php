<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
} 
class api_list extends CI_Controller {
    //TODO: Semua method di masterx harus di-merge kesini

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user');
        $this->load->model('m_master');
        $this->load->model('m_rekap');
        $this->load->model('m_survei');
    }
    
    public function logon()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            if ($authenticatedUser = $this->user->authenticate($_POST['username'], $_POST['password'])) {
                $result = array("status"=>"success");
            } else {
                $result = array("status"=>"failed","message"=>"Wrong Username or Password");
            }
        } else {
            $result = array("status"=>"failed","message"=>"No Username and Password");
        }
        echo json_encode($result);
    }
    
    public function get_kemiskinan_by_KK()
    {
        if (isset($_POST['no_kk'])) {
            $no_kk = trim($_POST['no_kk']);
            $data =  $this->m_master->get_kemiskinan( $no_kk);
            $result = $data ? array("result"=>$data) : array("message"=>"Tidak ada data yang sesuai");;
        } else {
            $result = array("message"=>"Tidak ada nomor KK yang dikirim");
        }
        echo json_encode($result);
    }
	
    private function update_kemiskinan($no_kk=null)
    {
        $upload = $this->upload_image($no_kk);
        if($upload['status'] == "success"){
            $image = $upload['filenames'];
            $data =  array("lat" => $_POST['lat'], "lon" => $_POST['long'], "image" => json_encode($image), "imei"=>isset($_POST['imei']) ? $_POST['imei'] : "", "insert_date"=>date("Y-m-d H:i:s"));
            if($this->m_master->update("kemiskinan_tmp", $data, array("nomor_id_bdt"=>$no_kk))){
                $result = array("status"=>"success", "message"=>"Update data sukses", "data"=>$this->m_master->get_kemiskinan( $no_kk));
            } else {
                $result = array("status"=>"failed", "message"=>"Update data gagal");
            }
        } else {
            $result = array("status"=>"failed", "message"=>$upload['messages']);
        }
        echo json_encode($result);
    }
    
    public function insert_kemiskinan()
    {
        $emptyField = "";
        if (!isset($_POST['no_kk'])){
            $emptyField .= "'Nomor KK' ";
        }
        if (!isset($_POST['lat'])){
            $emptyField .= "'Latitude' ";
        }
        if (!isset($_POST['long'])){
            $emptyField .= "'Longitude' ";
        }
        if (!isset($_FILES['image']['name'])){
            $emptyField .= "'Image Files'";
        }
        if ($emptyField){
            $result = array("status"=>"failed", "message"=>"Ada field yang kosong: ".$emptyField);  
        } else {
            $no_kk = trim($_POST['no_kk']);
            if($this->m_master->get_kemiskinan($no_kk)){
                $this->update_kemiskinan($no_kk);
                return;
            }
            else {
                $id_survey = $this->m_master->get_survey_ID();
                $upload = $this->upload_image($no_kk);
                if($upload['status'] == "success"){
                    $image = $upload['filenames'];
                    $data =  array("id_survey"=>$id_survey, "nomor_id_bdt"=>$no_kk, "lat" => $_POST['lat'], "lon" => $_POST['long'], "image" => json_encode($image), "imei"=>isset($_POST['imei']) ? $_POST['imei'] : "", "insert_date"=>date("Y-m-d H:i:s"));
                    if($this->m_master->insert("kemiskinan_tmp", $data)){
                        $result = array("status"=>"success", "message"=>"Insert data sukses", "data"=>$this->m_master->get_kemiskinan( $no_kk));
                    } else {
                        $result = array("status"=>"failed", "message"=>"Insert data gagal");
                    }
                } else {
                    $result = array("status"=>"failed", "message"=>$upload['messages']);
                }
            }
        }
        echo json_encode($result);
    }
    
    private function upload_image($no_kk)
    {
        $target_dir = 'assets/survey/'.$no_kk."/";
        if(is_dir($target_dir)) $this->delete_folder($target_dir);
        mkdir($target_dir, 0777, true);
        if( isset($_FILES['image']['name'])) {
            $total_files = count($_FILES['image']['name']);
            $error = false;
            $msg = "";
            for($i = 0; $i < $total_files; $i++) {
                if(isset($_FILES['image']['name'][$i]) && $_FILES['image']['size'][$i] > 0) {
                    $original_filename = $_FILES['image']['name'][$i];
                    $target = $target_dir . basename($original_filename);
                    $tmp  = $_FILES['image']['tmp_name'][$i];
                    if(move_uploaded_file($tmp, $target)) {
                        $filenames[] = $original_filename;
                    } else {
                        $error = true;
                        $msg .= "'Upload gambar ke-".$i." gagal' ";
                    }
                } else {
                    $error = true;
                    $msg .= "'File ke-".$i." tidak ada' ";
                }
            }
            
            if(!$error) {
                $result = array("status"=>"success", "filenames"=>$filenames);
            } else {
                $result = array("status"=>"failed", "message"=>$msg);
            }
        } else {
            $result = array("status"=>"failed", "message"=>"Tidak ada file yang dikirim");
        }
        return $result;
    }
    
    private function delete_folder($dirPath) {
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}
