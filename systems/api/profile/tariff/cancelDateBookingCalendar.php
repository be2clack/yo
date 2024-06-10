<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
   http_response_code(500); exit('Authorization token error');
}

$date = $_POST['date'] ? date('Y-m-d', strtotime($_POST['date'])) : '';
$id_ad = intval($_POST['id_ad']);

$statusOpenDate = null;

$getAd = $Ads->get("ads_id=? and ads_id_user=?",[$id_ad,$idUser]);

if($getAd && $date){

   $checkCancelDate = findOne('uni_ads_booking_dates', 'date(ads_booking_dates_date)=? and ads_booking_dates_id_user=? and ads_booking_dates_id_ad=? and ads_booking_dates_id_order=?', [$date,$idUser,$id_ad,0]);

   if($checkCancelDate){
      update("delete from uni_ads_booking_dates where ads_booking_dates_id=?", [$checkCancelDate["ads_booking_dates_id"]]);
      $statusOpenDate = false;
   }else{
      insert("INSERT INTO uni_ads_booking_dates(ads_booking_dates_date,ads_booking_dates_id_ad,ads_booking_dates_id_order,ads_booking_dates_id_cat,ads_booking_dates_id_user)VALUES(?,?,?,?,?)", [$date,$id_ad,0,$getAd['ads_id_cat'],$getAd['ads_id_user']]);
      $statusOpenDate = true;
   }

}

echo json_encode(["status"=>$statusOpenDate]);
?>