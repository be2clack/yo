<?php

$config = require "./config.php";

$route_name = "order_booking";
$visible_footer = true;

$Main = new Main();
$settings = $Main->settings();

if( !$_SESSION["profile"]["id"] ){
	header( "Location: " . _link("auth") );
}

$Ads = new Ads();
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$CategoryBoard = new CategoryBoard();
$Banners = new Banners();
$ULang = new ULang();

$data["order"] = findOne('uni_ads_booking', 'ads_booking_id_order=? and (ads_booking_id_user_from=? or ads_booking_id_user_to=?)', [intval($id_order),$_SESSION["profile"]["id"],$_SESSION["profile"]["id"]]);

$data['ad'] = $Ads->get('ads_id=?', [intval($data["order"]['ads_booking_id_ad'])]);

if(!$data["order"] || !$data['ad']){
	$Main->response(404);
}

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

if($data["order"]["ads_booking_measure"] == 'hour'){
	$data["order"]["amount_prepayment"] = calcPercent($data["order"]["ads_booking_hour_count"] * $data['ad']["ads_price"], $data['ad']["ads_booking_prepayment_percent"]);
}else{
	$data["order"]["amount_prepayment"] = calcPercent($data["order"]["ads_booking_number_days"] * $data['ad']["ads_price"], $data['ad']["ads_booking_prepayment_percent"]);
}

$data['order']["ads_booking_additional_services"] = json_decode($data["order"]["ads_booking_additional_services"], true);

if( $data["order"]["ads_booking_id_user_from"] == $_SESSION["profile"]["id"] ){

	$data["user"] = findOne("uni_clients", "clients_id=?", [$data["order"]["ads_booking_id_user_to"]]);

}elseif( $data["order"]["ads_booking_id_user_to"] == $_SESSION["profile"]["id"] ){
	
	$data["user"] = findOne("uni_clients", "clients_id=?", [$data["order"]["ads_booking_id_user_from"]]);

}

echo $Main->tpl("order_booking.tpl", compact( 'Seo','Geo','Main','visible_footer','route_name','settings','config','data','Profile','CategoryBoard','Banners','getCategoryBoard','Ads','ULang' ) );

?>