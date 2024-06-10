<?php
$config = require "./config.php";
$static_msg = require "./static/msg.php";
$route_name = "catalog";
$visible_footer = true;
$visible_cities = true;

$Main = new Main();
$settings = $Main->settings();

$CategoryBoard = new CategoryBoard(); 
$Ads = new Ads();
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$Filters = new Filters();
$Banners = new Banners();
$Elastic = new Elastic();
$ULang = new ULang();

if($settings["main_type_products"] == 'physical'){

	$data["geo"] = $Geo->aliasCheckOut($alias_city);

	if($_SESSION["geo"]["action"] == "uri" || !$_SESSION["geo"]){
	    $Geo->set( array( "city_id" => $data["geo"]["city_id"] , "region_id" => $data["geo"]["region_id"] , "country_id" => $data["geo"]["country_id"], "action" => "uri" ) );
	}

	if($_SESSION["geo"]["action"] == "modal"){
	    
	    $uri = explode("/", trim( $_SERVER['REQUEST_URI'] , "/") );

		if($uri[0] != $_SESSION["geo"]["alias"]){
			$_SESSION["geo"]["action"] = "uri";
			header( "location: ".catalogLocationtUri() );
		}

	}

	$data["city_areas"] = getAll("select * from uni_city_area where city_area_id_city=? order by city_area_name asc", [ intval($_SESSION["geo"]["data"]["city_id"]) ]);
	$data["city_metro"] = getAll("select * from uni_metro where city_id=? and parent_id!=0 Order by name ASC", [ intval($_SESSION["geo"]["data"]["city_id"]) ]);

}


$title = $ULang->t( "Vip объявления" );

$data["meta_title"] = $title;
$data["h1"] = $title;
$data["meta_desc"] = $title;
$data["breadcrumb"] = '<li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><span itemprop="name">'.$title.'</span></li>';

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

parse_str("filter[vip]=1", $data["param_filter"]);

echo $Main->tpl("catalog.tpl", compact( 'Seo','Geo','Main','visible_footer','Ads','route_name','Profile','settings','CategoryBoard','data','getCategoryBoard', 'Filters', 'Banners', 'ULang', 'visible_cities') );

?>