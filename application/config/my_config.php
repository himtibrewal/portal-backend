<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['file_type_groups'] = array(
    IMAGE_FILE => 'jpg|jpeg|png|ico|gif|bmp',
    AUDIO_FILE => 'mp3|wav|ogg|m4a',
    VIDEO_FILE => 'mp4|webm',
    DOC_FILE   => 'pdf|doc|docx|ppt|pptx|xls|xlsx|ods|txt|rtx|rtf',
    EXCEL_FILE => 'xls|xlsx|ods',
    PPT_FILE   => 'ppt|pptx',
    PDF_FILE   => 'pdf',
    TEXT_FILE  => 'txt|rtx|rtf',
    ALL_FILE   => '*'
);

$config['db_backup_dir'] = FCPATH . 'assets/db_backup/';

$config['otp_expiry'] = 120;

