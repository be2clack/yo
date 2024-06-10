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

if($_POST["alias"]){

    foreach ($_POST["alias"] as $id_cat => $nested) {
      foreach ($nested as $id_item => $value) {

          update("delete from uni_ads_filters_alias where ads_filters_alias_id_cat=? and ads_filters_alias_id_filter_item=?", [$id_cat,$id_item]);
      
          if($value["title"]){

              if(!$value["alias"]){
                 $value["alias"] = translite($value["title"]);
              }

              if(!$value["h1"]){
                 $value["h1"] = $value["title"];
              }

              if(!$value["name"]){
                $value["name"] = $value["title"];
              }

              insert("INSERT INTO uni_ads_filters_alias(ads_filters_alias_id_filter_item,ads_filters_alias_title,ads_filters_alias_alias,ads_filters_alias_id_cat,ads_filters_alias_desc,ads_filters_alias_h1,ads_filters_alias_name,ads_filters_alias_text)VALUES(?,?,?,?,?,?,?,?)", array( intval($id_item), clear($value["title"]), translite($value["alias"]), intval($id_cat), clear($value["desc"]), clear($value["h1"]), clear($value["name"]), $value["text"] ) );

          }
         
      }
    }

}

$_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";

}
?>