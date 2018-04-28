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
    <h1 class="title">Step 2: Connect to the database</h1>
    <div class="form_content">
      <?php
      if(isset($error)){echo $error;}
      ?>
      <form action="" method="post">
        <div class="form-group">
          <label>Database name</label>
          <p class="input_des">The database you will use to install the CMS</p>
          <input type="text" name="database" class="form-control" id="" placeholder="Database name" value="">
        </div>
        <div class="form-group">
          <label>Database account</label>
          <p class="input_des">Database account</p>
          <input type="text" name="username" class="form-control" id="" placeholder="Database account" value="">
        </div>
        <div class="form-group">
          <label>Database password</label>
          <p class="input_des">The password of the database account</p>
          <input type="password" name="password" class="form-control" id="" placeholder="Database password" value="">
        </div>
        <div class="form-group">
          <label>Database server</label>
          <p class="input_des">The database server address, usually localhost</p>
          <input type="text" name="host" class="form-control" id="" placeholder="Database server" value="localhost">
        </div>
        <div class="form-group">
          <label>Prefix table</label>
          <p class="input_des">The prefix of the table in case you used on a database already has other data available</p>
          <input type="text" name="prefix" class="form-control" id="" placeholder="Prefix table" value="hm_">
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-default" name="submit" value="Connect">
        </div>
      </form>
    </div>
  </div>
</body>

</html>
