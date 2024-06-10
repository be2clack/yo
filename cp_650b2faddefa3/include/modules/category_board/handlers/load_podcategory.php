<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

include("../fn.php");

if(isAjax() == true){

  $id_cat = (int)$_POST["id_cat"];  
  $level = 0;

  $CategoryBoard = new CategoryBoard();

  $getCategories = $CategoryBoard->getCategories();

  $reverseIds = $CategoryBoard->reverseMainIds($getCategories,$id_cat);
  
  if($reverseIds){
    foreach (explode(",", $reverseIds) as $key => $value) {
      $level += 15;
    }
  }
  echo outCategory($id_cat, $level); 
           
}
?>