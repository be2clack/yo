<?php

$idAd = (int)$_POST["id_ad"];
$idOrder = (int)$_POST["id_order"];

$getOrder = findOne("uni_clients_orders", "clients_orders_uniq_id=? and (clients_orders_from_user_id=? or clients_orders_to_user_id=?)", [$idOrder, $_SESSION['profile']['id'], $_SESSION['profile']['id']]);

if($getOrder){
 $getAds = getAll('select * from uni_secure_ads where secure_ads_order_id=?', [$idOrder]);
 if(count($getAds) > 1){

    update('delete from uni_secure_ads where secure_ads_order_id=? and secure_ads_ad_id=?', [$idOrder,$idAd]);

    $getAds = getAll('select * from uni_secure_ads where secure_ads_order_id=?', [$idOrder]);

    foreach ($getAds as $value) {
      $totalPrice += $value['secure_ads_total'];
    }

    update("update uni_secure set secure_price=? where secure_id_order=?", [$totalPrice,$idOrder]);

    if($settings["main_type_products"] == 'physical'){
      update("update uni_ads set ads_status=? where ads_id=?", [4,$idAd], true);
    } 

 }else{

 }
}

echo true;

?>