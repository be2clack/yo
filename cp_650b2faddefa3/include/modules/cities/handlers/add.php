<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_city']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$Cache = new Cache();
$Main = new Main();

$status = intval($_POST["status"]);
$country = clear($_POST["country"]);
$desc = clear($_POST["desc"]);
$code_phone = clear($_POST["code_phone"]);
$format_phone = clear($_POST["format_phone"]);
$alias = translite($_POST["alias"]);

$lat = str_replace(array(',','°',"\"","'"),array('.','','',''),$_POST["lat"]);
$lng = str_replace(array(',','°',"\"","'"),array('.','','',''),$_POST["lng"]);

if(!$code_phone){
   $format_phone = '';
}

if(!$alias){
	$alias = translite($country);
}

$error = array();
 
if(empty($country)){$error[] = 'Укажите название страны';}
if(empty($alias)){$error[] = 'Укажите алиас';}
if(empty($lat)){$error[] = 'Укажите широту';}
if(empty($lng)){$error[] = 'Укажите долготу';}

if( !$_POST["image_delete"] ){

  if(count($error) == 0){
      $image = $Main->uploadedImage(["files"=>$_FILES["image"], "path"=>$config["media"]["other"], "prefix_name"=>"country"]);
      if($image["error"]){
          $error = array_merge($error,$image["error"]);
      }    
  }

}

if (count($error) == 0) {

   insert("INSERT INTO uni_country(country_name, country_status, country_alias, country_desc, country_lat, country_lng, country_format_phone, country_code_phone, country_image, country_declination)VALUES(?,?,?,?,?,?,?,?,?,?)", array($country,$status,$alias,$desc,$lat,$lng,$format_phone,$code_phone,$image["name"],clear($_POST["declination"])));             
   $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";
   echo true;            

   $Cache->update( "cityDefault" );
     
} else { $_SESSION["CheckMessage"]["warning"] = implode("<br/>", $error); }
   
}    
?>