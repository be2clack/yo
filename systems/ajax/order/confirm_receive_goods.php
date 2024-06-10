<?php

$id = (int)$_POST["id"];

$getOrder = findOne("uni_secure", "secure_id=? and secure_id_user_buyer=?", [ $id, $_SESSION['profile']['id'] ]);

if($getOrder){

update("update uni_secure set secure_status=? where secure_id=?", [ 3 , $id ]);

if($settings["main_type_products"] == 'physical'){

  $getAds = getAll('select * from uni_secure_ads where secure_ads_order_id=?', [$getOrder['secure_id_order']]);

  foreach ($getAds as $value) {

       $findAd = $Ads->get("ads_id=?", [$value['secure_ads_ad_id']]);
     
       if(!$findAd['ads_available_unlimitedly'] && !$findAd['ads_available']){
           update("update uni_ads set ads_status=? where ads_id=?", [5,$value['secure_ads_ad_id']], true);                
       }

  }

}

$payments = findOne("uni_secure_payments", "secure_payments_id_order=? and secure_payments_id_user=?", [$getOrder["secure_id_order"],$getOrder["secure_id_user_seller"]]);

$user = findOne("uni_clients", "clients_id=?", [$getOrder["secure_id_user_seller"]]);

if(!$payments && $user){

  $Ads->addSecurePayments(["id_order"=>$getOrder["secure_id_order"], "amount"=>$getOrder["secure_price"], "score"=>$user["clients_score"], "id_user"=>$getOrder["secure_id_user_seller"], "status_pay"=>0, "status"=>1, "amount_percent" => $Ads->secureTotalAmountPercent($getOrder["secure_price"])]);

}

}

echo true;

?>