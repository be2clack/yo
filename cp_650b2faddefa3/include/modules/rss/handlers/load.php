<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_settings']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){
     
     $param = [];

	 if( $_POST["content"] == 1 ){

        $turbo = (int)$_POST["blog_turbo"];
        $category = (int)$_POST["blog_category"];
        $sort = clear($_POST["blog_sort"]);
        $count = (int)$_POST["blog_count"];
        $title = custom_substr(clear($_POST["blog_title"]), 150, "...");
        $desc = custom_substr(clear($_POST["blog_desc"]), 150, "...");
        $id_metrics = clear($_POST["blog_id_metrics"]);
        
        if( $title ){
            $param[] = 'title=' . urlencode($title);
        }

        if( $desc ){
            $param[] = 'desc=' . urlencode($desc);
        }

        if( $category ){
        	$param[] = 'category=' . $category;
        }
        
        if( $turbo ){
        	$param[] = 'turbo=' . $turbo;
        }

        if( $id_metrics && $turbo ){
            $param[] = 'id_metrics=' . $id_metrics;
        }

        if( $sort ){
        	$param[] = 'sort=' . $sort;
        }

        if( $count ){
        	$param[] = 'count=' . $count;
        }

        if( count($param) ){
            echo $config["urlPath"] . "/rss.php?content=blog&" . implode("&", $param);
        }else{
            echo $config["urlPath"] . "/rss.php?content=blog&title=" . $settings["site_name"];
        }

	 }elseif( $_POST["content"] == 2 ){
	 	
        $turbo = (int)$_POST["ads_turbo"];
        $category = (int)$_POST["ads_category"];
        $sort = clear($_POST["ads_sort"]);
        $count = (int)$_POST["ads_count"];
        $title = custom_substr(clear($_POST["ads_title"]), 150, "...");
        $desc = custom_substr(clear($_POST["ads_desc"]), 150, "...");
        $id_metrics = clear($_POST["ads_id_metrics"]);

        if( $title ){
            $param[] = 'title=' . urlencode($title);
        }

        if( $desc ){
            $param[] = 'desc=' . urlencode($desc);
        }

        if( $category ){
        	$param[] = 'category=' . $category;
        }
        
        if( $turbo ){
        	$param[] = 'turbo=' . $turbo;
        }

        if( $id_metrics && $turbo ){
            $param[] = 'id_metrics=' . $id_metrics;
        }

        if( $sort ){
        	$param[] = 'sort=' . $sort;
        }

        if( $count ){
        	$param[] = 'count=' . $count;
        }

        if( count($param) ){
            echo $config["urlPath"] . "/rss.php?content=ads&" . implode("&", $param);
        }else{
            echo $config["urlPath"] . "/rss.php?content=ads&title=" . $settings["site_name"];
        }

	 }

}

?>
