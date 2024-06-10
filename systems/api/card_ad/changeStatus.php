<?php

$idAd = (int)$_POST['id_ad'];
$status = (int)$_POST['status'];
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

if($status == 5){
    update("update uni_ads set ads_status=? where ads_id=? and ads_id_user=?", array(5,$idAd,$idUser),true);
    $Main->addActionStatistics(['ad_id'=>$idAd,'from_user_id'=>0,'to_user_id'=>$idUser],"ad_sell");	
}elseif($status == 1){
	$period = date("Y-m-d H:i:s", time() + ($settings["ads_time_publication_default"] * 86400) );
    update( "update uni_ads set ads_status=?,ads_period_publication=? where ads_id=? and ads_id_user=?", array(1,$period,$idAd,$idUser), true);
}else{
	update("update uni_ads set ads_status=? where ads_id=? and ads_id_user=?", array($status,$idAd,$idUser),true);
}

echo json_encode(['status'=>true]);
?>