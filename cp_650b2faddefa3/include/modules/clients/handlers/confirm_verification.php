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

   $id = (int)$_POST["id"];

   $getVerification = findOne("uni_clients_verifications", "id=?", [$id]);
   $getUser = findOne("uni_clients", "clients_id=?", [$getVerification["user_id"]]);

   update('update uni_clients_verifications set status=? where id=?', [1, $id]);
   update('update uni_clients set clients_verification_status=? where clients_id=?', [1, $getVerification["user_id"]]);
    
   $data = array("{USER_NAME}"=>$getUser["clients_name"],
                "{PROFILE_LINK}"=>_link("user/".$getUser["clients_id_hash"]),                         
                "{UNSUBSCRIBE}"=>"",                          
                "{EMAIL_TO}"=>$getUser["clients_email"]
                );

   email_notification( array( "variable" => $data, "code" => "USER_CONFIRM_VERIFICATION" ) );

}
?>