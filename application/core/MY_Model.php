<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function resp($data = array(), $code = null, $message = '', $other = array()) {
        $ret_data = array();
        if ($code !== null) {
            $ret_data['error'] = $code === true || is_numeric($code);
            if (is_numeric($code))
                $ret_data['code'] = $code;
        }
        else
            $ret_data['error'] = $data === false;
        $ret_data['data'] = $data && $data !== true ? $data : array();
        $ret_data['message'] = $message;
        $ret_data['other'] = $other;

        return $ret_data;
    }

    function trans_start($always_roll_back = false) {
        return $this->db->trans_start($always_roll_back);
    }

    function trans_complete() {
        return $this->db->trans_complete();
    }

    function trans_status() {
        return $this->db->trans_status();
    }
}