<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$results = [];

$get = $Ads->getAll( [ "navigation" => false, "query" => "ads_id_user='".$idUser."' and ads_auto_renewal='1'", "sort" => "order by ads_id desc" ], [], false );

if($get['count']){
	 foreach ($get['all'] as $key => $value) {
	  	$value = $Ads->getDataAd($value);
	  	$images = $Ads->getImages($value["ads_images"]);
	  	$results[] = ['ad_id'=>$value['ads_id'] ,'title'=>$value['ads_title'],'image'=>Exists($config["media"]["big_image_ads"],$images[0],$config["media"]["no_image"]),'status'=>intval($value['ads_status']),'status_name'=>apiPublicationAndStatus($value)];
	 }
}

echo json_encode($results);

?>