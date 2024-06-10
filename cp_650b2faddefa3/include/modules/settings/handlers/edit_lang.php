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

$Main = new Main();

$id = (int)$_POST["id"];

$find = findOne("uni_languages","id=?", array($id));

$status = (int)$_POST["status"];
$name = clear($_POST["name"]);
$alias = clear($_POST["alias"]);
$iso = clear($_POST["iso"]);

if(!$alias){
	$alias = translite($name);
}

if(empty($name)){$error[] = "Пожалуйста, укажите название языка";}
if(empty($iso)){$error[] = "Пожалуйста, укажите ISO языка";}else{

    $dir = $config["basePath"]."/lang/".$iso;
    if(!is_dir($dir)){
       if( !mkdir($dir, $config["create_mode"] ) ){
             $error[] = "Недостаточно прав на запись в директорию lang";
       }
    }

}

if(count($error) == 0){
    $image = $Main->uploadedImage( ["files"=>$_FILES["image"], "path"=>$config["media"]["other"], "prefix_name"=>"lang_icon"] );
    if($image["error"]){
        $error = array_merge($error,$image["error"]);
    }    
}

if($image["name"]) $find->image = $image["name"];

if(count($error)==0){

  update("UPDATE uni_languages SET name=?, code=?, iso=?, status=?, image=? WHERE id=?", [$name,$alias,$iso,$status,$find->image,$id]);

  $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
  echo true;

}else{ $_SESSION["CheckMessage"]["warning"] = implode("<br/>",$error); }

}  
?>