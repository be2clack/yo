<?php

header('Content-Type: image/jpeg');

define('unisitecms', true);
session_start();

$config = require "../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_settings']) ){
   exit;
}

$Watermark = new Watermark();
$Watermark->create( $config["basePath"] . "/" . $config["folder_admin"] . "/files/images/watermark_preview.jpg" );

?>