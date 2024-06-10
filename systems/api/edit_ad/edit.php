<?php

$id = (int)$_POST['id'];
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
    http_response_code(500); exit('Authorization token error');
}

$catId = (int)$_POST["cat_id"];
$title = mb_substr(clear($_POST["title"]), 0, $settings["ad_create_length_title"], 'utf-8');
$text = mb_substr(clear($_POST["text"]), 0, $settings["ad_create_length_text"], 'utf-8');
$period = (int)$_POST["period"];
$currency = clear($_POST["currency"]);
$price = $_POST["price"] ? round($_POST["price"], 2) : 0;
$price_free = (int)$_POST["price_free"];
$images = isset($_POST["images"]) ? json_decode($_POST["images"], true) : [];
$measure = clear($_POST["measure"]);
$city_id = (int)$_POST["city_id"];
$var_price = clear($_POST["var_price"]);
$video = clear($_POST["video"]);
$online_view = (int)$_POST["online_view"];
$phone = formatPhone(clear($_POST["phone"]));
$phone_verify_code = (int)$_POST["phone_verify_code"];
$address = clear($_POST["address"]);
$available = (int)$_POST["available"];
$available_unlimitedly = (int)$_POST["available_unlimitedly"];
$renewal = (int)$_POST["renewal"];

$booking_prepayment_percent = (int)$_POST["booking_prepayment_percent"];
$booking = (int)$_POST["booking"];
$booking_max_guests = (int)$_POST["booking_max_guests"];
$booking_min_days = (int)$_POST["booking_min_days"];
$booking_max_days = (int)$_POST["booking_max_days"];
$booking_available = (int)$_POST["booking_available"];
$booking_available_unlimitedly = (int)$_POST["booking_available_unlimitedly"];

$map_lat = '';
$map_lon = '';
$results = [];
$errors = [];
$gallery = [];
$price_sell = 0;
$duration_day = 0;
$auction = 0;
$stock_price = 0; 
$booking_additional_services = [];

$getAd = $Ads->get('ads_id=? and ads_id_user=?', [$id,$idUser]);

if(!$getAd){
    http_response_code(500); exit('Ad not found');
}

$getTariff = $Profile->getOrderTariff($idUser);
$getUser = findOne('uni_clients', 'clients_id=?', [$idUser]);

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");
$filters = apiStructureAdVariantsFilters(json_decode($_POST["filters"], true));

if(!$getCategoryBoard["category_board_id"][$catId]["category_board_auto_title"]){
		if(empty($title)){ $errors[] = apiLangContent("Пожалуйста, укажите заголовок объявления"); }
}

if(!$text){
    $errors[] = apiLangContent("Пожалуйста, укажите описание объявления");
}

if($settings["ad_create_always_image"]){
    if(!$images){
      $errors[] = apiLangContent("Загрузите хотя бы одну фотографию");
    }
}

if($settings["main_type_products"] == "physical"){
    if(!$settings["city_id"]){
      if(empty($city_id)){ $errors[] = apiLangContent("Пожалуйста, укажите город"); }else{
        $getCity = findOne("uni_city","city_id=?", array($city_id));
        if(count($getCity) == 0){
          $errors[] = apiLangContent("Пожалуйста, укажите город");
        }
      }
    }else{
      $getCity = findOne("uni_city","city_id=?", array($settings["city_id"]));  
    }
}

if($getCategoryBoard["category_board_id"][$catId]["category_board_measures_price"]){
    if($getCategoryBoard["category_board_id"][$catId]["category_board_rules"]["measure_booking"]){
        if($booking){
            if(empty($measure)){ $errors[] = "Пожалуйста, выберите вариант измерения"; }
        }
    }else{
        if(empty($measure)){ $errors[] = "Пожалуйста, выберите вариант измерения"; }
    }
}

if($booking_prepayment_percent){
    if(abs($booking_prepayment_percent) > 100){
        $booking_prepayment_percent = 100;
    }else{
        $booking_prepayment_percent = abs($booking_prepayment_percent);
    }
}else{
    $booking_prepayment_percent = 0;
}

if( !$Cart->modeAvailableCart($getCategoryBoard,$catId,$idUser) ){
    $available = 0;
    $available_unlimitedly = 0;
}

if($available_unlimitedly){
    $available = 0;
}

if($_POST["booking_additional_services"] && $booking){
    foreach (array_slice(json_decode($_POST["booking_additional_services"], true),0,$settings['count_add_booking_additional_services']) as $value) {
        if($value['name']) $booking_additional_services[] = ['name'=>$value['name'], 'price'=>$value['price'] ? round($value['price'],2) : 0];
    }
}

$filters_ids = $Filters->getCategory(["id_cat"=>$catId]);

if($filters_ids){
  $getFilters = getAll('select * from uni_ads_filters where ads_filters_visible=1 and ads_filters_required=1 and ads_filters_id IN('.implode(",", $filters_ids).')');
  foreach ($getFilters as $key => $value) {

      if($value['ads_filters_id_parent']){

         if(isset($filters[$value['ads_filters_id_parent']])){ 

              $getParentFilterItem = findOne('uni_ads_filters_items', 'ads_filters_items_id_filter=? and ads_filters_items_id_item_parent=?', [$value['ads_filters_id'], $filters[$value['ads_filters_id_parent']][0]]);

              if($getParentFilterItem){

                  if(empty($filters[$value['ads_filters_id']])){
                      if($value['ads_filters_type'] == 'input'){
                         $getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=? order by ads_filters_items_value asc", [$value["ads_filters_id"]]);

                         if($filters[$value['ads_filters_id']][0] < $getItems[0]['ads_filters_items_value'] || $filters[$value['ads_filters_id']][0] > $getItems[1]['ads_filters_items_value']){
                              $errors[] = apiLangContent("Укажите фильтр:")." ".mb_strtolower($value['ads_filters_name'], 'utf-8')." ".apiLangContent("от")." ".$getItems[0]['ads_filters_items_value']." ".apiLangContent("до")." ".$getItems[1]['ads_filters_items_value'];
                         }
                      }else{
                         $errors[] = apiLangContent("Выберите фильтр:")." ".mb_strtolower($value['ads_filters_name'], 'utf-8');
                      }                  
                  }else{
                      if($value['ads_filters_type'] == 'input'){
                         $getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=? order by ads_filters_items_value asc", [$value["ads_filters_id"]]);

                         if($filters[$value['ads_filters_id']][0] < $getItems[0]['ads_filters_items_value'] || $filters[$value['ads_filters_id']][0] > $getItems[1]['ads_filters_items_value']){
                              $errors[] = apiLangContent("Укажите фильтр:")." ".mb_strtolower($value['ads_filters_name'], 'utf-8')." ".apiLangContent("от")." ".$getItems[0]['ads_filters_items_value']." ".apiLangContent("до")." ".$getItems[1]['ads_filters_items_value'];
                         }
                      }                    
                  }

              }

         }

      }else{

         if(empty($filters[$value['ads_filters_id']])){ 
              $errors[] = "Выберите фильтр: ".mb_strtolower($value['ads_filters_name'], 'utf-8');
         }else{
              if($value['ads_filters_type'] == 'input'){
                 $getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=? order by ads_filters_items_value asc", [$value["ads_filters_id"]]);
                 if($filters[$value['ads_filters_id']][0] < $getItems[0]['ads_filters_items_value'] || $filters[$value['ads_filters_id']][0] > $getItems[1]['ads_filters_items_value']){
                      $errors[] = apiLangContent("Укажите фильтр:")." ".mb_strtolower($value['ads_filters_name'], 'utf-8')." ".apiLangContent("от")." ".$getItems[0]['ads_filters_items_value']." ".apiLangContent("до")." ".$getItems[1]['ads_filters_items_value'];
                 }
              }
         }

      }

  }
}

if($settings["ad_create_period"]){
    if(!$period){
      $errors[] = apiLangContent("Выберите срок публикации");
    }
}

if($settings["main_type_products"] == 'electron'){
    if(empty($_POST["electron_product_links"])){ $errors[] = apiLangContent("Пожалуйста, укажите ссылку на электронный товар"); }
    if(!$price){
        $errors[] = apiLangContent("Пожалуйста, укажите цену");
    }
}

if($_POST['electron_product_links']){
    $electron_product_links = implode(',', array_slice(explode(',', $_POST['electron_product_links']), 0, 10));
}

if(intval($_POST["auction"])){

   $measure = '';
   $price_free = 0;

   $price_sell = $_POST["auction_price_sell"] ? round(preg_replace('/\s/', '', $_POST["auction_price_sell"]),2) : 0;
   $duration_day = intval($_POST["auction_duration_day"]);

   if(!$price){ $errors[] = apiLangContent("Начальная ставка не может начинаться с нуля"); }else{
      if($price_sell){
        if($price_sell < $price){
            $errors[] = apiLangContent("Цена продажи не может быть меньше начальной ставки");
        }
      }
   }
   
   if( $duration_day < 1 || $duration_day > 30 ){ $errors[] = apiLangContent("Укажите длительность торгов от 1-го до 30-ти дней"); }
   
   $auction_duration = date("Y-m-d H:i:s", time() + ($duration_day * 86400) );

}

if(!$errors){

    if($images){

        $path = $config["basePath"] . "/" . $config["media"]["temp_images"];

        foreach(array_slice($images, 0, $settings["count_images_add_ad"], true) AS $key => $data){
                             
          if(file_exists($path . "/big_" . $data['name'])){

            $gallery[] = $data['name'];

            @copy($path . "/big_" . $data['name'], $config["basePath"] . "/" . $config["media"]["big_image_ads"] . "/" . $data['name']);
            @copy($path . "/small_" . $data['name'], $config["basePath"] . "/" . $config["media"]["small_image_ads"] . "/" . $data['name']);

          }else{

            $gallery[] = $data['name'];

          } 
               
        }

    }

    $imagesAd = $Ads->getImages($getAd["ads_images"]);

    if($imagesAd){
        foreach ($imagesAd as $name) {
            if($gallery){
                if(!in_array($name, $gallery)){
                    @unlink($config["basePath"] . "/" . $config["media"]["big_image_ads"] . "/" . $name);
                    @unlink($config["basePath"] . "/" . $config["media"]["small_image_ads"] . "/" . $name);
                }
            }else{
                @unlink($config["basePath"] . "/" . $config["media"]["big_image_ads"] . "/" . $name);
                @unlink($config["basePath"] . "/" . $config["media"]["small_image_ads"] . "/" . $name);
            }
        }
    }

  $period = $Ads->adPeriodPub($period);

  if(strtotime($getAd["ads_period_publication"]) <= time()){
     $ads_period_day = $period["days"];
     $ads_period_publication = $period["date"];
  }else{
     $ads_period_day = $getAd["ads_period_day"];
     $ads_period_publication = $getAd["ads_period_publication"];                
  }

  if($settings["ad_create_currency"] && $currency){
      if(!isset($settings["currency_data"][$currency])){
         $currency = $settings["currency_main"]["code"];
      }
  }else{
      $currency = $settings["currency_main"]["code"];
  }

  if($getCategoryBoard["category_board_id"][$catId]["category_board_auto_title"]){
      $title = $Ads->autoTitle($filters,$getCategoryBoard["category_board_id"][$catId]);
  }else{
      $title = custom_substr($title, $settings["ad_create_length_title"]);
  }

  $text = custom_substr($text, $settings["ad_create_length_text"]);

  if( $getCategoryBoard["category_board_id"][$catId]["category_board_status_paid"] ){

    if($Ads->userCountAvailablePaidAddCategory($catId, $getAd['ads_id_user']) > $getCategoryBoard["category_board_id"][$catId]["category_board_count_free"]){

        $findOrder = findOne('uni_orders', 'orders_id_ad=? and orders_action_name=? and orders_status_pay=?', [$id, 'category', 1]);
        if($findOrder){
            $status = $Ads->autoModeration($id, [ "title" => $title, "text" => $text, "video" => videoLink($video) ] );
        }else{
            $status = 6;
        }

    }else{
       $status = $Ads->autoModeration($id, [ "title" => $title, "text" => $text, "video" => videoLink($video) ] ); 
    }

  }else{
    $status = $Ads->autoModeration($id, [ "title" => $title, "text" => $text, "video" => videoLink($video) ] );
  }

  if($var_price == "from"){
  	 $price_from = 1;
  }

  if($renewal){
     if($getTariff['services']['scheduler']){
        $renewal = 1;
     }
  }

  if($measure){
      $measuresPrice = json_decode($getCategoryBoard["category_board_id"][$catId]["category_board_measures_price"], true);
      if(!in_array($measure, $measuresPrice)){
          unset($measure);
      }
  }

  $address = $Geo->searchAddressByLatLon($_POST["lat"],$_POST["lon"]);
  if(!$address){
      $_POST["lat"] = '';
      $_POST["lon"] = '';
  }

  if(clear($_POST["lat"]) && clear($_POST["lon"])){
    $map_lat = clear($_POST["lat"]);
    $map_lon = clear($_POST["lon"]);
  }elseif($getCity['city_lat'] && $getCity['city_lng']){
    $map_lat = $getCity['city_lat'];
    $map_lon = $getCity['city_lng'];
  }

  smart_update('uni_ads',[
  	'ads_title' => mb_ucfirst($title),
  	'ads_alias' => translite($title),
  	'ads_text' => $text,
  	'ads_id_cat' => $catId,
  	'ads_price' => $price,
  	'ads_city_id' => $getCity['city_id'],
  	'ads_region_id' => $getCity['region_id'],
  	'ads_country_id' => $getCity['country_id'],
  	'ads_address' => $address,
  	'ads_latitude' => clear($_POST["lat"]),
  	'ads_longitude' => clear($_POST["lon"]),
  	'ads_period_publication' => $ads_period_publication,
  	'ads_status' => $status,
  	'ads_note' => '',
  	'ads_images' => json_encode($gallery),
  	//'ads_metro_ids' => '',
  	'ads_currency' => $currency,
  	'ads_period_day' => $ads_period_day,
  	'ads_datetime_add' => date("Y-m-d H:i:s"),
    'ads_auction' => intval($_POST["auction"]),
    'ads_auction_duration' => $auction_duration,
    'ads_auction_price_sell' => $price_sell,
    'ads_auction_day' => $duration_day,
  	//'ads_area_ids' => '',
  	'ads_video' => videoLink($video),
  	'ads_online_view' => $online_view,
  	'ads_price_old' => 0,
  	'ads_filter_tags' => $Filters->buildTags($filters),
  	'ads_price_free' => $price_free,
  	'ads_available' => $available,
  	'ads_available_unlimitedly' => $available_unlimitedly,
  	'ads_auto_renewal' => $renewal,
  	'ads_booking' => $booking,
  	'ads_price_measure' => $measure,
  	'ads_price_from' => intval($price_from),
    'ads_booking_additional_services' => json_encode($booking_additional_services,JSON_UNESCAPED_UNICODE),
    'ads_booking_prepayment_percent' => $booking_prepayment_percent,
    'ads_booking_max_guests' => $booking_max_guests,
    'ads_booking_min_days' => $booking_min_days,
    'ads_booking_max_days' => $booking_max_days,
    'ads_booking_available' => $booking_available,
    'ads_booking_available_unlimitedly' => $booking_available_unlimitedly,
  	'ads_electron_product_links' => $electron_product_links,
  	'ads_electron_product_text' => clear($_POST["electron_product_text"]),
    'ads_delivery_status' => intval($_POST["delivery_status"]),
    'ads_delivery_weight' => intval($_POST["delivery_weight"]),
    'ads_search_tags' => $getCategoryBoard["category_board_id"][$catId]["category_board_name"],
    'ads_map_lat' => $map_lat,
    'ads_map_lon' => $map_lon,
  ], 'ads_id='.$id.' and ads_id_user='.$idUser);

  $Filters->addVariants($filters,$id);

  $Ads->changeStatus($id, $status, "update");

  $getAd = $Ads->get("ads_id=?", [$id]);

  if($status == 0){
    $Admin->notifications("ads", [ "title" => $getAd["ads_title"], "link" => $Ads->alias($getAd), "image" => $gallery[0], "user_name" => $getAd["clients_name"], "id_hash_user" => $getAd["clients_id_hash"] ] );
  }

  echo json_encode(['status'=>true]);

}else{
	echo json_encode(['status'=>false, 'answer'=>implode("\n", $errors)]);
}

?>