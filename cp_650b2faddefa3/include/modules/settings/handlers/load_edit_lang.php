<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_settings']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

$id = intval($_POST["id"]);
$get = findOne("uni_languages","id=?", array($id));
  
?>

    <div class="form-group row mb-5">
      <label class="col-lg-4 form-control-label">Статус</label>
      <div class="col-lg-8">
          <label>
            <input class="toggle-checkbox-sm" type="checkbox" name="status" <?php if($get->status) echo 'checked=""'; ?> value="1" >
            <span><span></span></span>
          </label>
      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-4 form-control-label">Картинка</label>
      <div class="col-lg-8">

        <?php echo img( array( "img1" => array( "class" => "change-img-edit change-image-edit", "path" => $config["media"]["other"] . "/" . $get->image, "width" => "60px" ), "img2" => array( "class" => "change-img-edit change-image-edit", "path" => $config["media"]["other"] . "/icon_photo_add.png", "width" => "60px" ) ) ); ?>

        <input type="file" name="image" class="input-img-edit" >
            
      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-4 form-control-label">Название</label>
      <div class="col-lg-8">
           <input type="text" class="form-control" value="<?php echo $get->name; ?>" name="name" >
      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-4 form-control-label">Алиас</label>
      <div class="col-lg-8">
           <input type="text" class="form-control" value="<?php echo $get->code; ?>" name="alias" >
      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-4 form-control-label">ISO</label>
      <div class="col-lg-8">
           <input type="text" class="form-control" value="<?php echo $get->iso; ?>" name="iso" >
      </div>
    </div>

    <input type="hidden" name="id" value="<?php echo $id; ?>" />                                                              

 