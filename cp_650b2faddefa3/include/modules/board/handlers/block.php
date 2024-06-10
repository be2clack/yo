<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_board']) && !(new Admin())->accessAdmin($_SESSION['cp_processing_board']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$Ads = new Ads();
$Cache = new Cache();

$text = clear($_POST["text"]) ? clear($_POST["text"]) : clear($_POST["comment"]);

if($text){

   update("UPDATE uni_ads SET ads_status=?,ads_note=? WHERE ads_id=?", [3,$text,intval($_POST["id_ad"])], true);

   $Ads->changeStatus(intval($_POST["id_ad"]), 3, "", $text);

   $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
   echo true;

   $Cache->update("uni_ads");

}else{
   $_SESSION["CheckMessage"]["error"] = "Пожалуйста, укажите причину";
}


}

?>
