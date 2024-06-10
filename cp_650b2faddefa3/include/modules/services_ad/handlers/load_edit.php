<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_board']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

$id = intval($_POST["id"]);
$get = findOne("uni_services_ads","services_ads_id=?", array($id));
  
?>


    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-4 form-control-label">Статус</label>
      <div class="col-lg-6">
          <label>
            <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="visible" <?php if(!empty($get->services_ads_visible)) echo 'checked=""'; ?> value="1" >
            <span><span></span></span>
          </label>
      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-4 form-control-label">Рекомендуемый</label>
      <div class="col-lg-6">
          <label>
            <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="recommended" <?php if(!empty($get->services_ads_recommended)) echo 'checked=""'; ?> value="1" >
            <span><span></span></span>
          </label>
      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-4 form-control-label">Изображение</label>
      <div class="col-lg-8">
          <?php echo img( array( "img1" => array( "class" => "change-img change-image", "path" => $config["media"]["other"] . "/" . $get->services_ads_image, "width" => "60px" ), "img2" => array( "class" => "change-img change-image", "path" => $config["media"]["other"] . "/icon_photo_add.png", "width" => "60px" ) ) ); ?>
          <input type="file" name="image" class="input-img" >
      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-4 form-control-label">Заголовок</label>
      <div class="col-lg-8">
           <input type="text" class="form-control" value="<?php echo $get->services_ads_name; ?>" name="title" >
      </div>
    </div>

    <?php if(!$get->services_ads_onetime){ ?>
    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-4 form-control-label"></label>
      <div class="col-lg-8">

          <div class="styled-radio">
          <input type="radio" name="variant_count_day" <?php if($get->services_ads_variant == 1){ echo 'checked=""'; } ?> value="1" id="rad-1">
          <label for="rad-1">Фиксированный срок действия услуги</label>
          </div>
          <div class="styled-radio">
          <input type="radio" name="variant_count_day" <?php if($get->services_ads_variant == 2){ echo 'checked=""'; } ?> value="2" id="rad-2">
          <label for="rad-2">Срок действия выбирает пользователь</label>
          </div>

      </div>
    </div>

    <div class="box-variant-services box-variant-services1" <?php if($get->services_ads_variant == 1){ echo 'style="display: block;"'; } ?> >

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-4 form-control-label">Стоимость услуги</label>
      <div class="col-lg-3">
           <input type="text" class="form-control" value="<?php echo $get->services_ads_price; ?>" name="price_variant1" >
      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-4 form-control-label">Новая цена</label>
      <div class="col-lg-3">
           <input type="text" class="form-control" value="<?php echo $get->services_ads_new_price; ?>" name="new_price_variant1" >
      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-4 form-control-label">Срок действия (дней)</label>
      <div class="col-lg-3">
           <input type="text" class="form-control" value="<?php echo $get->services_ads_count_day; ?>" name="count_day" >
      </div>
    </div>

    </div>

    <div class="box-variant-services box-variant-services2" <?php if($get->services_ads_variant == 2){ echo 'style="display: block;"'; } ?> >

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-4 form-control-label">Стоимость услуги за 1 день</label>
      <div class="col-lg-3">
           <input type="text" class="form-control" value="<?php echo $get->services_ads_price; ?>" name="price_variant2" >
      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-4 form-control-label">Новая цена</label>
      <div class="col-lg-3">
           <input type="text" class="form-control" value="<?php echo $get->services_ads_new_price; ?>" name="new_price_variant2" >
      </div>
    </div>

    </div>
    
    <?php }else{ ?>

      <div class="form-group row d-flex align-items-center mb-5">
        <label class="col-lg-4 form-control-label">Стоимость услуги</label>
        <div class="col-lg-3">
             <input type="text" class="form-control" value="<?php echo $get->services_ads_price; ?>" name="price_variant2" >
        </div>
      </div>

      <div class="form-group row d-flex align-items-center mb-5">
        <label class="col-lg-4 form-control-label">Новая цена</label>
        <div class="col-lg-3">
             <input type="text" class="form-control" value="<?php echo $get->services_ads_new_price; ?>" name="new_price_variant2" >
        </div>
      </div>      

    <?php } ?>
    
    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-4 form-control-label">Описание</label>
      <div class="col-lg-8">
           <textarea class="form-control" name="desc" ><?php echo urldecode($get->services_ads_text); ?></textarea>
      </div>
    </div>
  
  <input type="hidden" name="id" value="<?php echo $id; ?>" />

 