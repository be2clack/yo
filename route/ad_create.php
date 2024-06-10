<?php
$config = require "./config.php";

$route_name = "ad_create";
$visible_footer = false;

$Main = new Main();
$settings = $Main->settings();

$CategoryBoard = new CategoryBoard(); 
$Ads = new Ads();
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$Filters = new Filters();
$Banners = new Banners();
$ULang = new ULang();
$Shop = new Shop();
$Cart = new Cart();

if( !$_SESSION["profile"]["id"] ){
    header("location:" . _link("auth") );
}

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

$data["user"] = findOne("uni_clients", "clients_id=?", [$_SESSION["profile"]["id"]]);

if($data["user"]["clients_city_id"]){
   $data["user_geo"] = findOne('uni_city', 'city_id=?', [$data["user"]["clients_city_id"]]);
}elseif($_SESSION["geo"]["data"]["city_id"]){
   $data["user_geo"] = findOne('uni_city', 'city_id=?', [$_SESSION["geo"]["data"]["city_id"]]);
}

if( $settings["ad_create_phone"] ){
    if( !$data["user"]["clients_phone"] ){
        $data["display_phone"] = true;
    }
}

if($settings["ad_create_period"]){
	$ad_create_period_list = explode(",", $settings["ad_create_period_list"]);
	if ($ad_create_period_list) {
	  foreach ($ad_create_period_list as $key => $value) {

	  	if( $value == $settings["ads_time_publication_default"] ){
	  		$active = 'class="uni-select-item-active"';
	  	}else{
	  		$active = '';
	  	}

	  	$list_period .= '<label '.$active.' > <input type="radio" name="period" value="'.$value.'"> <span>'.$value.' '.ending($value, $ULang->t("день") , $ULang->t("дня"),$ULang->t("дней") ).'</span> <i class="la la-check"></i> </label>';
	  }
	}
}

echo $Main->tpl("ad_create.tpl", compact( 'Seo','Geo','Main','visible_footer','Ads','route_name','Profile','CategoryBoard','getCategoryBoard','data','settings','Filters','Banners', 'ULang', 'list_period', 'Cart' ) );


?>