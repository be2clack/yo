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

   $getStory = findOne("uni_clients_stories_media", "clients_stories_media_id=?", array($id));

   if($getStory){

      $Main->returnPaymentStory($id);

      if($getStory['clients_stories_media_status'] == 0 && $settings["user_stories_free_add"]){
         $getUser = findOne('uni_clients', 'clients_id=?', [$idUser]);
         if($getUser['clients_count_story_publication']!=0){
            update('update uni_clients set clients_count_story_publication=clients_count_story_publication-1 where clients_id=?', [$idUser]);
         }
      }

      @unlink($config['basePath'].'/'.$config['media']['user_stories'].'/'.$getStory['clients_stories_media_name']);

      if($getStory['clients_stories_media_preview']) @unlink($config['basePath'].'/'.$config['media']['user_stories'].'/'.$getStory['clients_stories_media_preview']);

      update("delete from uni_clients_stories_media where clients_stories_media_id=?", array($id));

      update('delete from uni_clients_stories_view where story_id=?', [$id]);

      $checkStoryMedia = getAll('select * from uni_clients_stories_media where clients_stories_media_user_id=?', [$getStory['clients_stories_media_user_id']]);

      if(!$checkStoryMedia){
         update("delete from uni_clients_stories where clients_stories_user_id=?", array($getStory['clients_stories_media_user_id']));
      }

   }

   $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
             
   echo true;
    
}
?>