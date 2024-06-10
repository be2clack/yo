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
  $params = $_POST["params"];

  if($settings["main_type_products"] == 'physical'){
     $always = ["title", "phone", "name_user", "city", "region", "text", "category"];
  }else{
     $always = ["title", "phone", "name_user", "electron_product_links", "text", "category"];
  }

  foreach ($always as $key => $value) {
     if(!$params[$value]){
      $_SESSION["CheckMessage"]["error"] = "Пожалуйста, заполните обязательные поля";
      echo json_encode( ["status"=>false] );
      exit;
     }
  }

  update("update uni_ads_import set ads_import_status=?,ads_import_params=? where ads_import_id=?", [1,json_encode($params),$id]);
  
  $_SESSION["CheckMessage"]["warning"] = "Импорт успешно создан и поставлен в очередь";
  echo json_encode( ["status"=>true] ); 

}  
?>