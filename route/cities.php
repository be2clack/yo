<?php
$config = require "./config.php";

$route_name = "cities";
$visible_footer = false;

$Main = new Main();
$settings = $Main->settings();

$CategoryBoard = new CategoryBoard(); 
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$Banners = new Banners();
$ULang = new ULang();

$_SESSION["geo"]["action"] = "uri";

if( $_GET["country"] ){
	$getCountry = findOne( "uni_country", "country_alias=? and country_status=?", [ clear($_GET["country"]), 1 ] );
	if( !$getCountry ){
		 $Main->response(404);
	}
}

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

echo $Main->tpl("cities.tpl", compact( 'Seo','Geo','Main','visible_footer','data','route_name','Profile','settings','CategoryBoard','getCategoryBoard','Banners', 'ULang' ) );

?>