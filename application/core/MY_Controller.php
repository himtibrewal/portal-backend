<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public $data = array();
    public $date = '';
    public $web_url = '';
    private $class = '';
    private $method = '';
    private $class_method = '';

    function __construct() {
        parent::__construct();

        $allowed_url = $this->config->item('allowed_url');
        $pos = !empty($_SERVER['HTTP_ORIGIN']) ? strpos($_SERVER['HTTP_ORIGIN'], $allowed_url) : false;

        $this->web_url = $pos !== false && $pos == strlen($_SERVER['HTTP_ORIGIN']) - strlen($allowed_url) ? $_SERVER['HTTP_ORIGIN'] : ($_SERVER['REQUEST_SCHEME'] . '://' . $allowed_url);

        header('Access-Control-Allow-Origin: ' . $this->web_url);
        header('Access-Control-Allow-Credentials: true');

        /* Handles pre flight request */
        if (!empty($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('HTTP/1.1 200 OK');
            header('Access-Control-Allow-Headers: Content-Type, Origin, Accept');
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            exit;
        }

        $this->date = date('Y-m-d H:i:s');

        $this->class = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->class_method = $this->class . '/' . $this->method;

        $this->data = !empty($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET' ? $_GET : $_POST;

        // $this->get_user_by_token();
        $this->check_access();
        $this->validate();
    }

    function check_access() {
        $user_id = $this->session->userdata('user_id');
        $role_id = $this->session->userdata('role_id');

        $this->config->load('access');

        if (in_array($this->class_method, $this->config->item('public_access')))
            return true;

        if (!$user_id) {
            $data = array('login' => false);
            if (ENVIRONMENT != ENV_PROD)
                $data['env'] = ENVIRONMENT;
            $this->resp($data, 500, 'not_logged_in');
        }

        if (in_array($this->class_method, $this->config->item('common_private_access')))
            return true;

        $access_list = $this->config->item('private_access');

        if (empty($access_list[$role_id]) || !in_array($this->class_method, $access_list[$role_id]))
            $this->resp(array(), true, 'no_permission');

        return true;
    }

    function get_user_by_token() {
        $public_methods = $this->config->item('public_methods');
        if (in_array($this->class_method, $public_methods))
            return true;

        if (isset($_SERVER['HTTP_ACCESS_TOKEN']) && is_string($_SERVER['HTTP_ACCESS_TOKEN']) && !is_numeric($_SERVER['HTTP_ACCESS_TOKEN'])) {
            $token = $_SERVER['HTTP_ACCESS_TOKEN'];
            $data = $this->user_model->get_user_by_token($token);
            if (!$data)
                $this->resp(array(), 500, 'invalid_token');
            $this->set_user($data);
        }

        return true;
    }

    function set_user($user_data, $is_login = false) {
        $session_data = array(
            'user_id' => $user_data['user_id'],
            'role_id' => $user_data['role_id']
        );

        $this->session->set_userdata($session_data);

        if (ENVIRONMENT != ENV_PROD)
            $user_data['env'] = ENVIRONMENT;

        return $user_data;
    }

    function generate_token() {
        $token = openssl_random_pseudo_bytes(16);
        $token = bin2hex($token);

        return $token;
    }

    function resp($data = array(), $code = null, $message = '', $other = array()) {
        if ($code == null && $message == '' && isset($data['data']) && isset($data['error']) && isset($data['message'])) {
            $code = isset($data['code']) ? $data['code'] : $data['error'];
            $message = $data['message'];
            $other = $data['other'];
            $data = $data['data'];
        }
        $ret_data = array();
        if ($code !== null) {
            $ret_data['error'] = $code === true || is_numeric($code);
            if (is_numeric($code))
                $ret_data['code'] = $code;
        }
        else
            $ret_data['error'] = $data === false;
        $ret_data['data'] = $data && $data !== true ? $data : array();
        if ($message)
            $ret_data['message'] = ($msg = lang($message)) ? $msg : $message;

        foreach ($other as $k => $v)
            $ret_data[$k] = $v;

        $ret_data['_ts'] = time() . substr(explode(' ', microtime())[0], 2, 3);

        header('Content-type:application/json;charset=utf-8');
        exit(json_encode($ret_data, JSON_NUMERIC_CHECK));
    }

    function validate() {
        $this->config->load('validate');
        $valid_arr = $this->config->item($this->class);
        if (!$valid_arr || !isset($valid_arr[$this->method]))
            return true;

        foreach ($valid_arr[$this->method] as $field => $valid_data) {
            if (!$valid_data['method']($this->data, $field)
                || valid_number($valid_data, 'minlength') && strlen($this->data[$field]) < $valid_data['minlength']
                || valid_array($valid_data, 'options') && !in_array($this->data[$field], $valid_data['options'])
                || valid_number($valid_data, 'min') && $this->data[$field] < $valid_data['min']
            ) {
                if (array_key_exists('default', $valid_data))
                    $this->data[$field] = $valid_data['default'];
                else {
                    $code = isset($valid_data['code']) ? $valid_data['code'] : true;
                    $message = $valid_data['message'] ? $valid_data['message'] : '';
                    if (ENVIRONMENT != ENV_PROD)
                        $message .= " ($field)";
                    $this->resp(array(), $code, $message);
                }
            }
        }

        return true;
    }
}