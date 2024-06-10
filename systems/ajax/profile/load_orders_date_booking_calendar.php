<?php

$id_ad = intval($_POST['id_ad']);
$results = [];
$dates = [];

if($id_ad){
    $getDates = getAll("select * from uni_ads_booking_dates where ads_booking_dates_id_user=? and ads_booking_dates_id_ad=?", [ intval($_SESSION['profile']['id']),$id_ad ]);
}else{
    $getDates = getAll("select * from uni_ads_booking_dates where ads_booking_dates_id_user=?", [ intval($_SESSION['profile']['id']) ]);
}

if($getDates){
    foreach ($getDates as $value) {
    if($value['ads_booking_dates_id_order']){
        $dates[date('Y-m-d', strtotime($value['ads_booking_dates_date']))] += 1;
    }elseif($id_ad){
        $dates[date('Y-m-d', strtotime($value['ads_booking_dates_date']))] = 0;
    }
    }
    foreach ($dates as $date => $orders) {
    $results[$date] = ['count'=>$orders, 'title'=>$orders.' '.ending($orders, $ULang->t('заказ'),$ULang->t('заказа'),$ULang->t('заказов'))];
    }
}

echo json_encode($results);

?>