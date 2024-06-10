<?php

$idUser = (int)$_GET["id_user"];
$city_id = (int)$_GET["city_id"];
$region_id = (int)$_GET["region_id"];
$country_id = (int)$_GET["country_id"];

echo json_encode(['data'=>apiGetUserStories($idUser,$city_id,$region_id,$country_id)]);

?>