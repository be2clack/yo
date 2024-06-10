<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_blog']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

  $id = intval($_POST["id"]);
  $status = intval($_POST["status"]);

  update("update uni_blog_articles set blog_articles_visible=? where blog_articles_id=?", array($status,$id));

  $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
  echo true;

}
?>