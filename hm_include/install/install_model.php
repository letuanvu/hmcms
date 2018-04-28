<?php
session_start();
error_reporting(0);
if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);
    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}
if (PHP_VERSION_ID) {
    define('PHP_MAJOR_VERSION', $version[0]);
    define('PHP_MINOR_VERSION', $version[1]);
    define('PHP_RELEASE_VERSION', $version[2]);
}
function allow_version() {
    if (PHP_MAJOR_VERSION > 7) {
        return TRUE;
    } elseif (PHP_MAJOR_VERSION == 7) {
        if (PHP_MINOR_VERSION >= 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}
function gdVersion($user_ver = 0) {
    if (!extension_loaded('gd')) {
        return;
    }
    static $gd_ver = 0;
    if ($user_ver == 1) {
        $gd_ver = 1;
        return 1;
    }
    if ($user_ver != 2 && $gd_ver > 0) {
        return $gd_ver;
    }
    if (function_exists('gd_info')) {
        $ver_info = gd_info();
        preg_match('/\d/', $ver_info['GD Version'], $match);
        $gd_ver = $match[0];
        return $match[0];
    }
    if (preg_match('/phpinfo/', ini_get('disable_functions'))) {
        if ($user_ver == 2) {
            $gd_ver = 2;
            return 2;
        } else {
            $gd_ver = 1;
            return 1;
        }
    }
    ob_start();
    phpinfo(8);
    $info = ob_get_contents();
    ob_end_clean();
    $info = stristr($info, 'gd version');
    preg_match('/\d/', $info, $match);
    $gd_ver = $match[0];
    return $match[0];
}
function check_all() {
    $check = array();
    if (allow_version()) {
        $check['allow_version'] = '1';
    } else {
        $check['allow_version'] = '0';
    }
    if ($gdv = gdVersion()) {
        if ($gdv >= 2) {
            $check['gdVersion'] = '1';
        } else {
            $check['gdVersion'] = '0';
        }
    } else {
        $check['gdVersion'] = '0';
    }
    if (is_writable('hm_content/uploads')) {
        $check['uploadWritable'] = '1';
    } else {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $check['uploadWritable'] = '1';
        } else {
            $check['uploadWritable'] = '0';
        }
    }
    if (function_exists('openssl_encrypt')) {
        $check['openssl'] = '1';
    } else {
        $check['openssl'] = '0';
    }
    if (function_exists('mysqli_connect')) {
        $check['mysqli'] = '1';
    } else {
        $check['mysqli'] = '0';
    }
    if (!in_array('0', $check)) {
        return TRUE;
    } else {
        return FALSE;
    }
}
function is_connect() {
    $host            = $_POST['host'];
    $username        = trim($_POST['username']);
    $password        = trim($_POST['password']);
    $database        = trim($_POST['database']);
    $prefix          = trim($_POST['prefix']);

    $charset = 'utf8';
    $dsn = "mysql:host=$host;dbname=$database;charset=$charset";
    try{
      $opt = array(
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::ATTR_EMULATE_PREPARES   => false,
      );
      $hmdb = new PDO($dsn, $username, $password, $opt);

      $_SESSION['db']['host']     = $host;
      $_SESSION['db']['username'] = $username;
      $_SESSION['db']['password'] = $password;
      $_SESSION['db']['database'] = $database;
      $_SESSION['db']['prefix']   = $prefix;
      return TRUE;

    }catch(PDOException $ex){
      return FALSE;
      session_destroy();
    }

}
function generateRandomString($length = 30) {
    $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function hm_encode_str($string = NULL, $secret_key = ENCRYPTION_KEY) {
    $secret_iv = '123456';
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
    $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    return $output;
}
function install_db() {
    $host            = $_SESSION['db']['host'];
    $username        = $_SESSION['db']['username'];
    $password        = $_SESSION['db']['password'];
    $database        = $_SESSION['db']['database'];
    $prefix          = $_SESSION['db']['prefix'];
    $charset         = 'utf8';
    $dsn             = "mysql:host=$host;dbname=$database;charset=$charset";
    $admin_username  = trim($_POST['admin_username']);
    $admin_email     = trim($_POST['admin_email']);
    $admin_password  = trim($_POST['admin_password']);
    $encryption_key  = trim($_POST['encryption_key']);
    $url_path        = parse_url(getenv('REQUEST_URI'), PHP_URL_PATH);

    /** install */
    $opt = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    );
    $hmdb = new PDO($dsn, $username, $password, $opt);
    $hmdb->exec('SET NAMES "UTF8"');

    $sql = "
    CREATE TABLE IF NOT EXISTS `" . $prefix . "content` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(500) NOT NULL,
      `slug` varchar(500) NOT NULL,
      `key` varchar(50) NOT NULL,
      `parent` int(11) NOT NULL,
      `status` varchar(50) NOT NULL,
      `content_order` int(11) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Created table: ' . $prefix . 'content</p>';
    /**--------------------------------------------------------*/

    $sql = "
    CREATE TABLE IF NOT EXISTS `" . $prefix . "field` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) NOT NULL,
      `val` longtext NOT NULL,
      `object_id` int(11) NOT NULL,
      `object_type` varchar(50) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Created table: ' . $prefix . 'field</p>';
    /**--------------------------------------------------------*/

  $sql = "
    CREATE TABLE IF NOT EXISTS `" . $prefix . "media_groups` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) NOT NULL,
      `folder` varchar(255) NOT NULL,
      `parent` int(11) NOT NULL,
      `order_number` int(11),
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Created table: ' . $prefix . 'media_groups</p>';
    /**--------------------------------------------------------*/

    $sql = "
    CREATE TABLE IF NOT EXISTS `" . $prefix . "media` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `media_group_id` int(11) NOT NULL,
      `file_info` text NOT NULL,
      `file_is_image` varchar(5) NOT NULL,
      `file_name` varchar(255) NOT NULL,
      `file_folder` varchar(255) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Created table: ' . $prefix . 'media</p>';
    /**--------------------------------------------------------*/

    $sql = "
    CREATE TABLE IF NOT EXISTS `" . $prefix . "object` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) NOT NULL,
      `key` varchar(50) NOT NULL,
      `parent` int(11) NOT NULL,
      `order_number` int(11) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Created table: ' . $prefix . 'object</p>';
    /**--------------------------------------------------------*/

    $sql = "
    CREATE TABLE IF NOT EXISTS `" . $prefix . "option` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `section` varchar(50) NOT NULL,
      `key` varchar(50) NOT NULL,
      `value` text NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Created table: ' . $prefix . 'option</p>';
    /**--------------------------------------------------------*/

    $sql = "
    CREATE TABLE IF NOT EXISTS `" . $prefix . "plugin` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `key` varchar(50) NOT NULL,
      `active` int(1) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Created table: ' . $prefix . 'plugin</p>';
    /**--------------------------------------------------------*/

    $sql = "
    CREATE TABLE IF NOT EXISTS `" . $prefix . "relationship` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `object_id` int(11) NOT NULL,
      `target_id` int(1) NOT NULL,
      `relationship` varchar(255) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Created table: ' . $prefix . 'relationship</p>';
    /**--------------------------------------------------------*/

    $sql = "
    CREATE TABLE IF NOT EXISTS `" . $prefix . "request_uri` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `object_id` int(11) NOT NULL,
      `object_type` varchar(50) NOT NULL,
      `uri` varchar(1000) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Created table: ' . $prefix . 'request_uri</p>';
    /**--------------------------------------------------------*/

    $sql = "
    CREATE TABLE IF NOT EXISTS `" . $prefix . "taxonomy` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) NOT NULL,
      `slug` varchar(255) NOT NULL,
      `key` varchar(50) NOT NULL,
      `parent` int(11) NOT NULL,
      `status` varchar(255) NOT NULL,
          `taxonomy_order` int(11),
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Created table: ' . $prefix . 'taxonomy</p>';
    /**--------------------------------------------------------*/

    $sql = "
    CREATE TABLE IF NOT EXISTS `" . $prefix . "users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_login` varchar(255) NOT NULL,
      `user_pass` varchar(255) NOT NULL,
      `salt` int(6) NOT NULL,
      `user_nicename` varchar(255) NOT NULL,
      `user_email` varchar(255) NOT NULL,
      `user_activation_key` varchar(255) NOT NULL,
      `user_role` int(11) NOT NULL,
      `user_group` int(11) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Created table: ' . $prefix . 'users</p>';
    /**--------------------------------------------------------*/

  $sql = "
  CREATE INDEX `indexed_content` ON `" . $prefix . "content` (`key`, `parent`, `status`, `content_order`);
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Add Index : ' . $prefix . 'content</p>';
  /**--------------------------------------------------------*/

  $sql = "
  CREATE INDEX `indexed_taxonomy` ON `" . $prefix . "taxonomy` (`key`, `parent`, `status`, `taxonomy_order`);
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Add Index : ' . $prefix . 'taxonomy</p>';
  /**--------------------------------------------------------*/

  $sql = "
  CREATE INDEX `indexed_field` ON `" . $prefix . "field` (`object_id`);
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Add Index : ' . $prefix . 'field</p>';
  /**--------------------------------------------------------*/

  $sql = "
  CREATE INDEX `indexed_media_groups` ON `" . $prefix . "media_groups` (`parent`, `order_number`);
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Add Index : ' . $prefix . 'media_groups</p>';
  /**--------------------------------------------------------*/

  $sql = "
  CREATE INDEX `indexed_media` ON `" . $prefix . "media` (`media_group_id`);
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Add Index : ' . $prefix . 'media</p>';
  /**--------------------------------------------------------*/

  $sql = "
  CREATE INDEX `indexed_object` ON `" . $prefix . "object` (`key`, `parent`);
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Add Index : ' . $prefix . 'object</p>';
  /**--------------------------------------------------------*/

  $sql = "
  CREATE INDEX `indexed_option` ON `" . $prefix . "option` (`key`, `section`);
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Add Index : ' . $prefix . 'option</p>';
  /**--------------------------------------------------------*/

  $sql = "
  CREATE INDEX `indexed_plugin` ON `" . $prefix . "plugin` (`key`, `active`);
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Add Index : ' . $prefix . 'plugin</p>';
  /**--------------------------------------------------------*/

  $sql = "
  CREATE INDEX `indexed_relationship` ON `" . $prefix . "relationship` (`object_id`, `target_id`, `relationship`);
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Add Index : ' . $prefix . 'relationship</p>';
  /**--------------------------------------------------------*/

  $sql = "
  CREATE INDEX `indexed_request_uri` ON `" . $prefix . "request_uri` (`object_id`, `object_type`);
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Add Index : ' . $prefix . 'request_uri</p>';
  /**--------------------------------------------------------*/

    /** user admin */
    $admin_salt      = rand(0, 999999);
    $password_encode = hm_encode_str(md5($admin_password . $admin_salt), $encryption_key);
    $sql             = "
    INSERT INTO `" . $prefix . "users` (`id`, `user_login`, `user_pass`, `salt`, `user_nicename`, `user_email`, `user_activation_key`, `user_role`, `user_group`) VALUES
    (1, '" . $admin_username . "', '" . $password_encode . "', '" . $admin_salt . "', '" . $admin_username . "', '" . $admin_email . "', '0', '1', '0');
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Create user: ' . $admin_username . '</p>';
    /**--------------------------------------------------------*/

    /** default theme */
    $sql = "
    INSERT INTO `" . $prefix . "option` (`id`, `section`, `key`, `value`) VALUES
    (1, 'system_setting', 'theme', 'hello'),
    (2, 'system_setting', 'post_per_page', '10'),
    (3, 'system_setting', 'from_email', '" . $admin_email . "');
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Active default theme</p>';
    /**--------------------------------------------------------*/

    /** default plugin */
    $sql = "
    INSERT INTO `" . $prefix . "plugin` (`id`, `key`, `active`) VALUES
    (1, 'post', '1'),
    (2, 'hm_tinymce', '1'),
    (3, 'hm_seo', '1');
  ";
    /** echo '<p class="text-info">Query: ' . $sql . '</p>'; */
    try {
        $hmdb->exec($sql);
    } catch (PDOException $e) {
        echo '<p class="text-danger">Error: ' . $e->getMessage() . '</p>';
        die();
    }
    echo '<p class="text-success">Active default plugin</p>';
    /**--------------------------------------------------------*/

    /** Tạo .htaccess */
    $htaccess = '<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /
  RewriteRule ^index\.php$ - [L]
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule . ' . $url_path . 'index.php [L]
</IfModule>

<FilesMatch "\.php$">
  Order Deny,Allow
  Deny from all
</FilesMatch>
<FilesMatch "^index\.php$">
  Order Allow,Deny
  Allow from all
</FilesMatch>

<IfModule pagespeed_module>
  ModPagespeed on
  ModPagespeedEnableFilters
  extend_cache,combine_css,combine_javascript,collapse_whitespace,move_css_to_head
</IfModule>

# BEGIN GZIP
<IfModule mod_deflate.c>
  # Compress HTML, CSS, JavaScript, Text, XML and fonts
  AddOutputFilterByType DEFLATE application/javascript
  AddOutputFilterByType DEFLATE application/rss+xml
  AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
  AddOutputFilterByType DEFLATE application/x-font
  AddOutputFilterByType DEFLATE application/x-font-opentype
  AddOutputFilterByType DEFLATE application/x-font-otf
  AddOutputFilterByType DEFLATE application/x-font-truetype
  AddOutputFilterByType DEFLATE application/x-font-ttf
  AddOutputFilterByType DEFLATE application/x-javascript
  AddOutputFilterByType DEFLATE application/xhtml+xml
  AddOutputFilterByType DEFLATE application/xml
  AddOutputFilterByType DEFLATE font/opentype
  AddOutputFilterByType DEFLATE font/otf
  AddOutputFilterByType DEFLATE font/ttf
  AddOutputFilterByType DEFLATE image/svg+xml
  AddOutputFilterByType DEFLATE image/x-icon
  AddOutputFilterByType DEFLATE text/css
  AddOutputFilterByType DEFLATE text/html
  AddOutputFilterByType DEFLATE text/javascript
  AddOutputFilterByType DEFLATE text/plain
  AddOutputFilterByType DEFLATE text/xml

  # Remove browser bugs (only needed for really old browsers)
  BrowserMatch ^Mozilla/4 gzip-only-text/html
  BrowserMatch ^Mozilla/4\.0[678] no-gzip
  BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
  Header append Vary User-Agent
</IfModule>
# END GZIP

# BEGIN Expire headers
<ifModule mod_expires.c>
  ExpiresActive On
  ExpiresDefault "access plus 5 seconds"
  ExpiresByType image/x-icon "access plus 2592000 seconds"
  ExpiresByType image/jpeg "access plus 2592000 seconds"
  ExpiresByType image/png "access plus 2592000 seconds"
  ExpiresByType image/gif "access plus 2592000 seconds"
  ExpiresByType application/x-shockwave-flash "access plus 2592000 seconds"
  ExpiresByType text/css "access plus 604800 seconds"
  ExpiresByType text/javascript "access plus 2592000 seconds"
  ExpiresByType application/javascript "access plus 2592000 seconds"
  ExpiresByType application/x-javascript "access plus 2592000 seconds"
  ExpiresByType text/html "access plus 2592000 seconds"
  ExpiresByType application/xhtml+xml "access plus 2592000 seconds"
</ifModule>
# END Expire headers';
    $fp       = fopen('.htaccess', 'w');
    if ($fp) {
        fwrite($fp, $htaccess);
        fclose($fp);
    } else {
        echo '<p class="text-danger"><strong>The process to create the file: .htaccess failed, please create a .htaccess file (peer index.php) on the host with the following content:</strong></p>';
        echo '<textarea class="form-control" rows="10">' . $htaccess . '</textarea>';
    }
    /** tạo file config */
    $hm_config = file_get_contents('hm_include/install/hm_config_sample.php');
    $hm_config = str_replace("define('DB_NAME', '');", "define('DB_NAME', '" . $database . "');", $hm_config);
    $hm_config = str_replace("define('DB_USER', '');", "define('DB_USER', '" . $username . "');", $hm_config);
    $hm_config = str_replace("define('DB_PASSWORD', '');", "define('DB_PASSWORD', '" . $password . "');", $hm_config);
    $hm_config = str_replace("define('DB_HOST', '');", "define('DB_HOST', '" . $host . "');", $hm_config);
    $hm_config = str_replace("define('DB_PREFIX', '');", "define('DB_PREFIX', '" . $prefix . "');", $hm_config);
    $hm_config = str_replace("define('ENCRYPTION_KEY', '');", "define('ENCRYPTION_KEY', '" . $encryption_key . "');", $hm_config);
    $hm_config = str_replace("define('FOLDER_PATH', '');", "define('FOLDER_PATH', '" . $url_path . "');", $hm_config);
    if (getenv('SERVER_PORT') != '80') {
        $hm_config = str_replace("define('SERVER_PORT', '');", "define('SERVER_PORT', ':" . getenv('SERVER_PORT') . "');", $hm_config);
    }
    $fp = fopen('hm_config.php', 'w');
    if ($fp) {
        fwrite($fp, $hm_config);
        fclose($fp);
        echo '<p class="alert alert-success" role="alert">Installation of source code successfully</p>';
        echo '<p class="text-success"><a href="' . BASE_URL . 'admin/" class="btn btn-default btn-xs">Login Admin</a></p>';
    } else {
        echo '<p class="text-danger"><strong>The process to create the file: hm_config.php failed, please create a file hm_config.php (peer index.php) on the host with the following content:</strong></p>';
        echo '<textarea class="form-control" rows="10">' . $hm_config . '</textarea>';
    }
}
?>
