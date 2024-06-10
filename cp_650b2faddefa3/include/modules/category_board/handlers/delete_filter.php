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

$Filters = new Filters();
$Cache = new Cache();

if(isAjax() == true){

    $id = (int)$_POST["id"];

    $ids = idsBuildJoin($Filters->idsBuild($id),$id);

    $getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter IN({$ids})");

    if( count($getItems) ){
        foreach ($getItems as $value) {
           $ids_items[] = $value["ads_filters_items_id"];
        }
        update("DELETE FROM uni_ads_filters_alias WHERE ads_filters_alias_id_filter_item IN(".implode(",", $ids_items).")");
    }

    update("DELETE FROM uni_ads_filters WHERE ads_filters_id IN({$ids})");
    update("DELETE FROM uni_ads_filters_items WHERE ads_filters_items_id_filter IN({$ids})"); 
    update("DELETE FROM uni_ads_filters_category WHERE ads_filters_category_id_filter IN({$ids})");  
    update("DELETE FROM uni_ads_filters_variants WHERE ads_filters_variants_id_filter IN({$ids})");
     
     $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";                  
     echo true; 

     $Cache->update( "uni_ads_filters" );

}
?>