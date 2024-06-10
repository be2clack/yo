<?php

$date = $_POST['date'] ? date('Y-m-d', strtotime($_POST['date'])) : '';
$id_ad = intval($_POST['id_ad']);

$getAd = $Ads->get("ads_id=? and ads_id_user=?",[$id_ad,intval($_SESSION['profile']['id'])]);

if($getAd){
   update("delete from uni_ads_booking_dates where date(ads_booking_dates_date)=? and ads_booking_dates_id_ad=? and ads_booking_dates_id_order=?", [$date,$id_ad,0]);
}

echo json_encode(["status"=>true]);

?>