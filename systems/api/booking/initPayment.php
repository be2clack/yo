<?php

$id = (int)$_POST["id"];
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
  http_response_code(500); exit('Authorization token error');
}

if(!$settings["booking_payment_service_name"]){
   exit(json_encode(array("status" => false, "answer" => apiLangContent("Платежная система не определена!"))));
}

$getOrder = findOne("uni_ads_booking", "ads_booking_id_order=? and (ads_booking_id_user_from=? or ads_booking_id_user_to=?)", [ $id, $idUser, $idUser ]);

if($getOrder){

  if($getOrder['ads_booking_status_pay'] == 1){
     exit(json_encode(array("status" => false, "answer" => apiLangContent("Заказ уже оплачен"))));
  }

  if(time() > strtotime($getOrder["ads_booking_date_add"]) + 10*60){
    echo json_encode(["status" => false, "answer" => apiLangContent("Время резервирования истекло, оформите заказ повторно")]);
  }

  $getAd = $Ads->get("ads_id=?", [$getOrder['ads_booking_id_ad']]);

  if($getOrder["ads_booking_measure"] == 'hour'){
     $prepayment = calcPercent($getOrder['ads_booking_hour_count'] * $getAd["ads_price"], $getAd["ads_booking_prepayment_percent"]);
  }else{
     $prepayment = calcPercent($getOrder['ads_booking_number_days'] * $getAd["ads_price"], $getAd["ads_booking_prepayment_percent"]);
  }

  $answer = $Profile->payMethod($settings["booking_payment_service_name"] , array("amount" => $prepayment, "id_order" => $getOrder['ads_booking_id_order'], "id_ad" => $getOrder['ads_booking_id_ad'], "from_user_id" => $getOrder['ads_booking_id_user_from'], "to_user_id" => $getOrder['ads_booking_id_user_to'], "action" => "booking", "email" => $getAd['clients_email'], "phone" => $getAd['clients_phone'], "title" => $static_msg["57"]." №".$getOrder['ads_booking_id_order']));

  echo json_encode(["status" => true, "link" => $answer['link']]);

}

?>