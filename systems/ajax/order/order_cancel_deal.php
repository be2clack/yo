<?php

$id = (int)$_POST["id"];

$getOrder = findOne("uni_secure", "secure_id=? and secure_id_user_buyer=? and secure_status=?", [$id,$_SESSION['profile']['id'],1]);

if($getOrder){

   $Cart->returnAvailable($getOrder["secure_id_order"]);

   update("update uni_secure set secure_status=? where secure_id=?", [ 5 , $id ]);

   $getAds = getAll('select * from uni_secure_ads where secure_ads_order_id=?', [$getOrder['secure_id_order']]);
   if(count($getAds)){
      foreach ($getAds as $ad) {
         update("update uni_ads set ads_status=? where ads_id=?", [1, $ad["secure_ads_ad_id"] ], true);
      }
   }

   $payments = findOne("uni_secure_payments", "secure_payments_id_order=? and secure_payments_id_user=? and secure_payments_status=?", [$getOrder["secure_id_order"],$getOrder["secure_id_user_buyer"],2]);

   $user = findOne("uni_clients", "clients_id=?", [$getOrder["secure_id_user_buyer"]]);

   if(!$payments && $user){

     if(!$getOrder["secure_balance_payment"]){
         $Ads->addSecurePayments( ["id_order"=>$getOrder["secure_id_order"], "amount"=>$getOrder["secure_price"], "score"=>$user["clients_score"], "id_user"=>$getOrder["secure_id_user_buyer"], "status_pay"=>0, "status"=>2, "amount_percent" => $Ads->secureTotalAmountPercent($getOrder["secure_price"], false)] );
     }else{
         $Ads->addSecurePayments( ["id_order"=>$getOrder["secure_id_order"], "amount"=>$getOrder["secure_price"], "score"=>$user["clients_score"], "id_user"=>$getOrder["secure_id_user_buyer"], "status_pay"=>1, "status"=>2, "amount_percent" => $Ads->secureTotalAmountPercent($getOrder["secure_price"], false)] );
         $Profile->actionBalance(array("id_user"=>$getOrder["secure_id_user_buyer"],"summa"=>$getOrder["secure_price"],"title"=>$static_msg["61"].' '.$getOrder["secure_id_order"],"id_order"=>generateOrderId(),"email" => $user["clients_email"],"name" => $user["clients_name"], "note" => ""),"+");
     }

   }

}

echo true;

?>