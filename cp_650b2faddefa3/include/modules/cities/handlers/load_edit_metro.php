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

$id = intval($_POST["id"]);
$get = findOne("uni_metro","id=?", array($id));
  
?>

<form method="post" id="form-data-metro-edit" >

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Цвет ветки</label>
    <div class="col-lg-9">
         <input type="text" class="form-control" value="<?php echo $get->color; ?>" name="color" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Название</label>
    <div class="col-lg-9">
         <input type="text" class="form-control" value="<?php echo $get->name; ?>" name="name" >
    </div>
  </div>

  <input type="hidden" name="id" value="<?php echo $id; ?>" />
</form>

 