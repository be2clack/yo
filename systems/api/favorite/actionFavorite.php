<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$idAd = (int)$_POST["id_ad"];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getFavorite = findOne('uni_favorites', 'favorites_id_ad=? and favorites_from_id_user=?', [$idAd,$idUser]);

if($getFavorite){
	update('delete from uni_favorites where favorites_id_ad=? and favorites_from_id_user=?', [$idAd,$idUser]);
	echo json_encode(['action'=>'delete']);
}else{
	$getAd = findOne('uni_ads', 'ads_id=?', [$idAd]);
	if($getAd){
		smart_insert('uni_favorites',['favorites_id_ad'=>$idAd, 'favorites_from_id_user'=>$idUser, 'favorites_to_id_user'=>$getAd['ads_id_user'], 'favorites_date'=>date('Y-m-d H:i:s')]);
		$Profile->sendChat( array("id_ad" => $idAd, "action" => 1, "user_from" => $idUser, "user_to" => $getAd["ads_id_user"]) );
        $Main->addActionStatistics(['ad_id'=>$idAd,'from_user_id'=>$idUser,'to_user_id'=>$getAd["ads_id_user"]],"favorite");
	}
	echo json_encode(['action'=>'added']);
}	

?>