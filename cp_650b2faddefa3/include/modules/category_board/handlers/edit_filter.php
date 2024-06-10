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

$Cache = new Cache();

$id_filter = (int)$_POST["id_filter"];
$id_item = (int)$_POST["id_item"];

$getFilter = findOne("uni_ads_filters","ads_filters_id=?", array($id_filter));

$name = clear($_POST["name"]);
$type_filter = clear($_POST["type_filter"]);  
$visible = (int)$_POST["visible"];    
$podcat = (int)$_POST["podcat"];   
$alias = translite($_POST["alias"]);

if(!$alias){
   $alias = translite($_POST["name"]);
}

$error = array();
$ids_delete = array();
$value_filter = array();
 
if(empty($name)){$error[] = "Укажите название фильтра!";}

if($getFilter->ads_filters_id_parent == 0){
  if(!$_POST["id_cat"] || !is_array($_POST["id_cat"])){ $error[] = "Выберите категорию!";  }
}

if($type_filter != 'input_text'){
   foreach ($_POST["value_filter"] as $action => $array) {
      foreach ($array as $id => $value) {

         if(trim($value)) $value_filter[] =  trim($value);
         
      }
   }

   if(!$value_filter){ $error[] = "Добавьте значения фильтра!"; }
}

if (count($error) == 0) {

    update("UPDATE uni_ads_filters SET ads_filters_name=?,ads_filters_alias=?,ads_filters_visible=?,ads_filters_type=?,ads_filters_required=? WHERE ads_filters_id=?", array($name,$alias,$visible,$type_filter,intval($_POST["required"]),$id_filter));
    
    if($getFilter->ads_filters_id_parent == 0){
      if($_POST["id_cat"] && is_array($_POST["id_cat"])){
         
         update("delete from uni_ads_filters_category where ads_filters_category_id_filter=?", [$id_filter]);

         foreach ($_POST["id_cat"] as $value) {
            insert("INSERT INTO uni_ads_filters_category(ads_filters_category_id_cat,ads_filters_category_id_filter)VALUES(?,?)", array( intval($value), $id_filter ) );
         }

      }
    }
    
    $sort = 1;

    if($_POST["value_filter"] && $type_filter != 'input_text'){

       foreach ($_POST["value_filter"] as $action => $array) {
         foreach ($array as $id => $value) {
            
            if( trim($value) != "" ){
              if($action == "add"){
                 $insert = insert("INSERT INTO uni_ads_filters_items(ads_filters_items_id_filter,ads_filters_items_value,ads_filters_items_id_item_parent,ads_filters_items_alias,ads_filters_items_sort)VALUES(?,?,?,?,?)", array( $id_filter, clear($value), $id_item, translite($value), intval($sort) ));
                 $ids_delete[$insert] = $insert;
              }elseif($action == "edit"){
                 update("UPDATE uni_ads_filters_items SET ads_filters_items_value=?,ads_filters_items_alias=?,ads_filters_items_sort=? WHERE ads_filters_items_id=?", array(clear($value),translite($value),intval($sort),$id));
                 $ids_delete[$id] = $id;
              }
            }

            $sort++;
            
         }
       }

       update("DELETE FROM uni_ads_filters_items WHERE ads_filters_items_id_filter=".$id_filter." and ads_filters_items_id NOT IN(".implode(",", $ids_delete).")");

    }        
      
    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
    echo true;

    $Cache->update( "uni_ads_filters" );

} else {

    $_SESSION["CheckMessage"]["warning"] = implode("\n", $error); 

}

}
?>