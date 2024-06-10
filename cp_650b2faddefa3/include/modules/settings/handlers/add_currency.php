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

$error = array();

$name = clear($_POST["name"]);
$sign = clear($_POST["sign"]);
$code = clear($_POST["code"]);
$price = round($_POST["price"],2);

if(empty($name)){$error[] = "Укажите название валюты";}
if(empty($sign)){$error[] = "Укажите знак валюты";}
if(empty($code)){$error[] = "Укажите код валюты";}

if(count($error)==0){

insert("INSERT INTO uni_currency(name,sign,code)VALUES(?,?,?)", array($name,$sign,$code));      

$_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
 echo true;
}else{ $_SESSION["CheckMessage"]["error"] = implode("<br/>",$error); }

}  
?>