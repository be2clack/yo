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

 $Elastic = new Elastic();
 $Cache = new Cache();

 $time_current = time();
  
 $period_time = date("Y-m-d H:i:s", $time_current + ($settings["ads_time_publication_default"] * 86400) );

 if( is_array($_POST["id"]) ){
 	 $ids = iteratingArray($_POST["id"], "int");
     update("UPDATE uni_ads SET ads_period_publication='".$period_time."' WHERE ads_id IN(".implode(",", $ids).")");
     foreach ($_POST["id"] as $key => $value) {
     	$Elastic->update( [ "index" => "uni_ads", "type" => "ad", "id" => $value, "body" => [ "doc" => [ "ads_period_publication" => $period_time ] ] ] );
     }
 }else{
     update("UPDATE uni_ads SET ads_period_publication='".$period_time."' WHERE ads_id IN(".intval($_POST["id"]).")");
     $Elastic->update( [ "index" => "uni_ads", "type" => "ad", "id" => intval($_POST["id"]), "body" => [ "doc" => [ "ads_period_publication" => $period_time ] ] ] );
 }

 $Cache->update( "uni_ads" );

 $_SESSION["CheckMessage"]["success"] = "Объявления продлены на " . $settings["ads_time_publication_default"] . " " . ending( $settings["ads_time_publication_default"], "день", "дня", "дней" );          
 echo true;

}

?>
