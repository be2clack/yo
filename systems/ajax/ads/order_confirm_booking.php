<?php

$id = (int)$_POST['id'];

$getOrder = findOne("uni_ads_booking", "ads_booking_id=? and ads_booking_id_user_to=?", [ $id, $_SESSION['profile']['id'] ]);

if($getOrder){

   update('update uni_ads_booking set ads_booking_status=? where ads_booking_id=?', [1, $id]);

   $getUser = findOne("uni_clients", "clients_id=?", [$getOrder["ads_booking_id_user_from"]]);

   $getAd = $Ads->get("ads_id=?", [$getOrder["ads_booking_id_ad"]]);

   $data      = array("{USER_NAME}"=>$getUser["clients_name"],
                       "{USER_EMAIL}"=>$getUser["clients_email"],
                       "{ADS_TITLE}"=>$getAd["ads_title"],
                       "{ADS_LINK}"=>$Ads->alias($getAd),
                       "{PROFILE_LINK_ORDER}"=>_link('booking/'.$getOrder['ads_booking_id_order']),
                       "{UNSUBCRIBE}"=>"",
                       "{EMAIL_TO}"=>$getUser["clients_email"]);

   if($getOrder['ads_booking_variant'] == 0){
      email_notification( array( "variable" => $data, "code" => "USER_CONFIRM_ORDER_BOOKING" ) );
   }else{
      email_notification( array( "variable" => $data, "code" => "USER_CONFIRM_ORDER_RENT" ) );
   }

   echo true;

}

?>