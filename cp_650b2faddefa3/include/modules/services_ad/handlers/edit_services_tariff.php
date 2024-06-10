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

$_POST['service'] = $_POST['service'] ? $_POST['service'] : [];

if(count($_POST['service'])){
    foreach ($_POST['service'] as $id => $value) {
        if(trim($value['name'])){
            update("UPDATE uni_services_tariffs_checklist SET services_tariffs_checklist_name=?,services_tariffs_checklist_desc=?  WHERE services_tariffs_checklist_id=?", [$value['name'],$value['desc'],$id]); 
        }
    }
}

$_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
echo true;

}              
?>