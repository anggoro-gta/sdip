<?php class Rekap extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_cube');
        $this->load->model('m_params');
    }

    public function get_rekap(){
        $id_parameter = isset($_POST['id_parameter']) ?$_POST['id_parameter'] : NULL;

        $cube_list = $this->m_cube->get_cube_list();
        
        echo json_encode($cube_list);
    }

    public function get_rekap_params(){
        $use_select2_format = isset($_GET['use_select2_format']) ?(boolean)$_GET['use_select2_format'] : FALSE;
        $show_only_parent = isset($_GET['show_only_parent']) ?(boolean)$_GET['show_only_parent'] : FALSE;
        $id_parent = isset($_GET['id_parent']) ?$_GET['id_parent'] : FALSE;

        $params = (object)array(
            'show_only_parent' => $show_only_parent ,
            'id_parent' => $id_parent
        );
        
        $param_list = $this->m_params->get_kemiskinan_paramlist($params);

        if ($use_select2_format){
            $result = array();
            foreach($param_list as $param){
                array_unshift($result, array(
                    "text"=>$param->nama_parameter,
                    "id"=>$param->id_parameter
                ));
            }
        }else{
            $result = $param_list;
        }
        
        echo json_encode($result);
    }
}