<?php
define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_page']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$data = findOne( "uni_promo_pages", "promo_pages_id=?", [ intval($_POST["id"]) ] );

   ?>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Заголовок страницы</label>
      <div class="col-lg-9">
           <input type="text" class="form-control" name="title" value="<?php echo $data["promo_pages_title"]; ?>" >
      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Алиас</label>
      <div class="col-lg-9">
           <input type="text" class="form-control" name="alias" value="<?php echo $data["promo_pages_alias"]; ?>" >
      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Meta Description</label>
      <div class="col-lg-9">
           <textarea class="form-control" name="desc" ><?php echo $data["promo_pages_desc"]; ?></textarea>
      </div>
    </div>

    <input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>" >

   <?php

}
?>