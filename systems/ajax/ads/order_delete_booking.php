<?php

$id = (int)$_POST['id'];

$getOrder = findOne("uni_ads_booking", "ads_booking_id=? and (ads_booking_id_user_from=? or ads_booking_id_user_to=?)", [ $id, $_SESSION['profile']['id'], $_SESSION['profile']['id'] ]);

if($getOrder){
  update('delete from uni_ads_booking where ads_booking_id=?', [$id]);
  update('delete from uni_ads_booking_dates where ads_booking_dates_id_order=?', [$id]);
}

echo json_encode(['link'=>_link( "user/" . $_SESSION["profile"]["data"]["clients_id_hash"] . "/booking" )]);

?>