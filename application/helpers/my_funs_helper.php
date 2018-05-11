<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('valid_date')) {
    function valid_date($data, $index = false, $return_str = false, $format = 'Y-m-d') {
        if ($index) {
            if (!isset($data[$index]))
                return $return_str;
            $data = $data[$index];
        }
        $d = DateTime::createFromFormat($format, $data);

        return $d && $d->format($format) === $data ? $data : $return_str;
    }
}

if (!function_exists('valid_email')) {
    function valid_email($data, $index = false, $return_str = false) {
        if ($index) {
            if (!isset($data[$index]))
                return $return_str;
            $data = $data[$index];
        }

        return filter_var($data, FILTER_VALIDATE_EMAIL) ? $data : $return_str;
    }
}

if (!function_exists('valid_from_todate')) {
    function valid_from_todate($from_date, $to_date) {
        $from_date = date('Y-m-d', strtotime($from_date));
        $to_date = date('Y-m-d', strtotime($to_date));

        return $from_date <= $to_date;
    }
}

if (!function_exists('valid_id')) {
    function valid_id($data, $index = false, $return_str = false) {
        if ($index) {
            if (!isset($data[$index]))
                return $return_str;
            $data = $data[$index];
        }

        return $data && is_numeric($data) && $data > 0 ? $data : $return_str;
    }
}

if (!function_exists('valid_mobile')) {
    function valid_mobile($data, $index = false, $return_str = false) {
        if ($index) {
            if (!isset($data[$index]))
                return $return_str;
            $data = $data[$index];
        }

        return $data && strpos($data, 0) !== 0 && is_numeric($data) && strlen($data) == 10 ? $data : $return_str;
    }
}

if (!function_exists('valid_data')) {
    function valid_data($data, $index = false, $return_str = false) {
        if ($index) {
            if (!isset($data[$index]))
                return $return_str;
            $data = $data[$index];
        }

        return $data || $data == 0 ? $data : $return_str;
    }
}

if (!function_exists('valid_password')) {
    function valid_password($data, $index = false, $return_str = false) {
        return valid_data($data, $index, $return_str);
    }
}

if (!function_exists('valid_yn')) {
    function valid_yn($data, $index = false, $return_str = false) {
        if ($index) {
            if (!isset($data[$index]))
                return $return_str;
            $data = $data[$index];
        }

        return $data == 1 || $data == 0 ? ($return_str === false ? true : $data) : $return_str;
    }
}

if (!function_exists('valid_latitude')) {
    function valid_latitude($data, $index = false, $return_str = false) {
        if ($index) {
            if (!isset($data[$index]))
                return $return_str;
            $data = $data[$index];
        }

        return is_numeric($data) && abs($data) <= 90 && (!is_float($data + 0) || strlen((explode('.', $data))[1]) <= 7) ? $data : $return_str;
    }
}

if (!function_exists('valid_longitude')) {
    function valid_longitude($data, $index = false, $return_str = false) {
        if ($index) {
            if (!isset($data[$index]))
                return $return_str;
            $data = $data[$index];
        }

        return is_numeric($data) && abs($data) <= 180 && (!is_float($data + 0) || strlen((explode('.', $data))[1]) <= 7) ? $data : $return_str;
    }
}

if (!function_exists('valid_number')) {
    function valid_number($data, $index = false, $return_str = false, $min = false, $max = false) {
        if ($index) {
            if (!isset($data[$index]))
                return $return_str;
            $data = $data[$index];
        }

        return is_numeric($data) && ($min === false || $data >= $min) && ($max === false || $data <= $max) ? true : $return_str;
    }
}

if (!function_exists('valid_hex_color')) {
    function valid_hex_color($data, $index = false, $return_str = false) {
        if ($index) {
            if (!isset($data[$index]))
                return $return_str;
            $data = $data[$index];
        }

        if (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $data))
            return $data;

        return $return_str;
    }
}

if (!function_exists('valid_time')) {
    function valid_time($data, $index = false, $return_str = false) {
        if ($index) {
            if (!isset($data[$index]))
                return $return_str;
            $data = $data[$index];
        }
        if (preg_match("/(2[0-3]|[01][1-9]|10|00):([0-5][0-9]):([0-5][0-9])/", $data))
            return $data;

        return $return_str;
    }
}

if (!function_exists('valid_array')) {
    function valid_array($data, $index = false, $return_str = false) {
        if ($index) {
            if (!isset($data[$index]))
                return $return_str;
            $data = $data[$index];
        }

        return is_array($data) && $data ? $data : $return_str;
    }
}

if (!function_exists('valid_file')) {
    function valid_file($data, $index = false, $return_str = false) {
        if ($index) {
            if (!isset($_FILES[$index]))
                return $return_str;
            $data = $_FILES[$index];
        }

        return is_array($data) && !$data['error'] && $data['size'] ? $data : $return_str;
    }
}

if (!function_exists('generate_password')) {
    function generate_password($type = 'alnum', $len = PASSWORD_LENGTH) {
        return ENVIRONMENT == ENV_PROD ? random_string($type, $len) : DEFAULT_PASSWORD;
    }
}

if (!function_exists('array_values_recursive')) {
    function array_values_recursive($array, $convert_only_numeric_keys = true) {
        if (!$convert_only_numeric_keys || is_numeric(key($array)))
            $array = array_values($array);
        foreach ($array as $k => $v)
            if (is_array($v))
                $array[$k] = array_values_recursive($v, $convert_only_numeric_keys);

        return $array;
    }
}

if (!function_exists('is_admin')) {
    function is_admin() {
        return in_array($_SESSION['role_id'], array(ROLE_SYSTEM_ADMIN, ROLE_ADMIN));
    }
}

if (!function_exists('is_pm')) {
    function is_pm() {
        return $_SESSION['role_id'] == ROLE_PM;
    }
}

if (!function_exists('is_reviewer')) {
    function is_reviewer() {
        return $_SESSION['role_id'] == ROLE_REVIEWER;
    }
}

if (!function_exists('is_client')) {
    function is_client() {
        return $_SESSION['role_id'] == ROLE_CLIENT;
    }
}

if (!function_exists('is_auditor')) {
    function is_auditor() {
        return $_SESSION['role_id'] == ROLE_AUDITOR;
    }
}

if (!function_exists('is_client_user')) {
    function is_client_user() {
        return $_SESSION['role_id'] == ROLE_CLIENT_USER;
    }
}

if (!function_exists('is_client_gm')) {
    function is_client_gm() {
        return $_SESSION['role_id'] == ROLE_CLIENT_GM;
    }
}

if (!function_exists('is_client_type_user')) {
    function is_client_type_user() {
        return $_SESSION['role_id'] > ROLE_CLIENT && $_SESSION['role_id'] <= ROLE_CLIENT_GM;
    }
}

if (!function_exists('is_choice_type')) {
    function is_choice_type($question_type) {
        return in_array($question_type, [QUESTION_RADIO, QUESTION_CHECKBOX, QUESTION_DROPDOWN]);
    }
}

if (!function_exists('preview_pdf')) {
    function preview_pdf($content, $file_name) {
        header('Content-Type: application/pdf');
        header('Content-Length: ' . strlen($content));
        header('Content-Disposition: inline; filename="' . $file_name . '"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        exit($content);
    }
}

if (!function_exists('get_benchmarking_color')) {
    function get_benchmarking_color($benchmark, $score, $with_hash = false) {
        foreach ($benchmark as $d) {
            if ($score >= $d['benchmark_range_from'] && $score <= $d['benchmark_range_to']) {
                return $with_hash ? $d['benchmark_color'] : ltrim($d['benchmark_color'], '#');
            }
        }

        return $with_hash ? '#000000' : '000000';
    }
}

if (!function_exists('get_score')) {
    function get_score($obtained_mark, $max_mark, $precision = 1, $to_string = false) {
        $score = $max_mark > 0 && $obtained_mark > 0 ? round(($obtained_mark / $max_mark * 100), $precision) : 0;
        if ($to_string)
            $score = $max_mark > 0 ? $score . '%' : 'NA';

        return $score;
    }
}

if (!function_exists('get_report_file_name')) {
    function get_report_file_name($name, $date, $ext = 'pdf', $audit_id, $second_prefix = false) {
        return REPORT_CACHE_PATH . $audit_id . '_' . ($second_prefix ? $second_prefix . '_' : '') . $name . '_' . str_replace(array('-', ' ', ':'), array('', '_', ''), $date) . '.' . $ext;
    }
}

if (!function_exists('get_new_file_name')) {
    function get_new_file_name($prefix = false) {
        $micro_sec = explode(' ', microtime())[0];
        $micro_sec = trim(trim($micro_sec, '0'), '.');
        $random_num = mt_rand(0, mt_getrandmax());
        $file_name = date('Ymd_His') . '_' . $micro_sec . $random_num;
        if ($prefix)
            $file_name = $prefix . '_' . $file_name;

        return $file_name;
    }
}

if (!function_exists('merge_pdf')) {
    function merge_pdf($pdf, $dest_file) {
        if (is_array($pdf))
            $pdf = implode(' ', $pdf);

        $command = 'pdfunite ' . $pdf . ' ' . $dest_file;
        exec($command, $output, $return);
    }
}

if (!function_exists('dump')) {
    function dump($data, $exit = true) {
        if (!is_array($data))
            $data = array($data);

        echo json_encode($data);

        if ($exit)
            exit;
    }
}