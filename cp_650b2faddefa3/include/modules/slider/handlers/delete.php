<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_tpl']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

    $id = (int)$_POST["id"];
    if ($id){
           update("DELETE FROM uni_sliders WHERE sliders_id=?", array($id));                 
           $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
           echo true;
    }

}  
?>