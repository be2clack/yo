<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_city']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

 $id = (int)$_POST["id"];
 $get = findOne("uni_region","region_id=?", array($id));
  
?>

<form id="form-data-region-edit" >

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Название</label>
    <div class="col-lg-9">
         <input type="text" class="form-control setTranslate" value="<?php echo $get->region_name; ?>" name="name" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Алиас</label>
    <div class="col-lg-9">
         <input type="text" class="form-control outTranslate" value="<?php echo $get->region_alias; ?>" name="alias" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Склонение</label>
    <div class="col-lg-9">
         <input type="text" class="form-control" value="<?php echo $get->region_declination; ?>" name="declination" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Описание</label>
    <div class="col-lg-9">
         <textarea name="desc" class="form-control" ><?php echo $get->region_desc; ?></textarea>
    </div>
  </div>

  <input type="hidden" name="id" value="<?php echo $id; ?>" />                                                              
</form>
 