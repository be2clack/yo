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
  $name = $_POST["name"];

  $error = array();
 
  if(!$city_id){$error[] = "Пожалуйста, выберите город";}
  if(!$name){$error[] = "Пожалуйста, укажите название района";}

  if (count($error) == 0) {

    insert("INSERT INTO uni_city_area(city_area_id_city,city_area_name)VALUES(?,?)", array($city_id,$name));        
              
    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
    echo true;
    $Cache->update( "cityDefault" );
                       
    } else {
             $_SESSION["CheckMessage"]["error"] = implode("<br/>", $error);        
           }

}
?>