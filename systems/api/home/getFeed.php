<?php

$page = (int)$_GET["page"];

$results = [];

$totalCountFeed = (int)getOne("SELECT count(*) as total FROM uni_feed_stories")["total"];

$getFeeds = getAll('select * from uni_feed_stories order by feed_stories_id desc '.navigation_offset(["count"=>$totalCountFeed, "output"=>$settings['feed_stories_output'], "page"=>$page]));

if(count($getFeeds)){
	foreach ($getFeeds as $value) {

		$linkImages = [];
		$params = [];

		$data = json_decode($value['feed_stories_data'], true);

    	if($value['feed_stories_user_id']){
    		$getUser = findOne('uni_clients', 'clients_id=?', [$value['feed_stories_user_id']]);
    		$getShop = $Shop->getUserShop($value['feed_stories_user_id']);
    	}

    	if($value['feed_stories_action'] == 'new_ads'){

    		$getAds = $Ads->getAll(["query"=>"ads_id IN(".implode(',',$data['ids']).") and ads_status='1' and clients_status IN(0,1) and ads_period_publication > now()"]);

    		if($getAds['count']){

    			foreach ($getAds['all'] as $ad_value) {

    				$linkImages = [];

    				$getShop = $Shop->getUserShop($ad_value['clients_id']);

			    	$images = $Ads->getImages($ad_value["ads_images"]);
			    	if($images){
			    		foreach ($images as $img) {
			    			$linkImages[] = Exists($config["media"]["small_image_ads"],$img,$config["media"]["no_image"]);
			    		}
			    	}

    				$params[] = [
						"id" => $ad_value['ads_id'],
						"title" => $ad_value['ads_title'],
						"price" => apiOutPrice(['data'=>$ad_value, 'shop'=>$getShop]),
						"images" => $linkImages ?: [$config["urlPath"].'/'.$config["media"]["no_image"]],
						"text" => $ad_value['ads_text'],
						"city_name" => $ad_value['city_name'],
						"city_area" => apiOutAdAddressArea($ad_value),
						"count_view" => $Ads->getCountView($ad_value['ads_id']),
						"user" => apiArrayDataUser($ad_value),										
					];

    			}

				$results[] = [
					"action" => $value['feed_stories_action'],
					"title" => 'Новые объявления',
					"date" => datetime_format($value["feed_stories_timestamp"], false),
					"ads" => $params,		
				];

    		}

    	}elseif($value['feed_stories_action'] == 'new_stories'){

    		$getStories = getAll("select * from uni_clients_stories_media where clients_stories_media_id IN(".implode(',',$data['ids']).") order by clients_stories_media_timestamp desc");

    		if($getStories){

    			$getUser = findOne('uni_clients', 'clients_id=?', [$data['user_id']]);

    			foreach ($getStories as $story_value) {

    				if($story_value['clients_stories_media_type'] == 'image'){
    					if(file_exists($config['basePath'].'/'.$config['media']['user_stories'].'/'.$story_value['clients_stories_media_name'])){
		    				$params[] = [
								"url" => $config['urlPath'].'/'.$config['media']['user_stories'].'/'.$story_value['clients_stories_media_name'],		
								"type" => $story_value['clients_stories_media_type'],							
							];
						}
    				}else{
    					if(file_exists($config['basePath'].'/'.$config['media']['user_stories'].'/'.$story_value['clients_stories_media_preview'])){
		    				$params[] = [
								"url" => $config['urlPath'].'/'.$config['media']['user_stories'].'/'.$story_value['clients_stories_media_preview'],		
								"type" => $story_value['clients_stories_media_type'],							
							];
						}
    				}

    			}

    			if($params){
					$results[] = [
						"action" => $value['feed_stories_action'],
						"title" => $Profile->name($getUser),
						"date" => datetime_format($value["feed_stories_timestamp"], false),
						"stories" => $params,		
						"user" => apiArrayDataUser($getUser),					
					];
				}

    		}
    		
    	}elseif($value['feed_stories_action'] == 'new_reviews'){
    		
    		$getReviews = getAll("select * from uni_clients_reviews where clients_reviews_id IN(".implode(',',$data['ids']).") and clients_reviews_status=?",[1]);

    		if($getReviews){

    			foreach ($getReviews as $review_value) {

    				$getAd = findOne('uni_ads', 'ads_id=?', [$review_value['clients_reviews_id_ad']]);
    				$getUser = findOne('uni_clients', 'clients_id=?', [$review_value['clients_reviews_from_id_user']]);

    				$params[] = [
						"id" => $review_value['clients_reviews_id'],
						"ad_title" => $getAd['ads_title'],
						"text" => $review_value['clients_reviews_text'],	
						"rating" => $review_value['clients_reviews_rating'],
						"user" => apiArrayDataUser($getUser),															
					];

    			}

				$results[] = [
					"action" => $value['feed_stories_action'],
					"title" => 'Отзывы пользователей',
					"date" => datetime_format($value["feed_stories_timestamp"], false),
					"reviews" => $params,		
				];

			}

    	}elseif($value['feed_stories_action'] == 'services_ad'){

    		$getAd = $Ads->get("ads_id=? and ads_status=? and clients_status IN(0,1) and ads_period_publication > now()", [$data['id'],1]);

    		if($getAd){

				$linkImages = [];

				$getShop = $Shop->getUserShop($getAd['clients_id']);

		    	$images = $Ads->getImages($getAd["ads_images"]);
		    	if($images){
		    		foreach ($images as $img) {
		    			$linkImages[] = Exists($config["media"]["small_image_ads"],$img,$config["media"]["no_image"]);
		    		}
		    	}

				$results[] = [
					"action" => $value['feed_stories_action'],
					"title" => $getAd['ads_title'],
					"date" => datetime_format($value["feed_stories_timestamp"], false),
					"ad" => [
							"id" => $getAd['ads_id'],
							"title" => $getAd['ads_title'],
							"price" => apiOutPrice(['data'=>$getAd, 'shop'=>$getShop]),
							"images" => $linkImages ?: [$config["urlPath"].'/'.$config["media"]["no_image"]],
							"text" => $getAd['ads_text'],
							"city_name" => $getAd['city_name'],
							"city_area" => apiOutAdAddressArea($getAd),
							"count_view" => $Ads->getCountView($getAd['ads_id']),
							"user" => apiArrayDataUser($getAd),										
					],		
				];

    		}


    	}


	}
}

echo json_encode(['data'=>$results, 'count'=>$totalCountFeed, 'pages'=>getCountPage($totalCountFeed,$settings['feed_stories_output'])]);

?>