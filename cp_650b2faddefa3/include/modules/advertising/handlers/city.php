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

$Geo = new Geo();

if(isAjax() == true){

    $query = clearSearch( $_POST["q"] );

	if($query && mb_strlen($query, "UTF-8") >= 2 ){

	    $results = $Geo->search($query);

	    if(count($results)){

	       foreach($results AS $data){
                 
                 if($data["region_name"]){
		       	 $list["region"][$data["region_name"]] = '
		            <div class="item-city" data-name="'.$data["region_name"].'" data-geo-name="region" data-id="'.$data["region_id"].'" >
		            	<strong>'.$data["region_name"].'</strong>
		            </div>
		       	 ';
		       	 }
                 
                 if($data["city_name"]){
		       	 $list["city"][] = '
		            <div class="item-city"  data-name="'.$data["city_name"].'" data-geo-name="city" data-id="'.$data["city_id"].'" >
		            	<strong>'.$data["city_name"].'</strong> <span class="span-subtitle" >'.$data["region_name"].', '.$data["country_name"].'</span>
		            </div>
		       	 ';
		       	 }
                 
                 if($data["country_name"]){
		       	 $list["country"][$data["country_name"]] = '
		            <div class="item-city" data-name="'.$data["country_name"].'" data-geo-name="country" data-id="'.$data["country_id"].'"  >
		            	<strong>'.$data["country_name"].'</strong>
		            </div>
		       	 ';
		       	 }	

	       }


	       if($list["region"]) echo implode("", $list["region"]);
	       echo implode("", $list["city"]);
	       if($list["country"]) echo implode("", $list["country"]);



	    }else{
	    	echo false;
	    }

	}else{
		echo false;
	}

}

?>