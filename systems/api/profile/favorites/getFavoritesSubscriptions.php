<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$favorites = [];
$viewed = [];
$subscriptions = [];

$getFavorites = getAll("select * from uni_favorites where favorites_from_id_user=? order by favorites_id desc", [$idUser]);

if(count($getFavorites)){
	 foreach ($getFavorites as $key => $value) {
	  	$getAd = $Ads->get("ads_id=?", [$value['favorites_id_ad']]);
	  	if($getAd){
	  		$favorites[] = apiArrayDataAd($getAd,$idUser);
	  	}
	 }
}

$getViewAds = getAll("select * from uni_ads_views_user where user_id=?", [$idUser]);

if($getViewAds){

	$viewAds = [];

	foreach ($getViewAds as $key => $value) {
		$viewAds[] = $value["ads_id"];
	}

	$getAds = $Ads->getAll(["query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id IN(".implode(",", $viewAds).")"]);

    $viewed = apiArrayDataAds($getAds,$idUser); 

}

$getSubscriptions = getAll("select * from uni_clients_subscriptions where clients_subscriptions_id_user_from=? order by clients_subscriptions_id desc", [$idUser]);

if(count($getSubscriptions)){
	 foreach ($getSubscriptions as $key => $value) {
	 	$getUser = findOne('uni_clients','clients_id=?', [$value['clients_subscriptions_id_user_to']]);
	 	if($getUser){
	  		$subscriptions[] = ['id'=>$value['clients_subscriptions_id'],'user_id'=>$getUser['clients_id'],'name'=>$Profile->name($getUser),'avatar'=>$Profile->userAvatar($getUser)];
	  	}
	 }
}

echo json_encode(["favorites"=>$favorites ?: null, "viewed"=>$viewed ?: null, "subscriptions"=>$subscriptions ?: null]);

?>