<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

$favorites = [];
$orders = [];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getUser = $Profile->oneUser("where clients_id=?", [$idUser]);

if(!$getUser){
	http_response_code(500); exit('User not found'); 
}

$getReviews = (int)getOne("select count(*) as total from uni_clients_reviews where clients_reviews_id_user=? and clients_reviews_status=?", [$getUser["clients_id"],1])["total"];
$getSubscribers = (int)getOne("select count(*) as total from uni_clients_subscriptions where clients_subscriptions_id_user_to=?", [$getUser["clients_id"]])["total"];
$getSubscriptions = (int)getOne("select count(*) as total from uni_clients_subscriptions where clients_subscriptions_id_user_from=?", [$getUser["clients_id"]])["total"];
$getActiveAds = $Ads->getAll( ["query" => "ads_id_user='".$getUser["clients_id"]."' and ads_status='1' and ads_period_publication > now()", "sort" => "order by ads_id desc" ] );
$getFavorites = getAll("select * from uni_favorites where favorites_from_id_user=? order by favorites_id desc", [$getUser["clients_id"]]);
$getOrdersBuy = (int)getOne("select count(*) as total from uni_clients_orders where clients_orders_from_user_id=? order by clients_orders_id desc", [$getUser["clients_id"]])["total"];
$getOrdersBooking = (int)getOne("select count(*) as total from uni_ads_booking where ads_booking_id_user_from=? or ads_booking_id_user_to=? order by ads_booking_id desc", [$getUser["clients_id"],$getUser["clients_id"]])["total"];

$getOrdersBookingAll = getAll("select * from uni_ads_booking where (ads_booking_id_user_from=? or ads_booking_id_user_to=?) and (ads_booking_status=? or ads_booking_status=?) order by ads_booking_id desc", [$getUser["clients_id"],$getUser["clients_id"],0,1]);
$getOrdersSecureBuyAll = getAll("select * from uni_secure where (secure_id_user_buyer=? or secure_id_user_seller=?) and (secure_status=? or secure_status=? or secure_status=? or secure_status=?) order by secure_id desc", [$getUser["clients_id"],$getUser["clients_id"],0,1,2,4]);


if($getFavorites){
	foreach ($getFavorites as $value) {
		$favorites[] = $value['favorites_id_ad'];
	}
}

if($getOrdersBookingAll || $getOrdersSecureBuyAll){
	if($getOrdersBookingAll){
		foreach ($getOrdersBookingAll as $key => $value) {
			$getAd = $Ads->get("ads_id=?", [$value['ads_booking_id_ad']]);
			$images = $Ads->getImages($getAd["ads_images"]);
			$orders[] = ["order_id"=>$value["ads_booking_id_order"], "date"=>datetime_format($value["ads_booking_date_add"], false), "status"=>$value["ads_booking_status"], "status_name"=>apiSecureBookingStatusLabel($value,$getAd,$getUser["clients_id"]), "image" => Exists($config["media"]["small_image_ads"],$images[0],$config["media"]["no_image"]), "type_order"=>"booking"];
		}
	}
	if($getOrdersSecureBuyAll){
		foreach ($getOrdersSecureBuyAll as $key => $value) {
			$getAdsCount = (int)getOne("select count(*) as total from uni_secure_ads where secure_ads_order_id=?", [$value["secure_id_order"]])["total"];
			$orders[] = ["order_id"=>$value["secure_id_order"], "date"=>datetime_format($value["secure_date"], false), "status"=>$value["secure_status"], "status_name"=>apiSecureStatusLabel($value,$getUser["clients_id"]), "image" => null, "count_ads" => $getAdsCount["total"], "type_order"=>"buy"];
		}
	}
}

if($getUser['clients_delivery_id_point_send']){
	$getDeliveryPoint = findOne('uni_boxberry_points', 'boxberry_points_code=?', [$getUser['clients_delivery_id_point_send']]);
}
debug($orders);
$results = [
	"id" => $getUser['clients_id'],
	"status" => $getUser['clients_status'],
	"support_token" => md5('support'.$getUser['clients_id']),
	"link" => _link( "user/" . $getUser['clients_id_hash'] ),
	"display_name" => $Profile->name($getUser, false),
	"nicname" => $getUser['clients_id_hash'] ?: '',
	"name" => $getUser['clients_name'] ?: '',
	"surname" => $getUser['clients_surname'] ?: '',
	"middlename" => $getUser['clients_patronymic'] ?: '',
	"balance" => apiPrice($getUser['clients_balance']),
	"avatar" => $Profile->userAvatar($getUser, false),
	"phone" => $getUser['clients_phone'] ?: '',
	"email" => $getUser['clients_email'] ?: '',
	"note_status" => $getUser['clients_note_status'] ?: '',
	"rating" => $Profile->ratingBalls($getUser['clients_id']),
	"reviews" => $getReviews,
	"subscribers_count" => $getSubscribers,
	"subscriptions_count" => $getSubscriptions,
	"count_ads" => $getActiveAds['count'],
	"type_person" => $getUser['clients_type_person'] ?: 'user',
	"name_company" => $getUser['clients_name_company'] ?: '',
	"view_phone" => $getUser['clients_view_phone'] ? true : false,
	"favorites_count" => count($getFavorites),
	"orders_count" => $getOrdersBuy+$getOrdersBooking,
	"favorites_ids" => $favorites?:null,
	"secure_status" => $getUser['clients_secure'] ? true : false,
	"delivery_status" => $getUser['clients_delivery_status'] ? true : false,
	"delivery_point_address" => isset($getDeliveryPoint) ? $getDeliveryPoint['boxberry_points_address'] : null,
	"delivery_point_code" => $getUser['clients_delivery_id_point_send'] ?: null,
	"count_story_publication" => $getUser['clients_count_story_publication'],
	"score_data" => [
		"score" => decrypt($getUser['clients_score']) ?: null,
		"score_type" => $getUser['clients_score_type'] ?: null,
	],
	"ref" => [
		"link" => $Profile->refAlias($getUser['clients_ref_id']),
		"percent" => (int)$settings["referral_program_award_percent"],
		"text" => apiLangContent("Распространяйте свою реферальную ссылку и получайте пожизненное вознаграждение от пополнения баланса пользователем в размере")." ".$settings["referral_program_award_percent"].apiLangContent("% от суммы пополнения."),
	],
	"orders" => $orders ?: null,
	"tariff" => apiGetTariff($getUser) ?: null,
	"tariff_id" => $getUser['clients_tariff_id'],
];

$getShop = findOne("uni_clients_shops", "clients_shops_id_user=?", [$getUser["clients_id"]]);

if($getShop){
	$results['shop'] = ['link'=>$Shop->linkShop($getUser["clients_shops_id_hash"]), 'title'=>$getShop['clients_shops_title'], 'text'=>$getShop['clients_shops_desc'], 'id'=>$getShop['clients_shops_id'], 'status'=>$getShop['clients_shops_status']];
}

echo json_encode($results); 

?>