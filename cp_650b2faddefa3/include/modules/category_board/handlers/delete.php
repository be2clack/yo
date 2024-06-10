<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_board']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){
 
 $CategoryBoard = new CategoryBoard();
 $Filters = new Filters();
 $Cache = new Cache();
 $Ads = new Ads();

 $getCategories = $CategoryBoard->getCategories();

 $id = (int)$_POST["id"];

 $nested_cat_ids = idsBuildJoin($CategoryBoard->idsBuild($id,$getCategories),$id);

 if($nested_cat_ids){
 	foreach (explode(",", $nested_cat_ids) as $key => $value) {
 		
		 update("DELETE FROM uni_category_board WHERE category_board_id = ?", [$value] );
       update("DELETE FROM uni_ads_filters_alias WHERE ads_filters_alias_id_cat = ?", [$value] );
		 update("DELETE FROM uni_ads_filters_category WHERE ads_filters_category_id_cat = ?", [$value] );

       $Ads->delete(["id_cat"=>$value]);

 	}
 }

 

 $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";                  
 echo true;

 $Cache->update( "uni_category_board" );

}   

?>