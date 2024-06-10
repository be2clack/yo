<?php
$id = clear($_GET['id']);
$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

$results = [];
$sliders = [];
$pages = [];
$activity_shop = false;
$status_note = "";

$getShop = findOne('uni_clients_shops', 'clients_shops_id=? or clients_shops_id_hash=?', [$id,$id]);

if($getShop){

$getUser = findOne('uni_clients', 'clients_id=?', [$getShop["clients_shops_id_user"]]);

if($idUser && $tokenAuth){
	if(checkTokenAuth($tokenAuth, $idUser) == true){
		if($idUser == $getShop['clients_shops_id_user']){
			$owner = true;
		}
	}
}


$getSliders = getAll("select * from uni_clients_shops_slider where clients_shops_slider_id_shop=?", [$getShop["clients_shops_id"]]);

if(count($getSliders)){
	foreach ($getSliders as $slider) {
		if(file_exists($config["basePath"] . "/" . $config["media"]["user_attach"] . "/" . $slider["clients_shops_slider_image"])){
			$sliders[] = $config["urlPath"] . "/" . $config["media"]["user_attach"] . "/" . $slider["clients_shops_slider_image"];
		}
	}
}

$getPages = getAll('select * from uni_clients_shops_page where clients_shops_page_id_shop=? and clients_shops_page_status=?', [$getShop["clients_shops_id"],1]);

if(count($getPages)){
	foreach ($getPages as $page) {
		$pages[] = [
			"id"=>$page['clients_shops_page_id'],
			"name"=>$page['clients_shops_page_name'],
			"text"=>html_entity_decode(strip_tags($page['clients_shops_page_text'])),
		];
	}
}

$getCountAds = $Ads->getCount("ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_user='".$getShop["clients_shops_id_user"]."'");
$getSubscribers = (int)getOne("select count(*) as total from uni_clients_subscriptions where clients_subscriptions_id_user_to=?", [$getShop["clients_shops_id_user"]])["total"];
$getInSubscribe = findOne('uni_clients_subscriptions', 'clients_subscriptions_id_user_from=? and clients_subscriptions_id_user_to=?', [$idUser,$getShop["clients_shops_id_user"]]);

if($getUser["clients_status"] == 1){

  if(strtotime($getShop["clients_shops_time_validity"]) > time() || !$getShop["clients_shops_time_validity"] || $getShop["clients_shops_time_validity"] == null){

     $activity_shop = true;

  }

}

if($getShop["clients_shops_status"] == 0){
	$status_note = apiLangContent("Магазин на модерации");
}elseif($getShop["clients_shops_status"] == 2){
    $status_note = apiLangContent("Магазин отклонен по причине: ") . ' ' . $getShop["clients_shops_status_note"];
}

$results = [
	"owner"=>$owner ? true : false,
	"id" => $getShop['clients_shops_id'],
	"title" => html_entity_decode($getShop['clients_shops_title']),
	"desc" => $getShop['clients_shops_desc'] ? html_entity_decode($getShop['clients_shops_desc']) : null,
	"logo" => Exists($config["media"]["other"], $getShop["clients_shops_logo"], $config["media"]["no_image"]),
	"count_ads" => $getCountAds .' '.ending($getCountAds, apiLangContent('объявление'), apiLangContent('объявления'), apiLangContent('объявлений')),
	"sliders" => $sliders ?: null,
	"pages" => $pages ?: null,
	"subscribers_count" => $getSubscribers,
	"in_subscribers" => $getInSubscribe ? true : false,
    "user" => apiArrayDataUser($getUser, true),
    "activity_shop" => $activity_shop,
    "status" => (int)$getShop['clients_shops_status'],
    "status_note" => $status_note,
];

}

echo json_encode(["data"=>$results?:null]);

?>