<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

$date = $_GET['date'] ? date('Y-m-d', strtotime($_GET['date'])) : '';
$id_ad = intval($_GET['id_ad']);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$booking = [];
$statusOpenDate = null;

if($date){
	if($id_ad){
	    $getDates = getAll("select * from uni_ads_booking_dates where date(ads_booking_dates_date)=? and ads_booking_dates_id_user=? and ads_booking_dates_id_ad=? and ads_booking_dates_id_order!=?", [ $date,$idUser,$id_ad,0 ]);
	}else{
	    $getDates = getAll("select * from uni_ads_booking_dates where date(ads_booking_dates_date)=? and ads_booking_dates_id_user=? and ads_booking_dates_id_order!=?", [ $date,$idUser,0 ]);
	}
	if($getDates){

	        foreach ($getDates as $date_value) {
	            
		        $getOrders = getAll("select * from uni_ads_booking where ads_booking_id=?", [$date_value['ads_booking_dates_id_order']]);
		        
		        if($getOrders){
		            foreach ($getOrders as $value) {

						$getAd = $Ads->get("ads_id=?", [$value['ads_booking_id_ad']]);
						$booking[] = [
							"date" => datetime_format($value["ads_booking_date_add"], true),
							"status" => $value["ads_booking_status"],
							"status_name" => apiSecureBookingStatusLabel($value,$getAd,$idUser),
							"order_id" => $value["ads_booking_id_order"],
						];

		            }
		        }
	            
	        }

	}else{

	    if($id_ad && $date >= date('Y-m-d')){ 
	        $checkCancelDate = findOne('uni_ads_booking_dates', 'date(ads_booking_dates_date)=? and ads_booking_dates_id_user=? and ads_booking_dates_id_ad=? and ads_booking_dates_id_order=?', [$date,$idUser,$id_ad,0]);
	        if(!$checkCancelDate){
	        	$statusOpenDate = true;               
	        }else{
		        $statusOpenDate = false; 
	        }
	    }

	}
}


echo json_encode(["orders" => $booking, "statusOpenDate" => $statusOpenDate]);

?>