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

if(isAjax() == true){

    $mediaId = (int)$_POST['media_id'];
    $userId = (int)$_POST['user_id'];

    $getStory = findOne('uni_clients_stories', 'clients_stories_user_id=?', [$userId]);

    if(!$getStory){
      smart_insert('uni_clients_stories', [
        'clients_stories_user_id'=>$userId,
        'clients_stories_loaded'=>1,
        'clients_stories_timestamp'=>date("Y-m-d H:i:s"),
      ]);   
    }else{
      update('update uni_clients_stories set clients_stories_timestamp=? where clients_stories_user_id=?', [date("Y-m-d H:i:s"), $userId]);
    }

    update("UPDATE uni_clients_stories_media SET clients_stories_media_status=?,clients_stories_media_timestamp=? WHERE clients_stories_media_id=?", [intval($_POST["status"]),date('Y-m-d H:i:s'),$mediaId]);

    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
    echo true;

}

?>