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
$Shop = new Shop();
$ULang = new ULang();

cleanFiltersVars();

unset($_SESSION["temp_change_category"]);

if($settings["main_type_products"] == 'physical'){

if( ($_SESSION["geo"]["action"] == "uri" || !$_SESSION["geo"]) && $data["geo"] ){
    $Geo->set( array( "city_id" => $data["geo"]["city_id"] , "region_id" => $data["geo"]["region_id"] , "country_id" => $data["geo"]["country_id"], "action" => "uri" ) );
}

if($_SESSION["geo"]["action"] == "modal"){
    
    $uri = explode("/", trim( $_SERVER['REQUEST_URI'] , "/") );

	if($uri[0] != $_SESSION["geo"]["alias"]){
		$_SESSION["geo"]["action"] = "uri";
		header( "location: ".catalogLocationtUri() );
	}

}

$geo = $Ads->queryGeo() ? " and " . $Ads->queryGeo() : "";

}else{
	$getCategoryBoard = (new CategoryBoard())->getCategories("where category_board_visible=1");
}

if($data["category"]){
	$Ads->clickKeyword(intval($_GET['s_id']));
}

if( !$_GET["search"] ){

$Profile->addUserInterest($data["category"]["category_board_id"], $_SESSION['profile']['id']);

if($data["filter"]){

    $data["meta_title"] = $Seo->replace($ULang->t($data["filter"]["ads_filters_alias_title"] , [ "table" => "uni_ads_filters_alias", "field" => "ads_filters_alias_title"]));
	$data["h1"] = $Seo->replace($ULang->t($data["filter"]["ads_filters_alias_h1"] , [ "table" => "uni_ads_filters_alias", "field" => "ads_filters_alias_h1" ]));
	$data["meta_desc"] = $Seo->replace($ULang->t($data["filter"]["ads_filters_alias_desc"] , [ "table" => "uni_ads_filters_alias", "field" => "ads_filters_alias_desc"]));
	$data["seo_text"] = $Seo->replace($ULang->t($data["filter"]["ads_filters_alias_text"] , [ "table" => "uni_ads_filters_alias", "field" => "ads_filters_alias_text"]));
	$data["breadcrumb"] = breadcrumb_count($CategoryBoard->breadcrumb($getCategoryBoard,$data["category"]["category_board_id"],'
                  <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a itemprop="item" href="{LINK}"><span itemprop="name">{NAME}</span></a><meta itemprop="position" content="{INDEX}"></li>
                ') . '<li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><span itemprop="name">'.$data["h1"].'</span><meta itemprop="position" content="{INDEX}"></li>
	');

	parse_str("filter[".$data["filter"]["ads_filters_items_id_filter"]."][]=".$data["filter"]["ads_filters_alias_id_filter_item"], $data["param_filter"]);

}elseif($data["seo_filter"]){

	$Filters->viewSeoFilter( $data["seo_filter"]["seo_filters_id"] );

    $data["meta_title"] = $Seo->replace( $ULang->t( $data["seo_filter"]["seo_filters_title"] , [ "table" => "uni_seo_filters", "field" => "seo_filters_title" ] ) );
	$data["h1"] = $Seo->replace( $ULang->t( $data["seo_filter"]["seo_filters_h1"] , [ "table" => "uni_seo_filters", "field" => "seo_filters_h1" ] ) );
	$data["meta_desc"] = $Seo->replace( $ULang->t( $data["seo_filter"]["seo_filters_desc"] , [ "table" => "uni_seo_filters", "field" => "seo_filters_desc" ] ));
	$data["seo_text"] = $Seo->replace( $ULang->t( $data["seo_filter"]["seo_filters_text"] , [ "table" => "uni_seo_filters", "field" => "seo_filters_text" ] ) );
	$data["breadcrumb"] = breadcrumb_count($CategoryBoard->breadcrumb($getCategoryBoard,$data["category"]["category_board_id"],'
                  <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a itemprop="item" href="{LINK}"><span itemprop="name">{NAME}</span></a><meta itemprop="position" content="{INDEX}"></li>
                ') . '<li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><span itemprop="name">'.$data["h1"].'</span><meta itemprop="position" content="{INDEX}"></li>
	');

	parse_str($data["seo_filter"]["seo_filters_params"], $data["param_filter"]);

}elseif($data["category"]){

    $data["meta_title"] = $Seo->out(["page" => "board", "field" => "meta_title"], $data);
	$data["h1"] = $Seo->out(["page" => "board", "field" => "h1"], $data);
	$data["meta_desc"] = $Seo->out(["page" => "board", "field" => "meta_desc"], $data);
	if(!$_GET["filter"]) $data["seo_text"] = $Seo->out(["page" => "board", "field" => "text"], $data);
	$data["breadcrumb"] = breadcrumb_count($CategoryBoard->breadcrumb($getCategoryBoard,$data["category"]["category_board_id"],'
                  <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a itemprop="item" href="{LINK}"><span itemprop="name">{NAME}</span></a><meta itemprop="position" content="{INDEX}"></li>
                '));
	$data["param_filter"] = $_GET;

}elseif($_SESSION["geo"]){
    
    $data["meta_title"] = $Seo->out(["page" => "board_geo", "field" => "meta_title"], $data);
	$data["h1"] = $Seo->out(["page" => "board_geo", "field" => "h1"], $data);
	$data["meta_desc"] = $Seo->out(["page" => "board_geo", "field" => "meta_desc"], $data);
	$data["seo_text"] = $Seo->out(["page" => "board_geo", "field" => "text"], $data);
	$data["breadcrumb"] = '<li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><span itemprop="name">'.$data["h1"].'</span><meta itemprop="position" content="2"></li>';
	$data["param_filter"] = $_GET;    

}else{

    $data["meta_title"] = $Seo->out(["page" => "board_geo", "field" => "meta_title"], $data);
	$data["h1"] = $Seo->out(["page" => "board_geo", "field" => "h1"], $data);
	$data["meta_desc"] = $Seo->out(["page" => "board_geo", "field" => "meta_desc"], $data);
	$data["seo_text"] = $Seo->out(["page" => "board_geo", "field" => "text"], $data);
	$data["param_filter"] = $_GET; 

}

}else{

	$_GET["search"] = clearSearch($_GET["search"]);

	$data["meta_title"] = $static_msg["50"] . ' "'.$_GET["search"].'" ' . $Geo->outGeoDeclination();
	$data["h1"] = $static_msg["50"] . ' "'.$_GET["search"].'" ' . $Geo->outGeoDeclination();
	$data["meta_desc"] = "";
	$data["param_filter"]["search"] = $_GET["search"];

	if($_GET["filter"]){
		if($_GET["filter"]["sort"]){
			$data["param_filter"]["filter"]["sort"] = $_GET["filter"]["sort"];
		}
	}

	$Ads->addUserKeyword($_GET["search"]);
}

$data["count_filters"] = $Filters->countFilters($data["category"]["category_board_id"]);
$data["count_get_filters"] = $Filters->countGetFilters( $data["param_filter"] );

$data["city_areas"] = getAll("select * from uni_city_area where city_area_id_city=? order by city_area_name asc", [ intval($_SESSION["geo"]["data"]["city_id"]) ]);
$data["city_metro"] = getAll("select * from uni_metro where city_id=? and parent_id!=0 Order by name ASC", [ intval($_SESSION["geo"]["data"]["city_id"]) ]);

if($data["category"]){
   $ids_cat = idsBuildJoin($CategoryBoard->idsBuild($data["category"]["category_board_id"], $getCategoryBoard), $data["category"]["category_board_id"]);
   $data["vip"] = $Ads->getAll( ["query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_vip='1' and ads_id_cat IN(".$ids_cat.") $geo order by rand() limit 16", "param_search" => $param_search, "output" => 16 ] );
}else{
   $data["vip"] = $Ads->getAll( ["query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_vip='1' $geo order by rand() limit 16", "param_search" => $param_search, "output" => 16 ] );
}

if($_SESSION["geo"]["alias"]){
  $data["vip_link"] = _link($_SESSION["geo"]["alias"]."/vip");
}else{
  $data["vip_link"] = _link($settings["country_default"]."/vip"); 
}

$data["seo_alias_category"] = $Filters->outSeoAliasCategory( $data["category"]["category_board_id"] );

$Geo->viewCity($data["geo"]["city_id"]);

echo $Main->tpl("catalog.tpl", compact( 'Seo','Geo','Main','visible_footer','Ads','route_name','Profile','settings','CategoryBoard','data','getCategoryBoard', 'Filters', 'Banners', 'ULang', 'visible_cities','Shop') );

?>