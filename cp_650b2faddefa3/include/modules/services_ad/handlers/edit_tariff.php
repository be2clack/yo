<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_board']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$Main = new Main();

$id = (int)$_POST["id"];
$status = (int)$_POST["status"];

$name = clear($_POST["name"]);
$count_day = (int)$_POST["count_day"];
$onetime = (int)$_POST["onetime"];
$desc = clear($_POST["desc"]);
$services = $_POST["services"] ? json_encode($_POST["services"]) : [];

$price = round($_POST["price"],2);
$new_price = round($_POST["new_price"],2);
$bonus = round($_POST["bonus"],2);

$error = array();
 
if(empty($name)){$error[] = "Пожалуйста, укажите название!";}
if(empty($desc)){$error[] = "Пожалуйста, укажите краткое описание!";}

if($new_price >= $price && $new_price){
   $error[] = "Новая цена не может равняться или быть больше старой цены!";
}

if (count($error) == 0) {
    
     update("UPDATE uni_services_tariffs SET services_tariffs_name=?,services_tariffs_price=?,services_tariffs_days=?,services_tariffs_desc=?,services_tariffs_status=?,services_tariffs_services=?,services_tariffs_new_price=?,services_tariffs_bonus=?,services_tariffs_onetime=? WHERE services_tariffs_id=?", [$name,$price,$count_day,$desc,$status,$services,$new_price,$bonus,$onetime,$id]);          
     $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
     echo true;
               
    
} else {
           $_SESSION["CheckMessage"]["warning"] = implode("<br>", $error);        
       }


}              
?>