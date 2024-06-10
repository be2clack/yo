<?php

$id = (int)$_GET['id'];
$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);
$ip = clear($_GET["ip"]);

$results = [];
$link_images = [];
$services = [];
$active_services_ids = [];
$reviews = [];
$order = [];
$auctionBettingUsers = [];
$auctionUserWinner = [];

$getAd = $Ads->get('ads_id=?', [$id]);

if(!$getAd){
	http_response_code(500); exit('Ad not found');
}

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

if($idUser && $tokenAuth){
	if(checkTokenAuth($tokenAuth, $idUser) == true){
		if($idUser == $getAd['ads_id_user']){
			$owner = true;
		}
	}
}

// Фиксация просмотров объявления

apiViewAds($getAd['ads_id'], $getAd['ads_id_user'], $ip);
$Profile->addUserInterest($getAd['ads_id_cat'],$idUser);

$Ads->viewAdsUser($getAd['ads_id'],$idUser);

$images = $Ads->getImages($getAd["ads_images"]);
$countReviews = (int)getOne("select count(*) as total from uni_clients_reviews where clients_reviews_id_user=?", [$getAd["ads_id_user"]])["total"];
$getShop = $Shop->getUserShop($getAd["ads_id_user"]);

if($images){
	foreach ($images as $img) {
		$link_images[] = Exists($config["media"]["big_image_ads"],$img,$config["media"]["no_image"]);
	}
}


// Блоки с информацией

if($getShop){
	$informers[] = ['id'=>'shop', 'image'=>$settings["path_other"].'/shop_icon.png', 'title'=>apiLangContent('Онлайн-магазин'), 'desc'=>apiLangContent('Персональный магазин пользователя'),'shop_id'=>$getShop['clients_shops_id']];
}

if($Ads->getStatusSecure($getAd)){
	$informers[] = ['id'=>'secure','image'=>$settings["path_other"].'/icon_shield.png','title'=>apiLangContent('Безопасная сделка'), 'desc' => apiLangContent('Безопасная оплата с гарантией возврата'), 'tooltip' => ['title'=>apiLangContent('Покупайте безопасно!'), 'text'=>apiLangContent('Наш сервис зарезервирует деньги. Продавец получит оплату только после того как вы подтвердите получение товара.')]];
}

if($getAd["ads_booking"] && $settings["booking_status"]){
	if($getAd["category_board_booking_variant"] == 0){
		$informers[] = ['id'=>'booking','image'=>$settings["path_other"].'/schedule_icon.png','title'=>apiLangContent('Бронирование'), 'desc' => apiLangContent('Можно забронировать онлайн')];
	}else{
		$informers[] = ['id'=>'booking','image'=>$settings["path_other"].'/schedule_icon.png','title'=>apiLangContent('Аренда'), 'desc' => apiLangContent('Можно взять в аренду онлайн')];
	}
}

if($getAd['ads_online_view']){
	$informers[] = ['id'=>'online_view','image'=>$settings["path_other"].'/camera_icon.png','title'=>apiLangContent('Онлайн-показ'), 'desc' => apiLangContent('Можно посмотреть по видеосвязи'), 'tooltip' => ['title'=>apiLangContent('Как проходит онлайн-показ'), 'text'=>apiLangContent('Продавец проведёт показ по видеосвязи: покажет все детали и ответит на вопросы. Договоритесь о времени и приложении, в котором будет удобно пообщаться.')]];
}

if($Ads->getStatusDelivery($getAd)){
	$informers[] = ['id'=>'delivery','image'=>$settings["path_other"].'/shipping_icon.png','title'=>apiLangContent('Доставка'), 'desc' => apiLangContent('Возможна доставка товара')];
}

// Подключенные услуги

if($owner){

    $getServices = getAll("select * from uni_services_order INNER JOIN `uni_services_ads` ON `uni_services_ads`.services_ads_uid = `uni_services_order`.services_order_id_service where services_order_id_ads=? and services_order_time_validity > now() and services_order_status=?", [$getAd["ads_id"],1]);

    if(count($getServices)){
    	foreach ($getServices as $value) {

    		$arrTime = [];
    		$strTime = "";
    		$diffDates = $Ads->dateDiff($value["services_order_time_validity"]);
         $progress = ((time() - strtotime($value["services_order_time_create"])) / (strtotime($value["services_order_time_validity"]) - strtotime($value["services_order_time_create"]))) * 10;

          	if($diffDates["day"]){ 

               $arrTime[] = $diffDates["day"] . ' ' . ending($diffDates["day"], apiLangContent("день"), apiLangContent("дня"), apiLangContent("дней"));

               if($diffDates["hour"]){
                  $arrTime[] = $diffDates["hour"] . ' ' . ending($diffDates["hour"], apiLangContent("час"), apiLangContent("часа"), apiLangContent("часов"));
               }

               $strTime = implode(" ", $arrTime);

          	}else{

	             if($diffDates["hour"]){

	                 $arrTime[] = $diffDates["hour"] . ' ' . ending($diffDates["hour"], apiLangContent("час"), apiLangContent("часа"), apiLangContent("часов"));

	                 if($diffDates["min"]){
	                    $arrTime[] = $diffDates["min"] . ' ' . ending($diffDates["min"], apiLangContent("минута"), apiLangContent("минуты"), apiLangContent("минут"));
	                 }

	                 $strTime = implode(" ", $arrTime);  

	             }else{

	                 $strTime = $diffDates["min"] . ' ' . ending($diffDates["min"], apiLangContent("минута"), apiLangContent("минуты"), apiLangContent("минут"));

	             }

          	}

            if($progress >= 100 || $progress >= 10){
            	$progressLine = '1.0';
            }else{
            	$progressLine = '0.' . str_replace('.', '' , $progress);
            }

    		$services[] = [
    			'name' => $value['services_ads_name'],
    			'progress' => $progressLine,
    			'date' => $strTime,
    		];

    	}
    }

}


// В избранном у пользователя

$getUserFavorite = findOne('uni_favorites', 'favorites_id_ad=? and favorites_from_id_user=?', [$id,$idUser]);

// Активные услуги

$available_services_ids = $Ads->getAvailableServiceIds($id);

// Отзывы

$getReviews = getAll('select * from uni_clients_reviews where clients_reviews_id_user=? and clients_reviews_id_ad=? and clients_reviews_status=? order by rand() limit 2', [$getAd["ads_id_user"],$id,1]);

if(count($getReviews)){
	foreach ($getReviews as $value) {
    	$reviews[] = apiArrayDataReviews($value);
	}
}	

$getOrder = $Main->getSecureAdOrder('secure_ads_ad_id=? and secure_status NOT IN(3,5) and secure_id_user_buyer=?', [$id,$idUser]);
if($getOrder){
		$order = [
			"id"=>$getOrder["secure_id_order"],
			"date_create"=>$getOrder["secure_date"],
		];
}

$getBettingUsers = getAll("select * from uni_ads_auction INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads_auction`.ads_auction_id_user where ads_auction_id_ad=? order by ads_auction_id desc", [$id]);
if($getBettingUsers){
	foreach ($getBettingUsers as $key => $value) {
		$auctionBettingUsers[] = ['id'=>$value['clients_id'], 'avatar'=> $Profile->userAvatar($value, false), 'name'=>$Profile->name($value, false), 'price'=>apiPrice($value['ads_auction_price'])];
	}
}

if(time() >= strtotime($getAd["ads_auction_duration"])){
	$seconds_completed = 0;
	$getAuctionUserWinner = $Ads->getAuctionWinner($id);
	if($getAuctionUserWinner){
		$auctionUserWinner = ['id'=>$getAuctionUserWinner['clients_id'],'avatar'=>$Profile->userAvatar($getAuctionUserWinner, false),'name'=>$Profile->name($getAuctionUserWinner, false)];
	}
}else{
	$seconds_completed = abs(time() - strtotime($getAd["ads_auction_duration"]));
}

if($getAd['ads_video']){
	$videoLink = explode("//", $getAd['ads_video']);
	$getAd['ads_video'] = "https://".$videoLink[1];
}

// Статус аренды и бронирования

if($Ads->getStatusBooking($getAd)){

	 if($getAd["category_board_booking_variant"] == 0){
	 	 $bookingStatus = true;
	 }else{
		 if(!$getAd["ads_booking_available_unlimitedly"]){ 
		     if($Ads->adCountActiveRent($getAd["ads_id"]) >= $getAd["ads_booking_available"]){
		         $bookingStatus = false; 
		     }else{
		     		$bookingStatus = true;
		     }
		 }else{
		 	 $bookingStatus = true;
		 }
	 }

}else{
	$bookingStatus = false;
}

if($getAd['ads_auction_price_sell']){
	if($getAd['ads_price'] >= $getAd['ads_auction_price_sell']){
		$statusAcceptBet = false;
	}else{
		$statusAcceptBet = true;
	}
}else{
	$statusAcceptBet = true;
}

$results = [
	"owner"=>$owner ? true : false,
	"status" => $getAd['ads_status'],
	"status_note" => $getAd['ads_note'] ?: null,
	"ads_id" => $getAd['ads_id'],
	"ads_title" => $getAd['ads_title'],
	"link" => $Ads->alias($getAd),
	"video_link" => $getAd['ads_video'] ?: null,
	"ads_price" => apiOutPrice(['data'=>$getAd, 'shop'=>$getShop, 'abbreviation_million'=>true]),
	"price_currency" => $getAd['ads_price'] ? apiAdOutCurrency($getAd['ads_price'], $getAd['ads_currency']) : [],
	"city_name" => $getAd['city_name'],
	"latitude" => $getAd['ads_latitude'] ? $getAd['ads_latitude'] : '',
	"longitude" => $getAd['ads_longitude'] ? $getAd['ads_longitude'] : '',
	"ads_text" => $getAd['ads_text'],
	"address" => html_entity_decode(apiOutAdAddress($getAd)),
	"city_area" => apiOutAdAddressArea($getAd),
	"ads_images" => $link_images,
	"count_images" => count($link_images),
	"count_view" => $getAd['ads_count_view'],
	"ads_online_view" => $getAd['ads_online_view'],
	"ads_datetime_add" => datetime_format($getAd["ads_datetime_add"], false),
	"params" => $Filters->outProductPropArray($getAd["ads_id"], $getAd["ads_id_cat"], $getCategoryBoard),
	"informers" => $informers ?: null,
	"services" => $services ?: null,
	"in_favorites" => $getUserFavorite ? true : false,
	"button_added_services_tariffs" => $available_services_ids ? true : false,
	"reviews" => $reviews ? $reviews : null,
	"category_paid_price" => apiPrice($getAd["category_board_price"]),
	"category_paid_count_free" => $getAd['category_board_count_free'],
	"secure_status" => $Ads->getStatusSecure($getAd),
	"secure_order" => $order ?: null,
	"auction" => [
		"status" => $getAd['ads_auction'] ? true : false,
		"duration" => date('Y-m-d H:i:s', strtotime($getAd['ads_auction_duration'])),
		"completed" => time() > strtotime($getAd['ads_auction_duration']) ? true : false, 
		"seconds_completed" => $seconds_completed,
		"price_sell" => $getAd['ads_auction_price_sell'] ? apiPrice($getAd['ads_auction_price_sell']) : null,
		"price_sell_secure" => $Ads->getStatusSecure($getAd,$getAd['ads_auction_price_sell']),
		"day" => $getAd['ads_auction_day'],
		"betting_users" => $auctionBettingUsers ?: null,
		"winner" => $auctionUserWinner ?: null,
		"status_accept_bet" => $statusAcceptBet ? true : false,
	],
	"booking" => [
		"status" => $bookingStatus,
		"variant" => (int)$getAd["category_board_booking_variant"],
		"prepayment" => (int)$getAd["ads_booking_prepayment_percent"],
	],
	"delivery_status" => $Ads->getStatusDelivery($getAd),
	"user" => apiArrayDataUser($getAd, true),
];


echo json_encode($results);

?>