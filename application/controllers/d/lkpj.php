<?php class lkpj extends CI_Controller
{
    public function __construct()
    {
        parent::__construct(); 
        $this->load->model('m_lkpj');  
    }

    public function get_lkpj_data(){
        $unit=$_POST['unit'];
        $id_kecamatan=$_POST['id_kecamatan'];
        $id_elemen=isset($_POST['unsur'])? $_POST['unsur']:"0";
        
        $id_unit='201709000005'; //default id_unit kab kediri
        if($unit=='kecamatan'){
            $id_unit=$id_kecamatan;
        }

        $dataFromDb = $this->m_lkpj->get_lkpj(null,$id_unit,$id_elemen);
        
        $data = array();
        $tahun_rekap = array();
        $chartTitle = "";

        foreach($dataFromDb as $d){
            array_push($data, $d->jumlah);
            array_push($tahun_rekap, $d->periode);
            $chartTitle = sprintf("%s (%s)",$d->keterangan_elemen,$d->nama_unit);
        }       
        
        $result = array(
            "chartTitle"=>$chartTitle,
            "data"=>$data,
            "tahun"=>$tahun_rekap
        );
        echo json_encode($result);
    }

    public function get_lkpj_elemen(){
        $key = isset($_GET['q']) ? $_GET['q']:"";
        $dataFromDb = $this->m_lkpj->get_elemen_lkpj($key);
        $data = array();

        foreach($dataFromDb as $d){
            $tmp = array(
                "text" => $d->keterangan, 
                "kategori" => $d->kategori, 
                "id" => $d->id_elemen
            );
            array_push($data, $tmp);
        }
        echo json_encode($data);
    }
}