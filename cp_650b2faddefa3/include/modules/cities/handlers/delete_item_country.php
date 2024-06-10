<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_city']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$Cache = new Cache();

$id = (int)$_POST["id"];

  if ($id){
     update("DELETE FROM uni_country WHERE country_id=?", array($id));
     update("DELETE FROM uni_region WHERE country_id=?", array($id));
     update("DELETE FROM uni_city WHERE country_id=?", array($id));

     $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";     
     echo true; 
     $Cache->update( "cityDefault" );
  }

}   
?>