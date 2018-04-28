<?php
/**
 * The file configures the main settings
 * Location: /hm_config.php
 */
if (!defined('BASEPATH'))
    exit('403');

/** Database name */
define('DB_NAME', '');

/** database account */
define('DB_USER', '');

/** password database */
define('DB_PASSWORD', '');

/** Hostname */
define('DB_HOST', '');

/** Database Charset */
define('DB_CHARSET', 'utf8');

/** next prefix */
define('DB_PREFIX', '');

/** Folder contains library files and processors */
define('HM_INC', 'hm_include');

/** The directory containing the frontend */
define('HM_FRONTENT_DIR', 'hm_frontend');

/** Directory containing uploads and content */
define('HM_CONTENT_DIR', 'hm_content');

/** Folder contains interfaces */
define('HM_THEME_DIR', 'hm_themes');

/** The directory containing the plugin */
define('HM_PLUGIN_DIR', 'hm_plugins');

/** The directory contains the integrated modules */
define('HM_MODULE_DIR', 'hm_module');

/** Admin directory */
define('HM_ADMINCP_DIR', 'hm_admin');

/** Admin Path */
define('HM_ADMINCP_PART', 'admin');

/** Connection port (: 80,: 8080,: 443 etc) */
define('SERVER_PORT', '');

/** Website name */
define('SITE_NAME', 'UnitedMai CMS');

/** Delete copyright in admin */
define('REMOVE_ADMINCP_COPYRIGHT', false);

/** Display plugin page in admin */
define('ALLOW_PLUGIN_PAGE', true);

/** Theme page theme in admin */
define('ALLOW_THEME_PAGE', true);

/** Command page display in admin */
define('ALLOW_COMMAND_PAGE', true);

/** Prompt and allow update */
define('ALLOW_UPDATE', true);

/** Updates */
define('SYSTEM_DASHBOARD', true);

/** Use a simple captcha */
define('SIMPLE_CAPTCHA', true);

/** Website address */
$protocol = '//';
define('SITE_URL', $protocol . getenv('SERVER_NAME') . SERVER_PORT);

/** Directory paths */
define('FOLDER_PATH', '');

/** Language */
define('LANG', 'vi_VN');

/** Date format */
define('DATE_FORMAT', 'H:i:s d-m-Y');

/** Encode */
define('ENCRYPTION_KEY', '');

/** Allow login with password md5 */
define('MD5_PASSWORD', false);

/** Cookie Time */
define('COOKIE_EXPIRES', 3600);

/** default interface */
define('DEFAULT_THEME', 'hello');

/** Error */
define('HM_DEBUG', false);

/** The basis for paging */
define('PAGE_BASE', 'page-');

/** Time zone */
date_default_timezone_set('Asia / Ho_Chi_Minh');

/** Create your own photo folder by month */
define('MEDIA_FOLDER_BY_MONTH', false);

?>
