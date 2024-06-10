<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_city']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$Cache = new Cache();

$error = [];

$id = intval($_POST["id"]);
$name = clear($_POST["name"]);
$alias = translite($_POST["alias"]);
$desc = clear($_POST["desc"]);

$lat = clear($_POST["lat"]);
$lng = clear($_POST["lng"]);

if(!$_POST["alias"]){
	$alias = translite($_POST["name"]);
}

if(!$name){
	$error[] = "Пожалуйста, укажите название";
}

if(!$lat){
	$error[] = "Пожалуйста, укажите широту";
}

if(!$lng){
	$error[] = "Пожалуйста, укажите долготу";
}

if(count($error) == 0){

update("UPDATE uni_city SET city_name=?, city_alias = ?, city_desc=?, city_declination=?, city_lat=?, city_lng=? WHERE city_id=?", array($name,$alias,$desc,clear($_POST["declination"]),$lat,$lng,$id));  
$_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
echo true;
$Cache->update( "cityDefault" );

}else{
	$_SESSION["CheckMessage"]["warning"] = implode("<br/>", $error);
}


}  
?>