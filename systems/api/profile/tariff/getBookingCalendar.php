<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);
$id_ad = intval($_GET['id_ad']);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$results = [];
$dates = [];
$ordersList = [];

if($id_ad){
	$getDates = getAll("select * from uni_ads_booking_dates where ads_booking_dates_id_user=? and ads_booking_dates_id_ad=?", [$idUser, $id_ad]);
}else{
	$getDates = getAll("select * from uni_ads_booking_dates where ads_booking_dates_id_user=?", [$idUser]);
}

if($getDates){
    foreach ($getDates as $value) {
	    if($value['ads_booking_dates_id_order']){
	        $ordersList[date('Y-m-d', strtotime($value['ads_booking_dates_date']))] += 1;
	    }elseif($id_ad){
	        $ordersList[date('Y-m-d', strtotime($value['ads_booking_dates_date']))] = 1;
	    }
    }
    foreach ($ordersList as $date => $orders) {
    	$dates[$date] = ['count'=>$orders, 'title'=>$orders.' '.ending($orders, apiLangContent('заказ'),apiLangContent('заказа'),apiLangContent('заказов'))];
    }
}

$results = [
	"dates" => $dates ?: null,
];

echo json_encode($results);

?>