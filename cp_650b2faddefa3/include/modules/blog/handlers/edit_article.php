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

$id = (int)$_POST["id"];

$getBlog = findOne("uni_blog_articles","blog_articles_id=?", array($id));

if(empty($_POST["alias"])){
   $alias = translite($_POST["title"]);         
}else{
   $alias = translite($_POST["alias"]);
}

if($alias) $getAlias = findOne("uni_blog_articles","blog_articles_alias=? and blog_articles_id!=?", array($alias,$id));

if($getAlias){
    $error[] = "Публикация с указанным URL адресом уже существует!";
}else{
    if(!$_POST["title"]){$error[] = "Пожалуйста, укажите заголовок публикации";}
    if(!intval($_POST["id_cat"])){$error[] = "Пожалуйста, выберите категорию";}
}

if( !$_POST["image_delete"] ){

    if(count($error) == 0){
        $image = $Main->uploadedImage( ["files"=>$_FILES["image"], "path"=>$config["media"]["big_image_blog"], "prefix_name"=>"article_blog"] );
        if($image["error"]){
            $error = array_merge($error,$image["error"]);
        }else{
            if($image["name"]) $getBlog["blog_articles_image"] = $image["name"];
        }  
    }

}else{
   $getBlog["blog_articles_image"] = "";
}

if (count($error) == 0) {

      update("UPDATE uni_blog_articles SET blog_articles_title='".clear($_POST["title"])."',blog_articles_alias='$alias',blog_articles_text='".$_POST["text"]."',blog_articles_desc='".clear($_POST["desc"])."',blog_articles_visible='".intval($_POST["visible"])."',blog_articles_id_cat='".intval($_POST["id_cat"])."',blog_articles_image='".$getBlog["blog_articles_image"]."' WHERE blog_articles_id='$id'");        
        
      $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
      echo true;                
    
    } else {
              $_SESSION["CheckMessage"]["warning"] = implode("<br/>", $error);        
           }

}
?>