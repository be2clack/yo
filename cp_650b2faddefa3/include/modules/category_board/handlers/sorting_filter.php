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

$Cache = new Cache();

if(isAjax() == true){

    $pos_new = 1;
    if(count($_POST['arrays'])>0){
        foreach($_POST['arrays'] as $item){
            $item = (int)substr($item,4,strlen($item));
        	update("UPDATE uni_ads_filters SET `ads_filters_position`=? WHERE `ads_filters_id`=?", array($pos_new,$item));
        	$pos_new++;
        }
        $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
    }

    $Cache->update( "uni_ads_filters" );

}      
?>