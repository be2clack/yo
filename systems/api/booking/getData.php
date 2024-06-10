<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$id = (int)$_GET["ad_id"];
$additional_services_list = [];
$additional_services_total_price = 0;
$booking_guests = (int)$_GET['booking_guests'] ?: 1;
$booking_hour_count = (int)$_GET['booking_hour_count'] ?: 1;
$booking_hour_start = clear($_GET['booking_hour_start']) ?: '12:00';

$results = [];
$busy_dates = [];
$additional_services_list_options = [];
$additional_services_list_options_name = [];

$getAd = $Ads->get("ads_id=?", [$id]);

if($Ads->getStatusBooking($getAd)){

	 if($getAd["category_board_booking_variant"] == 0){
	 	 $bookingStatus = true;
	 }else{
		 if(!$getAd["ads_booking_available_unlimitedly"]){ 
		     if($Ads->adCountActiveRent($getAd["ads_id"]) >= $getAd["ads_booking_available"]){
		         $bookingStatus = false; 
		     }else{
		     	  $bookingStatus = true;
		     }
		 }else{
		 	 $bookingStatus = true;
		 }
	 }

}else{
	$bookingStatus = false;
}

$booking_date_start = $_GET['booking_date_start'] ? date('d.m.Y', strtotime($_GET['booking_date_start'])) : date('d.m.Y');

if($_GET['booking_date_end']){
	$booking_date_end = date('d.m.Y', strtotime($_GET['booking_date_end']));
}else{
	if($getAd["ads_booking_min_days"]){ 
	    $booking_date_end = date('d.m.Y', strtotime('+'.$getAd["ads_booking_min_days"].' days')); 
	}else{ 
	    $booking_date_end = date('d.m.Y', strtotime('+1 days')); 
	}
}

$difference_days = difference_days($booking_date_end,$booking_date_start) ?: 1;

if($getAd["ads_booking_additional_services"]){

	$additional_services_list = json_decode($getAd["ads_booking_additional_services"], true);

	foreach ($additional_services_list as $key => $value) {
		$additional_services_list_options[] = ["name"=>$value["name"], "price"=>apiPrice($value["price"])];
		$additional_services_list_options_name[$value["name"]] = $value["price"];
	}

	if($_GET['booking_additional_services']){
		foreach (json_decode($_GET['booking_additional_services'], true) as $key => $value) {
		    if($additional_services_list_options_name[$value["name"]]){
		        $additional_services_total_price += $additional_services_list_options_name[$value["name"]];
		    }
		}
	}

}

if($getAd['ads_price_measure'] == 'hour'){
	$total = ($booking_hour_count * $getAd["ads_price"]) + $additional_services_total_price;
	$prepayment = calcPercent($booking_hour_count * $getAd["ads_price"], $getAd["ads_booking_prepayment_percent"]);
}else{
	$total = ($difference_days * $getAd["ads_price"]) + $additional_services_total_price;
	$prepayment = calcPercent($difference_days * $getAd["ads_price"], $getAd["ads_booking_prepayment_percent"]);
}

if($getAd["category_board_booking_variant"] == 0){ 
	$days = $difference_days . ' ' . ending($difference_days, apiLangContent('день'), apiLangContent('дня'), apiLangContent('дней'));
}else{

    if($getAd['ads_price_measure'] == 'hour'){
    	$days = $booking_hour_count . ' ' . ending($booking_hour_count, apiLangContent('час'), apiLangContent('часа'), apiLangContent('часов'));
    }else{
    	$days = $difference_days . ' ' . ending($difference_days, apiLangContent('день'), apiLangContent('дня'), apiLangContent('дней'));
    }

}

$getDates = getAll('select * from uni_ads_booking_dates where ads_booking_dates_id_ad=?', [$id]);

if(count($getDates)){
    foreach ($getDates as $value) {
        $busy_dates[date('d.m.Y', strtotime($value['ads_booking_dates_date']))] = $value['ads_booking_dates_date'];
    }
}

$results = [
	"status" => $bookingStatus,
	"variant" => (int)$getAd["category_board_booking_variant"],
	"variant_name" => $getAd["category_board_booking_variant"] == 0 ? apiLangContent("Бронирование") : apiLangContent("Аренда"),
	"prepayment" => (int)$getAd["ads_booking_prepayment_percent"],
	"max_guests" => $getAd["ads_booking_max_guests"],
	"min_days" => $getAd["ads_booking_min_days"],
	"max_days" => $getAd["ads_booking_max_days"],
	"price" => apiPrice($getAd["ads_price"]),
	"ad" => apiArrayDataAd($getAd,$idUser),
	"days"=>$days,
	"total_amount"=>apiPrice($total),
	"prepayment_amount"=>apiPrice($prepayment),
	"additional_services_list"=>$additional_services_list_options ?: null,
	"busy_dates"=>$busy_dates ?: null,
];

echo json_encode(['data'=>$results]);

?>