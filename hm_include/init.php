<?php
/**
 * Khởi tạo mặc định
 * Vị trí : hm_include/init.php
 */
if (!defined('BASEPATH'))
    exit('403');

/** Tạo trang cài đặt tổng quan trong admincp */
$args = array(
    'label' => hm_lang('main_setting'),
    'key' => 'hm_main_setting',
    'function' => 'hm_main_setting',
    'function_input' => array(),
    'child_of' => FALSE
);
register_admin_setting_page($args);
function hm_main_setting() {
    if (isset($_POST['save_system_setting'])) {
        foreach ($_POST as $key => $value) {
            if ($key != 'save_system_setting') {
                $args = array(
                    'section' => 'system_setting',
                    'key' => $key,
                    'value' => $value
                );
                set_option($args);
            }
        }
    }
    hm_include(BASEPATH . '/' . HM_ADMINCP_DIR . '/layout/main_setting.php');
}

/** Tạo trang phân quyền */
$args = array(
    'label' => hm_lang('user_role'),
    'key' => 'hm_user_role',
    'function' => 'hm_user_role',
    'function_input' => array(),
    'child_of' => FALSE
);
register_admin_setting_page($args);
function hm_user_role() {
    global $hmcontent;
    global $hmtaxonomy;
    if (isset($_POST['save_user_role'])) {
        foreach ($_POST as $key => $value) {
            if ($key != 'save_user_role') {
                $args = array(
                    'section' => 'user_role',
                    'key' => $key,
                    'value' => $value
                );
                set_option($args);
            }
        }
    }
    hm_include(BASEPATH . '/' . HM_ADMINCP_DIR . '/layout/user_role.php', array(
        'hmcontent' => $hmcontent,
        'hmtaxonomy' => $hmtaxonomy
    ));
}

/** load init.php của giao diện */
$theme = activated_theme();
if (file_exists(BASEPATH . HM_THEME_DIR . '/' . $theme . '/init.php')) {
    require_once(BASEPATH . HM_THEME_DIR . '/' . $theme . '/init.php');
}
?>
