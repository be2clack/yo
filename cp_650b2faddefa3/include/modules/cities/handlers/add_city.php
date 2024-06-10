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

$region_id = intval($_POST["region_id"]);
$country_id = intval($_POST["country_id"]);
$name = clear($_POST["name"]);
$alias = translite($_POST["alias"]);
$desc = clear($_POST["desc"]);

$lat = clear($_POST["lat"]);
$lng = clear($_POST["lng"]);

if(!$_POST["alias"]){
  $alias = translite($_POST["name"]);
}

$error = array();
 
if(empty($region_id)){$error[] = "Выберите регион";}
if(empty($country_id)){$error[] = "Выберите страну";}
if(empty($name)){$error[] = 'Пожалуйста, укажите название';}

if(!$lat){
  $error[] = "Пожалуйста, укажите широту";
}

if(!$lng){
  $error[] = "Пожалуйста, укажите долготу";
}

if (count($error) == 0) {

    $insert = insert("INSERT INTO uni_city(city_name, country_id, region_id, city_alias, city_desc, city_declination, city_lat, city_lng)VALUES(?,?,?,?,?,?,?,?)", array($name,$country_id,$region_id,translite($name),$desc,clear($_POST["declination"]),$lat,$lng));        
            
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