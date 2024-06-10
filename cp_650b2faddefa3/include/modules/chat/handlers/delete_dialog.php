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

$idHash = clear($_POST['hash']);
$idUser = (int)$_POST['id'];

if(!$idUser || !$idHash) exit;

update('delete from uni_chat_messages where chat_messages_id_hash=?', [$idHash]);
update('delete from uni_chat_users where chat_users_id_hash=?', [$idHash]);

$_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
echo true;

?>
 

