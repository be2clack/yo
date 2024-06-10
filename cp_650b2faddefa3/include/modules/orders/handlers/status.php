<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_orders']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

   $id = (int)$_POST["id"];
   $status = (int)$_POST["status"];

   update("UPDATE uni_orders SET orders_status_pay=? WHERE orders_id=?", array($status,$id));

   $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
   echo true; 

}  

?>