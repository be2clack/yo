<?php
defined('unisitecms') or exit();

if($settings['delivery_api_key']){

update('TRUNCATE TABLE `uni_boxberry_cities`');

$getCities = json_decode(file_get_contents('https://lk.boxberry.ru/lap.json/?api_token='.decrypt($settings['delivery_api_key']).'&method=ListCities'), true);

if($getCities){

    foreach ($getCities["data"] as $value) {
        $getCity = findOne('uni_city', 'city_name=?', [$value['name']]);
        if($getCity){
            $getRegion = findOne('uni_region', 'region_id=?', [$getCity['region_id']]);
            insert("INSERT INTO uni_boxberry_cities(boxberry_cities_name,boxberry_cities_code,boxberry_cities_region,boxberry_cities_lat,boxberry_cities_lon)VALUES(?,?,?,?,?)", [$value['name'],$value['code'],$getRegion['region_name'],$getCity['city_lat'],$getCity['city_lng']]);
        }
    }

}

update('TRUNCATE TABLE `uni_boxberry_points`');

$getPoints = json_decode(file_get_contents('https://lk.boxberry.ru/lap.json/?api_token='.decrypt($settings['delivery_api_key']).'&method=ListPoints'), true);

if($getPoints){

    foreach ($getPoints["data"] as $value) {
        $latlon = explode(',', $value['gps']);
        insert("INSERT INTO uni_boxberry_points(boxberry_points_code,boxberry_points_address,boxberry_points_phone,boxberry_points_workshedule,boxberry_points_gps,boxberry_points_city_code,boxberry_points_lat,boxberry_points_lon,boxberry_points_send)VALUES(?,?,?,?,?,?,?,?,?)", [$value['code'],$value['address'],"",$value['schedule'],$value['gps'],$value['city'],$latlon[0],$latlon[1],1]);
    }

}

}
?>