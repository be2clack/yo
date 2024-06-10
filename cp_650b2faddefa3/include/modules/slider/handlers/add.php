<?php
define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_tpl']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$error = array();

$Main = new Main();

if(!$_POST["title1"]){$error[] = "Пожалуйста, укажите основной заголовок";}


if(count($error) == 0){
    $image = $Main->uploadedImage( ["files"=>$_FILES["image"], "path"=>$config["media"]["other"], "prefix_name"=>"slide"] );
    if($image["error"]){
        $error = array_merge($error,$image["error"]);
    }    
}


if (count($error) == 0) {

      insert("INSERT INTO uni_sliders(sliders_image,sliders_link,sliders_title1,sliders_title2,sliders_color_bg,sliders_color_text)VALUES(?,?,?,?,?,?)", array( $image["name"],clear($_POST["link"]),clear($_POST["title1"]),clear($_POST["title2"]),clear($_POST["color"]),clear($_POST["color_text"]) ));        
        
      $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
      echo true;                
    
    } else {
              $_SESSION["CheckMessage"]["warning"] = implode("<br/>", $error);        
           }

}
?>