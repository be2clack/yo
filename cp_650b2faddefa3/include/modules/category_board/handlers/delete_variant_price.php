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

     update('delete from uni_variants_price where variants_price_id=?', [ intval($_POST['id']) ]);

     $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";                  
     echo true; 

}      
?>