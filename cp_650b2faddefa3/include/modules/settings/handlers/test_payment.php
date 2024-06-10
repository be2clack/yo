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

$Profile = new Profile();

if(isAjax() == true){

   $orderId = generateOrderId();

   $answer = $Profile->payMethod( $_POST["payment"], array( "amount" => 100, "name" => $settings["site_name"], "email" => $settings["email_alert"], "phone" => $settings["phone_alert"], "id_order" => $orderId , "id_user" => 0, "action" => "test", "title" => "Тестовая оплата №".$orderId ) ); 

   echo json_encode($answer);

}     
?>
