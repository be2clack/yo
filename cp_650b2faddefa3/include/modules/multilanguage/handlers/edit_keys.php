<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_multilang']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

$content_type = $_POST['content_type'];

if(isAjax() == true){

    if(isset($_POST["keys"])){ 
        if(!$content_type || $content_type == "site"){
            $ULang = new ULang();
            foreach ($_POST["keys"] as $iso => $array) {
                if( $ULang->edit($array,$iso) ){
                    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
                }else{
                    $_SESSION["CheckMessage"]["warning"] = "Недостаточно прав на запись для файла /lang/{$iso}/main.php";
                }
            }
        }elseif($content_type == "app"){
            $ULang = new ULang(false);
            foreach ($_POST["keys"] as $iso => $array) {
                if( $ULang->editApp($array,$iso,$content_type) ){
                    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
                }else{
                    $_SESSION["CheckMessage"]["warning"] = "Недостаточно прав на запись для файла /lang/{$iso}/app.php";
                }
            }            
        }
    } 

    echo true;   

}
?>