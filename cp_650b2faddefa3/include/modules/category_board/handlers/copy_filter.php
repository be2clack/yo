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

if(isAjax() == true){

$Filters = new Filters();
$Cache = new Cache();

$id_filter = (int)$_POST["id_filter"];
$name = clear($_POST["name"]);
$type_filter = clear($_POST["type_filter"]);  
$visible = (int)$_POST["visible"];    
$podcat = (int)$_POST["podcat"];     
$alias = translite($_POST["alias"]);

if(!$alias){
   $alias = translite($_POST["name"]);
}

$error = array();
$value_filter = array();
 
if(empty($name)){$error[] = "Укажите название фильтра!"; }
if(!$_POST["id_cat"] || !is_array($_POST["id_cat"])){ $error[] = "Выберите категорию!";  }

if($type_filter != 'input_text'){
   foreach ($_POST["value_filter"] as $action => $array) {
      foreach ($array as $id => $value) {

         if(trim($value)) $value_filter[] =  trim($value);
         
      }
   }

   if(!$value_filter){ $error[] = "Добавьте значения фильтра!"; }
}

if (count($error) == 0) {

    $main_id_filter = insert("INSERT INTO uni_ads_filters(ads_filters_name,ads_filters_alias,ads_filters_visible,ads_filters_type,ads_filters_position,ads_filters_required)VALUES(?,?,?,?,?,?)", array($name,$alias,$visible,$type_filter,$Filters->filterPosition(),intval($_POST["required"]))); 

    if($_POST["id_cat"] && is_array($_POST["id_cat"])){
       
       foreach ($_POST["id_cat"] as $value) {
          insert("INSERT INTO uni_ads_filters_category(ads_filters_category_id_cat,ads_filters_category_id_filter)VALUES(?,?)", array( intval($value), $main_id_filter ) );
       }

    }

    if($_POST["value_filter"] && $type_filter != 'input_text'){
       foreach ($_POST["value_filter"] as $action => $array) {
         foreach ($array as $id => $value) {

            if($action == "add" && trim($value)){
               insert("INSERT INTO uni_ads_filters_items(ads_filters_items_id_filter,ads_filters_items_value,ads_filters_items_alias)VALUES(?,?,?)", array( $main_id_filter, clear($value), translite($value) ));
            }
            
         }
       }
    } 

    $getPodFilters = getAll("select * from uni_ads_filters where ads_filters_id_parent=?", [$id_filter]);

    if($getPodFilters){
      foreach ($getPodFilters as $value) {

         $insert = insert("INSERT INTO uni_ads_filters(ads_filters_name,ads_filters_alias,ads_filters_visible,ads_filters_type,ads_filters_position,ads_filters_required,ads_filters_id_parent)VALUES(?,?,?,?,?,?,?)", array($value["ads_filters_name"],$value["ads_filters_alias"],$value["ads_filters_visible"],$value["ads_filters_type"],$Filters->filterPosition(),$value["ads_filters_required"],$main_id_filter));

         if($_POST["id_cat"] && is_array($_POST["id_cat"])){
          
            foreach ($_POST["id_cat"] as $catId) {
               insert("INSERT INTO uni_ads_filters_category(ads_filters_category_id_cat,ads_filters_category_id_filter)VALUES(?,?)", array( intval($catId), $insert ) );
            }

         }

         $getPodFiltersItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=?", [$value["ads_filters_id"]]);
         if($getPodFiltersItems){
            foreach ($getPodFiltersItems as $item) {
               insert("INSERT INTO uni_ads_filters_items(ads_filters_items_id_filter,ads_filters_items_value,ads_filters_items_alias)VALUES(?,?,?)", array($insert, $item["ads_filters_items_value"], $item["ads_filters_items_alias"]));
            }
         }

      }  
    }

    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
    echo true;

    $Cache->update( "uni_ads_filters" );

} else {

    $_SESSION["CheckMessage"]["warning"] = implode("<br>", $error); 

}

}
?>