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
 $get = findOne("uni_country","country_id=?", array($id));
  
?>

 <form id="form-data-country-edit" >

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Статус видимости</label>
      <div class="col-lg-9">
          <label>
            <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="status" <?php if(!empty($get->country_status)) echo 'checked=""'; ?> value="1" >
            <span><span></span></span>
          </label>
      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Изображение</label>
      <div class="col-lg-9">

            <div class="small-image-container" >
              <span class="small-image-delete" <?php if(!$get->country_image){ echo 'style="display: none;"'; } ?> > <i class="la la-trash"></i> </span>

              <?php echo img( array( "img1" => array( "class" => "change-img", "path" => $config["media"]["other"] . "/" . $get->country_image, "width" => "40px" ), "img2" => array( "class" => "change-img", "path" => $config["media"]["other"] . "/icon_photo_add.png", "width" => "50px" ) ) ); ?>

              <input type="hidden" name="image_delete" value="0" >
            </div>

            <input type="file" name="image" class="input-img" >

      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Название</label>
      <div class="col-lg-9">
           <input type="text" class="form-control" value="<?php echo $get->country_name; ?>" name="country" >
      </div>
    </div>
	
    <div class="form-group row d-flex align-items-center mb-5">
         <label class="col-lg-3 form-control-label">Склонение</label>
         <div class="col-lg-9">
            <input type="text" class="form-control" value="<?php echo $get->country_declination; ?>" name="declination" >
         </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Алиас</label>
      <div class="col-lg-9">
           <input type="text" class="form-control" value="<?php echo $get->country_alias; ?>" name="alias" >
      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Описание</label>
      <div class="col-lg-9">
           <textarea name="desc" class="form-control" ><?php echo $get->country_desc; ?></textarea>
      </div>
    </div>

    <hr>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Телефонный код страны</label>
      <div class="col-lg-9">
           <select class="selectpicker" name="code_phone" data-live-search="true" >
              <option value="" >Без кода</option>
              <?php
                  if($config['format_phone']){
                     foreach ($config['format_phone'] as $value) {
                         ?>
                         <option <?php if($value['iso'] == $get->country_code_phone){ echo 'selected=""'; } ?> value="<?php echo $value['iso']; ?>" ><?php echo $value['country_ru']; ?>, <?php echo $value['code']; ?></option>
                         <?php
                     }
                  }
              ?>
           </select>
      </div>
    </div>

    <div class="country-format-phone-input" <?php if($get->country_code_phone){ echo 'style="display: block;"'; } ?> >
    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Формат номера телефона</label>
      <div class="col-lg-9">
           <input type="text" class="form-control" value="<?php echo $get->country_format_phone; ?>" name="format_phone" >
           <small>Пример формата: +7(___) ___ ____</small>
      </div>
    </div>
    </div>

    <hr>

    <div class="alert alert-primary alert-dissmissible fade show" style="margin-top: 10px; margin-bottom: 10px;">Укажите широту и долготу столицы страны</div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Широта</label>
      <div class="col-lg-9">
           <input type="text" class="form-control" value="<?php echo $get->country_lat; ?>" name="lat" >
      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Долгота</label>
      <div class="col-lg-9">
           <input type="text" class="form-control" value="<?php echo $get->country_lng; ?>" name="lng" >
      </div>
    </div>

    <input type="hidden" name="id" value="<?php echo $id; ?>" />                                                              
 </form>
 