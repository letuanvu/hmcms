<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Install</title>
  <script src="<?php echo BASE_URL . HM_ADMIN_DIR; ?>/layout/js/jquery-2.1.3.min.js"></script>
  <script src="<?php echo BASE_URL . HM_ADMIN_DIR; ?>/layout/bootstrap/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="<?php echo BASE_URL . HM_ADMIN_DIR; ?>/layout/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL . HM_ADMIN_DIR; ?>/layout/bootstrap/css/bootstrap-theme.min.css">
  <link rel="stylesheet" id=""  href="<?php echo BASE_URL . HM_INCLUDE_DIR; ?>/install/layout/style.css" media="all" />
</head>
<body>
  <div id="content">
    <p class="hello_world">Welcome to hmcms, we need to install to start using source code</p>
    <h1 class="title">Step 1: Check the server configuration</h1>
    <div class="form_content">
      <?php
      $check = array();

      if(allow_version()){
        $check['allow_version'] = '1';
        echo '<div class="alert alert-success" role="alert">PHP version is greater than 7.0</div>';
      }else{
        $check['allow_version'] = '0';
        echo '<div class="alert alert-danger" role="alert">PHP version is greater than 7.0</div>';
      }

      if($gdv = gdVersion()) {
        if ($gdv >=2) {
          $check['gdVersion'] = '1';
          echo '<div class="alert alert-success" role="alert">The GD library version is larger than 2.0</div>';
        } else {
          $check['gdVersion'] = '0';
          echo '<div class="alert alert-danger" role="alert">The GD library version is larger than 2.0</div>';
        }
      } else {
        $check['gdVersion'] = '0';
        echo '<div class="alert alert-danger" role="alert">GD library is not installed</div>';
      }


      if(is_writable('hm_content/uploads')){
        $check['uploadWritable'] = '1';
        echo '<div class="alert alert-success" role="alert">The directory hm_content/uploads has the write permission</div>';
      }else{
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
          $check['uploadWritable'] = '1';
          echo '<div class="alert alert-success" role="alert">The directory hm_content/uploads has the write permission</div>';
        }else{
          $check['uploadWritable'] = '0';
          echo '<div class="alert alert-danger" role="alert">The directory hm_content / uploads does not have write permission, please chmod 0777</div>';
        }
      }

      if(function_exists('openssl_encrypt')){
        $check['mcrypt'] = '1';
        echo '<div class="alert alert-success" role="alert">Support openssl_encrypt function</div>';
      }else{
        $check['mcrypt'] = '0';
        echo '<div class="alert alert-danger" role="alert">Not support openssl_encrypt function</div>';
      }

      if(function_exists('mysqli_connect')){
        $check['mysqli'] = '1';
        echo '<div class="alert alert-success" role="alert">Support mysqli_connect function</div>';
      }else{
        $check['mysqli'] = '0';
        echo '<div class="alert alert-danger" role="alert">Not support mysqli_connect function</div>';
      }

      if(!in_array('0',$check)){
        echo '<a href="?step=2" class="btn btn-default">Next step</a>';
      }
      ?>
    </div>
  </div>
</body>

</html>
