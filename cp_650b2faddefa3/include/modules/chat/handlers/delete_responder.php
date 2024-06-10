<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_chat']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

$Profile = new Profile();

$id = (int)$_POST['id'];

if(!$id) exit;

update('delete from uni_chat_responders where chat_responders_id=?', [$id]);

$_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
echo true;

?>