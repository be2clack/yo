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
  
  $id = (int)$_POST["id"];
  $action = (int)$_POST["action"];
  
  if($action == 1){
     update("delete from uni_ads_import where ads_import_id=?", [$id]);
     $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
  }else{
  	 update("update uni_ads_import set ads_import_status=? where ads_import_id=?", [3,$id]);
  	 $_SESSION["CheckMessage"]["warning"] = "Импорт поставлен в очередь на удаление";
  }

  echo true;

}  
?>