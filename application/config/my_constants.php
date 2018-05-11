<?php
defined('BASEPATH') OR exit('No direct script access allowed');

defined('ENV_PROD') OR define('ENV_PROD', 'production');
defined('ENV_TEST') OR define('ENV_TEST', 'testing');
defined('ENV_DEV') OR define('ENV_DEV', 'development');

// Page Limit
defined('PAGE_LIMIT') OR define('PAGE_LIMIT', 50);

// default password
defined('DEFAULT_PASSWORD') OR define('DEFAULT_PASSWORD', '123456');
defined('PASSWORD_LENGTH') OR define('PASSWORD_LENGTH', 8);

// Roles
defined('ROLE_SYSTEM_ADMIN') OR define('ROLE_SYSTEM_ADMIN', 1);
defined('ROLE_ADMIN') OR define('ROLE_ADMIN', 100);
defined('ROLE_PM') OR define('ROLE_PM', 125);
defined('ROLE_REVIEWER') OR define('ROLE_REVIEWER', 150);
defined('ROLE_CLIENT') OR define('ROLE_CLIENT', 200);
defined('ROLE_CLIENT_USER') OR define('ROLE_CLIENT_USER', 250);
defined('ROLE_CLIENT_GM') OR define('ROLE_CLIENT_GM', 260);
defined('ROLE_AUDITOR') OR define('ROLE_AUDITOR', 300);

// Gender
defined('GENDER_MALE') OR define('GENDER_MALE', 1);
defined('GENDER_FEMALE') OR define('GENDER_FEMALE', 2);
defined('GENDER_OTHER') OR define('GENDER_OTHER', 3);

// file type groups
defined('IMAGE_FILE') OR define('IMAGE_FILE', 'image');
defined('AUDIO_FILE') OR define('AUDIO_FILE', 'audio');
defined('VIDEO_FILE') OR define('VIDEO_FILE', 'video');
defined('DOC_FILE') OR define('DOC_FILE', 'doc');
defined('EXCEL_FILE') OR define('EXCEL_FILE', 'excel');
defined('PPT_FILE') OR define('PPT_FILE', 'ppt');
defined('PDF_FILE') OR define('PDF_FILE', 'pdf');
defined('TEXT_FILE') OR define('TEXT_FILE', 'txt');
defined('ALL_FILE') OR define('ALL_FILE', '*');

// active/inactive
defined('ACTIVE') OR define('ACTIVE', 1);
defined('INACTIVE') OR define('INACTIVE', 0);

// assets
defined('DUMMY_IMAGE') OR define('DUMMY_IMAGE', 'dummy.jpg');
