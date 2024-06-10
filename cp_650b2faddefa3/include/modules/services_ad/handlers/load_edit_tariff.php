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
$get = findOne("uni_services_tariffs","services_tariffs_id=?", array($id));
$services = json_decode($get->services_tariffs_services, true);
?>


<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-4 form-control-label">Статус</label>
  <div class="col-lg-6">
      <label>
        <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="status" <?php if(!empty($get->services_tariffs_status)) echo 'checked=""'; ?> value="1" >
        <span><span></span></span>
      </label>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-4 form-control-label">Единоразовое использование</label>
  <div class="col-lg-6">
      <label>
        <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="onetime" <?php if(!empty($get->services_tariffs_onetime)) echo 'checked=""'; ?> value="1" >
        <span><span></span></span>
      </label>
      <div><small>Пользователь сможет подключить тариф только 1 раз</small></div>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-4 form-control-label">Название</label>
  <div class="col-lg-8">
       <input type="text" class="form-control" value="<?php echo $get->services_tariffs_name; ?>" name="name" >
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-4 form-control-label">Цена</label>
  <div class="col-lg-3">
       <input type="number" class="form-control" value="<?php echo $get->services_tariffs_price; ?>" name="price" >
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-4 form-control-label">Новая цена</label>
  <div class="col-lg-3">
       <input type="number" class="form-control" value="<?php echo $get->services_tariffs_new_price; ?>" name="new_price" >
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-4 form-control-label">Срок действия (дней)</label>
  <div class="col-lg-3">
       <input type="number" class="form-control" value="<?php echo $get->services_tariffs_days; ?>" name="count_day" >
       <small>Оставьте это поле пустым если срок неограничен</small>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-4 form-control-label">Бонус</label>
  <div class="col-lg-3">
       <input type="number" class="form-control" value="<?php echo $get->services_tariffs_bonus; ?>" name="bonus" >
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-4 form-control-label">Краткое описание</label>
  <div class="col-lg-8">
       <textarea class="form-control" name="desc" ><?php echo $get->services_tariffs_desc; ?></textarea>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-4 form-control-label">Услуги тарифа</label>
  <div class="col-lg-8">

    <div class="services-tariffs-box" >

       <?php
          $getTariffServices = getAll('select * from uni_services_tariffs_checklist');
          if(count($getTariffServices)){
              foreach ($getTariffServices as $value) {
                 ?>

                   <div class="custom-control custom-checkbox">
                       <input type="checkbox" class="custom-control-input" name="services[]" <?php if(in_array($value['services_tariffs_checklist_id'], $services)) echo 'checked=""'; ?> value="<?php echo $value['services_tariffs_checklist_id']; ?>" id="edit_services_checkbox<?php echo $value['services_tariffs_checklist_id']; ?>">
                       <label class="custom-control-label" for="edit_services_checkbox<?php echo $value['services_tariffs_checklist_id']; ?>"><?php echo $value['services_tariffs_checklist_name']; ?></label>
                   </div>

                 <?php
              }
          }
       ?>

    </div>

  </div>
</div>

<input type="hidden" name="id" value="<?php echo $id; ?>" />

 