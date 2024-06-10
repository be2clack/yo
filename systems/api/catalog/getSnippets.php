<?php

$idCat = (int)$_GET["cat_id"];
$city_id = (int)$_GET["city_id"]; 
$region_id = (int)$_GET["region_id"]; 
$country_id = (int)$_GET["country_id"]; 

$price_start = $_GET["price_start"];
$price_end = $_GET["price_end"];

$vip = $_GET["vip"] ? true : false;
$online_view = $_GET["online_view"] ? true : false;
$booking = $_GET["booking"] ? true : false;
$secure = $_GET["secure"] ? true : false;
$auction = $_GET["auction"] ? true : false;

$booking_date_start = $_GET["booking_date_start"];
$booking_date_end = $_GET["booking_date_end"];

$results = [];

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

if($idCat){
	$results[] = ['name'=>$getCategoryBoard["category_board_id"][$idCat]["category_board_name"], 'code'=>'category', 'id'=>$idCat];
}else{
	$results[] = ['name'=>apiLangContent('Категории'), 'code'=>'category', 'id'=>null];
}

// if($city_id){
// 	$getCity = findOne("uni_city", "city_id=?", [$city_id]);
// 	$results[] = ['name'=>$getCity["city_name"], 'code'=>'geo', 'id'=>$city_id];
// }elseif($region_id){
// 	$getRegion = findOne("uni_region", "region_id=?", [$region_id]);
// 	$results[] = ['name'=>$getRegion["region_name"], 'code'=>'geo', 'id'=>$region_id];
// }elseif($country_id){
// 	$getCountry = findOne("uni_country", "country_id=?", [$country_id]);
// 	$results[] = ['name'=>$getCountry["country_name"], 'code'=>'geo', 'id'=>$country_id];
// }

if($price_start && $price_end){
	$results[] = ['name'=>apiLangContent('Цена от').' '.apiPrice($price_start).' '.apiLangContent('до').' '.apiPrice($price_end), 'code'=>'price'];
}elseif($price_start){
	$results[] = ['name'=>apiLangContent('Цена от').' '.apiPrice($price_start), 'code'=>'price'];
}elseif($price_end){
	$results[] = ['name'=>apiLangContent('Цена до').' '.apiPrice($price_end), 'code'=>'price'];
}


if($vip){
	$results[] = ['name'=>apiLangContent('Vip'), 'code'=>'vip'];
}

if($online_view){
	$results[] = ['name'=>apiLangContent('Онлайн-показ'), 'code'=>'online_view'];
}

if($idCat){

	if($getCategoryBoard["category_board_id"][$idCat]["category_board_booking"]){

	  if($getCategoryBoard["category_board_id"][$idCat]["category_board_booking_variant"] == 0){

	  	if($booking){
	    	$results[] = ['name'=>apiLangContent('Онлайн-бронирование'), 'code'=>'booking'];
	  	}

	  	if($booking_date_start){
				if($booking_date_start && $booking_date_end){
					$results[] = ['name'=>apiLangContent('Заселение с').' '.date("d.m.Y", strtotime($booking_date_start)).' '.apiLangContent('по').' '.date("d.m.Y", strtotime($booking_date_end)), 'code'=>'booking_date'];
				}elseif($booking_date_start){
					$results[] = ['name'=>apiLangContent('Заселение').' '.date("d.m.Y", strtotime($booking_date_start)), 'code'=>'booking_date'];
				}
			}

	  }else{

	  	if($booking){
	    	$results[] = ['name'=>apiLangContent('Онлайн-аренда'), 'code'=>'booking'];
	  	}

	  	if($booking_date_start){
				if($booking_date_start && $booking_date_end){
					$results[] = ['name'=>apiLangContent('Аренда с').' '.date("d.m.Y", strtotime($booking_date_start)).' '.apiLangContent('по').' '.date("d.m.Y", strtotime($booking_date_end)), 'code'=>'booking_date'];
				}elseif($booking_date_start){
					$results[] = ['name'=>apiLangContent('Аренда').' '.date("d.m.Y", strtotime($booking_date_start)), 'code'=>'booking_date'];
				}
			}

	  }

	}

}

if($secure){

	if($getCategoryBoard["category_board_id"][$idCat]["category_board_secure"] && $settings["secure_status"]){
		$results[] = ['name'=>apiLangContent('Безопасная сделка'), 'code'=>'secure'];
	}

}

if($auction){
	if($getCategoryBoard["category_board_id"][$idCat]["category_board_auction"]){
	  $results[] = ['name'=>apiLangContent('Аукцион'), 'code'=>'auction'];
	}
}

echo json_encode(['data'=>$results ?: null]);

?>