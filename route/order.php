<?php

$config = require "./config.php";

$route_name = "order";
$visible_footer = true;

$Main = new Main();
$settings = $Main->settings();

if( !$_SESSION["profile"]["id"] ){
	header( "Location: " . _link("auth") );
}

$data["order"] = findOne('uni_clients_orders', 'clients_orders_uniq_id=? and (clients_orders_from_user_id=? or clients_orders_to_user_id=?)', [intval($id_order),$_SESSION["profile"]["id"],$_SESSION["profile"]["id"]]);

if(!$data["order"]){
	$Main->response(404);
}

$Ads = new Ads();
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$CategoryBoard = new CategoryBoard();
$Banners = new Banners();
$ULang = new ULang();

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

if( $data["order"]["clients_orders_from_user_id"] == $_SESSION["profile"]["id"] ){

	$data["user"] = findOne("uni_clients", "clients_id=?", [$data["order"]["clients_orders_to_user_id"]]);

}elseif( $data["order"]["clients_orders_to_user_id"] == $_SESSION["profile"]["id"] ){
	
	$data["user"] = findOne("uni_clients", "clients_id=?", [$data["order"]["clients_orders_from_user_id"]]);

}

$data["order"] = findOne("uni_secure", "secure_id_order=?", [intval($id_order)]);

if(!$data["order"]["secure_marketplace"]){
	$data["order"]["ad"] = findOne('uni_secure_ads', 'secure_ads_order_id=?', [intval($id_order)]);
}

if(!$data["order"]){
	$Main->response(404);
}

$data["order"]["commission"] = $Ads->getSecureCommission($data["order"]["secure_price"]);
$data["order"]["commission_and_price"] = $Ads->secureTotalAmountPercent($data["order"]["secure_price"]);

if($data["user"]["clients_delivery_id_point_send"]) $data["user"]["delivery_point_send"] = findOne('uni_boxberry_points', 'boxberry_points_code=?', [$data["user"]["clients_delivery_id_point_send"]]);

$data["ratings"] = $Profile->outRating($data["user"]["clients_id"]);

$data["disputes"] = getOne("SELECT * FROM uni_secure_disputes INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_secure_disputes`.secure_disputes_id_user where secure_disputes_id_secure=?", [$data["order"]["secure_id"]]);

echo $Main->tpl("order.tpl", compact( 'Seo','Geo','Main','visible_footer','route_name','settings','config','data','Profile','CategoryBoard','Banners','getCategoryBoard','Ads','ULang' ) );
?>