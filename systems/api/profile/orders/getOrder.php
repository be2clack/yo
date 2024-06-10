<?php

$id = (int)$_GET["id"];
$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$results = [];
$ads = [];
$buyer = [];
$seller = [];
$product_links = [];

$getOrder = findOne('uni_secure', 'secure_id_order=? and (secure_id_user_buyer=? or secure_id_user_seller=?)', [$id,$idUser,$idUser]);

if(!$getOrder){
	exit(json_encode(['status'=>false]));
}

$getOrderAd = findOne('uni_secure_ads', 'secure_ads_order_id=?', [$getOrder['secure_id_order']]);

if($getOrderAd){
	$getAd = $Ads->get("ads_id=?", [$getOrderAd['secure_ads_ad_id']]);
	if($getAd){

        if($getOrder["secure_status"] != 0 && $getOrder["secure_id_user_buyer"] == $idUser && $settings["main_type_products"] == 'electron'){
            $getAd["ads_electron_product_links"] = explode(',', $getAd["ads_electron_product_links"]);
            foreach ($getAd["ads_electron_product_links"] as $link) {
            	$product_links[] = $link;
            }
        }

		$image = $Ads->getImages($getAd["ads_images"]);

		$ads[] = [
			'id'=>$getAd['ads_id'],
			'title'=>$getAd['ads_title'],
			'image'=>Exists($config["media"]["small_image_ads"],$image[0],$config["media"]["no_image"]),
			'title'=>$getAd['ads_title'],
			'amount'=>apiPrice($getOrderAd['secure_ads_total']),
			'count'=>$getOrderAd['secure_ads_count'],
			'product_links' => $product_links ?: null,
			'product_text' => $getAd["ads_electron_product_text"] ?: null,
		];

	}
}

$getUserBuyer = findOne('uni_clients', 'clients_id=?', [$getOrder['secure_id_user_buyer']]);
if($getUserBuyer){
	$buyer = [
		'id' => $getUserBuyer['clients_id'],
		'display_name' => $Profile->name($getUserBuyer),
		'avatar' => $Profile->userAvatar($getUserBuyer),
	];
}

$getUserSeller = findOne('uni_clients', 'clients_id=?', [$getOrder['secure_id_user_seller']]);
if($getUserSeller){
	$seller = [
		'id' => $getUserSeller['clients_id'],
		'display_name' => $Profile->name($getUserSeller),
		'avatar' => $Profile->userAvatar($getUserSeller),
	];
}

if($getUserSeller["clients_delivery_id_point_send"]){
	$boxberryPoints = findOne('uni_boxberry_points', 'boxberry_points_code=?', [$getUserSeller["clients_delivery_id_point_send"]]);
}

if($getOrder['secure_id_user_buyer'] == $idUser){
	$result_pay = apiSecureResultPay(['id_user'=>$getOrder['secure_id_user_buyer'], 'id_order'=>$id]);
}else{
	$result_pay = apiSecureResultPay(['id_user'=>$getOrder['secure_id_user_seller'], 'id_order'=>$id]);
}

// Доставка

$delivery_list[] = ['title'=>apiLangContent('Заберу сам у продавца'), 'price'=>apiLangContent('Бесплатно')];

if($Ads->getStatusDelivery($getAd)){
	$delivery_list[] = ['title'=>apiLangContent('Доставка в пункт Boxberry'), 'price'=>apiLangContent('Оплата доставки при получении')];
}

$disputes = getOne("SELECT * FROM uni_secure_disputes INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_secure_disputes`.secure_disputes_id_user where secure_disputes_id_secure=?", [$getOrder["secure_id"]]);

$seconds_completion = (strtotime($getOrder["secure_date"]) + 10*60) - time();

$results = [
	"status" => $getOrder['secure_status'],
	"status_name" => apiSecureStatusLabel($getOrder,$idUser),
	"date" => datetime_format($getOrder["secure_date"], true),
	"date_completion" => date('Y-m-d H:i:s', strtotime($getOrder["secure_date"]) + 10*60),
	"seconds_completion" => $seconds_completion ? $seconds_completion : 0,
	"amount" => apiPrice($getOrder["secure_price"]),
	"commission" => apiPrice($Ads->getSecureCommission($getOrder["secure_price"])),
	"amount_total" => apiPrice($Ads->secureTotalAmountPercent($getOrder["secure_price"])),
	"buyer" => $buyer,
	"seller" => $seller,
	"delivery" => [
		"list" => $delivery_list,
		"type" => $getOrder['secure_delivery_type'],
		"track_number" => $getOrder['secure_delivery_track_number'],
		"invoice_number" => $getOrder['secure_delivery_invoice_number'],
		"point_send" => isset($boxberryPoints['boxberry_points_address']) ?: '',
		"link_check_track_number" => 'https://boxberry.ru/tracking-page',
	],
	"result_pay" => isset($result_pay) ? $result_pay : null,
	"disputes" => $disputes ? [
		"text_user" => $disputes['secure_disputes_text'],
		"text_arbitr" => $disputes['secure_disputes_text_arbitr'] ?: null,
	] : null,
	"ads" => $ads,
];

echo json_encode(['status'=>true, 'data'=>$results]);

?>