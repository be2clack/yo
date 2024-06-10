<?php
defined('unisitecms') or exit();

$getAdAuction = $Ads->getAll( ["query"=>"ads_auction = '1' and ads_auction_duration < now() and ads_status='1' and clients_status!='2' and clients_status!='3' and ads_status!='8'"], [], false );

if( count($getAdAuction["all"]) ){
   foreach ($getAdAuction["all"] as $key => $value) {

      $value = $Ads->getDataAd($value);

      $user_winner = $Ads->getAuctionWinner( $value["ads_id"] );

      if($user_winner){

        $data = array("{ADS_LINK}"=>$Ads->alias($value),
                      "{ADS_TITLE}"=>$value["ads_title"],
                      "{USER_NAME}"=>$user_winner["clients_name"],
                      "{UNSUBSCRIBE}"=>"",
                      "{EMAIL_TO}"=>$user_winner["clients_email"]
                      );

        $Profile->userNotification( [ "mail"=>["params"=>$data, "code"=>"AUCTION_USER_WINNER", "email"=>$user_winner["clients_email"]],"method"=>1 ] );

        $Profile->sendChat( array("id_ad" => $value["ads_id"], "action" => 5, "user_from" => $value["ads_id_user"] , "user_to" => $user_winner["clients_id"] ) );

        update("update uni_ads set ads_status=? where ads_id=?", [4, $value["ads_id"] ], true);

      }else{

        $data = array("{ADS_LINK}"=>$Ads->alias($value),
                      "{ADS_TITLE}"=>$value["ads_title"],
                      "{USER_NAME}"=>$value["clients_name"],
                      "{UNSUBSCRIBE}"=>"",
                      "{EMAIL_TO}"=>$value["clients_email"]
                      );

        email_notification( array( "variable" => $data, "code" => "AUCTION_END_NO_USERS" ) );

        update("update uni_ads set ads_status=? where ads_id=?", [2, $value["ads_id"] ], true);

      }


   }

}


?>