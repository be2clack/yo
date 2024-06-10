<?php

$date = $_POST['date'] ? date('Y-m-d', strtotime($_POST['date'])) : '';
$id_ad = intval($_POST['id_ad']);

$getAd = $Ads->get("ads_id=? and ads_id_user=?",[$id_ad,intval($_SESSION['profile']['id'])]);

if($getAd){
   insert("INSERT INTO uni_ads_booking_dates(ads_booking_dates_date,ads_booking_dates_id_ad,ads_booking_dates_id_order,ads_booking_dates_id_cat,ads_booking_dates_id_user)VALUES(?,?,?,?,?)", [$date,$id_ad,0,$getAd['ads_id_cat'],$getAd['ads_id_user']]);
}

echo json_encode(["status"=>true]);

?>