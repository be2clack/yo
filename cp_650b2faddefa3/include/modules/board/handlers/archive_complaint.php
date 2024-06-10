<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_board']) && !(new Admin())->accessAdmin($_SESSION['cp_processing_board']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

 update("UPDATE uni_ads_complain SET ads_complain_status=? WHERE ads_complain_id=?", [2,intval($_POST["id"])]);

 $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
 echo true;

}

?>
