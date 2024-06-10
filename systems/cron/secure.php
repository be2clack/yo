<?php
defined('unisitecms') or exit();

$getSecureTransactions = getAll("select * from uni_secure where secure_status=? and unix_timestamp(secure_date)+10*60 < unix_timestamp(NOW())", [0]);

if(count($getSecureTransactions)){
   foreach ($getSecureTransactions as $key => $value) {
          
      $getAds = getAll('select * from uni_secure_ads where secure_ads_order_id=?', [$value['secure_id_order']]);
      if(count($getAds)){
         foreach ($getAds as $ad) {
            update("update uni_ads set ads_status=? where ads_id=?", [1, $ad["secure_ads_ad_id"] ], true);
         }
      }

      update("delete from uni_clients_orders where clients_orders_uniq_id=?", [$value["secure_id_order"]]);
      update("delete from uni_secure_ads where secure_ads_order_id=?", [$value["secure_id_order"]]);
      update("delete from uni_secure where secure_id=?", [$value["secure_id"] ]);
          
   }
}

if($settings["main_type_products"] == 'electron'){

   $getSecureTransactions = getAll("select * from uni_secure where secure_status=? and unix_timestamp(secure_date)+86400 < unix_timestamp(NOW())", [1]);

   if(count($getSecureTransactions)){
      foreach ($getSecureTransactions as $value) {
          update("update uni_secure set secure_status=? where secure_id=?", [3, $value["secure_id"]]);
      }
   }

}

?>