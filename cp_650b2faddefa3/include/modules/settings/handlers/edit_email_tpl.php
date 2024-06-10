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

$id = (int)$_POST["id"];
$email_subject = $_POST["email_subject"];
$email_text = $_POST["email_text"];

$error = array();

if(empty($email_subject)){ $error[] = "Пожалуйста, укажите тему письма!"; }
if(empty($email_text)){ $error[] = "Пожалуйста, укажите текст письма!"; }

if(count($error) == 0){
    
    update("UPDATE uni_email_message SET subject=?, text=? WHERE id=?", array($email_subject,urlencode($email_text),$id));

    echo true;
    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
}else{
    $_SESSION["CheckMessage"]["error"] = implode("<br/>", $error);
}


}  
?>