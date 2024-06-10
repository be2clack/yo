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
 
if(!$_POST["name"]){$error[] = "Укажите название страницы";}

if(!$_POST["alias"]){ $alias = translite($_POST["name"]); }else{ $alias = translite($_POST["alias"]); }

if (count($error) == 0) {

  $insert = insert("INSERT INTO uni_pages(name,title,alias,text,seo_text,visible)VALUES(?,?,?,?,?,?)", array(clear($_POST["name"]),clear($_POST["title"]),$alias,$_POST["text"],$_POST["desc"],intval($_POST["visible"])));   

      if($insert){
        $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
        echo true;
      }                  
    
    } else {
              $_SESSION["CheckMessage"]["warning"] = implode("<br/>", $error);        
           }

}
?>