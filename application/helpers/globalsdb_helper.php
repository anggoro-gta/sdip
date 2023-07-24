<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Application specific global variables
class Globalsdb
{
    private static $CI = null;   

    private static $initialized = false;

    private static function initialize()
    {
        if (self::$initialized)
            return;

        self::$CI = get_instance();         
                
        self::$initialized = true;
    }

    // mengubah kolom bayangan dari table survei_field menjadi kolomnya survei
    public static function get_survey_field_value($survei_id){
        self::initialize();
        self::$CI->load->model('m_survei');

        $field_value_list = self::$CI->m_survei->get_survei_field_value($survei_id);      
        
        // foreach($fields as $f){
        //     foreach($field_value_list as $fv){
        //         if ($fv->key_field == $f->key_field){
        //             $survei->{$f->key_field}= $fv->value;
        //             break;                
        //         }   
        //     }                    
        // }

        // $field_values = new STDClass();
        // foreach($field_value_list as $fv){
        //     $field_values->{$fv->key_field}= $fv->value;   
        // }
        // return $field_values;
        return $field_value_list;
    }

    public static function get_survey_detail_field_value($id_survey_detail){
        self::initialize();
        self::$CI->load->model('m_survei');

        $field_value_list = self::$CI->m_survei->get_survei_detail_field_value($id_survey_detail);
        return $field_value_list;
    }

    public static function get_fields($id_elemen_parent=null, $visible=TRUE){
        self::initialize();
        self::$CI->load->model('m_fields'); 

        $fields = self::$CI->m_fields->get_field_list($id_elemen_parent, null, $visible);  
        
        return $fields;
    }

    public static function get_detail_fields($singkat_keterangan_elemen){
        self::initialize();
        self::$CI->load->model('m_fields'); 

        $fields = array();
        $elemen = strtolower($singkat_keterangan_elemen);
        switch ($elemen) {
            case 'pertanian':
                $fields = self::$CI->m_fields->get_field_from_parent("elemen_survei_detail_pertanian");     
                break;
            case 'peternakan':
                $fields = self::$CI->m_fields->get_field_from_parent("elemen_survei_detail_peternakan");     
                break;
            // case 'pendidikan':
            //     $fields = self::$CI->m_fields->get_field_from_parent("elemen_survei_detail_pendidikan");    
            //     break;
            case 'sosial':
                $fields = self::$CI->m_fields->get_field_from_parent("elemen_survei_detail_kemiskinan");     
                break;
            case 'perikanan':
                $fields = self::$CI->m_fields->get_field_from_parent("elemen_survei_detail_perikanan");     
                break;
            case 'perkebunan':
                $fields = self::$CI->m_fields->get_field_from_parent("elemen_survei_detail_perkebunan");     
                break;
            case 'kesehatan':
                $fields = self::$CI->m_fields->get_field_from_parent("elemen_survei_detail_kesehatan");     
                break;
            default:
                # code...
                break;
        }
        return $fields;
    }
}