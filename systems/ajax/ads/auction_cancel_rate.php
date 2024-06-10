<?php
$id = (int)$_POST["id"];

$getAd = $Ads->get("ads_id=? and ads_auction=? and ads_id_user=?", [$id,1,$_SESSION['profile']['id']]);

if($getAd){

    $getRate = getOne("select * from uni_ads_auction where ads_auction_id_ad=? order by ads_auction_price desc", [$id]);

    update("delete from uni_ads_auction where ads_auction_id_user=? and ads_auction_id_ad=?", [ $getRate["ads_auction_id_user"], $id ]);
    
    $user_winner = getOne("select * from uni_ads_auction INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads_auction`.ads_auction_id_user where ads_auction_id_ad=? order by ads_auction_price desc", [$id]);

    if($user_winner){

        update("update uni_ads set ads_price=? where ads_id=?", [ $user_winner["ads_auction_price"] , $id ], true);

        $data = array("{ADS_LINK}"=>$Ads->alias($getAd),
                      "{ADS_TITLE}"=>$getAd["ads_title"],
                      "{USER_NAME}"=>$user_winner["clients_name"],
                      "{UNSUBSCRIBE}"=>"",
                      "{EMAIL_TO}"=>$user_winner["clients_email"]
                      );

        email_notification( array( "variable" => $data, "code" => "AUCTION_USER_WINNER" ) );   

        $Profile->sendChat( array("id_ad" => $id, "action" => 5, "user_from" => $getAd["ads_id_user"] , "user_to" => $user_winner["clients_id"] ) );      
    
    }else{

       update("update uni_ads set ads_status=? where ads_id=?", [2, $id ], true);

    }

}

$Cache->update("uni_ads");

?>