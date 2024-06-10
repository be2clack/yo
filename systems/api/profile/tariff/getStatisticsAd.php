<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$ad_id = (int)$_GET["ad_id"];
$date_start = $_GET["date_start"];
$date_end = $_GET["date_end"];

$results = [];
$active_users = [];
$history = [];
$dates = [];

$getUsers = appUsersActionStatistics($idUser); 

if($getUsers){
  foreach($getUsers AS $from_user_id => $value){

  	  $get = getAll('select * from uni_action_statistics where action_statistics_from_user_id=? and action_statistics_to_user_id=?', [$value["clients_id"],$idUser]);
  	  if($get){
  	  	foreach ($get as $item) {
  	  		$getAd = findOne("uni_ads", "ads_id=?", [$item['action_statistics_ad_id']]);
            if($item['action_statistics_action'] == 'favorite'){
                $action = apiLangContent('Добавил в избранное');
            }elseif($item['action_statistics_action'] == 'show_phone'){
                $action = apiLangContent('Просмотрел телефон');
            }elseif($item['action_statistics_action'] == 'ad_sell'){
                $action = apiLangContent('Купил');
            }elseif($item['action_statistics_action'] == 'add_to_cart'){
                $action = apiLangContent('Добавил в корзину');
            }
  	  		$history[] = ["title"=>$getAd['ads_title'], "action"=>$action];
  	  	}
  	  }

  	  $active_users[] = ["avatar"=>$Profile->userAvatar($value), "name"=>$value['clients_name'], "user_id"=>$value["clients_id"], "history"=>$history ?: null];                                         
  } 
} 

$dates = [
	"display" => apiDataActionStatistics('display', $ad_id, $date_start, $date_end, $idUser),
	"view" => apiDataActionStatistics('view', $ad_id, $date_start, $date_end, $idUser),
	"favorites" => apiDataActionStatistics('favorites', $ad_id, $date_start, $date_end, $idUser),
	"show_phone" => apiDataActionStatistics('show_phone', $ad_id, $date_start, $date_end, $idUser),
	"ad_sell" => apiDataActionStatistics('ad_sell', $ad_id, $date_start, $date_end, $idUser),
	"cart" => apiDataActionStatistics('cart', $ad_id, $date_start, $date_end, $idUser),
	"booking" => apiDataActionStatistics('booking', $ad_id, $date_start, $date_end, $idUser),
];

$results = [
	"items" => [
		["name"=>apiLangContent("Показы"), "code"=>"display"],
		["name"=>apiLangContent("Просмотры"), "code"=>"view"],
		["name"=>apiLangContent("Добавили в избранное"), "code"=>"favorites"],
		["name"=>apiLangContent("Просмотрели телефон"), "code"=>"show_phone"],
		["name"=>apiLangContent("Продаж"), "code"=>"ad_sell"],
		["name"=>apiLangContent("Добавили в корзину"), "code"=>"cart"],
		["name"=>apiLangContent("Бронировали/Арендовали"), "code"=>"booking"],
	],
	"active_users" => $active_users ?: null,
	"dates" => $dates,
];

echo json_encode($results);

?>