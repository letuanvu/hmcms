<div class="row" >
  <div class="col-md-6">
    <h1 class="page_title"><?php echo hm_lang('content'); ?></h1>
    <form autocomplete="off" action="" method="post" class="row_margin">
      <?php
      foreach($hmcontent->hmcontent as $content_key => $content_value){
      ?>
      <div class="col-md-12">
        <p class="page_action"><?php echo $content_value["content_name"]; ?></p>
        <div class="row admin_mainbar_box">

        </div>
      </div>
      <?php
      }
      ?>
      <div class="col-md-12">
        <div class="form-group">
          <button name="save_system_setting" type="submit" class="btn btn-primary"><?php echo hm_lang('save'); ?></button>
        </div>
      </div>
    </form>
  </div>
  <div class="col-md-6">
    <h1 class="page_title"><?php echo hm_lang('taxonomy'); ?></h1>
    <form autocomplete="off" action="" method="post" class="row_margin">
      <?php
      foreach($hmtaxonomy->hmtaxonomy as $taxonomy_key => $taxonomy_value){
      ?>
      <div class="col-md-12">
        <p class="page_action"><?php echo $taxonomy_value["taxonomy_name"]; ?></p>
        <div class="row admin_mainbar_box">

        </div>
      </div>
      <?php
      }
      ?>
      <div class="col-md-12">
        <div class="form-group">
          <button name="save_system_setting" type="submit" class="btn btn-primary"><?php echo hm_lang('save'); ?></button>
        </div>
      </div>
    </form>
  </div>
</div>
