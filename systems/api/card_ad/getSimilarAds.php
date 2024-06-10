<?php

$id = (int)$_GET['id'];

$results = [];
$output = 20;

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

$getAd = $Ads->get('ads_id=?', [$id]);

if(!$getAd){
	http_response_code(500); exit('Ad not found');
}

$getTariff = $Profile->getOrderTariff($getAd["ads_id_user"]);

if($getTariff['services']['hiding_competitors_ads']){
    $getAds = $Ads->getAll(["query" => "ads_id_cat IN(".idsBuildJoin($CategoryBoard->idsBuild($getAd["ads_id_cat"],$CategoryBoard->getCategories()),$getAd["ads_id_cat"]).") and clients_status IN(0,1) and ads_status='1' and ads_period_publication > now() and ads_id!='".$getAd['ads_id']."' and ads_id_user='".$getAd["ads_id_user"]."' order by ads_sorting desc limit 15", "output" => $output]);
    $title = apiLangContent('Другие объявления продавца');
}else{
	$getAds = $Ads->getAll(["query" => "ads_id_cat IN(".idsBuildJoin($CategoryBoard->idsBuild($getAd["ads_id_cat"],$CategoryBoard->getCategories()),$getAd["ads_id_cat"]).") and clients_status IN(0,1) and ads_status='1' and ads_period_publication > now() and ads_id!='".$getAd['ads_id']."' order by ads_sorting desc limit $output", "output" => $output]);
    $title = apiLangContent('Похожие объявления');
}

echo json_encode(['data'=>apiArrayDataAds($getAds) ?: null, 'title'=>$title]);
?>