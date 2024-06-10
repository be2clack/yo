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

    $insert = insert("INSERT INTO uni_ads_filters(ads_filters_name,ads_filters_alias,ads_filters_visible,ads_filters_type,ads_filters_position,ads_filters_required)VALUES(?,?,?,?,?,?)", array($name,$alias,$visible,$type_filter,$Filters->filterPosition(),intval($_POST["required"]))); 

    if($_POST["id_cat"] && is_array($_POST["id_cat"])){
       
       foreach ($_POST["id_cat"] as $value) {
          insert("INSERT INTO uni_ads_filters_category(ads_filters_category_id_cat,ads_filters_category_id_filter)VALUES(?,?)", array( intval($value), $insert ) );
       }

    }

    $sort = 1;

    if($_POST["value_filter"] && $type_filter != 'input_text'){
       foreach ($_POST["value_filter"] as $action => $array) {
         foreach ($array as $id => $value) {

            if($action == "add" && trim($value) != ""){
               insert("INSERT INTO uni_ads_filters_items(ads_filters_items_id_filter,ads_filters_items_value,ads_filters_items_alias,ads_filters_items_sort)VALUES(?,?,?,?)", array( $insert, clear($value), translite($value), intval($sort) ));
            }

            $sort++;
            
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