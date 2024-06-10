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

 if( is_array($_POST["id"]) ){
 	 $ids = iteratingArray($_POST["id"], "int");
 	 $Ads->delete(["id"=>implode(",", $ids)]);
 }else{
     $Ads->delete(["id"=>intval($_POST["id"])]);
 }

 $Cache->update( "uni_ads" );

 $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
 echo true;

}

?>
