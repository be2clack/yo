<?php

$id = (int)$_POST['id'];
$reason = clear($_POST['reason']);

if($reason){

  $getOrder = findOne("uni_ads_booking", "ads_booking_id=? and (ads_booking_id_user_from=? or ads_booking_id_user_to=?)", [ $id, $_SESSION['profile']['id'], $_SESSION['profile']['id'] ]);

  if($getOrder){

      update('update uni_ads_booking set ads_booking_status=?,ads_booking_reason_cancel=? where ads_booking_id=?', [2,$reason,$id]);
      update('delete from uni_ads_booking_dates where ads_booking_dates_id_order=?', [$id]);

      if($getOrder["ads_booking_id_user_from"] == $_SESSION['profile']['id']){
          $getUser = findOne("uni_clients", "clients_id=?", [$getOrder["ads_booking_id_user_to"]]);
      }else{
          $getUser = findOne("uni_clients", "clients_id=?", [$getOrder["ads_booking_id_user_from"]]);
      }
      
      $getAd = $Ads->get("ads_id=?", [$getOrder["ads_booking_id_ad"]]);

      $data      = array("{USER_NAME}"=>$getUser["clients_name"],
                         "{USER_EMAIL}"=>$getUser["clients_email"],
                         "{ADS_TITLE}"=>$getAd["ads_title"],
                         "{ADS_LINK}"=>$Ads->alias($getAd),
                         "{REASON_TEXT}"=>$reason,
                         "{PROFILE_LINK_ORDER}"=>_link('booking/'.$getOrder['ads_booking_id_order']),
                         "{UNSUBCRIBE}"=>"",
                         "{EMAIL_TO}"=>$getUser["clients_email"]);

      email_notification( array( "variable" => $data, "code" => "USER_CANCEL_ORDER_BOOKING" ) );

  }

  echo json_encode(['status'=>true]);

}else{
 echo json_encode(['status'=>false, 'answer'=>$ULang->t('Пожалуйста, укажите причину отмены заказа.')]);
}

?>