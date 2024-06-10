<?php
defined('unisitecms') or exit();

update("update uni_ads set ads_vip=?", [0], true);

$getOrderAd = getAll("select * from uni_services_order where services_order_id_service IN(2,3) and services_order_status=1");

if(count($getOrderAd)){

   foreach ($getOrderAd as $key => $value) {
     
         if( strtotime($value["services_order_time_validity"]) > time() ){
            update("update uni_ads set ads_vip=? where ads_id=?", [1,$value["services_order_id_ads"]], true);
         }else{
            update("update uni_ads set ads_vip=? where ads_id=?", [0,$value["services_order_id_ads"]], true);
         }

   }

}

?>