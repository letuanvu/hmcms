<?php
/** 
 * Tệp tin xử lý taxonomy trong admin
 * Vị trí : admin/taxonomy.php 
 */
if (!defined('BASEPATH'))
    exit('403');
/** gọi tệp tin admin base */
require_once(dirname(__FILE__) . '/admin.php');
/** gọi model xử lý taxonomy */
require_once(dirname(__FILE__) . '/taxonomy/taxonomy_model.php');
/** check quyền của user */
$session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']), TRUE);
$user_id             = $session_admin_login['user_id'];
user_role_redirect($user_id, 'taxonomy');
$key             = hm_get('key');
$id              = hm_get('id');
$action          = hm_get('action');
$taxonomy_access = user_field_data(array(
    'id' => $_SESSION['admin_user']['user_id'],
    'field' => 'taxonomy_access'
));
$taxonomy_access = json_decode($taxonomy_access, true);
if (!is_array($taxonomy_access)) {
    $taxonomy_access = array();
}
switch ($action) {
    case 'add':
        if ((isset($taxonomy_access[$key]['add']) AND in_array($taxonomy_access[$key]['add'], array(
            'allow'
        ))) OR !in_array($_SESSION['user_role'], array(
            1,
            2
        ))) {
            /** Thực hiện thêm taxonomy trả về array */
            $args = taxonomy_data($key);
            /** Hiển thị giao diện thêm taxonomy bằng array ở trên */
            function admin_content_page() {
                global $args;
                require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'taxonomy_add.php');
            }
        } else {
            function admin_content_page() {
                require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_403.php');
            }
        }
        break;
    case 'edit':
        $args_tax = taxonomy_data_by_id($id);
        $key      = $args_tax['taxonomy']->key;
        if ((isset($taxonomy_access[$key]['edit']) AND in_array($taxonomy_access[$key]['edit'], array(
            'allow',
            'owner_only'
        ))) OR !in_array($_SESSION['user_role'], array(
            1,
            2
        ))) {
            
            if ($taxonomy_access[$key]['edit'] == 'owner_only') {
                if (!isset($args_tax['field']['user_id']) OR $args_tax['field']['user_id'] != $_SESSION['admin_user']['user_id']) {
                    function admin_content_page() {
                        require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_403.php');
                    }
                    return false;
                }
            }
            
            $args = taxonomy_data($key);
            function admin_content_page() {
                global $args;
                global $args_tax;
                require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'taxonomy_edit.php');
            }
        } else {
            function admin_content_page() {
                require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_403.php');
            }
        }
        break;
    default:
        $args = taxonomy_show_all($key);
        /** Hiển thị giao diện thêm tất cả taxonomy */
        function admin_content_page() {
            global $args;
            require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'taxonomy_all.php');
        }
}
/** fontend */
hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');
?>