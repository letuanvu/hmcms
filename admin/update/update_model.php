<?php
/**
 * Tệp tin model của update trong admin
 * Vị trí : admin/update/update_model.php
 */
if (!defined('BASEPATH'))
    exit('403');
function update_check($type = 'core', $key = '', $server = '') {
    switch ($type) {
        case 'core':
            $check_url = HM_API_SERVER . '/api/update/check';
            @$content = file_get_contents($check_url);
            $content = json_decode($content, TRUE);
            if (isset($content['newest']) AND $content['newest'] == HM_VERSION) {
                return TRUE;
            } else {
                return $content;
            }
            break;
        case 'plugin':
            $check_url = HM_API_SERVER . '/api/plugin/check/?plugin=' . $key;
            @$content = file_get_contents($check_url);
            $content = json_decode($content, TRUE);
            return $content;
            break;
    }
}
/** Cài đặt update theo domain */
function update_core_for_domain() {
    $domain                = $_SERVER['HTTP_HOST'];
    $args                  = array(
        'section' => 'update_core_for_domain',
        'key' => 'version',
        'default_value' => '0'
    );
    $old_version           = get_option($args);
    $available_plugin      = json_decode(available_plugin(), TRUE);
    $available_plugin_send = array();
    foreach ($available_plugin['plugins'] as $plugin_key => $plugin_val) {
        $available_plugin_send[] = $plugin_key;
    }
    $available_plugin_send = implode(',', $available_plugin_send);
    $check_url             = HM_API_SERVER . '/api/update/for_domain/' . '?domain=' . $domain . '&old_version=' . $old_version . '&cms_version=' . HM_VERSION . '&available_plugin=' . $available_plugin_send;
    @$content = file_get_contents($check_url);
    $content = json_decode($content, TRUE);
    if (isset($content['version']) AND isset($content['download'])) {
        $version = $content['version'];
        $href    = $content['download'];
        if ($old_version < $version) {
            if (is_url_exist($href)) {
                $filename = basename($href);
                $saveto   = BASEPATH . '/' . $filename;
                file_put_contents($saveto, fopen($href, 'r'));
                if (file_exists($saveto)) {
                    if (class_exists('ZipArchive')) {
                        $zip = new ZipArchive;
                        $res = $zip->open($saveto);
                        if ($res === TRUE) {
                            $zip->extractTo(BASEPATH . '/');
                            $zip->close();
                            unlink($saveto);
                        }
                    }
                }
            }
            $args = array(
                'section' => 'update_core_for_domain',
                'key' => 'version',
                'value' => $version
            );
            set_option($args);
        }
    }
}
/** Chạy các hàm xử lý sau khi update */
function update_auto_load() {
    if (file_exists(BASEPATH . HM_ADMINCP_DIR . '/update/config.php')) {
        unlink(BASEPATH . HM_ADMINCP_DIR . '/update/config.php');
    }
    if (file_exists(BASEPATH . HM_ADMINCP_DIR . '/update/.htaccess')) {
        unlink(BASEPATH . HM_ADMINCP_DIR . '/update/.htaccess');
    }
}
/** Cài đặt update */
function update_core($href = FALSE) {
    if (!$href) {
        $href = 'https://github.com/manhnam91/hmcms/archive/master.zip';
    }
    $handle = curl_init($href);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($handle);
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    if ($httpCode != 404) {
        $filename = basename($href);
        $saveto   = BASEPATH . '/' . $filename;
        file_put_contents($saveto, fopen($href, 'r'));
        if (file_exists($saveto)) {
            if (class_exists('ZipArchive')) {
                $zip = new ZipArchive;
                $res = $zip->open($saveto);
                if ($res === TRUE) {
                    $zip->extractTo(BASEPATH . '/');
                    $zip->close();
                    update_auto_load();
                    if (file_exists(BASEPATH . '/hmcms-master')) {
                        recurse_copy(BASEPATH . '/hmcms-master', BASEPATH . '/');
                        delete_dir(BASEPATH . '/hmcms-master');
                    }
                    unlink($saveto);
                    return hm_json_encode(array(
                        'status' => 'success',
                        'mes' => hm_lang('server_download') . ' : ' . $filename
                    ));
                } else {
                    return hm_json_encode(array(
                        'status' => 'error',
                        'mes' => hm_lang('unable_to_extract_file')
                    ));
                }
            } else {
                return hm_json_encode(array(
                    'status' => 'error',
                    'mes' => hm_lang('the_server_does_not_support_the_zipArchive_class_please_unpack_file_name_manually',array('file_name'=>$filename))
                ));
            }
        } else {
            return hm_json_encode(array(
                'status' => 'error',
                'mes' => hm_lang('can_not_save_file')
            ));
        }
    } else {
        return hm_json_encode(array(
            'status' => 'error',
            'mes' => hm_lang('can_not_connect_with_the_server_download')
        ));
    }
}
?>
