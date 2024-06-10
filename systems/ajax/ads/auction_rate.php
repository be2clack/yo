<?php
if(!$_SESSION["profile"]["id"]){ exit(json_encode(array("status"=>false,"auth" => false))); }

$error = [];

$id = (int)$_POST["id"];
$rate = intval($_POST["rate"]);

if(!$id){  $error[] = $ULang->t("Объявление не определено"); }else{
  $getAd = $Ads->get("ads_id=? and ads_auction=?", [$id,1]);
  if($getAd){
    if( strtotime($getAd["ads_auction_duration"]) <= time() ){
       $error[] = $ULang->t("Ставка не принята. Аукцион завершен!");
    }else{
       if( $rate <= $getAd["ads_price"]){
          $error[] = $ULang->t("Минимальная ставка на данный момент: ") . $Main->price($getAd["ads_price"]) . $ULang->t(". Пожалуйста, повысьте свою ставку!");
       }else{
          if( $getAd["ads_auction_price_sell"] && $rate > $getAd["ads_auction_price_sell"] ){
             $error[] = $ULang->t("Вы не можете сделать ставку, превышающую цену \"Купить сейчас\". По цене \"Купить сейчас\" Вы можете купить лот без торга.");
          }
       }
    }
  }else{
     $error[] = $ULang->t("Для данного товара аукцион не действует!");
  }
}

if(!$error){

    insert("INSERT INTO uni_ads_auction(ads_auction_id_ad,ads_auction_price,ads_auction_id_user,ads_auction_date)VALUES(?,?,?,?)", [$id, $rate, $_SESSION["profile"]["id"],date("Y-m-d H:i:s")]);

    update("update uni_ads set ads_price=? where ads_id=?", [$rate , $id ], true);

    $getRate = getOne("select * from uni_ads_auction INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads_auction`.ads_auction_id_user where ads_auction_id_ad=? and ads_auction_id_user!=? order by ads_auction_price desc", [$id, intval($_SESSION["profile"]["id"])]);
     
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

    echo json_encode( [ "status"=>true,"auth" => true ] );

    $Cache->update("uni_ads");

}else{
  echo json_encode( [ "status"=>false, "answer"=> implode("<br>", $error),"auth" => true ] );
}

?>