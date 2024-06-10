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

$Cache = new Cache();

$name = clear($_POST["name"]);
$type_filter = clear($_POST["type_filter"]);  
$visible = (int)$_POST["visible"];    
$always = (int)$_POST["always"];  
$id_parent = (int)$_POST["id_parent"];  

$error = array();
 
if(empty($name)){ $error[] = "Укажите название фильтра!"; }

if (count($error) == 0) {

   	$insert = insert("INSERT INTO uni_ads_filters(ads_filters_name,ads_filters_alias,ads_filters_visible,ads_filters_type,ads_filters_id_parent,ads_filters_required)VALUES(?,?,?,?,?,?)", array($name,translite($name),$visible,$type_filter,$id_parent,intval($_POST["required"]))); 

   	$getFilterCat = getAll("select * from uni_ads_filters_category where ads_filters_category_id_filter=?", [ $id_parent ]);

    if( count($getFilterCat) ){

       foreach ($getFilterCat as $key => $value) {
       	  insert("INSERT INTO uni_ads_filters_category(ads_filters_category_id_cat,ads_filters_category_id_filter)VALUES(?,?)", array( $value["ads_filters_category_id_cat"], $insert ) );
       }
       
    }

    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
    echo true;

    $Cache->update( "uni_ads_filters" );

} else {

    $_SESSION["CheckMessage"]["warning"] = implode("<br>", $error); 

}

}
?>