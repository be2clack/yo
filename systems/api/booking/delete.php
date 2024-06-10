<?php

$id = (int)$_POST['order_id'];
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getOrder = findOne("uni_ads_booking", "ads_booking_id_order=? and (ads_booking_id_user_from=? or ads_booking_id_user_to=?)", [ $id, $idUser, $idUser ]);

if($getOrder){
  update('delete from uni_ads_booking where ads_booking_id=?', [$getOrder["ads_booking_id"]]);
  update('delete from uni_ads_booking_dates where ads_booking_dates_id_order=?', [$getOrder["ads_booking_id"]]);
}

echo json_encode(['status'=>true]);
?>