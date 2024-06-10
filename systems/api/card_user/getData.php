<?php
$idUserAuth = (int)$_GET["id_user_auth"];
$tokenAuth = clear($_GET["token"]);

$idUser = clear($_GET["id_user"]);

$getUser = $Profile->oneUser("where clients_id=? or clients_id_hash=?", [$idUser,$idUser]);

if($idUserAuth){
	if(checkTokenAuth($tokenAuth, $idUserAuth) == false){
		$idUserAuth = 0;
	}	
}

if($getUser){

	// Количество отзывов
	$countReviews = (int)getOne("select count(*) as total from uni_clients_reviews where clients_reviews_id_user=? and clients_reviews_status=?", [$getUser["clients_id"],1])["total"];
	// Количество подписчиков
	$countSubscriptions = (int)getOne("select count(*) as total from uni_clients_subscriptions where clients_subscriptions_id_user_to=?", [$getUser["clients_id"]])["total"];
	// Количество активных объявлений
	$countActiveAds = $Ads->getAll( ["query" => "ads_id_user='".$getUser["clients_id"]."' and ads_status='1' and ads_period_publication > now()", "sort" => "order by ads_id desc" ] );
	// Количество объявлений в избранных
	$getFavorites = getAll("select * from uni_favorites where favorites_from_id_user=? order by favorites_id desc", [$getUser["clients_id"]]);
	
	if($idUserAuth){
		// Проверка на подписку
		$getInSubscribe = findOne('uni_clients_subscriptions', 'clients_subscriptions_id_user_from=? and clients_subscriptions_id_user_to=?', [$idUserAuth,$getUser["clients_id"]]);
		// Заблокирован ли автор объявления у пользователя
		$getIsBlocked = findOne('uni_clients_blacklist', 'clients_blacklist_user_id = ? and clients_blacklist_user_id_locked = ?', [$idUserAuth,$getUser["clients_id"]]);
		// Заблокирован ли авторизованный пользователь у автора объявления
		$getImBlocked = findOne('uni_clients_blacklist', 'clients_blacklist_user_id = ? and clients_blacklist_user_id_locked = ?', [$getUser["clients_id"],$idUserAuth]);
	}

	// Stories пользователя

	$getStories = findOne('uni_clients_stories_media', 'clients_stories_media_user_id=? and clients_stories_media_loaded=? and clients_stories_media_status=? order by clients_stories_media_timestamp desc', [$getUser["clients_id"],1,1]);

	if($getUser['clients_status'] == 2){
		$status_note = apiLangContent('Пользователь заблокирован!');
	}elseif($getUser['clients_status'] == 3){
		$status_note = apiLangContent('Аккаунт удален!');
	}

	$results['data'] = [
		"id" => $getUser['clients_id'],
		"link" => _link( "user/" . $getUser['clients_id_hash'] ),
		"name" => $getUser['clients_name'] ?: '',
		"surname" => $getUser['clients_surname'] ?: '',
		"display_name" => $Profile->name($getUser, false),
		"avatar" => $Profile->userAvatar($getUser, false),
		"note_status" => (string)$getUser['clients_note_status'],
		"rating" => $Profile->ratingBalls($getUser['clients_id']),
		"reviews" => $countReviews,
		"subscribers_count" => $countSubscriptions,
		"count_ads" => $countActiveAds['count'],
		"status" => ['status'=>$getUser['clients_status'], 'note'=>$status_note],
		"favorites_count" => count($getFavorites),
		"mode_online" => modeOnline($getUser['clients_datetime_view']),
		"last_login_date" => api_datetime_format($getUser["clients_datetime_view"], false),
		"in_subscribers" => $getInSubscribe ? true : false,
		"is_stories" => $getStories ? true : false,
		"is_blocked" => $getIsBlocked ? true : false,
		"im_blocked" => $getImBlocked ? true : false,
		"view_phone" => $getUser['clients_view_phone'] ? true : false,
		"date" => apiLangContent('На').' '.$settings["site_name"].' '.apiLangContent('с').' '.date("d.m.Y", strtotime($getUser["clients_datetime_add"])),
		"verification_status" => $getUser["clients_verification_status"] ? true : false,
	];

	$getShop = $Shop->getUserShop($getUser["clients_id"]);

	if(count($getShop)){
		$results['shop'] = ['link'=>$Shop->linkShop($getUser["clients_shops_id_hash"]), 'title'=>$getShop['clients_shops_title'], 'text'=>$getShop['clients_shops_desc']];
	}

}

echo json_encode(['data'=>$results['data'] ?: null, 'shop'=>$results['shop'] ?: null]); 

?>