<?php
defined('unisitecms') or exit();

if($settings['display_count_ads_categories']){

   $get = getAll("select * from uni_ads_summary");
   if($get) update("TRUNCATE uni_ads_summary");

   $getCategories = getAll("select * from uni_category_board where category_board_visible=?", [1]);

   if($getCategories){

      foreach ($getCategories as $value) {

         $getAds = getAll("select * from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user where ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_cat='".$value["category_board_id"]."'");

         if($getAds){
            foreach ($getAds as $ad_value) {

               $getCount = findOne("uni_ads_summary","cat_id=? and country_id=? and region_id=? and city_id=?", [$value["category_board_id"],$ad_value['ads_country_id'],$ad_value['ads_region_id'],$ad_value['ads_city_id']]);

               if(!$getCount){
                  smart_insert('uni_ads_summary',[
                   'cat_id' => $value["category_board_id"],
                   'country_id' => $ad_value['ads_country_id'],
                   'region_id' => $ad_value['ads_region_id'],
                   'city_id' => $ad_value['ads_city_id'],
                   'count_ad' => 1,
                  ]);
               }else{
                  update("update uni_ads_summary set count_ad=? where id=?", [intval($getCount["count_ad"]) + 1,$getCount["id"]]);
               }

            }
         }

      }

   }


}

?>