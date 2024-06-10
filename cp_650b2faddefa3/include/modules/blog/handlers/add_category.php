<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_blog']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){
 
$error = array();

$Main = new Main();
$Blog = new Blog();
$Cache = new Cache();
 
 
if(!$_POST["name"]){$error[] = "Пожалуйста, укажите название категории";}

if(empty($_POST["alias"])){
     $alias = translite($_POST["name"]);         
}else{
     $alias = translite($_POST["alias"]);
}

if(!$_POST["title"]){
    $_POST["title"] = $_POST["name"];
}

if(!$_POST["h1"]){
    $_POST["h1"] = $_POST["title"];
}

if( !$_POST["image_delete"] ){

  if(count($error) == 0){
      $image = $Main->uploadedImage( ["files"=>$_FILES["image"], "path"=>$config["media"]["big_image_blog"], "prefix_name"=>"category_blog"] );
      if($image["error"]){
          $error = array_merge($error,$image["error"]);
      }    
  }

}

if (count($error) == 0) {

      insert("INSERT INTO uni_blog_category(blog_category_name,blog_category_visible,blog_category_title,blog_category_desc,blog_category_id_parent,blog_category_image,blog_category_text,blog_category_alias,blog_category_h1)VALUES(?,?,?,?,?,?,?,?,?)", array( clear($_POST["name"]),intval($_POST["visible"]),clear($_POST["title"]),clear($_POST["desc"]),intval($_POST["id_cat"]),$image["name"],urlencode($_POST["text"]),$alias,clear($_POST["h1"]) ));        
        
      $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
      echo true;   

      $Cache->update( "uni_blog_category" );            
    
    } else {
              $_SESSION["CheckMessage"]["warning"] = implode("<br/>", $error);        
           }

}
?>