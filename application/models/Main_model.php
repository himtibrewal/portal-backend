<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    function upload_excel($upload_path, $index, $prefix = false) {
        return $this->upload_file($upload_path, $index, $prefix, array(EXCEL_FILE));
    }

    function upload_pdf($upload_path, $index, $prefix = false, $file_name = false) {
        return $this->upload_file($upload_path, $index, $prefix, array(PDF_FILE), $file_name);
    }

    function upload_document($upload_path, $index, $prefix = false) {
        return $this->upload_file($upload_path, $index, $prefix, array(DOC_FILE));
    }

    function upload_video($upload_path, $index, $prefix = false) {
        return $this->upload_file($upload_path, $index, $prefix, array(VIDEO_FILE));
    }

    function upload_audio($upload_path, $index, $prefix = false) {
        return $this->upload_file($upload_path, $index, $prefix, array(AUDIO_FILE));
    }

    function upload_image($upload_path, $index, $prefix = false) {
        return $this->upload_file($upload_path, $index, $prefix, array(IMAGE_FILE));
    }

    function upload_file($upload_path, $index, $prefix = false, $file_types = false, $file_name = false) {
        if (!$file_name)
            $file_name = get_new_file_name($prefix) . '.' . pathinfo($_FILES[$index]['name'], PATHINFO_EXTENSION);

        $file_type_groups = $this->config->item('file_type_groups');
        if (is_array($file_types)) {
            foreach ($file_types as $k => $v)
                $file_types[$k] = $file_type_groups[$v];

            $file_types = implode('|', $file_types);
        }
        elseif ($file_types)
            $file_types = $file_type_groups[$file_types];
        else
            $file_types = implode('|', $file_type_groups);

        $this->load->library('upload');
        $this->upload->initialize(array(
            'upload_path'      => $upload_path,
            'allowed_types'    => $file_types,
            'max_size'         => 100000,
            'detect_mime'      => true,
            'file_ext_tolower' => true,
            'file_name'        => $file_name,
            'overwrite'        => true
        ));

        if ($this->upload->do_upload($index)) {
            $file_data = $this->upload->data();
            if ($file_data['file_ext'] == '.amr' && $file_data['file_type'] == 'application/octet-stream')
                $file_data['file_type'] = 'audio/amr';

            return $this->resp($file_data, false, 'file_uploaded');
        }

        return $this->resp(array(), true, !empty($this->upload->error_msg[0]) ? $this->upload->error_msg[0] : 'file_upload_failed');
    }

    function send_sms($number, $sms_content) {
        if (!$this->config->item('send_sms'))
            return true;

        $sms_config = $this->config->item('sms');

        if (strlen($number) == 10 && is_numeric($number))
            $number = '91' . $number;

        $sms_config['params']['number'] = $number;
        $sms_config['params']['text'] = $sms_content;

        $url = $sms_config['url'] . '?' . http_build_query($sms_config['params']);
        $response = file_get_contents($url);

        if ($response) {
            $response = json_decode($response, true);
            if (isset($response['ErrorCode'])) {
                if ($response['ErrorCode'] == '000')
                    return true;
                elseif (isset($sms_config['error_codes'][$response['ErrorCode']]))
                    return $sms_config['error_codes'][$response['ErrorCode']];

                return false;
            }
        }

        return false;
    }

    function create_thumbnail($source, $dest = '', $width = 100, $height = 100, $rename = true) {
        $size = getimagesize($source);
        if ($size[0] <= $width)
            $width = $size[0];
        if ($size[1] <= $height)
            $height = $size[1];

        $config['image_library'] = 'gd2';
        $config['source_image'] = $source;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $width;
        $config['height'] = $height;

        if ($dest)
            $config['new_image'] = $dest;

        if (!$rename)
            $config['thumb_marker'] = '';

        $this->load->library('image_lib', $config);
        $this->image_lib->clear();
        $this->image_lib->initialize($config);

        if ($this->image_lib->resize()) {
            $file_name = $dest ? $dest : $source;
            if ($rename) {
                $splits = explode('.', basename($file_name));
                $splits[count($splits) - 2] .= '_thumb';

                return implode('.', $splits);
            }

            return $file_name;
        }

        return $this->image_lib->display_errors();
    }

    function check_unique($table, $col, $colValue, $notCol = 0, $notColId = 0, $returnField = '') {
        if ($notCol !== 0)
            $this->db->where($notCol . ' !=', $notColId);

        $isReturn = 1;
        if ($returnField == '') {
            $returnField = $col;
            $isReturn = 0;
        }

        $res = $this->db->select($returnField)
            ->where($col, $colValue)
            ->where('deleted_on IS NULL', null, false)
            ->limit(1)
            ->get($table)
            ->row_array();

        return $res ? ($isReturn == 0 ? true : $res[$returnField]) : false;
    }
}