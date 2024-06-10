<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);
$page = (int)$_GET["page"];
$sorting = clear($_GET["sorting"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$results = [];

$output = 10;

if($sorting == 'active'){
	$query = "ads_id_user='".$idUser."' and ads_status='1' and ads_period_publication > now()";
}elseif($sorting == 'sold'){
	$query = "ads_id_user='".$idUser."' and ads_status IN(5,4)";
}elseif($sorting == 'archive'){
	$query = "ads_id_user='".$idUser."' and (ads_status NOT IN(1,5,4) or ads_period_publication < now()) and ads_status!=8";
}else{
	$query = "ads_id_user='".$idUser."' and ads_status='1' and ads_period_publication > now()";
}


$totalCountAds = (int)getOne("SELECT count(*) as total FROM `uni_ads` INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user where $query")["total"];

$getAds = $Ads->getAll(["navigation"=>true,"page"=>$page,"output"=>$output,"query"=>$query, "sort"=>"ORDER By ads_datetime_add DESC"]);

if($getAds['count']){
	foreach ($getAds['all'] as $key => $value) {

    	$link_images = [];

    	$images = $Ads->getImages($value["ads_images"]);
    	$getShop = $Shop->getUserShop($value["ads_id_user"]);

    	if($images){
    		foreach ($images as $img) {
    			$link_images[] = Exists($config["media"]["small_image_ads"],$img,$config["media"]["no_image"]);
    		}
    	}

		$results[] = [
			"ads_id" => $value['ads_id'],
			"ads_status" => $value['ads_status'],
			"ads_status_name" => apiPublicationAndStatus($value),
			"ads_title" => $value['ads_title'],
			"ads_text" => $value['ads_text'],
			"ads_price" => apiOutPrice(['data'=>$value, 'shop'=>$getShop]),
			"city_name" => $value['city_name'],
			"city_area" => apiOutAdAddressArea($value),
			"count_view" => $Ads->getCountView($value['ads_id']),
			"ads_images" => $link_images,
			"ads_datetime_add" => datetime_format($value["ads_datetime_add"], false),
			"link" => $Ads->alias($value),
			"user" => [
				"name" => $value['clients_name'],
				"surname" => $value['clients_surname'],
				"avatar" => Exists($config["media"]["avatar"],$value['clients_avatar'],$config["media"]["no_avatar"]),
				"rating" => $Profile->ratingBalls($value['clients_id']),
				"reviews" => $countReviews,
				"id_hash" => $value['clients_id_hash'],
				"status" => $value['clients_status'],
				"balance" => $value['clients_balance'],
			],
		];
	}
}

echo json_encode(['data'=>$results, 'count'=>$totalCountAds, 'pages'=>getCountPage($totalCountAds,$output)]);

?>