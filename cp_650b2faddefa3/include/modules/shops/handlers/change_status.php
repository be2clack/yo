<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_shops']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

$id = intval($_POST["id"]);
$status = intval($_POST["status"]);
$comment = clear($_POST["comment"]);

$getShop = findOne("uni_clients_shops", "clients_shops_id=?", [$id]);
$getUser = findOne("uni_clients", "clients_id=?", array($getShop["clients_shops_id_user"]));

if(isAjax() == true){

   if($status == 2){

      if(!$comment){
          $_SESSION["CheckMessage"]["error"] = "Укажите причину отклонения!";          
          echo json_encode(["status"=>false]);   
          exit;
      }else{

         update("UPDATE uni_clients_shops SET clients_shops_status=?,clients_shops_status_note=? WHERE clients_shops_id=?", [$status,$comment,$id]);

         $data = array("{SHOP_TITLE}"=>$getShop["clients_shops_title"],
                       "{SHOP_LINK}"=>$Shop->linkShop($getShop["clients_shops_id_hash"]),
                       "{USER_NAME}"=>$getUser["clients_name"],                          
                       "{UNSUBSCRIBE}"=>"",                          
                       "{EMAIL_TO}"=>$getUser["clients_email"]
                       );

         email_notification( array( "variable" => $data, "code" => "SHOP_MODERATION_CANCEL" ) );

      }

   }elseif($status == 1){

      update("UPDATE uni_clients_shops SET clients_shops_status=?,clients_shops_status_note=? WHERE clients_shops_id=?", [$status,"",$id]);

      $data = array("{SHOP_TITLE}"=>$getShop["clients_shops_title"],
                    "{SHOP_LINK}"=>$Shop->linkShop($getShop["clients_shops_id_hash"]),
                    "{USER_NAME}"=>$getUser["clients_name"],                          
                    "{UNSUBSCRIBE}"=>"",                          
                    "{EMAIL_TO}"=>$getUser["clients_email"]
                    );

      email_notification( array( "variable" => $data, "code" => "SHOP_MODERATION_PUBLISHED" ) );

   }

   $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";   
   echo json_encode(["status"=>true]);

}

?>