<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$buy = [];
$sell = [];
$booking = [];

$getOrdersBuy = getAll("select * from uni_clients_orders where clients_orders_from_user_id=? order by clients_orders_id desc", [$idUser]);

if(count($getOrdersBuy)){
	foreach ($getOrdersBuy as $value) {		
		$getOrder = findOne('uni_secure', 'secure_id_order=?', [$value["clients_orders_uniq_id"]]);
		if($getOrder){
			$buy[] = [
				"date" => datetime_format($getOrder["secure_date"], true),
				"status" => $getOrder["secure_status"],
				"status_name" =>apiSecureStatusLabel($getOrder,$idUser),
				"order_id" => $value["clients_orders_uniq_id"],
			];
		}
	}
}

$getOrdersSell = getAll("select * from uni_clients_orders where clients_orders_to_user_id=? order by clients_orders_id desc", [$idUser]);

if(count($getOrdersSell)){
	foreach ($getOrdersSell as $value) {
		$getOrder = findOne('uni_secure', 'secure_id_order=?', [$value["clients_orders_uniq_id"]]);
		if($getOrder){
			$sell[] = [
				"date" => datetime_format($getOrder["secure_date"], true),
				"status" => $getOrder["secure_status"],
				"status_name" => apiSecureStatusLabel($getOrder,$idUser),
				"order_id" => $value["clients_orders_uniq_id"],
			];
		}
	}
}

$getOrdersBooking = getAll("select * from uni_ads_booking where ads_booking_id_user_from=? or ads_booking_id_user_to=? order by ads_booking_id desc", [$idUser,$idUser]);

if(count($getOrdersBooking)){
	foreach ($getOrdersBooking as $value) {
		$getAd = $Ads->get("ads_id=?", [$value['ads_booking_id_ad']]);
		$booking[] = [
			"date" => datetime_format($value["ads_booking_date_add"], true),
			"status" => $value["ads_booking_status"],
			"status_name" => apiSecureBookingStatusLabel($value,$getAd,$idUser),
			"order_id" => $value["ads_booking_id_order"],
		];
	}
}

echo json_encode(['buy'=>$buy?:null, 'sell'=>$sell?:null, 'booking'=>$booking?:null]);

?>