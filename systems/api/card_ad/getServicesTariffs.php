<?php

$idAd = (int)$_GET['id'];
$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

$results = [];
$active_services_ids = [];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$available_services_ids = $Ads->getAvailableServiceIds($idAd);

if($available_services_ids){
    $getServices = getAll("SELECT * FROM uni_services_ads WHERE services_ads_uid IN(".implode(",", $available_services_ids).") order by services_ads_recommended desc, services_ads_id_position asc", []);
}

if($getServices){
  foreach ($getServices as $value) {
    
		$results[] = [
			"id" => $value['services_ads_uid'],
			"name" => $ULang->tApp( $value["services_ads_name"], [ "table"=>"uni_services_ads", "field"=>"services_ads_name" ] ),
			"image" => Exists($config["media"]["other"],$value['services_ads_image'],$config["media"]["no_image"]),
			"price" => $value["services_ads_new_price"] ? ['now'=>$value["services_ads_new_price"], 'old'=>$value['services_ads_price']] : ['now'=>$value["services_ads_price"], 'old'=>0],
			"text" => $ULang->tApp( $value['services_ads_text'], [ "table"=>"uni_services_ads", "field"=>"services_ads_text" ] ),
			"variant" => $value['services_ads_variant'] == 1 ? 'fix' : 'change',
			"count_day" => apiLangContent('Действует').' '.$value['services_ads_count_day'].' '.ending($value['services_ads_count_day'], apiLangContent('день'), apiLangContent('дня'), apiLangContent('дней')),
			"status_active" => in_array($value['services_ads_uid'], $available_services_ids) ? false : true,
			"recommended" => $value['services_ads_recommended'] ? true : false,
			"onetime" => $value['services_ads_onetime'] ? true : false,
		];  	

  }
}


echo json_encode($results);

?>