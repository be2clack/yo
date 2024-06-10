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

    update("TRUNCATE TABLE uni_ads_filters");
    update("TRUNCATE TABLE uni_ads_filters_items"); 
    update("TRUNCATE TABLE uni_ads_filters_category");  
    update("TRUNCATE TABLE uni_ads_filters_variants");
    update("TRUNCATE TABLE uni_ads_filters_alias");
     
    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";                  
    echo true; 

    $Cache->update( "uni_ads_filters" );

}
?>