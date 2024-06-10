<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_manager']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

	$id = (int)$_POST["id"];

	$getFile = findOne("uni_filemanager", "filemanager_id=?", [ $id ]);

    if( $getFile["filemanager_dir"] == 1 ){
        @unlink($config["basePath"] . "/" . $getFile["filemanager_name"]);
    }else{
        @unlink($config["basePath"] . "/" . $config["media"]["manager"] . "/" . $getFile["filemanager_name"] );
    }

    update("delete from uni_filemanager where filemanager_id=?", [$id]);

	$_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";

	echo true;

}
?>