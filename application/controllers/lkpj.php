<?php class lkpj extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();        
    }

    public function index()
    {
        $data['scripts'] = array( 
            "assets/smc/select2/js/select2.full.min.js", 
            "assets/smc/chartjs/Chart.min.js", 
            "assets/smc/treeTable.js");
        $data['styles'] = array( "assets/smc/select2/css/select2.min.css" );
        $this->load->view('lkpj/index.php', $data);
    }    
}