<?php
/**
 * Class này xử lý giao diện của website
 */
if (!defined('BASEPATH'))
    exit('403');
Class theme extends MySQL {
    public $hmtaxonomy = array();
    public $hmtheme_val = array();
    public function set_val($args) {
        $this->hmtheme_val[$args['key']] = $args['val'];
    }
    public function get_val($key) {
        if (isset($this->hmtheme_val[$key])) {
            return $this->hmtheme_val[$key];
        } else {
            return FALSE;
        }
    }
    /** Lấy thông tin giao diện đang được kích hoạt */
    public function activated_theme() {
        $tableName  = DB_PREFIX . "option";
        $whereArray = array(
            'section' => MySQL::SQLValue('system_setting'),
            'key' => MySQL::SQLValue('theme')
        );
        $this->SelectRows($tableName, $whereArray);
        if ($this->HasRecords()) {
            $row    = $this->Row();
            $return = $row->value;
        } else {
            if (file_exists(BASEPATH . HM_THEME_DIR . '/' . DEFAULT_THEME . '/init.php')) {
                $return = DEFAULT_THEME;
            } else {
                $return = FALSE;
            }
        }
        $return = hook_filter('activated_theme', $return);
        return $return;
    }
    /** Load file template dựa trên theme và request */
    public function load_theme($args) {
        $theme   = $args['theme'];
        $request = $args['request'];
        $request = urldecode($request);
        if ($request) {
            $tableName  = DB_PREFIX . "request_uri";
            $whereArray = array(
                'uri' => MySQL::SQLValue($request)
            );
            $this->SelectRows($tableName, $whereArray);
            if ($this->HasRecords()) {
                $row                 = $this->Row();
                $object_type         = $row->object_type;
                $object_id           = $row->object_id;
                $template_file_by_id = $object_type . '-' . $id;
                /** template riêng theo key */
                switch ($object_type) {
                    case 'content':
                        $status = get_con_val(array(
                            'name' => 'status',
                            'id' => $object_id
                        ));
                        if ($status == NULL) {
                            $status = 'public';
                        }
                        switch ($status) {
                            case 'public':
                                /** lượt xem */
                                $page_view = get_con_val(array(
                                    'name' => 'page_view',
                                    'id' => $object_id
                                ));
                                if (!is_numeric($page_view)) {
                                    $page_view = 1;
                                }
                                $page_view_new = $page_view + 1;
                                update_con_val(array(
                                    'name' => 'page_view',
                                    'id' => $object_id,
                                    'value' => $page_view_new
                                ));
                                $content_data         = content_data_by_id($object_id);
                                $key                  = $content_data['content']->key;
                                $template_file        = $object_type;
                                $template_file_by_key = $object_type . '-' . $key;
                                if (file_exists(BASEPATH . HM_THEME_DIR . '/' . $theme . '/' . $template_file_by_key . '.php')) {
                                    $template_file = $template_file_by_key;
                                } elseif (file_exists(BASEPATH . HM_THEME_DIR . '/' . $theme . '/' . $template_file_by_id . '.php')) {
                                    $template_file = $template_file_by_id;
                                }
                                break;
                            case 'hide':
                                $template_file = '404';
                                break;
                            case 'draft':
                                $template_file = '404';
                                break;
                            case 'password':
                                $template_file = '404';
                                break;
                        }
                        break;
                    case 'taxonomy':
                        $status = get_tax_val(array(
                            'name' => 'status',
                            'id' => $object_id
                        ));
                        if ($status == NULL) {
                            $status = 'public';
                        }
                        switch ($status) {
                            case 'public':
                                /** lượt xem */
                                $page_view = get_tax_val(array(
                                    'name' => 'page_view',
                                    'id' => $object_id
                                ));
                                if (!is_numeric($page_view)) {
                                    $page_view = 1;
                                }
                                $page_view_new = $page_view + 1;
                                update_tax_val(array(
                                    'name' => 'page_view',
                                    'id' => $object_id,
                                    'value' => $page_view_new
                                ));
                                $taxonomy_data        = taxonomy_data_by_id($object_id);
                                $key                  = $taxonomy_data['taxonomy']->key;
                                $template_file        = $object_type;
                                $template_file_by_key = $object_type . '-' . $key;
                                if (file_exists(BASEPATH . HM_THEME_DIR . '/' . $theme . '/' . $template_file_by_key . '.php')) {
                                    $template_file = $template_file_by_key;
                                } elseif (file_exists(BASEPATH . HM_THEME_DIR . '/' . $theme . '/' . $template_file_by_id . '.php')) {
                                    $template_file = $template_file_by_id;
                                }
                                break;
                            case 'hide':
                                $template_file = '404';
                                break;
                            case 'draft':
                                $template_file = '404';
                                break;
                            case 'password':
                                $template_file = '404';
                                break;
                        }
                        break;
                    default:
                        $template_file = $object_type;
                }
                $data['id']        = $row->object_id;
                $data['theme_val'] = $this->hmtheme_val;
                $this->load_main_template_file($theme, $template_file, $data);
            } else {
                $this->load_main_template_file($theme, '404');
            }
        } else {
            $data['theme_val'] = $this->hmtheme_val;
            $this->load_main_template_file($theme, 'index', $data);
        }
    }
    /** Load file template giao diện đang dùng */
    public function get_template_part($template_file, $data) {
        $theme = $this->activated_theme();
        $this->load_template_file($theme, $template_file, $data);
    }
    /** Load file template dựa trên tên file php */
    public function load_template_file($theme, $template_file, $data = array()) {
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        if (file_exists(BASEPATH . HM_THEME_DIR . '/' . $theme . '/' . $template_file . '.php')) {
            include(BASEPATH . HM_THEME_DIR . '/' . $theme . '/' . $template_file . '.php');
        } else {
            if ($template_file == '404') {
                include(BASEPATH . HM_FRONTENT_DIR . '/template/404.php');
            } else {
                hm_exit('Không thể load file "' . $template_file . '.php" của giao diện "' . $theme . '"');
            }
        }
    }
    /** Load file template chính dựa trên tên file php */
    public function load_main_template_file($theme, $template_file, $data = array()) {
        $data = hook_filter('before_load_main_template_file', $data);
        hook_action('before_load_main_template_file');
        $this->load_template_file($theme, $template_file, $data);
        hook_action('after_load_main_template_file');
    }
}
?>