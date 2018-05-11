<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pre_System {

    public function trim_input_data() {
        if (!empty($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] != 'GET' && !$_POST) {
            $json = file_get_contents("php://input");
            $json = json_decode($json, true);
            if (json_last_error() == JSON_ERROR_NONE)
                $_POST = $json;
        }

        if ($_POST)
            array_walk_recursive($_POST, function (&$value) {
                $value = trim($value);
            });
        elseif ($_GET)
            array_walk_recursive($_GET, function (&$value) {
                $value = trim($value);
            });
    }
}