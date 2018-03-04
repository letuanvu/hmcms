<?php
$protocol = '//';
$url_path = parse_url(getenv('REQUEST_URI'), PHP_URL_PATH);
if (getenv('SERVER_PORT') == '80') {
    define('BASE_URL', $protocol . getenv('SERVER_NAME') . $url_path);
} else {
    define('BASE_URL', $protocol . getenv('SERVER_NAME') . ':' . getenv('SERVER_PORT') . $url_path);
}
define('HM_ADMIN_DIR', 'hm_admin');
define('HM_INCLUDE_DIR', 'hm_include');
require_once(BASEPATH . '/' . HM_INCLUDE_DIR . '/install/install_model.php');
$step = $_GET['step'];
switch ($step) {
    case 1:
        require_once(BASEPATH . '/' . HM_INCLUDE_DIR . '/install/step1.php');
        break;
    case 2:
        if (check_all()) {
            if (isset($_POST['submit'])) {
                if (is_connect()) {
                    header('Location: ?step=3');
                    exit();
                } else {
                    $error = '<div class="alert alert-danger" role="alert">Không thể kết nối đến cơ sở dữ liệu</div>';
                }
            }
            require_once(BASEPATH . '/' . HM_INCLUDE_DIR . '/install/step2.php');
        } else {
            header('Location: ?step=1');
            exit();
        }
        break;
    case 3:
        if (is_array($_SESSION['db'])) {
            if (isset($_POST['submit'])) {
                require_once(BASEPATH . '/' . HM_INCLUDE_DIR . '/install/step_install.php');
            } else {
                require_once(BASEPATH . '/' . HM_INCLUDE_DIR . '/install/step3.php');
            }
        } else {
            header('Location: ?step=1');
            exit();
        }
        break;
    default:
        require_once(BASEPATH . '/' . HM_INCLUDE_DIR . '/install/step1.php');
}
?>
