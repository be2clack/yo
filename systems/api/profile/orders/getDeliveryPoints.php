<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

$topLat = clear($_GET["top_lat"]);
$topLon = clear($_GET["top_lon"]);

$bottomLat = clear($_GET["bottom_lat"]);
$bottomLon = clear($_GET["bottom_lon"]);

$results = [];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

if($topLat && $topLon && $bottomLat && $bottomLon){
	$getPoints = getAll("SELECT * FROM uni_boxberry_points WHERE (boxberry_points_lat < ? and boxberry_points_lon < ?) and (boxberry_points_lat > ? and boxberry_points_lon > ?)", [$topLat,$topLon,$bottomLat,$bottomLon]);
}else{
	$getPoints = getAll("SELECT * FROM uni_boxberry_points");
}

if($getPoints){

 foreach($getPoints AS $value){

	  $results[] = [
	  	'lat'=>$value['boxberry_points_lat'],
	  	'lon'=>$value['boxberry_points_lon'],
	  	'address' => $value['boxberry_points_address'],
	  	'workshedule' => $value['boxberry_points_workshedule'],
	  	'points_phone' => $value['boxberry_points_phone'],
	  	'points_code' => $value['boxberry_points_code'],
	  ];

 }   

}

echo json_encode(['data'=>$results]);

?>