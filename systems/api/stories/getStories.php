<?php

$idUser = (int)$_GET["id_user"];
$idUserAuth = (int)$_GET["id_user_auth"];

$city_id = (int)$_GET["city_id"];
$region_id = (int)$_GET["region_id"];
$country_id = (int)$_GET["country_id"];
$cat_id = (int)$_GET["cat_id"];

$results = [];
$stories = [];
$userShop = [];
$categories = [];
$categoriesList = [];
$query = [];

$CategoryBoard = new CategoryBoard();
$getCategories = $CategoryBoard->getCategories("where category_board_visible=1");

if($idUser){
	$getUserStories = getAll('select * from uni_clients_stories where clients_stories_user_id=?', [$idUser]);
}else{
	$getUserStories = getAll('select * from uni_clients_stories order by clients_stories_timestamp desc');
}

if($cat_id){
    $ids_cat = idsBuildJoin($CategoryBoard->idsBuild($cat_id, $getCategories), $cat_id);
    $query["cat"] = "clients_stories_media_cat_id IN(".$ids_cat.")";
}

if($city_id){
    $query["city"] = "(clients_stories_media_city_id='".$city_id."' or (clients_stories_media_city_id=0 and clients_stories_media_region_id=0 and clients_stories_media_country_id=0))";
}elseif($region_id){
    $query["region"] = "(clients_stories_media_region_id='".$region_id."' or (clients_stories_media_city_id=0 and clients_stories_media_region_id=0 and clients_stories_media_country_id=0))";
}elseif($country_id){
    $query["country"] = "(clients_stories_media_country_id='".$country_id."' or (clients_stories_media_city_id=0 and clients_stories_media_region_id=0 and clients_stories_media_country_id=0))";
}

if(count($getUserStories)){
	foreach ($getUserStories as $value_user) {

		$stories = [];
		$userShop = [];

		$getUser = findOne('uni_clients', 'clients_id=?', [$value_user['clients_stories_user_id']]);

		$getShop = $Shop->getShop(['user_id'=>$getUser["clients_id"],'conditions'=>true]);

		if($getShop){
		    $userShop = ['id'=>$getShop['clients_shops_id']];
		}

		if($idUserAuth == $value_user['clients_stories_user_id']){
			if($cat_id){
				$getStoriesMedia = getAll('select * from uni_clients_stories_media where clients_stories_media_user_id=? and clients_stories_media_loaded=? and clients_stories_media_cat_id IN('.$ids_cat.') order by clients_stories_media_id desc', [$value_user['clients_stories_user_id'],1]);
			}else{
				$getStoriesMedia = getAll('select * from uni_clients_stories_media where clients_stories_media_user_id=? and clients_stories_media_loaded=? order by clients_stories_media_id desc', [$value_user['clients_stories_user_id'],1]);
			}
		}else{
			if($query){
				$getStoriesMedia = getAll('select * from uni_clients_stories_media where clients_stories_media_user_id=? and clients_stories_media_loaded=? and clients_stories_media_status=? and '.implode(" and ",$query).' order by clients_stories_media_id desc', [$value_user['clients_stories_user_id'],1,1]);
			}else{
				$getStoriesMedia = getAll('select * from uni_clients_stories_media where clients_stories_media_user_id=? and clients_stories_media_loaded=? and clients_stories_media_status=? order by clients_stories_media_id desc', [$value_user['clients_stories_user_id'],1,1]);
			}
		}

		if($getStoriesMedia && $getUser){

			foreach ($getStoriesMedia as $value) {

				if($value['clients_stories_media_ad_id']){
					$getAd = findOne("uni_ads","ads_id=?", [$value['clients_stories_media_ad_id']]);
					if($getAd){
						$getShop = $Shop->getUserShop($getAd["ads_id_user"]);
						$images = $Ads->getImages($getAd["ads_images"]);
						$stories[] = ['id'=>$value['clients_stories_media_id'], 'status'=>$value['clients_stories_media_status'], 'url'=>$config['urlPath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_name'],'duration'=>$value['clients_stories_media_duration'],'type'=>$value['clients_stories_media_type'],'count_view'=>$Profile->countViewStories($value['clients_stories_media_id']).' '.ending($Profile->countViewStories($value['clients_stories_media_id']), apiLangContent('просмотр'), apiLangContent('просмотра'), apiLangContent('просмотров')),'ad'=>['id'=>$getAd['ads_id'], 'title'=>$getAd['ads_title'],'image'=>Exists($config["media"]["small_image_ads"],$images[0],$config["media"]["no_image"]), 'price'=>apiOutPrice(['data'=>$getAd, 'shop'=>$getShop, 'abbreviation_million'=>true])]];
					}
				}else{
					$stories[] = ['id'=>$value['clients_stories_media_id'], 'status'=>$value['clients_stories_media_status'], 'url'=>$config['urlPath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_name'],'duration'=>$value['clients_stories_media_duration'],'type'=>$value['clients_stories_media_type'],'count_view'=>$Profile->countViewStories($value['clients_stories_media_id']).' '.ending($Profile->countViewStories($value['clients_stories_media_id']), apiLangContent('просмотр'), apiLangContent('просмотра'), apiLangContent('просмотров'))];
				}

			}

			if($stories) $results['users'][] = ['id'=>$getUser['clients_id'],'name'=>$Profile->name($getUser),'avatar'=>$Profile->userAvatar($getUser),'stories'=>$stories, 'timestamp'=>strtotime($value_user['clients_stories_timestamp']), "shop" => $userShop ?: null];

		}

	}
}

if($query["cat"]) unset($query["cat"]);

if($query){
	$getStoriesMediaCategories = getAll('select * from uni_clients_stories_media where clients_stories_media_loaded=? and clients_stories_media_status=? and clients_stories_media_cat_id!=? and '.implode(" and ",$query), [1,1,0]);
}else{
	$getStoriesMediaCategories = getAll('select * from uni_clients_stories_media where clients_stories_media_loaded=? and clients_stories_media_status=? and clients_stories_media_cat_id!=?', [1,1,0]);
}

if($getStoriesMediaCategories){
	if($cat_id){
		$categories[$cat_id] = ["name"=>$getCategories["category_board_id"][$cat_id]["category_board_name"], "id"=>$getCategories["category_board_id"][$cat_id]["category_board_id"]];
	}
	foreach ($getStoriesMediaCategories as $key => $value) {
		if($getCategories["category_board_id"][$value["clients_stories_media_cat_id"]]){
			$categories[$value["clients_stories_media_cat_id"]] = ["name"=>$getCategories["category_board_id"][$value["clients_stories_media_cat_id"]]["category_board_name"], "id"=>$getCategories["category_board_id"][$value["clients_stories_media_cat_id"]]["category_board_id"]];
		}
	}
	$categoriesList = array_values($categories);
}

echo json_encode(['users'=>$results['users'], 'categories'=>$categoriesList]);

?>