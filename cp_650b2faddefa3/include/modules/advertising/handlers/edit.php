<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_banner']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

$pos_category_display_all = ["result", "catalog_sidebar", "catalog_top", "catalog_bottom", "ad_view_top", "ad_view_sidebar", "ad_view_bottom", "blog_sidebar", "blog_top", "blog_bottom", "blog_view_sidebar", "blog_view_top", "blog_view_bottom"];
$pos_category_display_board = ["result", "catalog_sidebar", "catalog_top", "catalog_bottom", "ad_view_top", "ad_view_sidebar", "ad_view_bottom"];
$pos_category_display_blog = ["blog_sidebar", "blog_top", "blog_bottom", "blog_view_sidebar", "blog_view_top", "blog_view_bottom"];


if(isAjax() == true){

 $Main = new Main();

 $id = (int)$_POST["id"];

 $find = findOne("uni_advertising", "advertising_id=?", array($id));

 $title = clear($_POST["title"]);
 $visible = (int)$_POST["visible"];
 $var_out = (int)$_POST["var_out"];
 
 $banner_position = $_POST["banner_position"];
 $out_region = (int)$_POST["out_region"];

 $type_banner = (int)$_POST["type_banner"];
 $out_podcat = (int)$_POST["out_podcat"];
 $index_out = abs($_POST["index_out"]);
 
 $link_site = urlencode($_POST["link"]);
 $code_script = urlencode($_POST["code"]);

 if($_POST["date_start"]) $date_start = date("Y-m-d H:i:s",strtotime($_POST["date_start"])); else $date_start = date("Y-m-d H:i:s");
 if($_POST["date_end"]) $date_end = date("Y-m-d H:i:s",strtotime($_POST["date_end"])); else $date_end = "0000-00-00 00:00:00";
 
 $error = array();

 if(!$title){$error[] = "Пожалуйста, укажите название";}
 if(!$banner_position){$error[] = "Пожалуйста, выберите позицию баннера";}
 if($banner_position == "stretching"){
   if(!$_POST["pages"]){$error[] = "Пожалуйста, выберите страницы показа";}
 }else{
   $_POST["pages"] = [];
 }

 if($type_banner == 1){

   $code_script = "";

   if(!$link_site){
      $error[] = "Пожалуйста, укажите ссылку перенаправления";
   }

 }elseif($type_banner == 2){
  
    $link_site = "";
    $find["advertising_image"] = "";

    if(!$code_script){
       $error[] = "Пожалуйста, укажите код скрипта/баннера";
    }

 }

 if(count($error) == 0){
    $image = $Main->uploadedImage( ["files"=>$_FILES["image"], "path"=>$config["media"]["banners"], "prefix_name"=>"banner"] );
    if($image["error"]){
        $error = array_merge($error,$image["error"]);
    }    
 }

 if($image["name"]) $find["advertising_image"] = $image["name"];

if (count($error) == 0) {

   if(is_array($_POST["geo"])){
     $geo = json_encode($_POST["geo"]);
   }else{
     $geo = "";
   }

   if($_POST["type_cat"] == 1){

       $ids_cat = "";

   }elseif($_POST["type_cat"] == 2){

      if( in_array($banner_position, $pos_category_display_board) ){

        $ids_cat = $_POST["ids_cat_board"] ? implode(",",$_POST["ids_cat_board"]) : "";

      }elseif( in_array($banner_position, $pos_category_display_blog) ){

        $ids_cat = $_POST["ids_cat_blog"] ? implode(",",$_POST["ids_cat_blog"]) : "";

      }else{

        $ids_cat = "";

      }

   }
    
    update("update uni_advertising set advertising_title=?,advertising_image=?,advertising_visible=?,advertising_ids_cat=?,advertising_type_banner=?,advertising_date_start=?,advertising_date_end=?,advertising_geo=?,advertising_banner_position=?,advertising_code_script=?,advertising_index_out=?,advertising_out_podcat=?,advertising_link_site=?,advertising_pages=?,advertising_var_out=? where advertising_id=?", array( $title,$find["advertising_image"],$visible,$ids_cat,$type_banner,$date_start,$date_end,$geo,$banner_position,$code_script,$index_out,$out_podcat,$link_site,implode(",", $_POST["pages"]),$var_out,$id ) );

    $_SESSION["CheckMessage"]["success"] = "Данные успешно сохранены!";
    echo true;
             
        
} else {
          $_SESSION["CheckMessage"]["warning"] = implode("<br>", $error);        
       }

}
?>
