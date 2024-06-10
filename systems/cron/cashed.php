<?php
defined('unisitecms') or exit();

$getViewsDisplayTemp = getAll("select * from uni_ads_views_display_temp");

if($getViewsDisplayTemp){
   foreach ($getViewsDisplayTemp as $key => $value) {
      $Ads->updateCountDisplay($value["ad_id"],$value["user_id"]);
   }
   update("delete from uni_ads_views_display_temp");
}

// Update count ads

$countAds = (int)getOne("select count(*) as total from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user where ads_status!='8' and clients_status!='3'")["total"];
update("UPDATE uni_settings SET value=? WHERE name=?", array($countAds,'total_count_ads'));

?>