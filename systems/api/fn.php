<?php

function checkTokenAuth($token, $id_user){
    if(!$token || !$id_user) return false;
    $getToken = findOne('uni_clients_auth', 'clients_auth_token=? and clients_auth_user_id=?', [$token,$id_user]);
    if($getToken){
        return true;
    }
    return false;
}

function apiPublicationAndStatus($data = []){

  if($data["ads_status"] == 0){
    $name = apiLangContent("На модерации");
  }elseif($data["ads_status"] == 1){

      if(strtotime($data["ads_period_publication"]) > time()){
          return apiLangContent("Активно");
      }else{
          return apiLangContent("Истек срок");
      }

  }elseif($data["ads_status"] == 2){
    $name = apiLangContent("Снято с публикации");
  }elseif($data["ads_status"] == 3){
    $name = apiLangContent("Заблокировано");
  }elseif($data["ads_status"] == 4){
    $name = apiLangContent("Зарезервировано");
  }elseif($data["ads_status"] == 5){
    $name = apiLangContent("Продано");
  }elseif($data["ads_status"] == 6){
    $name = apiLangContent("Ждет оплаты");
  }elseif($data["ads_status"] == 7){
    $name = apiLangContent("Отклонено");
  }elseif($data["ads_status"] == 8){
    $name = apiLangContent("Удалено");
  }

  return $name;

}

function apiValidatePhone($phone=""){
  global $settings, $config;

   $code_phone = [];

   $phone = preg_replace('/[^0-9]/', '', $phone);

   if(!$phone){
       return ['status'=>false, 'error'=>apiLangContent("Пожалуйста, укажите корректный номер телефона")];
   }

   $getCountry = getAll('select * from uni_country where country_code_phone!=? and country_status=?', ['',1]);

   if(count($getCountry)){
        foreach ($getCountry as $key => $value) {
            $data['code'][] = $value['country_code_phone'];
            $data['format'][] = $config["format_phone"][$value['country_code_phone']]["code"];
        }
        foreach ($data['code'] as $value) {

            if(substr($phone, 0, strlen($config["format_phone"][$value]["code"])) == $config["format_phone"][$value]["code"]){

                if(strlen($phone) != $config["format_phone"][$value]["length"]){
                   return ['status'=>false, 'error'=>apiLangContent("Пожалуйста, укажите корректный номер телефона")];
                }else{
                   return ['status'=>true]; 
                }

            }

        }

        return ['status'=>false, 'error'=>apiLangContent('Номер телефона должен начинаться с').' '.implode(', ',$data['format'])];
   }

   return ['status'=>true];

}

function apiPrice($float=0, $currency_code="", $abbreviation_million=false){
    global $config, $settings;


    if( !$settings["abbreviation_million"] ){
        $abbreviation_million = false;
    }

    if($currency_code != 'null'){
        if( $currency_code ){
           $currency = $config["number_format"]["currency_spacing"] . $settings["currency_data"][ $currency_code ]["sign"];
        }else{
           $currency = $settings["currency_main"]["sign"];
        }
    }

    $float_format = number_format($float,2,".",",");

    if($settings["currency_main"]["sign_position"] == "left"){

        if( $abbreviation_million == false ){

            if( intval(explode(".", $float_format )[1]) == 0 || intval(explode(".", $float_format )[1]) == 00 ){
               return $currency.number_format($float,$config["number_format"]["decimals"],$config["number_format"]["dec_point"],$config["number_format"]["thousands_sep"]);
            }else{
               if( strpos($float_format, ",") === false ){
                  return $currency.number_format($float,2,$config["number_format"]["dec_point"],$config["number_format"]["thousands_sep"]);
               }else{
                  return $currency.number_format($float,$config["number_format"]["decimals"],$config["number_format"]["dec_point"],$config["number_format"]["thousands_sep"]);
               }
            }

        }else{
            
            if( $float >= 1000000 && $float <= 9999999 ){
                
                if( substr($float, 1,1) != 0 ){
                   return $currency.substr($float, 0,1).','.substr($float, 1,1).' '.apiLangContent('млн');
                }else{
                   return $currency.substr($float, 0,1).' '.apiLangContent('млн');
                }

            }elseif( $float >= 10000000 && $float <= 99999999 ){
                return $currency.substr($float, 0,2).' '.apiLangContent('млн');
            }elseif( $float >= 100000000 && $float <= 999999999 ){
                return $currency.substr($float, 0,3).' '.apiLangContent('млн');
            }else{
                return $currency.number_format($float,$config["number_format"]["decimals"],$config["number_format"]["dec_point"],$config["number_format"]["thousands_sep"]);
            }

        }

    }else{

        if( $abbreviation_million == false ){

            if( intval(explode(".", $float_format )[1]) == 0 || intval(explode(".", $float_format )[1]) == 00 ){
               return number_format($float,$config["number_format"]["decimals"],$config["number_format"]["dec_point"],$config["number_format"]["thousands_sep"]).$currency;
            }else{
               if( strpos($float_format, ",") === false ){
                  return number_format($float,2,$config["number_format"]["dec_point"],$config["number_format"]["thousands_sep"]).$currency;
               }else{
                  return number_format($float,$config["number_format"]["decimals"],$config["number_format"]["dec_point"],$config["number_format"]["thousands_sep"]).$currency;
               }
            }

        }else{
            
            if( $float >= 1000000 && $float <= 9999999 ){
                
                if( substr($float, 1,1) != 0 ){
                   return substr($float, 0,1).','.substr($float, 1,1).' '.apiLangContent('млн').$currency;
                }else{
                   return substr($float, 0,1).' '.apiLangContent('млн').$currency;
                }

            }elseif( $float >= 10000000 && $float <= 99999999 ){
                return substr($float, 0,2).' '.apiLangContent('млн').$currency;
            }elseif( $float >= 100000000 && $float <= 999999999 ){
                return substr($float, 0,3).' '.apiLangContent('млн').$currency;
            }else{
                return number_format($float,$config["number_format"]["decimals"],$config["number_format"]["dec_point"],$config["number_format"]["thousands_sep"]).$currency;
            }

        }

    }

}

function apiSettings(){

  global $settings, $config;

  $Profile = new Profile();
  $CategoryBoard = new CategoryBoard();

  $getDefaultCountry = findOne('uni_country', 'country_id=?', [$settings['country_id']]);

  $results['app']['alert'] = true;
  $results['app']['version'] = $settings["app_version"];
  $results['app']['link'] = $settings["app_download_link"];

  $results['info']['project_name'] = $settings["app_name_project"];

  $results['home_adv_header_status'] = $settings["app_home_header_banner_status"] ? true : false;
  $results['home_adv_header'] = json_decode($settings["app_home_header_banner"], true) ?: null;

  $results['app_metrica_api_key'] = $settings["app_metrica_api_key"] ?: null;

  $results['operating_mode'] = $settings["main_type_products"];

  $results["status_multilang"] = $settings["visible_lang_site"] ? true : false;

  if($settings["functionality"]["booking"] && $settings["booking_status"]){
    $results['status_booking'] = true;
  }else{
    $results['status_booking'] = false;
  }

  $results['type_ad_publication'] = $settings["board_type_ad_publication"] ?: 'free';

  $results['main_currency']['code'] = $settings["currency_main"]["code"];
  $results['main_currency']['symbol'] = $settings["currency_main"]["sign"];
  $results['main_currency']['position'] = $settings["currency_main"]["sign_position"];

  $results['card_ad_view_phone'] = $settings["ad_view_phone"] == 1 ? 'reg' : 'all';

  $getCurrency = getAll("select * from uni_currency where visible=?", [1]);
  if(count($getCurrency)){
       foreach ($getCurrency as $value) {
          $results["list_currency"][] = ['name'=>$value['name'],'sign'=>$value['sign'],'code'=>$value['code']];
       }
  }

  // Get lang

  $getLanguages = getAll("select * from uni_languages where status=?", [1]);
  if(count($getLanguages)){
       foreach ($getLanguages as $value) {

            $results["list_languages"][] = ['name'=>$value['name'],'image'=>Exists( $config["media"]["other"],$value["image"],$config["media"]["no_image"] ),'iso'=>$value['iso']];
            $results["list_languages_iso"][$value['iso']] = ['name'=>$value['name'],'image'=>Exists( $config["media"]["other"],$value["image"],$config["media"]["no_image"] ),'iso'=>$value['iso'], 'content'=>apiLangTemplate($value['iso']) ? apiLangTemplate($value['iso']) : null];

       }
  }

  $results["default_language_iso"] = $settings["lang_site_default"];
  $results["default_language_locale"] = "ru_RU";

  // Reg - Auth method
  
  $results['confirmation_phone'] = $settings["confirmation_phone"] ? true : false;
  $results['registration_method'] = $settings["registration_method"];
  $results['authorization_method'] = $settings["authorization_method"];

  // Getting active countries with code phone

  $getPhoneCode = getAll("select * from uni_country where country_code_phone!=? and country_status=?", ['',1]);

  if(count($getPhoneCode) > 1){

    foreach ($getPhoneCode as $value) {
        $results['country_list_phone_code'][] = ["image"=>Exists($config["media"]["other"],$value['country_image'],$config["media"]["no_image"]),"format"=>$value['country_format_phone'],"name"=>$value['country_name'],"code"=>$value['country_code_phone']];
    }

  }else{
    $results['country_list_phone_code'] = null;
  }  

  $results['country_phone']['format'] = $getDefaultCountry['country_format_phone'];
  $results['country_phone']['code'] = $getDefaultCountry['country_code_phone'];
  $results['country_flag'] = Exists($config["media"]["other"],$getDefaultCountry['country_image'],$config["media"]["no_image"]);
  $results['country_lat'] = $settings["country_lat"];
  $results['country_lon'] = $settings["country_lng"];
  $results['country_id'] = $settings["country_id"];

  if($settings["region_id"]){
    $getRegion = findOne("uni_region", "region_id=?", [$settings["region_id"]]);
    $results["region_id"] = $settings["region_id"];
    $results["region_name"] = $getRegion["region_name"];
    $results["region_declination"] = $getRegion["region_declination"];

    $getTopRightLatLng = getOne('select city_lat,city_lng from uni_city where region_id=? and country_id=? and city_lat is not null and city_lng is not null and city_lat !=0 and city_lng !=0 order by city_lat desc, city_lng desc limit 1', [$settings["region_id"],$settings["country_id"]]);
    $getBottomLeftLatLng = getOne('select city_lat,city_lng from uni_city where region_id=? and country_id=? and city_lat is not null and city_lng is not null and city_lat !=0 and city_lng !=0 order by city_lat asc, city_lng asc limit 1', [$settings["region_id"],$settings["country_id"]]);

    if($getTopRightLatLng && $getBottomLeftLatLng){
        $latlngList = [['city_lat'=>$getTopRightLatLng['city_lat'],'city_lng'=>$getTopRightLatLng['city_lng']],['city_lat'=>$getBottomLeftLatLng['city_lat'],'city_lng'=>$getBottomLeftLatLng['city_lng']]];
    }

    $results["region_latlng"] = $latlngList ?: null;

  }else{
    $results["region_id"] = null;
  }
  
  $results['delivery_status'] = $settings["delivery_service"] ? true : false;

  // Variants payments

  if($settings["payment_variant"]){
    $getPayments = getAll("select * from uni_payments where id IN(".$settings["payment_variant"].")");
    foreach ($getPayments as $value) {
        $results['balance_list_payments'][] = ['name'=>$value["name"], 'code'=>$value["code"], 'logo'=>Exists($config["media"]["other"], $value["logo"], $config["media"]["no_image"])];
    }    
  }else{
    $results["balance_list_payments"] = null;
  }

  if($settings['bonus_program']['register']['status']){
      $results['bonus_program']['register']['status'] = true;
      $results['bonus_program']['register']['title'] = apiLangContent('Зарегистрируйтесь и получите').' '.apiPrice($settings['bonus_program']['register']['price']).' '.apiLangContent('на бонусный счет!');
      $results['bonus_program']['register']['price'] = $settings['bonus_program']['register']['price'];
  }else{
      $results['bonus_program']['register']['status'] = false;
  }

  if($settings['bonus_program']['balance']['status']){
      $results['bonus_program']['balance']['status'] = true;
      $results['bonus_program']['balance']['title'] = apiLangContent('Пополните баланс и получите бонус').' '.$settings['bonus_program']['balance']['price'].' '.apiLangContent('% от суммы пополнения');
      $results['bonus_program']['balance']['price'] = $settings['bonus_program']['balance']['price'];
  }else{
      $results['bonus_program']['balance']['status'] = false;
  }

  if($settings['bonus_program']['email']['status']){
      $results['bonus_program']['email']['status'] = true;
      $results['bonus_program']['email']['price'] = apiPrice($settings['bonus_program']['email']['price']);
  }else{
      $results['bonus_program']['email']['status'] = false;
  }

  // Social auth list

  // $authorization_social_list = explode(",", $settings["authorization_social"]);

  // if($authorization_social_list){
  //    $results['auth_social_status'] = false;
  // }else{
  //    $results['auth_social_status'] = false;
  // }

  $results['auth_social_status'] = false;

  $results['auth_social_list'][] = ["name"=>"Google","image"=>$settings["path_other"].'/media_social_google_62736.png',"link"=>"https://accounts.google.com/o/oauth2/auth", "params"=>$params];

  // if(in_array( "yandex" , $authorization_social_list) ){

  //   $social_auth_params = json_decode(decrypt($settings["social_auth_params"]), true);

  //   $params = array(
  //       'client_id'     => $social_auth_params["yandex"]["id_app"],
  //       'redirect_uri'  => $config["urlPath"] . "/systems/ajax/oauth.php?network=yandex",
  //       'response_type' => 'code',
  //       'state'         => '123'
  //   );
     
  //   $results['auth_social_list'][] = ["name"=>"Yandex","image"=>$settings["path_other"].'/media_social_yandex_61627.png',"link"=>"https://oauth.yandex.ru/authorize", "params"=>$params];  
  // }

  // if( in_array( "vk" , $authorization_social_list ) ){

  //   $social_auth_params = json_decode(decrypt($settings["social_auth_params"]), true);

  //   $params = array(
  //     'client_id'     => $social_auth_params["vk"]["id_client"],
  //     'redirect_uri'  => $config["urlPath"] . "/systems/ajax/oauth.php?network=vk",
  //     'scope'         => 'email',
  //     'response_type' => 'code',
  //     'state'         => $config["urlPath"] . "/auth",
  //   );
     
  //   $results['auth_social_list'][] = ["name"=>"VKontakte","image"=>$settings["path_other"].'/media_social_vk_vkontakte_icon_124252.png',"link"=>"https://oauth.vk.com/authorize","params"=>$params];
  // }

  // if( in_array( "google" , $authorization_social_list ) ){

  //   $social_auth_params = json_decode(decrypt($settings["social_auth_params"]), true);

  //   $params = array(
  //       'client_id'     => $social_auth_params["google"]["id_client"],
  //       'redirect_uri'  => $config["urlPath"] . "/systems/ajax/oauth.php?network=google",
  //       'response_type' => 'code',
  //       'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
  //       'state'         => '123'
  //   );
     
  //   $results['auth_social_list'][] = ["name"=>"Google","image"=>$settings["path_other"].'/media_social_google_62736.png',"link"=>"https://accounts.google.com/o/oauth2/auth", "params"=>$params]; 
  // }  

  // if( in_array( "fb" , $authorization_social_list ) ){

  //   $social_auth_params = json_decode(decrypt($settings["social_auth_params"]), true);

  //   $params = array(
  //     'client_id'     => $social_auth_params["fb"]["id_app"],
  //     'scope'         => 'email',
  //     'redirect_uri'  => $config["urlPath"] . "/systems/ajax/oauth.php?network=fb",
  //     'response_type' => 'code',
  //   );
     
  //   $results['auth_social_list'][] = ["name"=>"Facebook","image"=>$settings["path_other"].'/media_social_facebook_59205.png',"link"=>"https://www.facebook.com/dialog/oauth", "params"=>$params];   
  // }

  $results['service_pages']['user_agreement'] = $settings["app_user_agreement_link"];
  $results['service_pages']['privacy_policy'] = $settings["app_privacy_policy_link"];

  $results['profile_count_out_ads'] = 10;

  $results['create_review']['count_photo'] = 10;

  $results['referral_program_status'] = $settings["referral_program_status"] ? true : false;

  // Catalog map

  $results['catalog_map_view_status'] = $settings["main_type_products"] == 'physical' ? true : false;

  // Secure

  $results['secure']['payment_service'] = $settings["secure_payment_service_name"] ? $settings["secure_payment_service_name"] : null;
  $results['secure']['payment_balance'] = $settings["secure_payment_balance"] ? true : false;
  $results['secure']['status'] = $settings["secure_status"] ? true : false;
  $results['secure']['add_card'] = $settings["secure_payment_service"]["secure_add_card"] ? true : false;

  if($settings["secure_payment_service"]["secure_score_type"][0] == 'wallet'){
    $results['secure']['score_title'] = apiLangContent('Укажите счет кошелька').' '.$settings["secure_payment_service"]["name"];                  
  }elseif($settings["secure_payment_service"]["secure_score_type"][0] == 'card'){
    $results['secure']['score_title'] = apiLangContent('Укажите счет банковской карты');                   
  }else{
    $results['secure']['score_title'] = apiLangContent('Укажите счет банковской карты');
  }

  $results['secure']['dispute']['count_photo'] = 5;

  // User Stories

  $results['stories']['status'] = $settings["user_stories_status"] ? true : false;
  $results['stories']['moderation'] = $settings["user_stories_moderation"] ? true : false;
  $results['stories']['paid_add'] = $settings["user_stories_paid_add"] ? true : false;
  $results['stories']['price_add'] = $settings["user_stories_price_add"];
  $results['stories']['free_add'] = $settings["user_stories_free_add"];
  $results['stories']['video_length'] = $settings["user_stories_video_length"];
  $results['stories']['image_length'] = $settings["user_stories_image_length"];
  $results['stories']['period_add'] = $settings["user_stories_period_add"];

  $results['stories']['free_add_title'] = apiLangContent("Бесплатно доступно")." ".$settings["user_stories_free_add"] . ' ' . ending($settings["user_stories_free_add"], apiLangContent('размещение'), apiLangContent('размещения'), apiLangContent('размещений'));

  // Widgets home screen

  $app_widgets_home_screen = $settings["app_widgets_home_screen"] ? json_decode($settings["app_widgets_home_screen"], true) : [];

  if($app_widgets_home_screen){
     foreach ($app_widgets_home_screen as $value) {
         $results['widgets_home_screen'][$value] = true;
     }
  }else{
    $results['widgets_home_screen']['ads'] = true;
  }

  $app_widgets_home_tabs = $settings["app_widgets_home_tabs"] ? json_decode($settings["app_widgets_home_tabs"], true) : [];
  
  if($app_widgets_home_tabs){
     foreach ($app_widgets_home_tabs as $tab => $check) {
         if($tab == "feed"){
            if($check){
                $results['widgets_home_tabs'][] = ['name'=>apiLangContent('Лента'),'id'=>'feed'];
            }
         }elseif($tab == "recommendations_ads"){
            if($check){
                $results['widgets_home_tabs'][] = ['name'=>apiLangContent('Рекомендации'),'id'=>'recommendations_ads'];
            }
         }elseif($tab == "fresh_ads"){
            if($check){
                $results['widgets_home_tabs'][] = ['name'=>apiLangContent('Свежие'),'id'=>'fresh_ads'];
            }
         }elseif($tab == "auction"){
            if($check){
                $results['widgets_home_tabs'][] = ['name'=>apiLangContent('Аукционы'),'id'=>'auction'];
            }
         }elseif($tab == "shops"){
            if($check){
                $results['widgets_home_tabs'][] = ['name'=>apiLangContent('Магазины'),'id'=>'shops'];
            }
         }
     }
  }

  // Chat

  if($settings["app_chat_snippets_message"]){
    $results['chat_snippets_message'] = json_decode($settings["app_chat_snippets_message"], true);
  }else{
    $results['chat_snippets_message'] = null;
  }

  // Recommended price balance

  if($settings["app_balance_list_amounts"]){
    $results['balance_list_amounts'] = explode(',', $settings["app_balance_list_amounts"]);
  }else{
    $results['balance_list_amounts'] = null;
  }

  // Create ad

  $results['create_ad']['count_photo'] = $settings["count_images_add_ad"];
  $results['create_ad']['change_currency_price'] = $settings['ads_currency_price'] ? true : false;
  $results['create_ad']['change_period_publication'] = $settings["ad_create_period"] ? true : false;
  $results['create_ad']['added_phone'] = $settings["ad_create_phone"] ? true : false;
  $results['create_ad']['moderation'] = $settings["ads_publication_moderat"] ? true : false;
  $results['create_ad']['max_length_text'] = $settings["ad_create_length_text"];
  $results['create_ad']['max_length_title'] = $settings["ad_create_length_title"];
  $results['create_ad']['currency'] = $settings["ad_create_currency"] ? true : false;

  if($settings["ad_create_period_list"]){
     foreach (explode(",", $settings["ad_create_period_list"]) as $value) {
         $results['create_ad']['list_period_publication'][] = ['name'=>$value.' '.ending($value, apiLangContent('день'), apiLangContent('дня'), apiLangContent('дней')), 'count'=>$value];
     }
  }

  // Promo pages

  $results['home_promo_slider_status'] = (int)$settings["app_home_promo_slider_status"] ? true : false;
  $results['home_promo_slider_list'] = json_decode($settings["app_home_promo_slider_list"], true) ? json_decode($settings["app_home_promo_slider_list"], true) : null;

  // Promo banners

  $results['home_promo_banner_list'] = json_decode($settings["app_home_promo_banner_list"], true) ? json_decode($settings["app_home_promo_banner_list"], true) : null;

  // Services pages

  $results['services_pages'][] = ['name'=>'Правила подачи объявлений', 'link'=>_link('rules')];
  $results['services_pages'][] = ['name'=>'Пользовательское соглашение', 'link'=>_link('polzovatelskoe-soglashenie')];
  $results['services_pages'][] = ['name'=>'Запрещенные к публикации товары/услуги', 'link'=>_link('prohibited')];
  $results['services_pages'][] = ['name'=>'Политики конфиденциальности', 'link'=>_link('privacy-policy')];  

  // get all categories

  $results['all_categories_board'] = $CategoryBoard->getCategories("where category_board_visible=1");

  return $results;

}

function breadcrumbCategories($getCategories=[],$id=0){

  $ULang = new ULang();

  if($getCategories){

    if($getCategories["category_board_id"][$id]['category_board_id_parent']!=0){
        $return[] = breadcrumbCategories($getCategories,$getCategories["category_board_id"][$id]['category_board_id_parent']);  
    }

    $return[] = $ULang->tApp($getCategories["category_board_id"][$id]['category_board_name'], [ "table" => "uni_category_board", "field" => "category_board_name"]);

    return implode(' - ',$return);

  } 
           
}

function apiOutAdAddress($data=[]){

    if($data["ads_area_ids"]) $getArea = getOne("select * from uni_city_area_variants INNER JOIN `uni_city_area` ON `uni_city_area`.city_area_id = `uni_city_area_variants`.city_area_variants_id_area where city_area_variants_id_area=? and city_area_variants_id_ad=?",[$data["ads_area_ids"],$data["ads_id"]]);

    $address = [];

    if($data["ads_address"]){

        if(strpos(mb_strtolower($data["ads_address"], 'UTF-8'), mb_strtolower($data["region_name"], 'UTF-8')) === false){
            $address[] = $data["region_name"];
        }

        if(strpos(mb_strtolower($data["ads_address"], 'UTF-8'), mb_strtolower($data["city_name"], 'UTF-8')) === false){
            $address[] = $data["city_name"];
        }  

        $address[] = $data["ads_address"];    
        
        if(isset($getArea)) $address[] = apiLangContent("р-н") . "" . $getArea["city_area_name"];

        return implode(', ', $address);

    }else{
        if(isset($getArea)){
            return $data["region_name"] . ", " . $data["city_name"] . ", " . apiLangContent("р-н") . " " . $getArea["city_area_name"];
        }else{
            return $data["region_name"] . ", " . $data["city_name"];
        }
    }

}

  function apiOutAdAddressArea($data=[]){

    global $settings;

    $getArea = [];

    if($settings["main_type_products"] == 'physical'){

        if($data["ads_area_ids"]){
            $getArea = getOne("select * from uni_city_area_variants INNER JOIN `uni_city_area` ON `uni_city_area`.city_area_id = `uni_city_area_variants`.city_area_variants_id_area where city_area_variants_id_area=? and city_area_variants_id_ad=?",[$data["ads_area_ids"],$data["ads_id"]]); 
        }

        if($getArea["city_area_name"]){
            return $data["city_name"].', '.apiLangContent("р-н").' '.$getArea["city_area_name"];
        }else{
            return $data["city_name"]; 
        }

    }

  }

function apiOutPrice($param = []){
   
    $Main = new Main();

    if($param["data"]["ads_price_old"]){
        if(!isset($param["shop"])) $param["data"]["ads_price_old"] = 0;
    }

    if($param["data"]["ads_price_old"]){
      return ['old'=>apiPrice($param["data"]["ads_price_old"], $param["data"]["ads_currency"], $param["abbreviation_million"]), 'now'=>apiAdPrefixPrice(apiPrice($param["data"]["ads_price"], $param["data"]["ads_currency"], $param["abbreviation_million"]),$param["data"])];
    }elseif($param["data"]["ads_price"]){
      return ['now'=>apiAdPrefixPrice(apiPrice($param["data"]["ads_price"], $param["data"]["ads_currency"], $param["abbreviation_million"]),$param["data"]), 'old' => 0];
    }else{
       if($param["data"]["ads_price_free"]){
           return ['now'=>apiLangContent('Даром'), 'old' => 0];
       }else{ 
           return ['now'=>apiLangContent('Цена не указана'), 'old' => 0];
       }
    }

}

function apiAdPrefixPrice($price,$data=[],$html=true){

    $priceJoin = '';

    if($data){
        if($data['ads_price_from']){
            $priceJoin = apiLangContent('от');
        }
        $priceJoin .= ' '.$price.' ';
        if($data['ads_price_measure']){
            $priceJoin .= apiLangContent('за').' '.apiLangContent(getNameMeasuresPrice($data['ads_price_measure']));
        }            
        return trim($priceJoin);
    }

    return $price;
}

function apiAdOutCurrency($price=0, $currency=""){
  global $settings;

  $Main = new Main();

  $results = [];

  $get = getAll("SELECT * FROM uni_currency WHERE code!=?", [$currency]);

  if($get && $settings["ads_currency_price"] && $settings["currency_json"]){
     
     foreach ($get as $value) {
        $result = $Main->currencyConvert( [ "summa" => $price, "from" => $currency, "to" => $value["code"] ] );
        if($result) $results[$value["code"]] = $result;
     }

  }

  return $results;

}

function apiArrayDataAds($data,$idUser=0){

    global $config;

    $results = [];

    $Ads = new Ads();
    $Shop = new Shop();
    $Profile = new Profile();
    $ULang = new ULang();

    if($data['count']){
        foreach ($data['all'] as $value) {

            $value = $Ads->getDataAd($value);

            if($idUser) insert("INSERT INTO uni_ads_views_display_temp(ad_id,user_id)VALUES(?,?)", array($value["ads_id"],$idUser));

            $link_images = [];
            $active_services = [];
            $services = [];
            $markers = [];

            $images = $Ads->getImages($value["ads_images"]);
            $getShop = $Shop->getUserShop($value["ads_id_user"]);
            $active_services = $Ads->getOrderServiceIds($value["ads_id"]);
            $getUserFavorite = findOne('uni_favorites', 'favorites_id_ad=? and favorites_from_id_user=?', [$value['ads_id'],$idUser]);

            if($images){
                foreach ($images as $img) {
                    $link_images[] = Exists($config["media"]["small_image_ads"],$img,$config["media"]["no_image"]);
                }
            }

            if(in_array(1, $active_services)){
                $markers['top'] = ['name'=>apiLangContent('Топ'),'icon'=>'https://cdn-icons-png.flaticon.com/512/3272/3272612.png'];
            }else{
                $markers['top'] = null;
            }

            if(in_array(2, $active_services)){
                $markers['vip'] = ['name'=>apiLangContent('Вип'),'icon'=>'https://cdn-icons-png.flaticon.com/512/1200/1200781.png'];
            }else{
                $markers['vip'] = null;
            }

            if(in_array(3, $active_services)){
                $markers['turbo'] = ['name'=>apiLangContent('Турбо'),'icon'=>'https://cdn-icons-png.flaticon.com/512/1356/1356479.png'];
            }else{
                $markers['turbo'] = null;
            }

            if($Ads->getStatusSecure($value)){
                $markers['secure'] = ['name'=>'Secure','icon'=>'https://cdn-icons-png.flaticon.com/512/3643/3643948.png'];
            }else{
                $markers['secure'] = null;
            }

            if($Ads->getStatusDelivery($value)){
                $markers['delivery'] = ['name'=>'Secure','icon'=>'https://cdn-icons-png.flaticon.com/512/2936/2936939.png'];
            }else{
                $markers['delivery'] = null;
            }

            if($value['ads_auction']){
                $markers['auction'] = ['name'=>'Secure','icon'=>'https://cdn-icons-png.flaticon.com/512/7989/7989306.png'];
            }else{
                $markers['auction'] = null;
            }

            if($Ads->getStatusBooking($value)){
                $markers['booking'] = ['name'=>'Booking','icon'=>'https://cdn-icons-png.flaticon.com/512/668/668278.png'];
            }else{
                $markers['booking'] = null;
            }

            if(time() >= strtotime($value["ads_auction_duration"])){
                $seconds_completed = 0;
            }else{
                $seconds_completed = abs(time() - strtotime($value["ads_auction_duration"]));
            }

            $results[] = [
                "ads_id" => $value['ads_id'],
                "ads_status" => $value['ads_status'],
                "ads_status_name" => apiPublicationAndStatus($value),
                "ads_title" => $value['ads_title'],
                "ads_text" => $value['ads_text'],
                "lat" => $value['ads_latitude'] ? $value['ads_latitude'] : $value['city_lat'],
                "lon" => $value['ads_longitude'] ? $value['ads_longitude'] : $value['city_lng'],               
                "ads_price" => apiOutPrice(['data'=>$value, 'shop'=>$getShop, 'abbreviation_million'=>true]),
                "city_name" => $ULang->tApp( $value['city_name'] , [ "table"=>"geo", "field"=>"geo_name" ] ),
                "city_area" => apiOutAdAddressArea($value),
                "count_view" => $value['ads_count_view'],
                "ads_images" => $link_images ?: [$config["urlPath"].'/'.$config["media"]["no_image"]],
                "count_images" => count($link_images),
                "ads_datetime_add" => datetime_format($value["ads_datetime_add"], false),
                "link" => $Ads->alias($value),
                "markers" => $markers ?: null,
                "in_favorites" => $getUserFavorite ? true : false,
                "delivery_status" => $Ads->getStatusDelivery($value),
                "vip" => $value['ads_vip'] ? true : false,
                "auction" => [
                    "status" => $value['ads_auction'] ? true : false,
                    "duration" => date('Y-m-d H:i:s', strtotime($value['ads_auction_duration'])),
                    "seconds_completed" => $seconds_completed,
                    "completed" => time() > strtotime($value['ads_auction_duration']) ? true : false, 
                ],                
                "user" => apiArrayDataUser($value),
            ];
        }
    }

    return $results;    

}

function apiArrayDataAd($data,$idUser){

    global $config,$settings;

    $results = [];

    $Ads = new Ads();
    $Shop = new Shop();
    $Profile = new Profile();
    $ULang = new ULang();

    $link_images = [];
    $active_services = [];
    $services = [];
    $markers = [];

    $images = $Ads->getImages($data["ads_images"]);
    $getShop = $Shop->getUserShop($data["ads_id_user"]);
    $active_services = $Ads->getOrderServiceIds($data["ads_id"]);
    $getUserFavorite = findOne('uni_favorites', 'favorites_id_ad=? and favorites_from_id_user=?', [$data['ads_id'],$idUser]);

    if($images){
        foreach ($images as $img) {
            $link_images[] = Exists($config["media"]["small_image_ads"],$img,$config["media"]["no_image"]);
        }
    }

    if(in_array(1, $active_services)){
        $markers['top'] = ['name'=>apiLangContent('Топ'),'icon'=>'https://cdn-icons-png.flaticon.com/512/3272/3272612.png'];
    }else{
        $markers['top'] = null;
    }

    if(in_array(2, $active_services)){
        $markers['vip'] = ['name'=>apiLangContent('Вип'),'icon'=>'https://cdn-icons-png.flaticon.com/512/1200/1200781.png'];
    }else{
        $markers['vip'] = null;
    }

    if(in_array(3, $active_services)){
        $markers['turbo'] = ['name'=>apiLangContent('Турбо'),'icon'=>'https://cdn-icons-png.flaticon.com/512/1356/1356479.png'];
    }else{
        $markers['turbo'] = null;
    }

    if($Ads->getStatusSecure($data)){
        $markers['secure'] = ['name'=>'Secure','icon'=>'https://cdn-icons-png.flaticon.com/512/3643/3643948.png'];
    }else{
        $markers['secure'] = null;
    }

    if($Ads->getStatusDelivery($data)){
        $markers['delivery'] = ['name'=>'Delivery','icon'=>'https://cdn-icons-png.flaticon.com/512/2936/2936939.png'];
    }else{
        $markers['delivery'] = null;
    }

    if($value['ads_auction']){
        $markers['auction'] = ['name'=>'Auction','icon'=>'https://cdn-icons-png.flaticon.com/512/7989/7989306.png'];
    }else{
        $markers['auction'] = null;
    }

    if($Ads->getStatusBooking($data)){
        $markers['booking'] = ['name'=>'Booking','icon'=>'https://cdn-icons-png.flaticon.com/512/668/668278.png'];
    }else{
        $markers['booking'] = null;
    }

    if(time() >= strtotime($value["ads_auction_duration"])){
        $seconds_completed = 0;
    }else{
        $seconds_completed = abs(time() - strtotime($value["ads_auction_duration"]));
    }

    return [
        "ads_id" => $data['ads_id'],
        "ads_status" => $data['ads_status'],
        "ads_status_name" => apiPublicationAndStatus($data),
        "ads_title" => $data['ads_title'],
        "ads_text" => $data['ads_text'],
        "lat" => $data['ads_latitude'] ? $data['ads_latitude'] : $data['city_lat'],
        "lon" => $data['ads_longitude'] ? $data['ads_longitude'] : $data['city_lng'],               
        "ads_price" => apiOutPrice(['data'=>$data, 'shop'=>$getShop, 'abbreviation_million'=>true]),
        "city_name" => $ULang->t( $data['city_name'] , [ "table"=>"geo", "field"=>"geo_name" ] ),
        "city_area" => apiOutAdAddressArea($data),
        "count_view" => $data['ads_count_view'],
        "ads_images" => $link_images ?: [$config["urlPath"].'/'.$config["media"]["no_image"]],
        "count_images" => count($link_images),
        "ads_datetime_add" => datetime_format($data["ads_datetime_add"], false),
        "link" => $Ads->alias($data),
        "markers" => $markers ?: null,
        "in_favorites" => $getUserFavorite ? true : false,
        "delivery_status" => $Ads->getStatusDelivery($data),
        "vip" => $value['ads_vip'] ? true : false,
        "auction" => [
            "status" => $data['ads_auction'] ? true : false,
            "duration" => date('Y-m-d H:i:s', strtotime($data['ads_auction_duration'])),
            "seconds_completed" => $seconds_completed,
            "completed" => time() > strtotime($data['ads_auction_duration']) ? true : false, 
        ],      
        "booking" => [
            "status" => $Ads->getStatusBooking($data),
            "variant" => (int)$data["category_board_booking_variant"],
            "prepayment" => (int)$data["ads_booking_prepayment_percent"],
        ],                  
        "user" => apiArrayDataUser($data),
    ];

}

function apiArrayDataUser($data=[], $viewPhone = false){
    global $config,$settings;

    $Profile = new Profile();
    $Shop = new Shop();

    $results = [];
    $userShop = [];

    if($data){

        if($viewPhone){
            $phone = $data['clients_phone'] && $data["clients_view_phone"] ? '+'.$data['clients_phone'] : null;
        }else{
            $phone = null;
        }

        $countReviews = (int)getOne("select count(*) as total from uni_clients_reviews where clients_reviews_id_user=?", [$data["clients_id"]])["total"];

        $getShop = $Shop->getShop(['user_id'=>$data["clients_id"],'conditions'=>true]);

        if($getShop){
            $userShop = ['id'=>$getShop['clients_shops_id']];
        }

        $results = [
            "id" => $data['clients_id'],
            "display_name" => $Profile->name($data),
            "name" => $data['clients_name'],
            "surname" => $data['clients_surname'] ?: '',
            "avatar" => $Profile->userAvatar($data),
            "rating" => $Profile->ratingBalls($data['clients_id']),
            "reviews" => $countReviews,
            "reviews_label" => $countReviews . ' ' . ending($countReviews, apiLangContent('отзыв'),apiLangContent('отзыва'),apiLangContent('отзывов')),
            "id_hash" => $data['clients_id_hash'],
            "status" => $data['clients_status'],
            "mode_online" => modeOnline($data['clients_datetime_view']),
            "date" => apiLangContent('На').' '.$settings["site_name"].' '.apiLangContent('с').' '.date("d.m.Y", strtotime($data["clients_datetime_add"])),
            "shop" => $userShop ?: null,
            "phone" => $phone,
            "verification_status" => $data["clients_verification_status"] ? true : false,
        ];
    }

    return $results;
}

function apiArrayDataReviews($data){

    global $config;

    $Profile = new Profile();

    $results = [];
    $link_images = [];

    if($data){

        $getUser = findOne('uni_clients', 'clients_id=?', [$data['clients_reviews_from_id_user']]);
        $getAd = findOne('uni_ads', 'ads_id=?', [$data['clients_reviews_id_ad']]);

        if($data["clients_reviews_status_result"] == 1){
            $status_deal = apiLangContent("Сделка состоялась");
        }elseif($data["clients_reviews_status_result"] == 2){
            $status_deal = apiLangContent("Сделка сорвалась");
        }elseif($data["clients_reviews_status_result"] == 3){
            $status_deal = apiLangContent("Не договорились");
        }elseif($data["clients_reviews_status_result"] == 4){
            $status_deal = apiLangContent("Не удалось связаться");
        }

        if($data["clients_reviews_files"]){
             $images = explode(",", $data["clients_reviews_files"]);
             foreach ($images as $image) {
                if(file_exists($config["basePath"] . "/" . $config["media"]["user_attach"] . "/" . $image)){
                    $link_images[] = $config["urlPath"] . "/" . $config["media"]["user_attach"] . "/" . $image;
                }
             }
        }

        if($getUser && $getAd){
            $results = [
                "id" => $data['clients_reviews_id'],
                "text" => $data['clients_reviews_text'],
                "date" => datetime_format($data["clients_reviews_date"], false),
                "rating" => $data['clients_reviews_rating'],
                "status_deal" => $status_deal,
                "images" => $link_images ?: null,
                "ad" => [
                    "id" => $getAd['ads_id'],
                    "title" => $getAd['ads_title'],
                ],
                "user" => [
                    "id" => $getUser['clients_id'],
                    "name" => $getUser['clients_name'],
                    "surname" => $getUser['clients_surname'],
                    "display_name" => $Profile->name($getUser),
                    "avatar" => $Profile->userAvatar($getUser),
                ],
            ];          
        }

    }

    return $results;

}

function modeOnline($date){
    if((strtotime($date) + 180) > time()){
      return true;
    }else{
      return false;
    }    
}

function api_datetime_format($string, $time = true) {

 if (is_numeric($string)) {
    $string = date( "Y-m-d H:i:s", $string );
 }

 $monn = array(
   '',
   apiLangContent('янв'),
   apiLangContent('фев'),
   apiLangContent('мар'),
   apiLangContent('апр'),
   apiLangContent('мая'),
   apiLangContent('июн'),
   apiLangContent('июл'),
   apiLangContent('авг'),
   apiLangContent('сент'),
   apiLangContent('окт'),
   apiLangContent('ноя'),
   apiLangContent('дек')
 );

 $a = preg_split("/[^\d]/",$string); 
 $today = date('Ymd');
 if(($a[0].$a[1].$a[2])==$today) {

   return(apiLangContent("сегодня в")." ".$a[3].":".$a[4]);
   
 } else {
   $b = explode("-",date("Y-m-d"));
   $tom = date("Ymd",mktime(0,0,0,$b[1],$b[2]-1,$b[0]));
   if(($a[0].$a[1].$a[2])==$tom) {
     
     return(apiLangContent("вчера в")." ".$a[3].":".$a[4]);
     
   } else {

     $mm = intval($a[1]);
     if($time){
        if($a[0] == date('Y')){
            return(ltrim($a[2],'0')." ".$monn[$mm].", ".$a[3].":".$a[4]);
        }else{
            return(ltrim($a[2],'0')." ".$monn[$mm]." ".$a[0].", ".$a[3].":".$a[4]);
        }
     }else{
        if($a[0] == date('Y')){
            return(ltrim($a[2],'0')." ".$monn[$mm]); 
        }else{
            return(ltrim($a[2],'0')." ".$monn[$mm]." ".$a[0]);
        }
     }

   }
 }

}

function apiStructureAdVariantsFilters($filters=[]){
    $results = [];
    if(count($filters)){
        foreach ($filters as $key => $value) {
           if(trim($value['item'])) $results[$value['filterId']][] = $value['item'];
        }
    }
    return $results;
}

function apiStructureFilters($filters=[]){
    $results = [];
    if($filters){
        foreach ($filters as $key => $value) {
           if($value['item']) $results[$value['filterId']] = $value['item'];
        }
    }
    return $results;
}

function apiStructureFiltersCatalog($filters=[]){
    $results = [];
    if($filters){
        foreach ($filters as $key => $value) {
           if($value['item']) $results[$value['filterId']][] = $value['item'];
        }
    }
    return $results;
}

function apiPodfilters($id_filter=0, $changeFilters=[], $getFilters=[]){

   $Filters = new Filters();
       
   if(isset($getFilters["id_parent"][$id_filter])){

      foreach ($getFilters["id_parent"][$id_filter] as $parent_value) {

         $items = [];

         $getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=? and ads_filters_items_id_item_parent=? order by ads_filters_items_sort asc", [$parent_value["ads_filters_id"],$changeFilters[$id_filter]]);

         if($getItems){

            foreach ($getItems as $item) {
                $ids = [];
                $ids_podfilter = $Filters->idsBuild($parent_value["ads_filters_id"],$getFilters);
                if($ids_podfilter){
                    foreach (explode(',', $ids_podfilter) as $id) {
                        $ids[] = $id;
                    }
                }                
                $items[] = ['name'=>$item['ads_filters_items_value'], 'id'=>$item['ads_filters_items_id'], 'podfilter'=>findOne('uni_ads_filters_items', 'ads_filters_items_id_item_parent=?', [$item['ads_filters_items_id']]) ? true : false, 'ids_podfilter'=>$ids ?: null];
            }

            $results[] = [
                'id' => $parent_value["ads_filters_id"],
                'view' => $parent_value["ads_filters_type"],
                'name' => $parent_value["ads_filters_name"],
                'items' => $items,
                'required' => $parent_value['ads_filters_required'] ? true : false,
                'podfilter' => findOne('uni_ads_filters', 'ads_filters_id_parent=?', [$parent_value['ads_filters_id']]) ? true : false,
            ];

         }


        if(isset($changeFilters[$parent_value["ads_filters_id"]])){
            $parentFilter = apiPodfilters($parent_value["ads_filters_id"], $changeFilters, $getFilters);
            if($parentFilter){
                $results = array_merge($results, $parentFilter);
            }
        }

      }

   }

   return $results ? $results : [];

}

function apiSecureStatusLabel( $data = [], $userId=0 ){

    if($data["secure_status"] == 0){ 

      return apiLangContent('Ожидается оплата');

    }elseif($data["secure_status"] == 1){

          return apiLangContent('Заказ оплачен');

    }elseif($data["secure_status"] == 2){

          if($data["secure_id_user_buyer"] == $userId){

             return apiLangContent('Подтвердите получение');

          }elseif($data["secure_id_user_seller"] == $userId){
            
             return apiLangContent('Ожидаем подтверждение покупателя');

          }

    }elseif($data["secure_status"] == 3){

          return apiLangContent('Заказ завершён');

    }elseif($data["secure_status"] == 4){

          return apiLangContent('Открыт спор');

    }elseif($data["secure_status"] == 5){

          return apiLangContent('Заказ отменен');

    }


}

function apiSecureResultPay( $data = [] ){

    $getPayment = findOne("uni_secure_payments", "secure_payments_id_user=? and secure_payments_id_order=? and secure_payments_status!=?", [ $data["id_user"], $data["id_order"], 0 ]);

    if($getPayment){
        if($getPayment["secure_payments_status_pay"] == 0){
          return ['status'=>true, 'desc'=>apiPrice($getPayment["secure_payments_amount_percent"]).' '.apiLangContent('будут зачислены на ваш счет в течении 24 часа')];
        }elseif($getPayment["secure_payments_status_pay"] == 1){
          return ['status'=>true, 'desc'=>apiPrice($getPayment["secure_payments_amount_percent"]).' '.apiLangContent('зачислены на ваш счет')];
        }elseif($getPayment["secure_payments_status_pay"] == 2){
          return ['status'=>false, 'desc'=>apiLangContent('Не удалось перевести деньги. Проверьте номер счета. При возникновении трудностей с зачислением средств, напишите в службу поддержки')];
        }
    }

}

function apiSecureBookingStatusLabel( $data = [], $dataAd = [], $userId=0 ){

    if($data["ads_booking_id_user_from"] == $userId){

        if($data["ads_booking_status"] == 0){ 

          if($dataAd["ads_booking_prepayment_percent"]){

              if(!$data["ads_booking_status_pay"]){

                  return apiLangContent('Ожидает предоплату');

              }else{

                  return apiLangContent('Ожидает подтверждения');

              }

          }else{
              
              return apiLangContent('Ожидает подтверждения');

          }

        }elseif($data["ads_booking_status"] == 1){

              return apiLangContent('Заказ подтвержден');

        }elseif($data["ads_booking_status"] == 2){

              return apiLangContent('Заказ отменен');

        }

    }elseif($data["ads_booking_id_user_to"] == $userId){
        
        if($data["ads_booking_status"] != 2){

          if($dataAd["ads_booking_prepayment_percent"]){

              if(!$data["ads_booking_status_pay"]){
                return apiLangContent('Ожидает предоплату');
              }else{

                if($data["ads_booking_status"] == 0){
                    return apiLangContent('Предоплата получена');
                }else{
                    return apiLangContent('Заказ подтвержден');
                }

              }

          }else{
             if($data["ads_booking_status"] == 0){
                return apiLangContent('Ожидает подтверждения');
             }else{
                return apiLangContent('Заказ подтвержден'); 
             }
          }

        }else{
            return apiLangContent('Заказ отменен');                           
        }
                        
    }

}

function apiGetUserStories($userId=0,$city_id=0,$region_id=0,$country_id=0,$cat_id=0){

    global $config;

    $Profile = new Profile();
    $CategoryBoard = new CategoryBoard();
    $getCategories = $CategoryBoard->getCategories("where category_board_visible=1");

    $results = [];
    $query = [];
    $imageStory = "";

    if($cat_id){
        $ids_cat = idsBuildJoin($CategoryBoard->idsBuild($cat_id, $getCategories), $cat_id);
        $query[] = "clients_stories_media_cat_id IN(".$ids_cat.")";
    }

    if($city_id || $region_id || $country_id){
        if($city_id){
            $query[] = "(clients_stories_media_city_id='".$city_id."' or (clients_stories_media_city_id=0 and clients_stories_media_region_id=0 and clients_stories_media_country_id=0))";
        }elseif($region_id){
            $query[] = "(clients_stories_media_region_id='".$region_id."' or (clients_stories_media_city_id=0 and clients_stories_media_region_id=0 and clients_stories_media_country_id=0))";
        }elseif($country_id){
            $query[] = "(clients_stories_media_country_id='".$country_id."' or (clients_stories_media_city_id=0 and clients_stories_media_region_id=0 and clients_stories_media_country_id=0))";
        }
    }

    $getUserStories = getAll('select * from uni_clients_stories order by clients_stories_timestamp desc limit 100');

    if(count($getUserStories)){
        foreach ($getUserStories as $value) {

            $getUser = findOne('uni_clients', 'clients_id=?', [$value['clients_stories_user_id']]);

            if($userId == $value['clients_stories_user_id']){
                $getLastStory = findOne('uni_clients_stories_media', 'clients_stories_media_user_id=? and clients_stories_media_loaded=? order by clients_stories_media_timestamp desc', [$value['clients_stories_user_id'],1]);
            }else{
                if($query){
                    $getLastStory = findOne('uni_clients_stories_media', 'clients_stories_media_user_id=? and clients_stories_media_loaded=? and clients_stories_media_status=? and '.implode(" and ",$query).' order by clients_stories_media_timestamp desc', [$value['clients_stories_user_id'],1,1]);
                }else{
                    $getLastStory = findOne('uni_clients_stories_media', 'clients_stories_media_user_id=? and clients_stories_media_loaded=? and clients_stories_media_status=? order by clients_stories_media_timestamp desc', [$value['clients_stories_user_id'],1,1]);
                }
            }

            if($getLastStory && $getUser){

                if($getLastStory['clients_stories_media_type'] == 'image'){
                    if(file_exists($config['basePath'].'/'.$config['media']['user_stories'].'/'.$getLastStory['clients_stories_media_name']) && $getLastStory['clients_stories_media_name']){
                        $imageStory = $config['urlPath'].'/'.$config['media']['user_stories'].'/'.$getLastStory['clients_stories_media_name'];
                    }
                }else{
                    if(file_exists($config['basePath'].'/'.$config['media']['user_stories'].'/'.$getLastStory['clients_stories_media_preview']) && $getLastStory['clients_stories_media_preview']){
                        $imageStory = $config['urlPath'].'/'.$config['media']['user_stories'].'/'.$getLastStory['clients_stories_media_preview'];
                    }
                }

                $results[] = ['id'=>$getUser['clients_id'],'name'=>$Profile->name($getUser),'image'=>$imageStory ? $imageStory : $Profile->userAvatar($getUser),'timestamp'=>strtotime($value['clients_stories_timestamp']), 'loaded'=>$value['clients_stories_loaded']];

            }

        }
    }

    return $results;

}

function apiViewAds($id = 0, $id_user = 0, $ip = ""){
    if($ip){
        $getView = findOne('uni_ads_views', 'ads_views_id_ad=? and ads_views_ip=?', [$id,$ip]);
        if(!$getView){
            insert("INSERT INTO uni_ads_views(ads_views_id_ad,ads_views_date,ads_views_id_user,ads_views_ip)VALUES(?,?,?,?)", array($id,date("Y-m-d H:i:s"),$id_user,$ip));
            update("update uni_ads set ads_count_view=ads_count_view+1 where ads_id=?", [$id]); 
        }    
    }  
 }

 function apiLangContent($text=""){
    global $config;
    $iso = $_GET["lang_iso"] ? $_GET["lang_iso"] : $_POST["lang_iso"];
    if($iso){

        if(file_exists($config["basePath"]."/lang/{$iso}/app.php")){
            $langContent = include $config["basePath"]."/lang/{$iso}/app.php";
            if($langContent[md5($text)]){
                return $langContent[md5($text)];
            }
        }

    }
    return $text;
 }

 function apiLangTemplate($iso=""){
    global $config;
    $content = [];
    if($iso){
        $langTemplate = include $config["basePath"]."/static/app.php";
        if(file_exists($config["basePath"]."/lang/{$iso}/app.php")){
            $langContent = include $config["basePath"]."/lang/{$iso}/app.php";
            foreach ($langTemplate as $value) {
                $content[$value] = $langContent[md5($value)];
            }
        }
    }
    return $content;
 }

 function apiGetTariff($userData=[]){

    $results = [];

    if($userData['clients_tariff_id']){
        
        $getTariff = findOne('uni_services_tariffs', 'services_tariffs_id=?', [$userData['clients_tariff_id']]);

        if($getTariff){

            $getTariffOrder = findOne('uni_services_tariffs_orders', 'services_tariffs_orders_id_tariff=? and services_tariffs_orders_id_user=?', [$userData['clients_tariff_id'],$userData['clients_id']]);

            if($getTariffOrder){
                if($getTariffOrder["services_tariffs_orders_days"]){
                   if(strtotime($getTariffOrder["services_tariffs_orders_date_completion"]) > time()){
                      $status = true;
                   }else{
                      $status = false;
                   }
                }else{
                    $status = true;
                }
            }else{
                $status = false;
            }

            $count_days = difference_days_array($getTariffOrder["services_tariffs_orders_date_completion"],date("Y-m-d H:i:s"));

            if($count_days["count"]!=0){
               if($count_days["format"] == "d"){
                  $count_days = $count_days["count"] . ' ' . ending($count_days["count"], apiLangContent('день'), apiLangContent('дня'), apiLangContent('дней'));
               }elseif($count_days["format"] == "h"){
                  $count_days = $count_days["count"] . ' ' . ending($count_days["count"], apiLangContent('час'), apiLangContent('часа'), apiLangContent('часов'));
               }elseif($count_days["format"] == "m"){
                  $count_days = $count_days["count"] . ' ' . ending($count_days["count"], apiLangContent('минуту'), apiLangContent('минуты'), apiLangContent('минут'));
               }
            }else{
               $count_days = apiLangContent('Истек срок');
            }

            $results["data"] = ["services"=>$getTariff['services_tariffs_services'], "name"=>$getTariff['services_tariffs_name'], "status"=>$status, "date_completion"=>date('d.m.Y',strtotime($getTariffOrder["services_tariffs_orders_date_completion"])),"autorenewal"=>$userData["clients_tariff_autorenewal"], "count_days" => $count_days];
            
            if($results["data"]['services']){
                $results["data"]['services'] = json_decode($results["data"]['services'], true);
                foreach ($results["data"]['services'] as $id) {
                    $getChecklist = findOne('uni_services_tariffs_checklist', 'services_tariffs_checklist_id=?', [$id]);
                    if($getChecklist) $results["services"][$getChecklist['services_tariffs_checklist_uid']] = ["name"=>$getChecklist["services_tariffs_checklist_name"],"desc"=>$getChecklist["services_tariffs_checklist_desc"],"uid"=>$getChecklist["services_tariffs_checklist_uid"]];
                }
            }

        }
        
    }
    
    return $results;

 }

 function appUsersActionStatistics($user_id=0, $ad_id=0){

    $data = [];

    if($ad_id){
        $get = getAll('select * from uni_action_statistics where action_statistics_to_user_id=? and action_statistics_ad_id=?', [$user_id,$ad_id]);
    }else{
        $get = getAll('select * from uni_action_statistics where action_statistics_to_user_id=?', [$user_id]);
    }

    if(count($get)){
        foreach ($get as $value) {
            $getUser = findOne("uni_clients", "clients_id=?", [$value["action_statistics_from_user_id"]]);
            if($getUser) $data[$value['action_statistics_from_user_id']] = $getUser;
        }
    }
    return $data;
 }

 function apiDataActionStatistics($action='', $ad_id=0, $date_start=0, $date_end=0, $user_id=0){

        $data = [];
        $days = [];
        $months = [];
        $years = [];
        $format = 'Y-m-d';


        if($date_start && $date_end){

            if(strtotime($date_end) > strtotime($date_start)){

                if(date("Y-m", strtotime($date_start)) == date("Y-m", strtotime($date_end))){
                    
                    $difference = difference_days($date_end,$date_start);

                    $days[ date($format, strtotime($date_start)) ] = date($format, strtotime($date_start));

                    $x=0;
                    while ($x++<$difference){
                       $days[ date($format, strtotime("+".$x." day", strtotime($date_start))) ] = date($format, strtotime("+".$x." day", strtotime($date_start)));
                    }

                    ksort($days);

                }elseif(date("Y", strtotime($date_start)) == date("Y", strtotime($date_end))){

                    $months[ date("Y-m", strtotime($date_start)) ] = date("Y-m", strtotime($date_start));

                    $new_m = (int)date("m", strtotime($date_end)) - (int)date("m", strtotime($date_start));

                    $x=0;
                    while ($x++<$new_m){
                       $months[ date("Y-m", strtotime("+".$x." month", strtotime($date_start))) ] = date("Y-m", strtotime("+".$x." month", strtotime($date_start)));
                    }   
                  
                }else{

                    $years[ date("Y", strtotime($date_start)) ] = date("Y", strtotime($date_start));

                    $new_y = (int)date("Y", strtotime($date_end)) - (int)date("Y", strtotime($date_start));

                    $x=0;
                    while ($x++<$new_y){
                       $years[ date("Y", strtotime("+".$x." year", strtotime($date_start))) ] = date("Y", strtotime("+".$x." year", strtotime($date_start)));
                    } 

                }

            }

        }elseif($date_start){

            $days[ date($format, strtotime($date_start)) ] = date($format, strtotime($date_start));

        }


        if(!$days && !$months && !$years){
            $x=0;
            while ($x++<30){
               $days[ date($format, strtotime("-".$x." day")) ] = date($format, strtotime("-".$x." day"));
            }

            $days[ date($format) ] = date($format);

            ksort($days);
        }

        if($action == 'display'){

            if($ad_id){
                if($days){
                    foreach ($days as $value) {
                        $data[$value] = (int)getOne("select sum(ads_views_display_count) as total from uni_ads_views_display where ads_views_display_id_user=? and date(ads_views_display_date)=? and ads_views_display_id_ad=?", [$user_id,$value,$ad_id])["total"];
                    }
                }elseif($months){
                    foreach ($months as $value) {
                        $explode = explode('-', $value);
                        $data[$value] = (int)getOne("select sum(ads_views_display_count) as total from uni_ads_views_display where ads_views_display_id_user=? and YEAR(ads_views_display_date)=? and MONTH(ads_views_display_date)=? and ads_views_display_id_ad=?", [$user_id,$explode[0],$explode[1],$ad_id])["total"];
                    }
                }elseif($years){
                    foreach ($years as $value) {
                        $data[$value] = (int)getOne("select sum(ads_views_display_count) as total from uni_ads_views_display where ads_views_display_id_user=? and YEAR(ads_views_display_date)=? and ads_views_display_id_ad=?", [$user_id,$value,$ad_id])["total"];
                    }
                }
            }else{
                if($days){
                    foreach ($days as $value) {
                        $data[$value] = (int)getOne("select sum(ads_views_display_count) as total from uni_ads_views_display where ads_views_display_id_user=? and date(ads_views_display_date)=?", [$user_id,$value])["total"];
                    }
                }elseif($months){
                    foreach ($months as $value) {
                        $explode = explode('-', $value);
                        $data[$value] = (int)getOne("select sum(ads_views_display_count) as total from uni_ads_views_display where ads_views_display_id_user=? and YEAR(ads_views_display_date)=? and MONTH(ads_views_display_date)=?", [$user_id,$explode[0],$explode[1]])["total"];
                    }
                }elseif($years){
                    foreach ($years as $value) {
                        $data[$value] = (int)getOne("select sum(ads_views_display_count) as total from uni_ads_views_display where ads_views_display_id_user=? and YEAR(ads_views_display_date)=?", [$user_id,$value])["total"];
                    }
                }                
            }

            return $data;

        }elseif($action == 'view'){

            if($ad_id){
                if($days){
                    foreach ($days as $value) {
                        $data[$value] = (int)getOne("select count(*) as total from uni_ads_views where ads_views_id_user=? and date(ads_views_date)=? and ads_views_id_ad=?", [$user_id,$value,$ad_id])["total"];
                    }
                }elseif($months){
                    foreach ($months as $value) {
                        $explode = explode('-', $value);
                        $data[$value] = (int)getOne("select count(*) as total from uni_ads_views where ads_views_id_user=? and YEAR(ads_views_date)=? and MONTH(ads_views_date)=? and ads_views_id_ad=?", [$user_id,$explode[0],$explode[1],$ad_id])["total"];
                    }
                }elseif($years){
                    foreach ($years as $value) {
                        $data[$value] = (int)getOne("select count(*) as total from uni_ads_views where ads_views_id_user=? and YEAR(ads_views_date)=? and ads_views_id_ad=?", [$user_id,$value,$ad_id])["total"];
                    }
                }
            }else{
                if($days){
                    foreach ($days as $value) {
                        $data[$value] = (int)getOne("select count(*) as total from uni_ads_views where ads_views_id_user=? and date(ads_views_date)=?", [$user_id,$value])["total"];
                    }
                }elseif($months){
                    foreach ($months as $value) {
                        $explode = explode('-', $value);
                        $data[$value] = (int)getOne("select count(*) as total from uni_ads_views where ads_views_id_user=? and YEAR(ads_views_date)=? and MONTH(ads_views_date)=?", [$user_id,$explode[0],$explode[1]])["total"];
                    }
                }elseif($years){
                    foreach ($years as $value) {
                        $data[$value] = (int)getOne("select count(*) as total from uni_ads_views where ads_views_id_user=? and YEAR(ads_views_date)=?", [$user_id,$value])["total"];
                    }
                }                
            }

            return $data;

        }elseif($action == 'favorites'){

            return apiGetCountActionStatistics($user_id,$ad_id,$days,$months,$years,'favorite');

        }elseif($action == 'show_phone'){

            return apiGetCountActionStatistics($user_id,$ad_id,$days,$months,$years,'show_phone');

        }elseif($action == 'ad_sell'){

            return apiGetCountActionStatistics($user_id,$ad_id,$days,$months,$years,'ad_sell');

        }elseif($action == 'cart'){

            return apiGetCountActionStatistics($user_id,$ad_id,$days,$months,$years,'add_to_cart');

        }elseif($action == 'booking'){

            return apiGetCountActionStatistics($user_id,$ad_id,$days,$months,$years,'booking');

        }elseif($action == 'date'){

            if($days){
                foreach ($days as $value) {
                    $quotation_month[$value] = '"'.$value.'"';
                }
            }elseif($months){
                foreach ($months as $value) {
                    $quotation_month[$value] = '"'.$value.'"';
                }
            }elseif($years){
                foreach ($years as $value) {
                    $quotation_month[$value] = '"'.$value.'"';
                }
            }

            return implode(',',$quotation_month);

        }
    }

    function apiGetCountActionStatistics($user_id=0, $ad_id=0,$days=[],$months=[],$years=[],$action=''){

        $data = [];

        if($ad_id){
            if($days){
                foreach ($days as $value) {
                    $data[$value] = (int)getOne("select count(*) as total from uni_action_statistics where action_statistics_to_user_id=? and date(action_statistics_date)=? and action_statistics_ad_id=? and action_statistics_action=?", [$user_id,$value,$ad_id,$action])["total"];
                }
            }elseif($months){
                foreach ($months as $value) {
                    $explode = explode('-', $value);
                    $data[$value] = (int)getOne("select count(*) as total from uni_action_statistics where action_statistics_to_user_id=? and YEAR(action_statistics_date)=? and MONTH(action_statistics_date)=? and action_statistics_ad_id=? and action_statistics_action=?", [$user_id,$explode[0],$explode[1],$ad_id,$action])["total"];
                }
            }elseif($years){
                foreach ($years as $value) {
                    $data[$value] = (int)getOne("select count(*) as total from uni_action_statistics where action_statistics_to_user_id=? and YEAR(action_statistics_date)=? and action_statistics_ad_id=? and action_statistics_action=?", [$user_id,$value,$ad_id,$action])["total"];
                }
            }
        }else{
            if($days){
                foreach ($days as $value) {
                    $data[$value] = (int)getOne("select count(*) as total from uni_action_statistics where action_statistics_to_user_id=? and date(action_statistics_date)=? and action_statistics_action=?", [$user_id,$value,$action])["total"];
                }
            }elseif($months){
                foreach ($months as $value) {
                    $explode = explode('-', $value);
                    $data[$value] = (int)getOne("select count(*) as total from uni_action_statistics where action_statistics_to_user_id=? and YEAR(action_statistics_date)=? and MONTH(action_statistics_date)=? and action_statistics_action=?", [$user_id,$explode[0],$explode[1],$action])["total"];
                }
            }elseif($years){
                foreach ($years as $value) {
                    $data[$value] = (int)getOne("select count(*) as total from uni_action_statistics where action_statistics_to_user_id=? and YEAR(action_statistics_date)=? and action_statistics_action=?", [$user_id,$value,$action])["total"];
                }
            }                
        }

        return $data;

    }

    function apiNameInputPrice($variant_price_id){

        if($variant_price_id){
            $get = findOne('uni_variants_price', 'variants_price_id=?', [$variant_price_id]);
            if($get) return apiLangContent($get['variants_price_name']);
        }

        return apiLangContent('Цена');

    }

    function apiArrayDataShops($data=[]){

        global $config;

        $results = [];
        $Ads = new Ads();
        $Profile = new Profile();
        $ULang = new ULang();
        $CategoryBoard = new CategoryBoard();

        $getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

        if($data){
            foreach ($data as $key => $value) {

                $sliders = [];
                $ads_images = [];

                $getCountAds = $Ads->getCount("ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_user='".$value["clients_shops_id_user"]."'");
                $getUser = findOne('uni_clients', 'clients_id=?', [$value['clients_shops_id_user']]);
                $getSliders = getAll("select * from uni_clients_shops_slider where clients_shops_slider_id_shop=?", [$value["clients_shops_id"]]);

                if(count($getSliders)){
                    foreach ($getSliders as $slider) {
                        if(file_exists($config["basePath"] . "/" . $config["media"]["user_attach"] . "/" . $slider["clients_shops_slider_image"])){
                            $sliders[] = $config["urlPath"] . "/" . $config["media"]["user_attach"] . "/" . $slider["clients_shops_slider_image"];
                        }
                    }
                }

                $getAds = getAll("select ads_images from uni_ads where ads_id_user=? and ads_status=? and ads_period_publication > now() order by ads_id desc limit 10", [$value['clients_shops_id_user'],1]);

                if($getAds){
                    shuffle($getAds);

                    foreach (array_slice($getAds, 0,2) as $ad) {
                        $images = $Ads->getImages($ad["ads_images"]);
                        if($images){
                            $ads_images[] = Exists($config["media"]["big_image_ads"],$images[0],$config["media"]["no_image"]);
                        }
                    }
                }

                $results[] = [
                    "id" => $value['clients_shops_id'],
                    "title" => html_entity_decode($value['clients_shops_title']),
                    "desc" => html_entity_decode($value['clients_shops_desc']),
                    "logo" => Exists($config["media"]["other"], $value["clients_shops_logo"], $config["media"]["no_image"]),
                    "count_ads" => $getCountAds .' '.ending($getCountAds, apiLangContent('объявление'), apiLangContent('объявления'), apiLangContent('объявлений')),
                    "count_ads_int" => $getCountAds,
                    "sliders" => $sliders ?: null,
                    "ads_images" => $ads_images ?: null,
                    "category_name" => $getCategoryBoard["category_board_id"][$value["clients_shops_id_theme_category"]] ? $ULang->tApp($getCategoryBoard["category_board_id"][$value["clients_shops_id_theme_category"]]["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name"]) : null,
                    "user" => [
                        "id" => $getUser['clients_id'],
                        "rating" => $Profile->ratingBalls($getUser['clients_id']),
                    ],
                ];

            }
        }

        return $results;

    }

?>