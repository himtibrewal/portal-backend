<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['main'] = array(
    'get_country_zones' => array(
        'country_id' => array('method' => 'valid_id', 'message' => 'invalid_country')
    )
);
