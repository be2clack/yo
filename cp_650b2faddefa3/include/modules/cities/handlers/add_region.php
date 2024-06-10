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

$country_id = intval($_POST["country_id"]);
$name = clear($_POST["name"]);

 $error = array();
 
 if(empty($country_id)){$error[] = 'Выберите страну';}
 if(empty($name)){$error[] = 'Укажите название города';}

    if (count($error) == 0) {

      $insert = insert("INSERT INTO uni_region(region_name, country_id, region_alias)VALUES(?,?,?)", array($name,$country_id,translite($name)));        
            
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