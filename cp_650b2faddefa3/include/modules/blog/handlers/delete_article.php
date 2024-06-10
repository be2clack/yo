<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_blog']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

   $Blog = new Blog();

	 if( is_array($_POST["id"]) ){
	 	 $ids = iteratingArray($_POST["id"], "int");
	 	 $Blog->delete( $ids );
	 }else{
	     $Blog->delete( [intval($_POST["id"])] );
	 }

                
   $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
   echo true;


}
?>