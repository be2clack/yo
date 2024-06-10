<?php

$id_ad = (int)$_POST["id_ad"];
$price = $_POST["price"] ? round(preg_replace('/\s/', '', $_POST["price"]),2) : 0;

$price_sell = 0;
$duration_day = 0;
$auction = 0;
$stock_price = 0; 
$map_lat = 0;
$map_lon = 0;
$address = '';

if(!$_SESSION['cp_auth'][ $config["private_hash"] ] && !$_SESSION['cp_control_board']){

  if($_SESSION["profile"]["id"]){ 
     
     $getAd = $Ads->get("ads_id=? and ads_id_user=?", [$id_ad,intval($_SESSION["profile"]["id"])]);

  }else{

     exit;

  }

}else{

  $getAd = $Ads->get("ads_id=?", [$id_ad]);

}

if($_POST["metro"]){
  if(!is_array($_POST["metro"])){
     $_POST["metro"] = [];
  }
}else{
  $_POST["metro"] = [];
}

if($_POST["area"]){
  if(!is_array($_POST["area"])){
     $_POST["area"] = [];
  }else{
     $_POST["area"] = array_slice($_POST["area"], 0,1);
  }
}else{
  $_POST["area"] = [];
}

if($_POST["booking_prepayment_percent"]){
    if(abs($_POST["booking_prepayment_percent"]) > 100){
        $_POST["booking_prepayment_percent"] = 100;
    }else{
        $_POST["booking_prepayment_percent"] = abs($_POST["booking_prepayment_percent"]);
    }
}else{
    $_POST["booking_prepayment_percent"] = 0;
}

$getCategories = (new CategoryBoard())->getCategories("where category_board_visible=1");

$error = $Ads->validationAdForm($_POST, ["categories"=>$getCategories] );

$period = $Ads->adPeriodPub($_POST["period"]);

if($settings["ad_create_currency"] && $_POST["currency"]){
    
    if($settings["currency_data"][ $_POST["currency"] ]){
       $currency = $_POST["currency"];
    }else{
       $currency = $settings["currency_main"]["code"];
    }

}else{

    $currency = $settings["currency_main"]["code"];
    
}

if( $getCategories["category_board_id"][$_POST["c_id"]]["category_board_auto_title"] ){
    $title = $Ads->autoTitle($_POST["filter"],$getCategories["category_board_id"][$_POST["c_id"]]);
}else{
    $title = custom_substr(clear($_POST["title"]), $settings["ad_create_length_title"]);
}

$text = custom_substr($Main->clearTags($_POST["text"]), $settings["ad_create_length_text"]);

$ads_status = $Ads->statusAd( [ "id_cat"=>$_POST["c_id"], "categories"=>$getCategories, "text" => $text ,"title" => $title, "video" => videoLink($_POST["video"]), "id_user" => $_SESSION["profile"]["id"], "period_publication"=>$getAd["ads_period_publication"], "ad_id"=>$id_ad], true );

if($_POST["var_price"] == "auction"){

   $_POST["measure"] = '';
   $price_free = 0;
   
   $price_sell = $_POST["auction_price_sell"] ? round(preg_replace('/\s/', '', $_POST["auction_price_sell"]),2) : 0;
   $duration_day = intval($_POST["auction_duration_day"]);

   if(!$price){ $error["price"] = $ULang->t("Начальная ставка не может начинаться с нуля"); }else{
      if($price_sell){
        if($price_sell < $price){
            $error["auction_price_sell"] = $ULang->t("Цена продажи не может быть меньше начальной ставки");
        }
      }
   }
   
   if( $duration_day < 1 || $duration_day > 30 ){ $error["auction_duration_day"] = $ULang->t("Укажите длительность торгов от 1-го до 30-ти дней"); }
   
   $auction = 1;
   $auction_duration = date("Y-m-d H:i:s", time() + ($duration_day * 86400) );

}elseif($_POST["var_price"] == "from"){

   $ads_price_from = 1;
   
}else{

    if( $_POST["stock"] ){

       $getShop = $Shop->getUserShop( $_SESSION["profile"]["id"] );

       if( $getShop ){

           $price = $_POST["stock_price"] ? round(preg_replace('/\s/', '', $_POST["stock_price"]),2) : 0; 
           $stock_price = $_POST["price"] ? round(preg_replace('/\s/', '', $_POST["price"]),2) : 0;

           if( $price >= $stock_price ){
               $error["price"] = $ULang->t("Новая цена должна быть меньше старой цены");
           }

       }

    }
   
}

if($_POST["gallery"] && count($error) == 0){

    $path = $config["basePath"] . "/" . $config["media"]["temp_images"];

    foreach(array_slice($_POST["gallery"], 0, $settings["count_images_add_ad"], true) AS $key => $data){
                         
      if(file_exists($path . "/big_" . $data)){

       $gallery[] = $data;
       
       @copy($path . "/big_" . $data, $config["basePath"] . "/" . $config["media"]["big_image_ads"] . "/" . $data);
       @copy($path . "/small_" . $data, $config["basePath"] . "/" . $config["media"]["small_image_ads"] . "/" . $data);

      }else{

        $gallery[] = $data;

      } 
           
    }
 
}

if( !$Cart->modeAvailableCart($getCategories,$_POST["c_id"],$_SESSION["profile"]["id"]) ){
    $_POST["available"] = 0;
    $_POST["available_unlimitedly"] = 0;
}

if( intval($_POST["available_unlimitedly"]) ){
    $_POST["available"] = 0;
}

if($_POST["measure"]){
    $measuresPrice = json_decode($getCategories["category_board_id"][$_POST["c_id"]]["category_board_measures_price"], true);
    if(!in_array($_POST["measure"], $measuresPrice)){
        unset($_POST["measure"]);
    }
}

if($_POST['electron_product_links']){
    $electron_product_links = implode(',', array_slice(explode(',', $_POST['electron_product_links']), 0, 10));
}

$booking_additional_services = [];

if($_POST["booking_additional_services"] && $_POST["booking"]){
    foreach (array_slice($_POST["booking_additional_services"],0,$settings['count_add_booking_additional_services']) as $value) {
        if($value['name']) $booking_additional_services[] = ['name'=>$value['name'], 'price'=>round($value['price'],2)];
    }
}

if($getAd){

  if(!$error){

    if($settings["main_type_products"] == 'physical'){

      if($settings["city_id"]){
        $getCity = $Geo->getCity($settings["city_id"]);
      }else{
        $getCity = $Geo->getCity($_POST["city_id"]);
      }

      $address = $Geo->searchAddressByLatLon($_POST["map_lat"],$_POST["map_lon"]);
      if(!$address){
          $_POST["map_lat"] = '';
          $_POST["map_lon"] = '';
      }

      if(clear($_POST["map_lat"]) && clear($_POST["map_lon"])){
        $map_lat = clear($_POST["map_lat"]);
        $map_lon = clear($_POST["map_lon"]);
      }elseif($getCity['city_lat'] && $getCity['city_lng']){
        $map_lat = $getCity['city_lat'];
        $map_lon = $getCity['city_lng'];
      } 

    }

    if(strtotime($getAd["ads_period_publication"]) <= time()){
        $ads_period_day = $period["days"];
        $ads_period_publication = $period["date"];
    }else{
        $ads_period_day = $getAd["ads_period_day"];
        $ads_period_publication = $getAd["ads_period_publication"];                
    }

    update("UPDATE uni_ads SET ads_title=?,ads_alias=?,ads_text=?,ads_id_cat=?,ads_price=?,ads_city_id=?,ads_region_id=?,ads_country_id=?,ads_address=?,ads_latitude=?,ads_longitude=?,ads_status=?,ads_note=?,ads_images=?,ads_metro_ids=?,ads_currency=?,ads_auction=?,ads_auction_duration=?,ads_auction_price_sell=?,ads_auction_day=?,ads_area_ids=?,ads_video=?,ads_online_view=?,ads_price_old=?,ads_filter_tags=?,ads_update=?,ads_period_day=?,ads_period_publication=?,ads_price_free=?,ads_available=?,ads_available_unlimitedly=?,ads_auto_renewal=?,ads_booking=?,ads_price_measure=?,ads_price_from=?,ads_booking_additional_services=?,ads_booking_prepayment_percent=?,ads_booking_max_guests=?,ads_booking_min_days=?,ads_booking_max_days=?,ads_booking_available=?,ads_booking_available_unlimitedly=?,ads_electron_product_links=?,ads_electron_product_text=?,ads_delivery_status=?,ads_delivery_weight=?,ads_map_lat=?,ads_map_lon=?,ads_search_tags=? WHERE ads_id=?", [mb_ucfirst($title),translite($title),$text,intval($_POST["c_id"]),$price,intval($getCity["city_id"]),intval($getCity["region_id"]),intval($getCity["country_id"]),clear($address),clear($_POST["map_lat"]),clear($_POST["map_lon"]),$ads_status["status"],$ads_status["message"],json_encode($gallery),implode(",", $_POST["metro"]),$currency,$auction,$auction_duration,$price_sell,$duration_day,implode(",", $_POST["area"]),videoLink($_POST["video"]),intval($_POST["online_view"]),$stock_price,$Filters->buildTags($_POST["filter"]),date("Y-m-d H:i:s"),$ads_period_day,$ads_period_publication,intval($_POST["price_free"]),abs($_POST["available"]),intval($_POST["available_unlimitedly"]),intval($_POST['renewal']),intval($_POST['booking']),clear($_POST["measure"]),intval($ads_price_from),json_encode($booking_additional_services,JSON_UNESCAPED_UNICODE),$_POST["booking_prepayment_percent"],intval($_POST["booking_max_guests"]),intval($_POST["booking_min_days"]),intval($_POST["booking_max_days"]),intval($_POST["booking_available"]),intval($_POST["booking_available_unlimitedly"]),$electron_product_links,clear($_POST["electron_product_text"]),intval($_POST["delivery_status"]),intval($_POST["delivery_weight"]),$map_lat,$map_lon,$Ads->buildTagsSearch(["city_id"=>intval($getCity["city_id"]), "cat_id"=>intval($_POST["c_id"])]),$id_ad], true);

    $Ads->addMetroVariants($_POST["metro"],$id_ad);
    $Ads->addAreaVariants($_POST["area"],$id_ad);

    $Filters->addVariants($_POST["filter"],$id_ad);

    $Ads->changeStatus( $id_ad, $ads_status["status"], "update" );

    $getAd = $Ads->get("ads_id=?", [$id_ad]);

    if($ads_status["status"] == 0){
        $Admin->notifications("ads", [ "title" => $getAd["ads_title"], "link" => $Ads->alias($getAd), "image" => $gallery[0], "user_name" => $getAd["clients_name"], "id_hash_user" => $getAd["clients_id_hash"] ] );
    }

    echo json_encode( array( "status" => true, "action" => "update" , "id" => $id_ad, "location" => $Ads->alias($getAd) . "?modal=update_ad" ) );

    unset($_SESSION['csrf_token'][$_POST['csrf_token']]);

    $Cache->update( "uni_ads" );

  }else{
    echo json_encode(array("status" => false, "answer" => $error));
  }

}
?>