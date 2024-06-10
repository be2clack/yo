<?php
defined('unisitecms') or exit();

$data = [];
$results = [];

// Отзывы пользователей

$data = [];

$getReviews = getAll("select * from uni_clients_reviews where clients_reviews_id_parent=? and clients_reviews_status=? and clients_reviews_date >= DATE_SUB(NOW() , INTERVAL 60 MINUTE)",[0,1]);

if(count($getReviews)){
	foreach ($getReviews as $key => $value) {
	   $data['ids'][] = $value['clients_reviews_id'];
	}
	$results[] = ['feed_stories_user_id'=>0,'feed_stories_action'=>'new_reviews','feed_stories_timestamp'=>date('Y-m-d H:i:s'),'feed_stories_data'=>json_encode($data)];
}

// Новые сторисы пользователей

$data = [];

$getUserStories = getAll("select * from uni_clients_stories where clients_stories_timestamp >= DATE_SUB(NOW() , INTERVAL 60 MINUTE)");

if(count($getUserStories)){
	foreach ($getUserStories as $value) {

			$data = [];

			$getStories = getAll("select * from uni_clients_stories_media where clients_stories_media_user_id=? and clients_stories_media_loaded=? and clients_stories_media_status=? and clients_stories_media_timestamp >= DATE_SUB(NOW() , INTERVAL 60 MINUTE)", [$value['clients_stories_user_id'],1,1]);
			if($getStories){
				 $data['user_id'] = $value['clients_stories_user_id'];
				 foreach ($getStories as $story) {
				 	 $data['ids'][] = $story['clients_stories_media_id'];
				 }
				 $results[] = ['feed_stories_user_id'=>0,'feed_stories_action'=>'new_stories','feed_stories_timestamp'=>date('Y-m-d H:i:s'),'feed_stories_data'=>json_encode($data)];
			}

	}
}

// Объявления с активной услугой "Лента историй"

$getServicesFeedStory = getAll("select * from uni_services_order INNER JOIN `uni_ads` ON `uni_ads`.ads_id = `uni_services_order`.services_order_id_ads where ads_status='1' and ads_period_publication > now() and services_order_id_service IN(3,4) and services_order_status=? and services_order_time_validity > now()", [1]);

if(count($getServicesFeedStory)){
	foreach ($getServicesFeedStory as $value) {
		$getAdServicesFeed = findOne('uni_feed_stories', 'feed_stories_action=? and feed_stories_ad_id=?', ['services_ad',$value['services_order_id_ads']]);
		if($getAdServicesFeed){
			update('delete from uni_feed_stories where feed_stories_id=?', [$getAdServicesFeed['feed_stories_id']]);
		}
		$results[] = ['feed_stories_user_id'=>0,'feed_stories_action'=>'services_ad','feed_stories_timestamp'=>date('Y-m-d H:i:s'),'feed_stories_data'=>json_encode(['id'=>$value['services_order_id_ads']]),'feed_stories_ad_id'=>$value['services_order_id_ads']];
	}
}

// Перемешаем массив

shuffle($results);

// Новые объявления

$getAds = $Ads->getAll(["query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_datetime_add >= DATE_SUB(NOW() , INTERVAL 60 MINUTE)"]);

if($getAds['count']){
  foreach ($getAds['all'] as $key => $value) {
  	$data['ids'][] = $value['ads_id'];
  }
  $results[] = ['feed_stories_user_id'=>0,'feed_stories_action'=>'new_ads','feed_stories_timestamp'=>date('Y-m-d H:i:s'),'feed_stories_data'=>json_encode($data)];
}

if($results){
	 foreach ($results as $value) {
	 	  smart_insert('uni_feed_stories', $value);
	 }
}

?>