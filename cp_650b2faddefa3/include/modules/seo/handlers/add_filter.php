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

$error = array();
 
if(!$_POST["name"]){$error[] = "Пожалуйста, укажите название";}
if(!$_POST["title"]){$error[] = "Пожалуйста, укажите заголовок";}
if(!$_POST["url"]){$error[] = "Пожалуйста, укажите параметры URL";}

if(!$_POST["alias"]){ $alias = translite($_POST["name"]); }else{ $alias = translite($_POST["alias"]); }

if(!$_POST["alias_category"]){ $error[] = "Пожалуйста, укажите алиас категории"; }

if(!$_POST["h1"]){
   $_POST["h1"] = $_POST["title"];
}

if (count($error) == 0) {

  insert("INSERT INTO uni_seo_filters(seo_filters_alias,seo_filters_name,seo_filters_desc,seo_filters_text,seo_filters_params,seo_filters_alias_category,seo_filters_alias_geo,seo_filters_h1,seo_filters_title)VALUES(?,?,?,?,?,?,?,?,?)", array($alias,clear($_POST["name"]),clear($_POST["desc"]),$_POST["text"],$_POST["url"],$_POST["alias_category"],$_POST["alias_geo"],clear($_POST["h1"]),clear($_POST["title"])));   

  $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
  echo true;
                       
    } else {
              $_SESSION["CheckMessage"]["error"] = implode("<br/>", $error);        
           }

}
?>