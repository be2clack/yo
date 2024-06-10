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

  $get = file_get_contents_curl('https://api.unisitecloud.ru/updates/init_payment_update.php?lnc_key='.$settings['lnc_key'].'&current_version='.$settings['system_version'].'&install_version='.clear($_POST['version']));

  if($get){

     $data = json_decode($get, true);

     if($data){

       echo json_encode(["status"=>true, "link"=>$data["link_payment"], "payment"=>(int)$data["payment"]]);

    }else{
       echo json_encode(["status"=>false, "answer"=>"Ошибка получения данных"]);
    }

  }else{
     echo json_encode(["status"=>false, "answer"=>"Ошибка получения данных"]);
  }

}  
?>