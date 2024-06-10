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
 $Cache = new Cache();

 $getCategories = $Blog->getCategories();

 $id = (int)$_POST["id"];

 $nested_cat_ids = idsBuildJoin($Blog->idsBuild($id,$getCategories),$id);

 if($nested_cat_ids){
 	foreach (explode(",", $nested_cat_ids) as $key => $value) {
 		
		 update("DELETE FROM uni_blog_category WHERE blog_category_id = ?", array($value));

 	}
 }

 $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";                  
 echo true;

 $Cache->update( "uni_blog_category" );

}   

?>