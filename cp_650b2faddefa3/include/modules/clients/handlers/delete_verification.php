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

$Main = new Main();

if(isAjax() == true){

   $id = (int)$_POST["id"];

   $getVerification = findOne("uni_clients_verifications", "id=?", [$id]);

   $files = json_decode($getVerification["files"], true);
   
   foreach ($files as $file) {
      if(file_exists($config["basePath"].'/'.$config["media"]["user_attach"].'/'.$file)){
         unlink($config["basePath"].'/'.$config["media"]["user_attach"].'/'.$file);
      }
   }

   update('delete from uni_clients_verifications where id=?', [$id]);

   $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
             
   echo true;
    
}
?>