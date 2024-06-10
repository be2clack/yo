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

    $measureNew = [];
    $measureDefault = ['hour'=>'час','day'=>'день','daynight'=>'сутки','week'=>'неделя','month'=>'месяц'];

    if($_POST['list_measure_price']){
        foreach ($_POST['list_measure_price'] as $key => $value) {
            if(trim($value)) $measureNew[ translite($value) ] = $value;
        }
    }

    $output = array_merge($measureDefault, $measureNew);

    if($output) update("UPDATE uni_settings SET value=? WHERE name=?", array(json_encode($output, JSON_UNESCAPED_UNICODE),'measures_price'));

    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";                  
    echo true; 

}      
?>