<?php
defined('unisitecms') or exit();

$getChatMessages = $Admin->getAllMessagesSupport(true);

if($getChatMessages){
    $Admin->notifications("chat_message");
}

$getOrderAd = getAll("select * from uni_services_order INNER JOIN `uni_services_ads` ON `uni_services_ads`.services_ads_uid = `uni_services_order`.services_order_id_service where services_order_time_validity < now() and services_order_status=1");

if( count($getOrderAd) ){
   foreach ($getOrderAd as $key => $value) {
      $getAd = $Ads->get("ads_id=?", [ $value["services_order_id_ads"] ]);

      $notifications = $Profile->paramNotifications($getAd["clients_notifications"]);

      if($notifications["services"]){

      $data = array("{AD_LINK}"=>'<a href="'.$Ads->alias($getAd).'" >'.$getAd["ads_title"].'</a>',
                   "{USER_NAME}"=>$getAd["clients_name"],
                   "{SERVICE_IMAGE}"=>Exists($config["media"]["other"],$value["services_ads_image"],$config["media"]["no_image"]),
                   "{SERVICE_NAME}"=>$value["services_ads_name"],
                   "{UNSUBCRIBE}"=>"",
                   "{LINK_NOTIFICATIONS}"=>_link("user/".$getAd["clients_id_hash"]."/settings?modal=notifications"),
                   "{EMAIL_TO}"=>$getAd["clients_email"]
                   );

      email_notification( array( "variable" => $data, "code" => "SERVICE_END_AD" ) );

      update("delete from uni_services_order where services_order_id=?", [ $value["services_order_id"] ]);

      }

   }
}

$getMessage = getAll("select * from uni_chat_messages where chat_messages_status=? and chat_messages_notification=? group by chat_messages_id_hash", [0,0]);

if($getMessage){

   foreach ($getMessage as $key => $value) {
      
      $getUser = getOne("select * from uni_chat_users INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_chat_users`.chat_users_id_user where chat_users_id_hash=? and chat_users_id_interlocutor=?", array( $value["chat_messages_id_hash"], $value["chat_messages_id_user"] ));

      $notifications = $Profile->paramNotifications($getUser["clients_notifications"]);

      if($notifications["messages"]){

          $data = array("{PROFILE_LINK}"=>_link("user/".$getUser["clients_id_hash"]),
                        "{USER_NAME}"=>$getUser["clients_name"],
                        "{UNSUBCRIBE}"=>"",
                        "{LINK_NOTIFICATIONS}"=>_link("user/".$getUser["clients_id_hash"]."/settings?modal=notifications"),
                        "{EMAIL_TO}"=>$getUser["clients_email"]
                       );

          $Profile->userNotification( [ "mail"=>["params"=>$data, "code"=>"NEW_MESSAGE", "email"=>$getUser["clients_email"]],"method"=>1 ] );

      }

      firebase_notification($value,"notification_chats");

      update("update uni_chat_messages set chat_messages_notification=? where chat_messages_id_hash=?", [1,$value["chat_messages_id_hash"]]);

   }

}

$getComments = getAll("select * from uni_ads_comments where ads_comments_notification=0 and ads_comments_id_parent!=0");

if(count($getComments)){

   $answers = [];

   foreach ($getComments as $key => $value) {

      $getMsg = findOne("uni_ads_comments", "ads_comments_id=?", [$value["ads_comments_id_parent"]]);

      $getUser = findOne("uni_clients", "clients_id=?", [$getMsg["ads_comments_id_user"]]);

      if($getUser){

          $getAd = $Ads->get("ads_id=?", [$getMsg["ads_comments_id_ad"]]);

          $notifications = $Profile->paramNotifications($getUser["clients_notifications"]);

          if( $notifications["answer_comments"] && !in_array($getUser["clients_email"], $answers[$getMsg["ads_comments_id_ad"]]) ){

              $data = array("{AD_LINK}"=>$Ads->alias($getAd),
                            "{AD_TITLE}"=>$getAd["ads_title"],
                            "{USER_NAME}"=>$getUser["clients_name"],
                            "{UNSUBCRIBE}"=>"",
                            "{LINK_NOTIFICATIONS}"=>_link("user/".$getUser["clients_id_hash"]."/settings?modal=notifications"),
                            "{EMAIL_TO}"=>$getUser["clients_email"]
                           );

              email_notification( array( "variable" => $data, "code" => "NEW_ANSWER_COMMENT" ) );

              $answers[$getMsg["ads_comments_id_ad"]][] = $getUser["clients_email"];

          }

      }

      update("update uni_ads_comments set ads_comments_notification=? where ads_comments_id=?", [1,$value["ads_comments_id"]]);

   }

}

$getComments = getAll("select * from uni_ads_comments where ads_comments_notification=0 and ads_comments_id_parent=0");

if(count($getComments)){

   $answers = [];

   foreach ($getComments as $key => $value) {

      $getMsg = findOne("uni_ads_comments", "ads_comments_id=?", [$value["ads_comments_id_parent"]]);

      $getUser = findOne("uni_clients", "clients_id=?", [$getMsg["ads_comments_id_user"]]);

      if($getUser){

          $getAd = $Ads->get("ads_id=?", [$getMsg["ads_comments_id_ad"]]);

          $notifications = $Profile->paramNotifications($getUser["clients_notifications"]);

          if( $notifications["answer_comments"] && !in_array($getUser["clients_email"], $answers[$getMsg["ads_comments_id_ad"]]) ){

              $data = array("{AD_LINK}"=>$Ads->alias($getAd),
                            "{AD_TITLE}"=>$getAd["ads_title"],
                            "{USER_NAME}"=>$getUser["clients_name"],
                            "{UNSUBCRIBE}"=>"",
                            "{LINK_NOTIFICATIONS}"=>_link("user/".$getUser["clients_id_hash"]."/settings?modal=notifications"),
                            "{EMAIL_TO}"=>$getUser["clients_email"]
                           );

              email_notification( array( "variable" => $data, "code" => "NEW_ADS_COMMENT" ) );

              $answers[$getMsg["ads_comments_id_ad"]][] = $getUser["clients_email"];

          }

      }

      update("update uni_ads_comments set ads_comments_notification=? where ads_comments_id=?", [1,$value["ads_comments_id"]]);

   }

}

$getClientsSubscriptions = getAll("select * from uni_clients_subscriptions limit 500");

if( count($getClientsSubscriptions) ){
    foreach ($getClientsSubscriptions as $key => $value) {
        
        $ads_list = "";

        $getShop = findOne("uni_clients_shops", "clients_shops_id=? and clients_shops_time_validity > now()", [ $value["clients_subscriptions_id_shop"] ]);

        if( $getShop && $value["clients_subscriptions_time_update"] ){

            $results = $Ads->getAll( array( "query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_user='".$value["clients_subscriptions_id_user_to"]."' and ads_datetime_add > '".$value["clients_subscriptions_time_update"]."'" ) );

            if( $results["count"] ){

                   foreach ( array_slice($results["all"], 0,5) as $ad_value) {
                       $image = $Ads->getImages($ad_value["ads_images"]);
                       $ads_list .= '
                           <div class="list-ads" >
                              <div class="list-ads-image" >
                                <img src="'.Exists($config["media"]["small_image_ads"],$image[0],$config["media"]["no_image"]).'"  >
                              </div>
                              <div class="list-ads-content" >
                                <div><a href="'.$Ads->alias($ad_value).'" >'.custom_substr($ad_value["ads_title"], 35).'</a></div>
                                <strong>'.$Main->price($ad_value["ads_price"], $ad_value["ads_currency"]).'</strong>
                              </div>
                              <div class="clr" ></div>
                           </div>
                       ';  
                   }
                  
                  $getUserFrom = findOne("uni_clients", "clients_id=?", [ $value["clients_subscriptions_id_user_from"] ]);
                  

                  $data = array("{SUBSCR_NAME}"=>$getShop["clients_shops_title"],
                                "{SUBSCR_ADS_LIST}"=>$ads_list,
                                "{SUBSCR_ADS_COUNT}"=> $results["count"],
                                "{SUBSCR_ALL_LINK}"=>$Shop->linkShop( $getShop["clients_shops_id_hash"] ),
                                "{UNSUBCRIBE}"=>"",
                                "{EMAIL_TO}"=>$getUserFrom["clients_email"]
                               );

                  email_notification( array( "variable" => $data, "code" => "NEW_ADS_USER" ) );

                  update("update uni_clients_subscriptions set clients_subscriptions_time_update=? where clients_subscriptions_id=?", [ date("Y-m-d H:i:s") ,$value["clients_subscriptions_id"]]);

            }

        }


    }
}


?>