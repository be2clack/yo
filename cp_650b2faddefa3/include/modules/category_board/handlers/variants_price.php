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

    if($_POST['list_variant_price']){

        if($_POST['list_variant_price']['edit']){
            foreach ($_POST['list_variant_price']['edit'] as $id => $value) {
                if(trim($value) != '') update('update uni_variants_price set variants_price_name=? where variants_price_id=?', [trim($value),$id]);
            }
        }

        if($_POST['list_variant_price']['add']){
            foreach ($_POST['list_variant_price']['add'] as $key => $value) {
                if(trim($value) != '') insert("INSERT INTO uni_variants_price(variants_price_name)VALUES(?)", [trim($value)]); 
            }
        }

    }

    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";                  
    echo true; 

}      
?>