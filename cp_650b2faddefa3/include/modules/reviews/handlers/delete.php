<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_reviews']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

   $id = (int)$_POST["id"];

   $getReview = findOne("uni_clients_reviews", "clients_reviews_id=?", [$id]);

   if($getReview["clients_reviews_files"]){
       $files = explode(",", $getReview["clients_reviews_files"]);
       if($files){
          foreach ($files as $name) {
              @unlink( $config["basePath"] . "/" . $config["media"]["user_attach"] . "/" . $name );
          }
       }
   }

   update('delete from uni_clients_reviews where clients_reviews_id=?', [$id]);

   $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
             
   echo true;
    
}
?>