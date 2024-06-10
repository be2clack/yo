<?php

$id_ad = (int)$_POST['id_ad'];

$dates = [];

$getDates = getAll('select * from uni_ads_booking_dates where ads_booking_dates_id_ad=?', [$id_ad]);

if(count($getDates)){
    foreach ($getDates as $value) {
        $dates[] = date('Y-m-d', strtotime($value['ads_booking_dates_date']));
    }
}

echo json_encode($dates);

?>