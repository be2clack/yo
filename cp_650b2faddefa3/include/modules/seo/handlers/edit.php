<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_seo']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$iso = $_POST["lang"];

update("delete from uni_seo where lang_iso=?", [$iso]);

if(isset($_POST["form"])){
	
	foreach ($_POST["form"] as $page => $value) {

		insert("INSERT INTO uni_seo(meta_title,meta_desc,text,h1,lang_iso,page)VALUES(?,?,?,?,?,?)", [$value["meta_title"],$value["meta_desc"],$value["text"],$value["h1"],$iso,$page]);
	
	}

}

$_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";   

echo true;              

}
?>