<?php
define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_tpl']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$error = array();

$Main = new Main();

if( !intval($_POST["media_slider_count_show"]) ){
	$_POST["media_slider_count_show"] = 2;
}elseif( intval($_POST["media_slider_count_show"]) > 10 ){
	$_POST["media_slider_count_show"] = 10;
}

update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["media_slider_autoplay"]),'media_slider_autoplay'));      
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["media_slider_count_show"]),'media_slider_count_show'));      
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["media_slider_height"]),'media_slider_height'));      
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["media_slider_arrows"]),'media_slider_arrows'));      

$_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
echo true;                
    

}
?>