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

if(empty($_POST["alias"])){
   $alias = translite($_POST["title"]);         
}else{
   $alias = translite($_POST["alias"]);
}

if($alias) $getAlias = findOne("uni_blog_articles","blog_articles_alias=?", array($alias));

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
      }    
  }

}

if (count($error) == 0) {

      insert("INSERT INTO uni_blog_articles(blog_articles_title,blog_articles_text,blog_articles_date_add,blog_articles_visible,blog_articles_desc,blog_articles_image,blog_articles_alias,blog_articles_id_cat)VALUES(?,?,?,?,?,?,?,?)", array( clear($_POST["title"]),$_POST["text"],date("Y-m-d H:i:s"),intval($_POST["visible"]),clear($_POST["desc"]),$image["name"],$alias,intval($_POST["id_cat"]) ));        
        
      $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
      echo true;                
    
    } else {
              $_SESSION["CheckMessage"]["warning"] = implode("<br/>", $error);        
           }

}
?>