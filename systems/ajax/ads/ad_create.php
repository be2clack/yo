<?php
if(!$_SESSION["profile"]["id"]){ exit(json_encode([ "status" => false, "auth" => true ])); }

$price_sell = 0;
$duration_day = 0;
$auction = 0;
$stock_price = 0;
$price = $_POST["price"] ? round(preg_replace('/\s/', '', $_POST["price"]),2) : 0;
$map_lat = 0;
$map_lon = 0;
$address = '';

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

if($_POST['renewal']){
   if($_SESSION['profile']['tariff']['services']['scheduler']){
      $renewal = 1;
   }
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
        if($value['name']) $booking_additional_services[] = ['name'=>$value['name'], 'price'=>$value['price'] ? round($value['price'],2) : 0];
    }
}

if(count($error) == 0){

  verify_mass_requests();

  if( $_SESSION["create-verify-phone"]["phone"] ){
      update( "update uni_clients set clients_phone=? where clients_id=?", [ $_SESSION["create-verify-phone"]["phone"], $_SESSION["profile"]["id"] ] );
  }

  $status = $Ads->statusAd( [ "id_cat"=>$_POST["c_id"], "categories"=>$getCategories, "text" => $text ,"title" => $title, "id_user" => $_SESSION["profile"]["id"] ] );

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

  $insert_id = smart_insert('uni_ads',[
    'ads_title' => mb_ucfirst($title),
    'ads_alias' => translite($title),
    'ads_text' => $text,
    'ads_id_cat' => intval($_POST["c_id"]),
    'ads_id_user' => intval($_SESSION["profile"]["id"]),
    'ads_price' => $price,
    'ads_city_id' => intval($getCity["city_id"]),
    'ads_region_id' => intval($getCity['region_id']),
    'ads_country_id' => intval($getCity['country_id']),
    'ads_address' => clear($address),
    'ads_latitude' => clear($_POST["map_lat"]),
    'ads_longitude' => clear($_POST["map_lon"]),
    'ads_period_publication' => $period["date"],
    'ads_status' => $status["status"],
    'ads_note' => $status["message"],
    'ads_images' => json_encode($gallery),
    'ads_metro_ids' => implode(",", $_POST["metro"]),
    'ads_currency' => $currency,
    'ads_period_day' => $period["days"],
    'ads_datetime_add' => date("Y-m-d H:i:s"),
    'ads_auction' => $auction,
    'ads_auction_duration' => $auction_duration,
    'ads_auction_price_sell' => $price_sell,
    'ads_auction_day' => $duration_day,
    'ads_area_ids' => implode(",",$_POST["area"]),
    'ads_video' => videoLink($_POST["video"]),
    'ads_online_view' => intval($_POST["online_view"]),
    'ads_price_old' => $stock_price,
    'ads_filter_tags' => $Filters->buildTags($_POST["filter"]),
    'ads_price_free' => intval($_POST["price_free"]),
    'ads_available' => abs($_POST["available"]),
    'ads_available_unlimitedly' => intval($_POST["available_unlimitedly"]),
    'ads_auto_renewal' => intval($renewal),
    'ads_booking' => intval($_POST["booking"]),
    'ads_price_measure' => clear($_POST["measure"]),
    'ads_price_from' => intval($ads_price_from),
    'ads_booking_additional_services' => json_encode($booking_additional_services,JSON_UNESCAPED_UNICODE),
    'ads_booking_prepayment_percent' => $_POST["booking_prepayment_percent"],
    'ads_booking_max_guests' => intval($_POST["booking_max_guests"]),
    'ads_booking_min_days' => intval($_POST["booking_min_days"]),
    'ads_booking_max_days' => intval($_POST["booking_max_days"]),
    'ads_booking_available' => intval($_POST["booking_available"]),
    'ads_booking_available_unlimitedly' => intval($_POST["booking_available_unlimitedly"]),
    'ads_electron_product_links' => $electron_product_links,
    'ads_electron_product_text' => clear($_POST["electron_product_text"]),
    'ads_delivery_status' => intval($_POST["delivery_status"]),
    'ads_delivery_weight' => intval($_POST["delivery_weight"]),
    'ads_search_tags' => $Ads->buildTagsSearch(["city_id"=>intval($getCity["city_id"]), "cat_id"=>intval($_POST["c_id"])]),
    'ads_map_lat' => $map_lat,
    'ads_map_lon' => $map_lon,
  ]);

  if($insert_id){

      $Ads->addMetroVariants($_POST["metro"],$insert_id);
      $Ads->addAreaVariants($_POST["area"],$insert_id);

      $Filters->addVariants($_POST["filter"],$insert_id);

      $getAd = $Ads->get("ads_id=?", [$insert_id]);
      
      $Elastic->index( [ "index" => "uni_ads", "type" => "ad", "id" => $insert_id, "body" => $Elastic->prepareFields( $getAd ) ] );
      
      if($status["status"] != 7){
        $Admin->notifications("ads", ["title" => $title, "link" => $Ads->alias($getAd), "image" => $gallery[0], "user_name" => $getAd["clients_name"], "id_hash_user" => $getAd["clients_id_hash"]]);
      }

      if($status["status"] == 1 && !$getAd['clients_first_ad_publication'] && $settings["bonus_program"]["ad_publication"]["status"] && $settings["bonus_program"]["ad_publication"]["price"]){
        
           $Profile->actionBalance(array("id_user"=>intval($_SESSION["profile"]["id"]),"summa"=>$settings["bonus_program"]["ad_publication"]["price"],"title"=>$settings["bonus_program"]["ad_publication"]["name"],"id_order"=>generateOrderId(),"email" => $getAd["clients_email"],"name" => $getAd["clients_name"], "note" => $settings["bonus_program"]["ad_publication"]["name"]),"+");   
           update('update uni_clients set clients_first_ad_publication=? where clients_id=?', [1, intval($_SESSION["profile"]["id"])]);            
      }

      if($settings["board_type_ad_publication"] == "paid"){

        if($getCategories["category_board_id"][intval($_POST["c_id"])]["category_board_status_paid"]){

          if($Ads->userCountAvailablePaidAddCategory(intval($_POST["c_id"]), intval($_SESSION["profile"]["id"])) >= $getCategories["category_board_id"][intval($_POST["c_id"])]["category_board_count_free"]){

              $getPackages = getAll("select * from uni_ads_packages_orders where user_id=? and cat_id=? and status_pay=? and completion_date > ?", [intval($_SESSION["profile"]["id"]),intval($_POST["c_id"]),1,date("Y-m-d H:i:s")]);

              if($getPackages){
                  foreach ($getPackages as $key => $value) {

                     $countAds = (int)getOne("select count(*) as total from uni_ads_packages_placements where user_id=? and cat_id=? and order_id=?", [intval($_SESSION["profile"]["id"]), intval($_POST["c_id"]), $value["id"]])["total"];

                     if($countAds < (int)$value["count_ad"]){
                        insert("INSERT INTO uni_ads_packages_placements(ad_id,user_id,cat_id,package_id,order_id)VALUES(?,?,?,?,?)", [$insert_id,$_SESSION['profile']['id'],intval($_POST["c_id"]),$value["package_id"],$value["id"]]);
                        break;
                     }

                  }
              }

          }

        }

      }

      if($status["status"] == 6){
         $location = _link("ad/publish/".$insert_id);
      }else{
         $location = $Ads->alias($getAd) . "?modal=new_ad";
      }

  }

  echo json_encode( [ "status" => true, "action" => "add" , "id" => $insert_id, "location" => $location ] );

  unset($_SESSION['csrf_token'][$_POST['csrf_token']]);

  $Cache->update( "uni_ads" );

}else{
  echo json_encode( [ "status" => false, "answer" => $error ] );
}
?>