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

if(isAjax() == true){

  $app_chat_snippets_message = [];

  if($_POST["app_chat_snippets_message"]){
    foreach ($_POST["app_chat_snippets_message"] as $key => $value) {
       if(trim($value)){
          $app_chat_snippets_message[$key] = explode(",", $value);
       }else{
          $app_chat_snippets_message[$key] = null;
       }
    }
  }

  update("UPDATE uni_settings SET value=? WHERE name=?", array(json_encode($app_chat_snippets_message),'app_chat_snippets_message'));

}  
?>