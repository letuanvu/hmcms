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
    <h1 class="title">Step 3: Perform the installation</h1>
    <div class="form_content">
      <div class="alert alert-success" role="alert">Check the database connection successfully, please enter the necessary information</div>
      <?php
      if(isset($error)){echo $error;}
      ?>
      <form action="" method="post">
        <div class="form-group">
          <label>Admin account</label>
          <p class="input_des">The account is used to login to the administration page</p>
          <input required type="text" name="admin_username" class="form-control" id="" placeholder="admin" value="">
        </div>
        <div class="form-group">
          <label>Admin email</label>
          <p class="input_des">Email to retrieve password</p>
          <input required type="text" name="admin_email" class="form-control" id="" placeholder="admin@mysite.com" value="">
        </div>
        <div class="form-group">
          <label>Administrator password</label>
          <p class="input_des">Administrator account password</p>
          <input required type="password" name="admin_password" class="form-control" id="" placeholder="******" value="">
        </div>
        <div class="form-group">
          <label>Encryption key</label>
          <p class="input_des">The string is encrypted data, if you are not sure what this is, please do not edit</p>
          <input required type="text" name="encryption_key" class="form-control" id="" placeholder="Tài khoản" value="<?php echo generateRandomString(); ?>">
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-default" name="submit" value="Install source code">
        </div>
      </form>
    </div>
  </div>
</body>

</html>
