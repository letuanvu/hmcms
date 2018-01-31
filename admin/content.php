<?php
/** 
 * Tệp tin xử lý content trong admin
 * Vị trí : admin/content.php 
 */
if (!defined('BASEPATH'))
    exit('403');
/** gọi tệp tin admin base */
require_once(dirname(__FILE__) . '/admin.php');
/** gọi model xử lý content */
require_once(dirname(__FILE__) . '/content/content_model.php');
require_once(dirname(__FILE__) . '/taxonomy/taxonomy_model.php');
/** check quyền của user */
$session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']), TRUE);
$user_id             = $session_admin_login['user_id'];
user_role_redirect($user_id, 'content');
$key            = hm_get('key');
$id             = hm_get('id');
$action         = hm_get('action');
$content_access = user_field_data(array(
    'id' => $_SESSION['admin_user']['user_id'],
    'field' => 'content_access'
));
$content_access = json_decode($content_access, true);
if (!is_array($content_access)) {
    $content_access = array();
}
switch ($action) {
    case 'add':
        if ((isset($content_access[$key]['add']) AND in_array($content_access[$key]['add'], array(
            'allow'
        ))) OR !in_array($_SESSION['user_role'], array(
            1,
            2
        ))) {
            /** Lấy thông tin content type trả về array */
            $args = content_data($key);
            if (isset($args['taxonomy_key']) AND $args['taxonomy_key'] != FALSE) {
                $args_tax = taxonomy_data($args['taxonomy_key']);
            } else {
                $args_tax = FALSE;
            }
            /** Hiển thị giao diện thêm content bằng array ở trên */
            function admin_content_page() {
                global $args;
                global $args_tax;
                require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'content_add.php');
            }
        } else {
            function admin_content_page() {
                require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_403.php');
            }
        }
        hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');
        break;
    case 'add_chapter':
        if (isset_content_id($id) == TRUE) {
            /** Lấy thông tin content type trả về array */
            $args_con = content_data_by_id($id);
            $key      = $args_con['content']->key;
            if ((isset($content_access[$key]['add']) AND in_array($content_access[$key]['add'], array(
                'allow'
            ))) OR !in_array($_SESSION['user_role'], array(
                1,
                2
            ))) {
                $args     = content_data($key);
                $args_tax = taxonomy_data($args['taxonomy_key']);
                /** Hiển thị giao diện thêm content bằng array ở trên */
                function admin_content_page() {
                    global $args;
                    global $args_tax;
                    global $args_con;
                    require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'content_add_chapter.php');
                }
            } else {
                function admin_content_page() {
                    require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_403.php');
                }
            }
        }
        hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');
        break;
    case 'edit':
        $layout = hm_get('layout', '');
        if (isset_content_id($id) == TRUE) {
            /** Thực hiện sửa content */
            $args_con = content_data_by_id($id);
            $key      = $args_con['content']->key;
            if ((isset($content_access[$key]['edit']) AND in_array($content_access[$key]['edit'], array(
                'allow',
                'owner_only'
            ))) OR !in_array($_SESSION['user_role'], array(
                1,
                2
            ))) {
                
                if ($content_access[$key]['edit'] == 'owner_only') {
                    if (!isset($args_con['field']['user_id']) OR $args_con['field']['user_id'] != $_SESSION['admin_user']['user_id']) {
                        function admin_content_page() {
                            require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_403.php');
                        }
                        return false;
                    }
                }
                
                $args = content_data($key);
                if (isset($args['taxonomy_key']) AND $args['taxonomy_key'] != '') {
                    $args_tax = taxonomy_data($args['taxonomy_key']);
                } else {
                    $args_tax = FALSE;
                }
                /** Hiển thị giao diện sửa content bằng array ở trên */
                function admin_content_page() {
                    global $args;
                    global $args_tax;
                    global $args_con;
                    global $layout;
                    if ($layout == 'popup') {
                        require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'content_edit_popup.php');
                    } else {
                        require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'content_edit.php');
                    }
                }
            } else {
                function admin_content_page() {
                    require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_403.php');
                }
            }
        } else {
            /** Không tồn tại content có id như request, hiển thị giao diện 404 */
            function admin_content_page() {
                require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_404.php');
            }
        }
        if ($layout == 'popup') {
            hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout_no_sidebar.php');
        } else {
            hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');
        }
        break;
    case 'view':
        if (isset_content_id($id) == TRUE) {
            /** Thực hiện sửa content */
            $args = content_data_by_id($id);
            /** Hiển thị giao diện sửa content bằng array ở trên */
            function admin_content_page() {
                global $args;
                require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'content_view.php');
            }
        } else {
            /** Không tồn tại content có id như request, hiển thị giao diện 404 */
            function admin_content_page() {
                require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_404.php');
            }
        }
        hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');
        break;
    default:
        $args = content_data($key);
        /** Hiển thị giao diện thêm tất cả content */
        function admin_content_page() {
            global $args;
            require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'content_all.php');
        }
        hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');
}
?>