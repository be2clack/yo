<?php

$id = (int)$_GET["id"];
$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$results = [];
$ad = [];
$buyer = [];
$seller = [];
$product_links = [];
$additional_services_list = [];

$getOrder = findOne('uni_ads_booking', 'ads_booking_id_order=? and (ads_booking_id_user_from=? or ads_booking_id_user_to=?)', [$id,$idUser,$idUser]);

if(!$getOrder){
	exit(json_encode(['status'=>false]));
}

$getAd = $Ads->get("ads_id=?", [$getOrder['ads_booking_id_ad']]);

if($getAd){

	$image = $Ads->getImages($getAd["ads_images"]);

	$ad = [
		'id'=>$getAd['ads_id'],
		'image'=>Exists($config["media"]["small_image_ads"],$image[0],$config["media"]["no_image"]),
		'title'=>$getAd['ads_title'],
	];

}else{
	exit(json_encode(['status'=>false]));
}

$getUserBuyer = findOne('uni_clients', 'clients_id=?', [$getOrder['ads_booking_id_user_from']]);
if($getUserBuyer){
	$buyer = [
		'id' => $getUserBuyer['clients_id'],
		'display_name' => $Profile->name($getUserBuyer),
		'avatar' => $Profile->userAvatar($getUserBuyer),
	];
}

$getUserSeller = findOne('uni_clients', 'clients_id=?', [$getOrder['ads_booking_id_user_to']]);
if($getUserSeller){
	$seller = [
		'id' => $getUserSeller['clients_id'],
		'display_name' => $Profile->name($getUserSeller),
		'avatar' => $Profile->userAvatar($getUserSeller),
	];
}

$seconds_completion = (strtotime($getOrder["ads_booking_date_add"]) + 10*60) - time();

if($getOrder["ads_booking_additional_services"]){

	foreach (json_decode($getOrder["ads_booking_additional_services"], true) as $name => $value) {
		$additional_services_list[] = ["name"=>$name, "price"=>apiPrice($value)];
	}

}

if($getOrder["ads_booking_measure"] == 'hour'){
	$amount_prepayment = calcPercent($getOrder["ads_booking_hour_count"] * $getAd["ads_price"], $getAd["ads_booking_prepayment_percent"]);
}else{
	$amount_prepayment = calcPercent($getOrder["ads_booking_number_days"] * $getAd["ads_price"], $getAd["ads_booking_prepayment_percent"]);
}

$results = [
	"status" => $getOrder['ads_booking_status'],
	"status_name" => apiSecureBookingStatusLabel($getOrder,$getAd,$idUser),
	"date" => datetime_format($getOrder["ads_booking_date_add"], true),
	"date_completion" => date('Y-m-d H:i:s', strtotime($getOrder["ads_booking_date_add"]) + 10*60),
	"seconds_completion" => $seconds_completion ? $seconds_completion : 0,
	"amount" => apiPrice($getOrder["ads_booking_total_price"]),
	"amount_total" => apiPrice($getOrder['ads_booking_total_price'] - $amount_prepayment),
	"amount_prepayment" => apiPrice($amount_prepayment),
	"buyer" => $buyer,
	"seller" => $seller,
	"ad" => $ad,
	"status_prepayment" => $getAd["ads_booking_prepayment_percent"] ? true : false,
	"status_pay" => $getOrder['ads_booking_status_pay'] ? true : false,
	"reason_cancel" => $getOrder["ads_booking_reason_cancel"] ?: null,
	"guests" => $getOrder["ads_booking_guests"],
	"date_start" => date('d.m.Y', strtotime($getOrder["ads_booking_date_start"])),
	"date_end" => date('d.m.Y', strtotime($getOrder["ads_booking_date_end"])),
	"variant" => (int)$getOrder["ads_booking_variant"],
	"count_days" => $getOrder["ads_booking_number_days"]." ".ending($getOrder["ads_booking_number_days"], apiLangContent('день'),apiLangContent('дня'),apiLangContent('дней')),
	"hour_start" => $getOrder["ads_booking_hour_start"],
	"hour_count" => $getOrder["ads_booking_hour_count"]." ".ending($getOrder["ads_booking_hour_count"], apiLangContent('час'),apiLangContent('часа'),apiLangContent('часов')),
	"measure" => $getOrder["ads_booking_measure"],
	"additional_services" => $additional_services_list ? $additional_services_list : null,
];

echo json_encode(['status'=>true, 'data'=>$results]);

?>