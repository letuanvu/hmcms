<?php
/**
 * Đây là tệp tin khởi tạo cấu trúc website
 * Tất cả các hàm ở đây đều có thể dùng trong plugin hay theme
 * Điều khác biệt là các hàm ở /hm_setup.php luôn chạy không phụ thuộc vào
 * plugin hay theme bạn đang dùng, còn nếu khai báo ở plugin hay theme thì
 * chỉ chạy khi plugin hoặc theme đó đã kích hoạt.
 * Trong mã nguồn này HoaMai được build dưới dạng một blog
 * Cấu trúc cho blog gồm 1 taxonomy "Danh mục bài viết" và 1 content type "Bài viết"
 * Để thực hiện việc này chúng ta sử dụng hàm register_taxonomy(); và register_content();
 * Vị trí : /hm_setup.php
 */
if (!defined('BASEPATH'))
    exit('403');
/**
 * Trong quản trị ngoài việc khai báo các trường bắt buộc cho thành viên như
 * tên đăng nhập, mật khẩu ... Bạn có thể bổ sung thêm các trường cần cho website của bạn
 * như skype, email, số điện thoại ... như dưới đây
 */
$args = array(
    array(
        'nice_name' => hm_lang('real_name'),
        'name' => 'name',
        'input_type' => 'text',
        'default_value' => '',
        'placeholder' => hm_lang('name_of_user'),
        'required' => FALSE
    ),
    array(
        'nice_name' => hm_lang('skype'),
        'name' => 'skype',
        'input_type' => 'text',
        'default_value' => '',
        'placeholder' => hm_lang('skype_of_user'),
        'required' => FALSE
    ),
    array(
        'nice_name' => hm_lang('facebook'),
        'name' => 'facebook',
        'input_type' => 'text',
        'default_value' => '',
        'placeholder' => hm_lang('facebook_of_user'),
        'required' => FALSE
    ),
    array(
        'nice_name' => hm_lang('phone_number'),
        'name' => 'phone',
        'input_type' => 'text',
        'default_value' => '',
        'placeholder' => hm_lang('enter_your_phone_number'),
        'required' => FALSE
    ),
    array(
        'nice_name' => hm_lang('personal_information'),
        'name' => 'bio',
        'input_type' => 'textarea',
        'default_value' => '',
        'placeholder' => hm_lang('introduce_yourself'),
        'required' => FALSE
    )
);
register_user_field($args);
if (SYSTEM_DASHBOARD == TRUE) {
    /**
     * Dashboard box bài viết mới từ trang chủ
     */
    $args = array(
        'width' => '4',
        'function' => 'hm_newsfeed',
        'label' => hm_lang('new_post')
    );
    register_dashboard_box($args);
    function hm_newsfeed() {
        $server = HM_API_SERVER . '/api/news/json';
        @$data = file_get_contents($server);
        $data = json_decode($data);
        if (is_array($data)) {
            echo '<ul class="dashboard_box_list">';
            foreach ($data as $item) {
                $name = $item->name;
                $link = $item->link;
                echo '<li><a href="' . $link . '" target="_blank">' . $name . '</a></li>';
            }
            echo '</ul>';
        }
    }
    /**
     * Dashboard box giao diện mới từ trang chủ
     */
    $args = array(
        'width' => '4',
        'function' => 'hm_newthemes',
        'label' => hm_lang('new_theme')
    );
    register_dashboard_box($args);
    function hm_newthemes() {
        $server = HM_API_SERVER . '/api/themes/json';
        @$data = file_get_contents($server);
        $data = json_decode($data);
        if (is_array($data)) {
            echo '<ul class="dashboard_box_list">';
            foreach ($data as $item) {
                $name = $item->name;
                $link = $item->link;
                echo '<li><a href="' . $link . '" target="_blank">' . $name . '</a></li>';
            }
            echo '</ul>';
        }
    }
    /**
     * Dashboard box plugin mới từ trang chủ
     */
    $args = array(
        'width' => '4',
        'function' => 'hm_newplugins',
        'label' => hm_lang('new_plugin')
    );
    register_dashboard_box($args);
    function hm_newplugins() {
        $server = HM_API_SERVER . '/api/plugins/json';
        @$data = file_get_contents($server);
        $data = json_decode($data);
        if (is_array($data)) {
            echo '<ul class="dashboard_box_list">';
            foreach ($data as $item) {
                $name = $item->name;
                $link = $item->link;
                echo '<li><a href="' . $link . '" target="_blank">' . $name . '</a></li>';
            }
            echo '</ul>';
        }
    }
}
/**
 * Block text mặc định
 */
function HmBlockText($block_id) {
    echo get_blo_val(array(
        'name' => 'content',
        'id' => $block_id
    ));
}
$args = array(
    'name' => 'HmBlockText',
    'nice_name' => hm_lang('text'),
    'input' => array(
        array(
            'nice_name' => hm_lang('content'),
            'description' => hm_lang('you_can_use_html'),
            'name' => 'content',
            'input_type' => 'textarea',
            'required' => FALSE
        )
    ),
    'function' => 'HmBlockText'
);
register_block($args);
/**
 * Block image mặc định
 */
function HmBlockImage($block_id) {
    $image = get_blo_val(array(
        'name' => 'image',
        'id' => $block_id
    ));
    $link  = get_blo_val(array(
        'name' => 'link',
        'id' => $block_id
    ));
    if (is_numeric($link)) {
        $data_uri = get_uri_data("id=$link");
        $href     = SITE_URL . FOLDER_PATH . $data_uri->uri;
    } else {
        $href = $link;
    }
    if ($link != '') {
        echo '<a href="' . $href . '">' . "\n\r";
    }
    echo img($image);
    if ($link != '') {
        echo '</a>' . "\n\r";
    }
}
$args = array(
    'name' => 'HmBlockImage',
    'nice_name' => hm_lang('image'),
    'input' => array(
        array(
            'nice_name' => hm_lang('image'),
            'name' => 'image',
            'input_type' => 'image',
            'required' => FALSE
        ),
        array(
            'nice_name' => hm_lang('permalink'),
            'description' => hm_lang('link_when_clicking_on_the_photo_if_available'),
            'name' => 'link',
            'input_type' => 'request_uri',
            'required' => FALSE
        )
    ),
    'function' => 'HmBlockImage'
);
register_block($args);
/**
 * Block menu mặc định
 */
function HmBlockMenu($block_id) {
    $menu = get_blo_val(array(
        'name' => 'menu',
        'id' => $block_id
    ));
    echo get_menu($menu);
}
$args = array(
    'name' => 'HmBlockMenu',
    'nice_name' => hm_lang('menu'),
    'input' => array(
        array(
            'nice_name' => hm_lang('select_menu'),
            'name' => 'menu',
            'input_type' => 'menu',
            'required' => FALSE
        )
    ),
    'function' => 'HmBlockMenu'
);
register_block($args);
/**
 * Đăng ký link ảnh captcha
 */
register_request('captcha.jpg', 'hm_create_captcha');
function hm_create_captcha() {
    global $hmcaptcha;
    $hmcaptcha->CreateImage();
    exit();
}
/**
 * shortcode hiển thị menu
 */
$args = array(
    'name' => 'menu',
    'func' => 'hm_shortcode_menu'
);
register_shortcode($args);
function hm_shortcode_menu($args) {
    if (isset($args['menu_id'])) {
        $menu_id        = $args['menu_id'];
        $args['name']   = get_menu_name($menu_id);
        $args['parent'] = $menu_id;
        if (is_numeric($menu_id)) {
            echo get_menu($args);
        }
    }
}
hook_action('after_web_setup');

/**
 * image_thumbnail module
 */
$args = array(
    'module_name' => 'image_thumbnail',
    'module_key' => 'image_thumbnail',
    'module_index' => 'index',
    'module_dir' => 'image_thumbnail'
);
register_module($args);
?>
