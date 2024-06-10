<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$errors = [];

$id_ad = (int)$_POST['id_ad'];
$booking_guests = $_POST['booking_guests'] ? abs($_POST['booking_guests']) : 1;
$busy_dates = [];
$additional_services_total_price = 0;
$booking_hour_count = (int)$_POST['booking_hour_count'] ?: 1;
$booking_hour_start = clear($_POST['booking_hour_start']) ?: '12:00';

$additional_services_list_options = [];
$additional_services_list_name = [];
$additional_services_list = [];

$getAd = $Ads->get("ads_id=?",[$id_ad]);

if(!$getAd) exit();

if($Ads->getStatusBooking($getAd)){

     if($getAd["category_board_booking_variant"] == 1){
         if(!$getAd["ads_booking_available_unlimitedly"]){ 
             if($Ads->adCountActiveRent($getAd["ads_id"]) >= $getAd["ads_booking_available"]){
                 exit(json_encode(['status'=>false, 'answer'=>apiLangContent('Объявление не доступно для аренды/бронирования')])); 
             }
         }
     }

}else{
    exit(json_encode(['status'=>false, 'answer'=>apiLangContent('Объявление не доступно для аренды/бронирования')]));
}

$booking_date_start = $_POST['booking_date_start'] ? date('Y-m-d', strtotime($_POST['booking_date_start'])) : date('Y-m-d');

 if($_POST['booking_date_end']){
    $booking_date_end = date('Y-m-d', strtotime($_POST['booking_date_end']));
 }else{
    if($getAd["ads_booking_min_days"]){ 
        $booking_date_end = date('Y-m-d', strtotime('+'.$getAd["ads_booking_min_days"].' days')); 
    }else{ 
        $booking_date_end = date('Y-m-d', strtotime('+1 days')); 
    }
 }

 $difference_days = difference_days($booking_date_end,$booking_date_start) ?: 1;
 
 if($getAd["ads_booking_additional_services"]){

     $additional_services_list = json_decode($getAd["ads_booking_additional_services"], true);

     foreach ($additional_services_list as $key => $value) {
        $additional_services_list_name[$value["name"]] = $value["price"];
     }

     if($_POST['booking_additional_services']){
        foreach (json_decode($_POST['booking_additional_services'], true) as $key => $value) {
            if($additional_services_list_name[$value["name"]]){
                $additional_services_list_options[$value["name"]] = $additional_services_list_name[$value["name"]];
                $additional_services_total_price += $additional_services_list_name[$value["name"]];
            }
        }
     }

 }

 if($booking_date_start < date('Y-m-d')){
    $errors[] = apiLangContent('Выбранная дата недоступна!');
 }

 if($getAd["category_board_booking_variant"] == 0){

     $total = ($difference_days * $getAd["ads_price"]) + $additional_services_total_price;

     $x=0;
     $dates[] = date('Y-m-d', strtotime($booking_date_start));
     while ($x++<$difference_days){
       $dates[] = date('Y-m-d', strtotime("+".$x." day", strtotime($booking_date_start)));
     }

    if($getAd['ads_booking_min_days'] && $getAd['ads_booking_max_days']){
        if($difference_days < $getAd['ads_booking_min_days'] || $difference_days > $getAd['ads_booking_max_days']){
            $errors[] = apiLangContent('Бронирование доступно от').' '.$getAd['ads_booking_min_days'].' '.apiLangContent('до').' '.$getAd['ads_booking_max_days'].' '.ending($getAd['ads_booking_max_days'], apiLangContent('день'), apiLangContent('дня'), apiLangContent('дней'));
        }                
    }elseif($getAd['ads_booking_min_days']){
        if($difference_days < $getAd['ads_booking_min_days']){
            $errors[] = apiLangContent('Срок бронирования минимум').' '.$getAd['ads_booking_min_days'].' '.ending($getAd['ads_booking_min_days'], apiLangContent('день'), apiLangContent('дня'), apiLangContent('дней'));
        }
    }elseif($getAd['ads_booking_max_days']){
        if($difference_days > $getAd['ads_booking_max_days']){
            $errors[] = apiLangContent('Срок бронирования максимум').' '.$getAd['ads_booking_max_days'].' '.ending($getAd['ads_booking_max_days'], apiLangContent('день'), apiLangContent('дня'), apiLangContent('дней'));
        }
    }

    if($getAd["ads_booking_max_guests"]){
        if($booking_guests > $getAd["ads_booking_max_guests"]){
            $errors[] = apiLangContent('Максимум гостей').' '.$getAd["ads_booking_max_guests"];
        }
    }

    foreach ($dates as $date) {
       if(findOne('uni_ads_booking_dates', 'ads_booking_dates_date=? and ads_booking_dates_id_ad=?', [$date,$id_ad])){
           $busy_dates[] = datetime_format(strtotime($date), false);
       }
    }

    if(count($busy_dates)){
        $errors[] = apiLangContent('По выбранным датам бронирование недоступно!');
    }

    if($booking_date_start > $booking_date_end){
        $errors[] = apiLangContent('Начальная дата не может быть больше конечной!');
    }

}else{

    if(!$getAd["ads_booking_available_unlimitedly"]){
        if($Ads->adCountActiveRent($id_ad) >= $getAd["ads_booking_available"]){
            $errors[] = apiLangContent('Аренда для данного объявления не доступна!');
        }
    }

    if($getAd['ads_price_measure'] == 'hour'){

        $total = ($booking_hour_count * $getAd["ads_price"]) + $additional_services_total_price;

        $booking_date_end = date('Y-m-d', strtotime('+'.$booking_hour_count.' days'));

    }else{

        $total = ($difference_days * $getAd["ads_price"]) + $additional_services_total_price;

        $x=0;
        $dates[] = date('Y-m-d', strtotime($booking_date_start));
        while ($x++<$difference_days){
           $dates[] = date('Y-m-d', strtotime("+".$x." day", strtotime($booking_date_start)));
        }

        foreach ($dates as $date) {
           if(findOne('uni_ads_booking_dates', 'ads_booking_dates_date=? and ads_booking_dates_id_ad=?', [$date,$id_ad])){
               $busy_dates[] = datetime_format(strtotime($date), false);
           }
        }

        if(count($busy_dates)){
            $errors[] = apiLangContent('По выбранным датам аренда недоступна!');
        }

        if($booking_date_start > $booking_date_end){
            $errors[] = apiLangContent('Начальная дата не может быть больше конечной!');
        }

    }

}

if(!$errors){

    $orderId = generateOrderId();

    $insert_id = insert("INSERT INTO uni_ads_booking(ads_booking_id_ad,ads_booking_id_user_from,ads_booking_id_user_to,ads_booking_date_start,ads_booking_date_end,ads_booking_guests,ads_booking_number_days,ads_booking_date_add,ads_booking_additional_services,ads_booking_id_order,ads_booking_total_price,ads_booking_variant,ads_booking_hour_start,ads_booking_hour_count,ads_booking_measure)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", [$id_ad,$idUser,$getAd['ads_id_user'],$booking_date_start,$booking_date_end,$booking_guests,$difference_days,date("Y-m-d H:i:s"),json_encode($additional_services_list_options,JSON_UNESCAPED_UNICODE),$orderId,$total,$getAd["category_board_booking_variant"],$booking_hour_start,$booking_hour_count,$getAd['ads_price_measure']]); 

    if($insert_id){

        if($getAd["category_board_booking_variant"] == 0){
            foreach ($dates as $date) {
               insert("INSERT INTO uni_ads_booking_dates(ads_booking_dates_date,ads_booking_dates_id_ad,ads_booking_dates_id_order,ads_booking_dates_id_cat,ads_booking_dates_id_user)VALUES(?,?,?,?,?)", [$date,$id_ad,$insert_id,$getAd['ads_id_cat'],$getAd['ads_id_user']]);
            }
        }

        if(!$getAd['ads_booking_prepayment_percent']){

             $param      = array("{USER_NAME}"=>$getAd["clients_name"],
                                 "{USER_EMAIL}"=>$getAd["clients_email"],
                                 "{ADS_TITLE}"=>$getAd["ads_title"],
                                 "{ADS_LINK}"=>$Ads->alias($getAd),
                                 "{PROFILE_LINK_ORDER}"=>_link('booking/'.$orderId),
                                 "{UNSUBCRIBE}"=>"",
                                 "{EMAIL_TO}"=>$getAd["clients_email"]);

             if($getAd['category_board_booking_variant'] == 0){
                $Profile->userNotification( [ "mail"=>["params"=>$param, "code"=>"USER_NEW_ORDER_BOOKING", "email"=>$getAd["clients_email"]],"method"=>1 ] );
                $Profile->sendChat( array("id_ad" => $getAd["ads_id"], "action" => 7, "user_from" => $idUser, "user_to" => $getAd["clients_id"] ) );
             }else{
                $Profile->userNotification( [ "mail"=>["params"=>$param, "code"=>"USER_NEW_ORDER_RENT", "email"=>$getAd["clients_email"]],"method"=>1 ] );
                $Profile->sendChat( array("id_ad" => $getAd["ads_id"], "action" => 8, "user_from" => $idUser, "user_to" => $getAd["clients_id"] ) );
             }

        }

        echo json_encode(['status'=>true, 'order_id'=>$orderId]);

    }

}else{
    echo json_encode(['status'=>false, 'answer'=>implode("\n", $errors)]);
}


?>