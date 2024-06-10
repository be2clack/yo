<?php
defined('unisitecms') or exit();

$get = getAll("select * from uni_ads_booking INNER JOIN `uni_ads` ON `uni_ads`.ads_id = `uni_ads_booking`.ads_booking_id_ad where `uni_ads`.ads_booking_prepayment_percent!=0 and ads_booking_status='0' and ads_booking_status_pay='0' and unix_timestamp(ads_booking_date_add)+10*60 < unix_timestamp(NOW())");

if(count($get)){
   foreach ($get as $value) {

       update("delete from uni_ads_booking_dates where ads_booking_dates_id_order=?", [$value["ads_booking_id"]]);
       update("delete from uni_ads_booking where ads_booking_id=?", [$value["ads_booking_id"]]);

   }
}

?>