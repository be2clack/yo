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

$city_id = intval($_POST["city_id"]);
$id = intval($_POST["id"]);
$name = $_POST["name"];

 $error = array();
 
 if(empty($id)){$error[] = 'Пожалуйста, выберите ветку';}
 if(empty($name)){$error[] = 'Пожалуйста, укажите название станции';}

    if (count($error) == 0) {

      $insert = insert("INSERT INTO uni_metro(name, parent_id, city_id)VALUES(?,?,?)", array($name,$id,$city_id));        
                
          if($insert){
                $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
                echo true;
                $Cache->update( "cityDefault" );
          }                 
        
      } else {
               $_SESSION["CheckMessage"]["warning"] = implode("<br/>", $error);        
             }

}
?>