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

 $Main = new Main();

 $id = (int)$_POST["id"];
 $visible = (int)$_POST["visible"];
 $recommended = (int)$_POST["recommended"];

 $title = clear($_POST["title"]);
 $variant_count_day = (int)$_POST["variant_count_day"];

 if($variant_count_day == 1){
    $price = round($_POST["price_variant1"],2);
    $new_price = round($_POST["new_price_variant1"],2);
    $count_day = intval($_POST["count_day"]);
 }else{
    $price = round($_POST["price_variant2"],2);
    $new_price = round($_POST["new_price_variant2"],2);
    $count_day = 1;
 }
  
 $text = clear($_POST["desc"]);

 $error = array();
 
if(empty($title)){$error[] = "Укажите название услуги!";}
if(empty($count_day)){$error[] = "Укажите срок действия услуги!";}
if(empty($price)){$error[] = "Укажите стоимость услуги!";}

if(count($error) == 0){
    $image = $Main->uploadedImage( ["files"=>$_FILES["image"], "path"=>$config["media"]["other"], "prefix_name"=>"services"] );
    if($image["error"]){
        $error = array_merge($error,$image["error"]);
    }    
}

if($image["name"]) $image = ",services_ads_image='{$image["name"]}'";

if (count($error) == 0) {
    
    $update = update("UPDATE uni_services_ads SET services_ads_name='$title',services_ads_price='$price',services_ads_new_price='$new_price',services_ads_count_day='$count_day',services_ads_text='$text',services_ads_variant='$variant_count_day',services_ads_visible='$visible',services_ads_recommended='$recommended' $image WHERE services_ads_id='$id'");        
            
     $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
     echo true;
               
    
} else {
           $_SESSION["CheckMessage"]["warning"] = implode("<br>", $error);        
       }


}              
?>