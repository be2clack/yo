<?php

$lat = clear($_GET["lat"]);
$lng = clear($_GET["lng"]);
$ip = clear($_GET["ip"]);

$city_name = clear($_GET["city_name"]);
$region_name = clear($_GET["region_name"]);
$country_name = clear($_GET["country_name"]);

$results = [];

$getCity = getOne("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_city`.city_name=? and `uni_region`.region_name=?", [$city_name,$region_name]);

if($getCity){
	$results = ['city_name'=>$ULang->tApp($getCity["city_name"], [ "table" => "geo", "field" => "geo_name"]), 'city_id'=>$getCity["city_id"], 'lat'=>$getCity["city_lat"] ?: 0,'lon'=>$getCity["city_lng"] ?: 0, 'declination'=>$ULang->tApp($getCity["city_declination"], [ "table" => "geo", "field" => "geo_name"])];
}

echo json_encode(["data"=>$results ?: null]);
?>