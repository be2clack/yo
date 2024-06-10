<?php

defined('unisitecms') or exit();

$getUserInterests = getAll("select * from uni_clients_interests order by date_view desc");

if($getUserInterests){
	foreach ($getUserInterests as $key => $value) {

		$getAds = getAll("select ads_id from uni_ads where ads_status=? and ads_period_publication > now() and ads_id_cat=? order by ads_id desc limit 5", [1,$value["cat_id"]]);

		if($getAds){
			foreach ($getAds as $ad) {
				$check = findOne("uni_ads_recommendations", "user_id=? and ad_id=?", [$value["user_id"],$ad["ads_id"]]);
				if(!$check){
					smart_insert('uni_ads_recommendations',[
					  	'user_id' => $value["user_id"],
					  	'cat_id' => $value["cat_id"],
					  	'sort' => $value["count_view"],
					  	'ad_id' => $ad["ads_id"],	
					  	'create_date' => date("Y-m-d H:i:s"),	  
					  	'view_date' => date("Y-m-d H:i:s"),		
				    ]);
				}
			}
		}
		
	}
}

?>