<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_clients']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

$Cache = new Cache();

if(isAjax() == true){

 if( is_array($_POST["id"]) ){
     $ids = iteratingArray($_POST["id"], "int");
     update("UPDATE uni_clients SET clients_status='".intval($_POST["status"])."' WHERE clients_id IN(".implode(",", $ids).")");
 }else{
     update("UPDATE uni_clients SET clients_status='".intval($_POST["status"])."' WHERE clients_id IN(".intval($_POST["id"]).")");
 }


 $Cache->update( "uni_ads" );

 $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
 echo true;

}

?>