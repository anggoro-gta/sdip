<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}class fields extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_fields');
    }

    public function get_fields(){
        $id_elemen_parent = isset($_POST['id_elemen_parent'])? $_POST['id_elemen_parent']:"";
        $fields = Globalsdb::get_fields($id_elemen_parent, TRUE);
        echo json_encode($fields);
    }

    public function get_fields_value(){
        $id_survey = isset($_POST['id_survey'])? $_POST['id_survey']:"";
        $field_values = Globalsdb::get_survey_field_value($id_survey);
        echo json_encode($field_values);
    }
}