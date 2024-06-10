<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$results = [];

$getFavorites = getAll("select * from uni_favorites where favorites_from_id_user=? order by favorites_id desc", [$idUser]);

if(count($getFavorites)){
	 foreach ($getFavorites as $key => $value) {
	  	$getAd = $Ads->get("ads_id=?", [$value['favorites_id_ad']]);
	  	if($getAd){
	  		$images = $Ads->getImages($getAd["ads_images"]);
	  		$results[] = ['favorite_id'=>$value['favorites_id'],'ad_id'=>$getAd['ads_id'],'ad_title'=>$getAd['ads_title'],'ad_image'=>Exists($config["media"]["big_image_ads"],$images[0],$config["media"]["no_image"]),'ad_status'=>$getAd['ads_status'],'ad_status_name'=>apiPublicationAndStatus($getAd)];
	  	}
	 }
}

echo json_encode($results);

?>