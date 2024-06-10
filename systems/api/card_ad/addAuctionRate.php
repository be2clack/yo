<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$id = (int)$_POST['id_ad'];
$rate = (int)$_POST["rate"];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$errors = [];

$getAd = $Ads->get("ads_id=? and ads_auction=?", [$id,1]);

if($getAd){
    if(strtotime($getAd["ads_auction_duration"]) <= time()){
       $errors[] = apiLangContent("Ставка не принята. Аукцион завершен!");
    }else{
       if($rate <= $getAd["ads_price"]){
          $errors[] = apiLangContent("Минимальная ставка на данный момент:") . " " . apiPrice($getAd["ads_price"]) . "." . apiLangContent("Пожалуйста, повысьте свою ставку!");
       }else{
          if($getAd["ads_auction_price_sell"] && $rate > $getAd["ads_auction_price_sell"]){
             $errors[] = apiLangContent("Вы не можете сделать ставку, превышающую цену \"Купить сейчас\". По цене \"Купить сейчас\" Вы можете купить лот без торга.");
          }
       }
    }
}else{
    $errors[] = apiLangContent("Для данного товара аукцион не действует!");
}

if(!$errors){

    insert("INSERT INTO uni_ads_auction(ads_auction_id_ad,ads_auction_price,ads_auction_id_user,ads_auction_date)VALUES(?,?,?,?)", [$id, $rate, $idUser,date("Y-m-d H:i:s")]);

    update("update uni_ads set ads_price=? where ads_id=?", [$rate , $id], true);

    $getRate = getOne("select * from uni_ads_auction INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads_auction`.ads_auction_id_user where ads_auction_id_ad=? and ads_auction_id_user!=? order by ads_auction_price desc", [$id, $idUser]);

    if($getRate){

       $data = array("{ADS_TITLE}"=>$getAd["ads_title"],
                     "{ADS_LINK}"=>$Ads->alias($getAd),
                     "{USER_NAME}"=>$getRate["clients_name"],                          
                     "{UNSUBSCRIBE}"=>"",                          
                     "{EMAIL_TO}"=>$getRate["clients_email"]
                     );

       email_notification( array( "variable" => $data, "code" => "AUCTION_INTERRUPT" ) );

       $Profile->sendChat( array("id_ad" => $id, "action" => 6, "user_from" => $getAd["ads_id_user"] , "user_to" => $getRate["clients_id"] ) );

    }

    echo json_encode(["status"=>true]);

}else{

    echo json_encode(["status"=>false, "answer"=> implode("<br>", $errors)]);

}
?>