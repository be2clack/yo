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

include_once("../fn.php");

if(isAjax() == true){

$CategoryBoard = new CategoryBoard();

$ids = [];

$id = (int)$_POST["id"];

$getPackage = findOne("uni_ads_packages", "id=?", [$id]);

$getPackageCategories = getAll("select * from uni_ads_packages_categories where package_id=?", [$id]);

if($getPackageCategories){
  foreach ($getPackageCategories as $key => $value) {
     $ids[] = $value["cat_id"];
  }
}

?>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-4 form-control-label">Категория</label>
  <div class="col-lg-6">
      <select name="cat_id[]" class="selectpicker" multiple="" title="Не выбрано" data-width="100%" >
         <?php echo outCategoryOptionsPackages(0,0,(new CategoryBoard())->getCategories(), $ids); ?>
      </select>                         
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-4 form-control-label">Срок пакета</label>
  <div class="col-lg-6">
      <div class="input-group mb-2">
         <input type="number" class="form-control" value="<?php echo $getPackage["period"]; ?>" name="period" >
         <div class="input-group-prepend">
            <div class="input-group-text">дней</div>
         </div>                       
      </div>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-4 form-control-label">Объявлений</label>
  <div class="col-lg-6">
       <input type="number" class="form-control" value="<?php echo $getPackage["count_ad"]; ?>" name="count_ad" >
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-4 form-control-label">Стоимость объявления</label>
  <div class="col-lg-6">
      <div class="input-group mb-2">
         <input type="number" class="form-control" value="<?php echo $getPackage["price_ad"]; ?>" name="price_ad" >
         <div class="input-group-prepend">
            <div class="input-group-text"><?php echo $settings["currency_main"]["sign"]; ?></div>
         </div>                       
      </div>
  </div>
</div>

<input type="hidden" name="id" value="<?php echo $id; ?>" >

<?php
} 
?>