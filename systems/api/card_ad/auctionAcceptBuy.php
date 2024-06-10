<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$id = (int)$_POST['id_ad'];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$errors = [];

$getAd = $Ads->get("ads_id=? and ads_auction=? and ads_id_user=?", [$id,1,$idUser]);

if($getAd){

  update( "update uni_ads set ads_status=? where ads_id=? and ads_id_user=?", array(5,$id,$idUser), true );

  $Main->addActionStatistics(['ad_id'=>$id,'from_user_id'=>0,'to_user_id'=>$idUser],"ad_sell");

}

echo json_encode(["status"=>true]);
?>