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

$idUser = (int)$_POST['id'];

if(!$idUser) exit;

$getLocked = findOne("uni_clients_blacklist", "clients_blacklist_user_id = ? and clients_blacklist_user_id_locked = ?", array(0,$idUser));

if($getLocked){
  update("DELETE FROM uni_clients_blacklist WHERE clients_blacklist_id=?", array($getLocked->clients_blacklist_id));
}else{
  insert("INSERT INTO uni_clients_blacklist(clients_blacklist_user_id,clients_blacklist_user_id_locked)VALUES(?,?)",[0,$idUser]);
}

$_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
echo true;

?>
 

