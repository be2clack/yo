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

$text = clear( urldecode($_POST["text"]) );
$attach = $_POST["attach"] ? array_slice($_POST["attach"],0, 10) : [];

if(!$idHash || !$idUser) exit;

$Profile->sendChat( array( "support" => 1, "id_hash" => $idHash, "text" => $text, "user_from" => 0, "user_to" => $idUser, "attach" => $attach, "firebase" => true ) );

?>
 

