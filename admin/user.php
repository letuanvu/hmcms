<?php
/**
 * Tệp tin xử lý user trong admin
 * Vị trí : admin/user.php
 */
if (!defined('BASEPATH'))
    exit('403');
/** gọi tệp tin admin base */
require_once(dirname(__FILE__) . '/admin.php');
/** gọi model xử lý user */
require_once(dirname(__FILE__) . '/user/user_model.php');
/** check quyền của user */
$session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']), TRUE);
$user_id             = $session_admin_login['user_id'];
user_role_redirect($user_id, 'user');
$key    = hm_get('key');
$id     = hm_get('id');
$action = hm_get('action');
switch ($action) {
    case 'add':
        /** Hiển thị giao diện thêm user */
        function admin_content_page() {
            require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'user_add.php');
        }
        break;
    case 'edit':
        /** Lấy thông tin user trả về array */
        if (is_numeric($id)) {
            $args_use = user_data($id);
        } else {
            $session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']), TRUE);
            $id                  = $session_admin_login['user_id'];
            $args_use            = user_data($id);
        }
        /** Phân quyền */
        $content_access = user_field_data(array(
            'id' => $id,
            'field' => 'content_access'
        ));
        $content_access = json_decode($content_access, true);
        if (!is_array($content_access)) {
            $content_access = array();
        }
        $taxonomy_access = user_field_data(array(
            'id' => $id,
            'field' => 'taxonomy_access'
        ));
        $taxonomy_access = json_decode($taxonomy_access, true);
        if (!is_array($taxonomy_access)) {
            $taxonomy_access = array();
        }
        $media_access = user_field_data(array(
            'id' => $id,
            'field' => 'media_access'
        ));
        $media_access = json_decode($media_access, true);
        if (!is_array($media_access)) {
            $media_access = array();
        }
        /** Hiển thị giao diện chỉnh sửa user */
        function admin_content_page() {
            global $args_use;
            global $hmcontent;
            global $hmtaxonomy;
            global $content_access;
            global $taxonomy_access;
            global $media_access;
            require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'user_edit.php');
        }
        break;
    default:
        function admin_content_page() {
            require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'user_all.php');
        }
}
/** fontend */
hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');
?>