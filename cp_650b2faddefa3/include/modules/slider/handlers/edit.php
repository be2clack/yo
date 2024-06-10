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

$data = findOne( "uni_sliders", "sliders_id=?", [ intval($_POST["id"]) ] );

if(!$_POST["title1"]){$error[] = "Пожалуйста, укажите заголовок";}

if( !$_POST["image_delete"] ){

  if(count($error) == 0){
      $image = $Main->uploadedImage( ["files"=>$_FILES["image"], "path"=>$config["media"]["other"], "prefix_name"=>"slide"] );
      if($image["error"]){
          $error = array_merge($error,$image["error"]);
      }else{
          if($image["name"]) $data["sliders_image"] = $image["name"];
      }    
  }

}else{

  $data["sliders_image"] = "";

}

if($image["name"]){
   $data["sliders_image"] = $image["name"];
}

if (count($error) == 0) {

      update("UPDATE uni_sliders SET sliders_image=?,sliders_link=?,sliders_title1=?,sliders_title2=?,sliders_color_bg=?,sliders_color_text=? WHERE sliders_id=?", array( $data["sliders_image"],clear($_POST["link"]),clear($_POST["title1"]),clear($_POST["title2"]),clear($_POST["color"]),clear($_POST["color_text"]),intval($_POST["id"]) ));        
        
      $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
      echo true;                
    
    } else {
              $_SESSION["CheckMessage"]["warning"] = implode("<br/>", $error);        
           }

}
?>