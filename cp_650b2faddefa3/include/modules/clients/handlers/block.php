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

$text = clear($_POST["text"]) ? clear($_POST["text"]) : clear($_POST["comment"]);
 
if($text){

   update("UPDATE uni_clients SET clients_status=?,clients_note=? WHERE clients_id=?", [2,$text,intval($_POST["id_user"])]);

   $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
   echo true;

   $Cache->update( "uni_ads" );

}else{
   $_SESSION["CheckMessage"]["error"] = "Пожалуйста, укажите причину жалобы";
}


}

?>
