<?php

$id = (int)$_POST['order_id'];
$reason = clear($_POST['reason']);
$status = (int)$_POST['status'];
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

if($status == 1){

  $getOrder = findOne("uni_ads_booking", "ads_booking_id_order=? and ads_booking_id_user_to=?", [ $id, $idUser ]);

  if($getOrder){

     update('update uni_ads_booking set ads_booking_status=? where ads_booking_id_order=?', [1, $id]);

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

     echo json_encode(['status'=>true]);

  }

}elseif($status == 2){

  if($reason){

    $getOrder = findOne("uni_ads_booking", "ads_booking_id_order=? and (ads_booking_id_user_from=? or ads_booking_id_user_to=?)", [ $id, $idUser, $idUser ]);

    if($getOrder){

        update('update uni_ads_booking set ads_booking_status=?,ads_booking_reason_cancel=? where ads_booking_id_order=?', [2,$reason,$id]);
        update('delete from uni_ads_booking_dates where ads_booking_dates_id_order=?', [$getOrder["ads_booking_id"]]);

        if($getOrder["ads_booking_id_user_from"] == $idUser){
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
    echo json_encode(['status'=>false, 'answer'=>apiLangContent('Пожалуйста, укажите причину отмены заказа.')]);
  }


}
?>