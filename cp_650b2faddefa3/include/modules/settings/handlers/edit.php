<?php
define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_settings']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

$Main = new Main();

if(isAjax() == true){

   $error = [];

   if(isset($_POST["tab"])){
      if(file_exists(__dir__."/include/{$_POST["tab"]}.php")){
         include __dir__."/include/{$_POST["tab"]}.php";
      }
   }
   
  if(count($error) > 0){ $_SESSION["CheckMessage"]["error"] = implode("<br/>",$error);  }
  else { $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!"; }
    
}     
?>