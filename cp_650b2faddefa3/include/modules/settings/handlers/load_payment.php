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

$code = $_POST["code"];

$sql = findOne("uni_payments","code=?", array($code));

$sql->param = decrypt($sql->param);

if($sql->param && $sql->param != "[]"){
  $param = json_decode($sql->param, true);
}else{
  $param = array();
}

if(file_exists($config["basePath"] . "/systems/payment/".$code."/inputs.php")) include $config["basePath"] . "/systems/payment/".$code."/inputs.php";

}  
?>