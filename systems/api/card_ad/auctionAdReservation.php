<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$id = (int)$_POST['id_ad'];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$errors = [];

$getAd = $Ads->get("ads_id=? and ads_auction=?", [$id,1]);

if($getAd["ads_auction_price_sell"]){

   update( "update uni_ads set ads_status=? where ads_id=?", array(4,$id), true );

   insert("INSERT INTO uni_ads_auction(ads_auction_id_ad,ads_auction_price,ads_auction_id_user,ads_auction_date)VALUES(?,?,?,?)", [$id, $getAd["ads_auction_price_sell"], $idUser, date("Y-m-d H:i:s")]);

   update("update uni_ads set ads_price=? where ads_id=?", [$getAd["ads_auction_price_sell"] , $id ], true);

   $Profile->sendChat( array("id_ad" => $id, "action" => 3, "user_from" => $idUser, "user_to" => $getAd["ads_id_user"] ) );

}

echo json_encode(["status"=>true]);
?>