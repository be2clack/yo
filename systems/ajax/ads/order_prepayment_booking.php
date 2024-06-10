<?php

$id = (int)$_POST['id'];

$getOrder = findOne("uni_ads_booking", "ads_booking_id=? and (ads_booking_id_user_from=? or ads_booking_id_user_to=?)", [ $id, $_SESSION['profile']['id'], $_SESSION['profile']['id'] ]);

if($getOrder){

  $getAd = $Ads->get("ads_id=?", [$getOrder['ads_booking_id_ad']]);

  if($getOrder["ads_booking_measure"] == 'hour'){
     $prepayment = calcPercent($getOrder['ads_booking_hour_count'] * $getAd["ads_price"], $getAd["ads_booking_prepayment_percent"]);
  }else{
     $prepayment = calcPercent($getOrder['ads_booking_number_days'] * $getAd["ads_price"], $getAd["ads_booking_prepayment_percent"]);
  }

  $result = $Profile->payMethod( $settings["booking_payment_service_name"] , array("amount" => $prepayment, "id_order" => $getOrder['ads_booking_id_order'], "id_ad" => $getOrder['ads_booking_id_ad'], "from_user_id" => $getOrder['ads_booking_id_user_from'], "to_user_id" => $getOrder['ads_booking_id_user_to'], "action" => "booking", "title" => $static_msg["57"]." №".$getOrder['ads_booking_id_order'], 'link_order' => _link('booking/'.$getOrder['ads_booking_id_order'])) );

  if($result['form']){
      echo json_encode(['status'=>true, 'form'=>$result['form']]);
  }else{
      echo json_encode(['status'=>true, 'link'=>$result['link']]);
  }

}

?>