<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_settings']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

  $get = json_decode(file_get_contents_curl('https://api.unisitecloud.ru/updates/get_info_update.php?lnc_key='.$settings['lnc_key'].'&current_version='.$settings['system_version']."&current_patch_version=".$settings["systems_patch_version"]), true);

  echo json_encode(["update"=>$get["update"],"patch"=>$get["patch"]]);

}  
?>