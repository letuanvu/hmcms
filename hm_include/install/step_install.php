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
    <div class="form_content">
      <?php install_db(); ?>
    </div>
  </div>
</body>

</html>
