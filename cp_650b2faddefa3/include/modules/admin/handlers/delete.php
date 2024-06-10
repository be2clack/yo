<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_admin']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

   $id = (int)$_POST["id"];

   $get = findOne("uni_admin", "id = ?",array($id));
    if($get->main_admin == 0){
       update("DELETE FROM uni_admin WHERE id=?", array($id));
       $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
       echo true;            
    }else{
       $_SESSION["CheckMessage"]["warning"] = "Вы не можете удалить главного администратора!"; 
    }
    
}
?>