<?php

 $id_ad = (int)$_POST["id"];

 $getAd = $Ads->get("ads_id=? and ads_auction=?", [$id_ad,1]);
 
 if( $getAd["ads_auction_price_sell"] ){

     update( "update uni_ads set ads_status=? where ads_id=?", array(4,$id_ad), true );

     insert("INSERT INTO uni_ads_auction(ads_auction_id_ad,ads_auction_price,ads_auction_id_user,ads_auction_date)VALUES(?,?,?,?)", [$id_ad, $getAd["ads_auction_price_sell"], $_SESSION["profile"]["id"], date("Y-m-d H:i:s")]);

     update("update uni_ads set ads_price=? where ads_id=?", [$getAd["ads_auction_price_sell"] , $id_ad ], true);

     $Profile->sendChat( array("id_ad" => $id_ad, "action" => 3, "user_from" => intval($_SESSION["profile"]["id"]), "user_to" => $getAd["ads_id_user"] ) );

     echo true;

 }

 $Cache->update("uni_ads");

?>