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

$Blog = new Blog();
$Main = new Main();
$Cache = new Cache();

$getCategories = $Blog->getCategories();

$id = (int)$_POST["id"];
$error = array();

$getCategory = findOne("uni_blog_category","blog_category_id=?", array($id));

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

if($_POST["id_cat"] == $id){
    $parent = "";
}else{
    $idsBuild = trim($Blog->idsBuild($id,$getCategories), ",");
    if(!$idsBuild){
         $parent = ",blog_category_id_parent='".intval($_POST["id_cat"])."'";
      }else{
         $explode = explode(",",$idsBuild);
         if(!in_array($_POST["id_cat"], $explode)){
            $parent = ",blog_category_id_parent='".intval($_POST["id_cat"])."'";
         }
      }
}     

if( !$_POST["image_delete"] ){

    if(count($error) == 0){
        $image = $Main->uploadedImage( ["files"=>$_FILES["image"], "path"=>$config["media"]["big_image_blog"], "prefix_name"=>"category_blog"] );
        if($image["error"]){
            $error = array_merge($error,$image["error"]);
        }else{
            if($image["name"]) $getCategory["blog_category_image"] = $image["name"];
        }   
    }

}else{
    $getCategory["blog_category_image"] = "";
}

if (count($error) == 0) {
    
  update("UPDATE uni_blog_category SET blog_category_name='".clear($_POST["name"])."',blog_category_title='".clear($_POST["title"])."',blog_category_alias='$alias',blog_category_text='".urlencode($_POST["text"])."',blog_category_desc='".clear($_POST["desc"])."',blog_category_visible='".intval($_POST["visible"])."',blog_category_image='".$getCategory["blog_category_image"]."',blog_category_h1='".clear($_POST["h1"])."' $parent WHERE blog_category_id='$id'"); 

            
  $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
  echo true;

  $Cache->update( "uni_blog_category" );
            
                
  } else {
           $_SESSION["CheckMessage"]["warning"] = implode("<br/>", $error);        
         }

}
?>