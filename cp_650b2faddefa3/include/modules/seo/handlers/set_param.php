<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_seo']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

	$getCategoryBoard = (new CategoryBoard())->getCategories("where category_board_visible=1");
    
    $url = parse_url($_POST["url"]);

	if($_POST["url"] && $url["host"]){

		if( strpos( $_POST["url"] , '?') !== false ){
			$param = explode('?', $_POST["url"])[1];
		}

		$explode = explode("/", trim($url["path"], "/") );
        
        if( $settings["languages_data"][ $explode[0] ] ){

            $alias_geo = $explode[1];
            unset($explode[0]);
            unset($explode[1]);

	    }else{

            $alias_geo = $explode[0];
            unset($explode[0]);

	    }

	    $alias_category = implode("/", $explode);

	    if( !$getCategoryBoard["category_board_chain"][ $alias_category ] ){
            
            $end_value = end($explode);
		    $end_value_delete = array_pop($explode);

		    $alias_category = implode("/", $explode);

		    if( $getCategoryBoard["category_board_chain"][ $alias_category ] ){
               
               $category = $getCategoryBoard["category_board_chain"][ $alias_category ];

			   $getAlias = getOne("select * from uni_ads_filters_alias INNER JOIN `uni_ads_filters_items` ON `uni_ads_filters_alias`.ads_filters_alias_id_filter_item = `uni_ads_filters_items`.ads_filters_items_id INNER JOIN `uni_ads_filters` ON `uni_ads_filters`.ads_filters_id = `uni_ads_filters_items`.ads_filters_items_id_filter where ads_filters_alias_alias=? and ads_filters_alias_id_cat=?", [ $end_value, $category["category_board_id"] ]);
			   
			   if( $getAlias ){

			   	   if( $param ){
                       $param = "filter[".$getAlias["ads_filters_items_id_filter"]."][]=".$getAlias["ads_filters_alias_id_filter_item"]."&".$param;
			   	   }else{
                       $param = "filter[".$getAlias["ads_filters_items_id_filter"]."][]=".$getAlias["ads_filters_alias_id_filter_item"];
			   	   }
		           
		       }

		    }

	    }

		echo json_encode( ["alias_geo"=>$alias_geo, "alias_category"=>$alias_category, "params"=>$param] );

	}else{
		echo json_encode( ["alias_geo"=>"", "alias_category"=>"", "params"=>""] );
	}

}
?>