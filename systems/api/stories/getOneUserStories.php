<?php

$idUser = (int)$_GET["id_user"];
$idUserAuth = (int)$_GET["id_user_auth"];

$city_id = (int)$_GET["city_id"];
$region_id = (int)$_GET["region_id"];
$country_id = (int)$_GET["country_id"];
$cat_id = (int)$_GET["cat_id"];

$stories = [];
$userShop = [];

$getUser = findOne('uni_clients', 'clients_id=?', [$idUser]);

$getShop = $Shop->getShop(['user_id'=>$getUser["clients_id"],'conditions'=>true]);

if($getShop){
    $userShop = ['id'=>$getShop['clients_shops_id']];
}

$getStoriesMedia = getAll('select * from uni_clients_stories_media where clients_stories_media_user_id=? and clients_stories_media_loaded=? and clients_stories_media_status=? order by clients_stories_media_id desc', [$idUser,1,1]);

if($getStoriesMedia && $getUser){

	foreach ($getStoriesMedia as $value) {

        if($value['clients_stories_media_type'] == 'image'){
            if(file_exists($config['basePath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_name']) && $value['clients_stories_media_name']){
                $imageStory = $config['urlPath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_name'];
            }
        }else{
            if(file_exists($config['basePath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_preview']) && $value['clients_stories_media_preview']){
                $imageStory = $config['urlPath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_preview'];
            }
        }

		if($value['clients_stories_media_ad_id']){
			$getAd = findOne("uni_ads","ads_id=?", [$value['clients_stories_media_ad_id']]);
			if($getAd){
				$getShop = $Shop->getUserShop($getAd["ads_id_user"]);
				$images = $Ads->getImages($getAd["ads_images"]);
				$stories = ['id'=>$value['clients_stories_media_id'], 'status'=>$value['clients_stories_media_status'], 'url'=>$config['urlPath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_name'],'duration'=>$value['clients_stories_media_duration'],'type'=>$value['clients_stories_media_type'],'count_view'=>$Profile->countViewStories($value['clients_stories_media_id']).' '.ending($Profile->countViewStories($value['clients_stories_media_id']), apiLangContent('просмотр'), apiLangContent('просмотра'), apiLangContent('просмотров')),'ad'=>['id'=>$getAd['ads_id'], 'title'=>$getAd['ads_title'],'image'=>Exists($config["media"]["small_image_ads"],$images[0],$config["media"]["no_image"]), 'price'=>apiOutPrice(['data'=>$getAd, 'shop'=>$getShop, 'abbreviation_million'=>true])],'image'=>$imageStory ? $imageStory : $Profile->userAvatar($getUser)];
			}
		}else{
			$stories = ['id'=>$value['clients_stories_media_id'], 'status'=>$value['clients_stories_media_status'], 'url'=>$config['urlPath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_name'],'duration'=>$value['clients_stories_media_duration'],'type'=>$value['clients_stories_media_type'],'count_view'=>$Profile->countViewStories($value['clients_stories_media_id']).' '.ending($Profile->countViewStories($value['clients_stories_media_id']), apiLangContent('просмотр'), apiLangContent('просмотра'), apiLangContent('просмотров')),'image'=>$imageStory ? $imageStory : $Profile->userAvatar($getUser)];
		}

		$results['users'][] = ['id'=>$getUser['clients_id'],'name'=>$Profile->name($getUser),'avatar'=>$Profile->userAvatar($getUser),'stories'=>$stories, 'timestamp'=>strtotime($value['clients_stories_media_timestamp']), "shop" => $userShop ?: null];

	}

}


echo json_encode(['users'=>$results['users'] ?: null]);

?>