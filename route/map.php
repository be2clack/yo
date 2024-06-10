<?php

$config = require "./config.php";

$route_name = "map";
$visible_footer = false;

$Main = new Main();
$settings = $Main->settings();

$Ads = new Ads();
$Filters = new Filters();
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$CategoryBoard = new CategoryBoard();
$Banners = new Banners();
$ULang = new ULang();
$Shop = new Shop();

cleanFiltersVars();

if($_SESSION['map_change_geo_alias'] != $_SESSION["geo"]["alias"]){
	unset($_GET['filter']['area']);
	unset($_GET['filter']['metro']);
}

$_SESSION['map_change_geo_alias'] = $alias_city;

if($_SESSION["geo"]["action"] == "modal"){
    
	if($alias_city != $_SESSION["geo"]["alias"]){
		$_SESSION["geo"]["action"] = "uri";
		$vars = trim( explode("?", $_SERVER['REQUEST_URI'])[1] , "/");
		$vars = $vars ? "?" . $vars : "";
		header( "location: ". _link( "map/" . $_SESSION["geo"]["alias"] . $vars ) );
	}

}else{

    if(!$alias_city && $_SESSION["geo"]["alias"]){
		$_SESSION["geo"]["action"] = "uri";
		$vars = trim( explode("?", $_SERVER['REQUEST_URI'])[1] , "/");
		$vars = $vars ? "?" . $vars : "";
		header( "location: ". _link( "map/" . $_SESSION["geo"]["alias"] . $vars ) );    	
    }

}

$data["city_areas"] = getAll("select * from uni_city_area where city_area_id_city=? order by city_area_name asc", [ intval($_SESSION["geo"]["data"]["city_id"]) ]);
$data["city_metro"] = getAll("select * from uni_metro where city_id=? and parent_id!=0 Order by name ASC", [ intval($_SESSION["geo"]["data"]["city_id"]) ]);

if(!$data["city_areas"]){
	unset($_GET['filter']['area']);
}

if(!$data["city_metro"]){
	unset($_GET['filter']['metro']);
}

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

$data["category"] = $getCategoryBoard["category_board_id"][ $_GET["id_c"] ];
$data["param_filter"] = $_GET;

$data["filters"] = $Filters->load_filters_catalog( $_GET["id_c"] , $data["param_filter"], "filters_modal" );

if(isset($_SESSION["geo"]["data"])){

	if(isset($_SESSION["geo"]["data"]["city_id"])){

		$data["geo_lat"] = isset($_SESSION["geo"]["data"]["city_lat"]) ? $_SESSION["geo"]["data"]["city_lat"] : $settings["country_lat"];
		$data["geo_lon"] = isset($_SESSION["geo"]["data"]["city_lng"]) ? $_SESSION["geo"]["data"]["city_lng"] : $settings["country_lng"];

	}elseif(isset($_SESSION["geo"]["data"]["region_id"])){

		$data["geo_lat"] = $settings["country_lat"];
		$data["geo_lon"] = $settings["country_lng"];

	}elseif(isset($_SESSION["geo"]["data"]["country_id"])){

		$data["geo_lat"] = $settings["country_lat"];
		$data["geo_lon"] = $settings["country_lng"];

	}

}else{
	$data["geo_lat"] = $settings["country_lat"];
	$data["geo_lon"] = $settings["country_lng"];
}

echo $Main->tpl("map.tpl", compact( 'Seo','Geo','Main','visible_footer','route_name','settings','config','data','Profile','CategoryBoard','Banners','getCategoryBoard','ULang','Ads','Filters','Shop' ) );

?>