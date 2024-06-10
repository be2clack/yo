<?php
defined('unisitecms') or exit();

$getOrderAd = getAll("select * from uni_services_order where services_order_id_service IN(1,3) and services_order_status=1");

update("update uni_ads set ads_sorting=?", [0], true);

if(count($getOrderAd)){
    foreach ($getOrderAd as $key => $value) {
       $ids[$value["services_order_id_ads"]] = $value["services_order_id_ads"];
       $data["ids"][$value["services_order_id_ads"]] = $value["services_order_id_ads"];
       $data["field"][$value["services_order_id_ads"]] = $value;
    }
}

if($data["ids"]){
   $getAds = $Ads->getAll( array("query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id IN(".implode(",",$data["ids"]).")", "sort"=>"order by ads_count_display desc", "navigation"=>false) );
   if($getAds["all"]){
      foreach ($getAds["all"] as $key => $value) {

         if( strtotime($data["field"][$value["ads_id"]]["services_order_time_validity"]) > time() ){
            update("update uni_ads set ads_sorting=? where ads_id=?", [$key+1,$value["ads_id"]], true);
         }else{
            update("update uni_ads set ads_sorting=? where ads_id=?", [0,$value["ads_id"]], true);
         }
         
      }
      $Cache->update( "uni_ads", $getAds["key_caching"] );
   }
}

?>