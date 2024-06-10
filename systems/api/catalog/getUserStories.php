<?php

$city_id = (int)$_GET["city_id"];
$region_id = (int)$_GET["region_id"];
$country_id = (int)$_GET["country_id"];
$cat_id = (int)$_GET["cat_id"];

echo json_encode(['data'=>apiGetUserStories(0,$city_id,$region_id,$country_id,$cat_id)]);

?>