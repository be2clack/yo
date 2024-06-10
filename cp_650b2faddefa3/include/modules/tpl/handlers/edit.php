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

$arrayFiles = getTplFiles();

$area_tpl = trim($_POST["area_tpl"]);
$file = $_POST["file"];
$type = $_POST["type"];
   
if( $type != "css" ){
  if( $settings["demo_view"] || $settings["demo_installment"] ){
    $_SESSION["CheckMessage"]["warning"] = "Шаблонизатор ограничен!";
    exit;   
  }
}

if( $arrayFiles[ $file ] && $type ){
 
   if($type == "file"){
      $dir = $config["template_path"]."/".str_replace('/', '', $file);
   }elseif($type == "css"){
      $dir = $config["template_path"]."/css/".str_replace('/', '', $file);
   }elseif($type == "js"){
      $dir = $config["template_path"]."/js/".str_replace('/', '', $file);
   }
     
  if(file_exists($dir)){  
    $fp = @fopen($dir, "w");
    $write = @fwrite($fp, $area_tpl);
    @fclose($fp);
    if($write){
      $_SESSION["CheckMessage"]["success"] = 'Файл "'.$file.'" успешно изменен!';
    }else{
      $_SESSION["CheckMessage"]["error"] = "Недостаточно прав на запись. Измените права на 777 для папки templates";
    }

    echo true;
  }else{
    $_SESSION["CheckMessage"]["error"] = "Файл не найден!";
  } 

}else{
  $_SESSION["CheckMessage"]["warning"] = "Выберите шаблон для редактирования";
}

}
?>