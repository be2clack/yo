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

$id_filter = (int)$_POST["id"];
$id_parent = (int)$_POST["id_parent"];

$getFilter = findOne("uni_ads_filters","ads_filters_id=?", array($id_filter));

?>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Название фильтра</label>
  <div class="col-lg-9">
      <input type="text" name="name" class="form-control setTranslate" value="<?php echo $getFilter->ads_filters_name; ?>" /> 
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Алиас</label>
  <div class="col-lg-9">
      <input type="text" name="alias" class="form-control outTranslate" value="<?php echo $getFilter->ads_filters_alias; ?>" /> 
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Вид при подаче объявления</label>
  <div class="col-lg-9">
      <select class="filter-list-cat-select selectpicker" name="type_filter" >
        <option value="select" <?php if($getFilter->ads_filters_type == "select"){ echo 'selected=""'; } ?> >Выпадающий список с одиночным выбором</option>
        <option value="select_multi" <?php if($getFilter->ads_filters_type == "select_multi"){ echo 'selected=""'; } ?> >Выпадающий список с множественным выбором</option>
        <option value="input" <?php if($getFilter->ads_filters_type == "input"){ echo 'selected=""'; } ?> >Поле ввода цифр</option>
      </select>
  </div>
</div>

<div class="form-group row d-flex mb-5">
  <label class="col-lg-3 form-control-label">Фильтр активен</label>
  <div class="col-lg-9">
      <label>
        <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="visible" value="1" <?php if($getFilter->ads_filters_visible){ echo 'checked=""'; } ?> >
        <span><span></span></span>
      </label>
  </div>
</div>

<div class="form-group row d-flex">
  <label class="col-lg-3 form-control-label">Обязательный фильтр</label>
  <div class="col-lg-9">
      <label>
        <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="required" value="1" <?php if($getFilter->ads_filters_required){ echo 'checked=""'; } ?> >
        <span><span></span></span>
      </label>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Выберите значение</label>
    <div class="col-lg-9">

        <select class="form-control select-item-podfilter" name="id_item"  >
        <option value="0" >Не выбрано</option>
        <?php
        
          $items = getAll("SELECT * FROM uni_ads_filters_items WHERE ads_filters_items_id_filter=? order by ads_filters_items_sort asc", array($id_parent));
            if(count($items)>0){
             
               foreach($items AS $item){ 

                     $getFilterItem = findOne("uni_ads_filters_items","ads_filters_items_id=?", array($item["ads_filters_items_id_item_parent"]));
                     
                     $countFilterItem = (int)getOne("select count(*) as total from uni_ads_filters_items where ads_filters_items_id_item_parent=? and ads_filters_items_id_filter=?", [$item["ads_filters_items_id"],$id_filter] )["total"];

                     if($getFilterItem){
                        $value = $getFilterItem["ads_filters_items_value"] . '-' . $item["ads_filters_items_value"] . '('.$countFilterItem.')';
                     }else{
                        $value = $item["ads_filters_items_value"] . '('.$countFilterItem.')';
                     }

                     ?>
                     <option value="<?php echo $item["ads_filters_items_id"]; ?>" data-id-filter="<?php echo $id_filter; ?>" ><?php echo $value; ?></option>
                     <?php
                 
               }   
            
            }        
        
        ?>
                
        </select>

    </div>
</div>

<div class="form-group row d-flex mb-5">
  <label class="col-lg-3 form-control-label"></label>
  <div class="col-lg-9">
   
   <div class="list-item-podfilter" ></div>

  </div>
</div>

<input type="hidden" name="id_filter" value="<?php echo $id_filter; ?>" >
<input type="hidden" name="id_parent" value="<?php echo $id_parent; ?>" >

<?php
} 
?>