<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_page']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$error = array();
 
if(!$_POST["title"]){$error[] = "Укажите заголовок страницы";}

if(!$_POST["alias"]){ $alias = translite($_POST["title"]); }else{ $alias = translite($_POST["alias"]); }

if( findOne("uni_promo_pages","promo_pages_alias=?", [$alias]) ){
    $error[] = "Страница с таким алиасом уже существует!";
}

if (count($error) == 0) {

  $insert = insert("INSERT INTO uni_promo_pages(promo_pages_title,promo_pages_desc,promo_pages_alias)VALUES(?,?,?)", array(clear($_POST["title"]),clear($_POST["desc"]),$alias));   

  $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
  echo true;                
    
    } else {
              $_SESSION["CheckMessage"]["warning"] = implode("<br/>", $error);        
           }

}
?>