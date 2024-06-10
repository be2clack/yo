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

$id_cat = (int)$_POST['id_cat'];
$keywords = $_POST['keywords'];

if(count($keywords)){

  foreach ($keywords as $action => $nested) {
      foreach ($nested as $id => $value) {

        if($value['text']){
          $params = [];

          if($value['macros']){
             $split_comma = explode(',',str_replace(['{','}',''], '', $value['macros']));
             foreach ($split_comma as $comma_value) {
                $split_colon = explode(':',$comma_value);
                if($split_colon[0] && $split_colon[1]){
                   $getFilter = findOne('uni_ads_filters', 'ads_filters_id=?', [$split_colon[0]]);
                   $getFilterItem = findOne('uni_ads_filters_items', 'ads_filters_items_id_filter=? and ads_filters_items_alias=?', [$getFilter['ads_filters_id'],$split_colon[1]]);
                   $params[] = 'filter['.$getFilter['ads_filters_id'].'][]='.$getFilterItem['ads_filters_items_id'];
                }
             }
          }

          if($action == 'edit'){
              update("UPDATE uni_ads_keywords SET ads_keywords_tag=?,ads_keywords_params=?,ads_keywords_macros=? WHERE ads_keywords_id=?", array($value['text'],implode('&',$params),$value['macros'],$id));
          }else{
              insert("INSERT INTO uni_ads_keywords(ads_keywords_tag,ads_keywords_params,ads_keywords_id_cat,ads_keywords_macros)VALUES(?,?,?,?)", array($value['text'],implode('&',$params),$id_cat,$value['macros']));
          }
        }

      }
  }

}

$_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!"; 

}
?>