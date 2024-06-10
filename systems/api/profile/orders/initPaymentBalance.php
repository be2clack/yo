<?php

$id = (int)$_POST["id"];
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
  http_response_code(500); exit('Authorization token error');
}

if($settings["secure_payment_service_name"]){
   if(!$settings["secure_payment_balance"]){
     exit(json_encode(array("status" => false, "answer" => apiLangContent("Ошибка инициализации оплаты"))));
   }
}

if($_POST['delivery_id_point']){
  $delivery['type'] = 'boxberry';
  $delivery['delivery_surname'] = clear($_POST['delivery_surname']);
  $delivery['delivery_name'] = clear($_POST['delivery_name']);
  $delivery['delivery_patronymic'] = clear($_POST['delivery_patronymic']);
  $delivery['delivery_phone'] = clear($_POST['delivery_phone']);
  $delivery['delivery_id_point'] = clear($_POST['delivery_id_point']);
}else{
  $delivery['type'] = 'self';
}

$getOrder = findOne('uni_secure', 'secure_id_order=?', [$id]);

if($getOrder['secure_status'] == 1){
   exit(json_encode(array("status" => false, "answer" => apiLangContent("Заказ уже оплачен"))));
}

$getOrderAd = findOne('uni_secure_ads', 'secure_ads_order_id=?', [$id]);

$findAd = $Ads->get("ads_id=? and ads_status IN(1,4)", [$getOrderAd['secure_ads_ad_id']]);

if(!$findAd){
   exit(json_encode(array("status" => false, "answer" => apiLangContent("Товар не доступен для заказа"))));
}

if(time() > strtotime($getOrder["secure_date"]) + 10*60){

  echo json_encode(["status" => false, "answer" => apiLangContent("Время резервирования истекло, оформите заказ повторно")]);

}else{

  if($findAd["ads_auction"]){

    if($findAd["ads_status"] == 1){

        if($findAd["ads_auction_price_sell"]){
          $price = $findAd["ads_auction_price_sell"];
        }

    }elseif($findAd["ads_status"] == 4){

        $auction_user_winner = $Ads->getAuctionWinner($getOrderAd['secure_ads_ad_id']);

        if($idUser == $auction_user_winner["ads_auction_id_user"]){
          $price = $findAd["ads_price"];
        }

    }

  }else{
     $price = $findAd["ads_price"];
  }

  $getUser = findOne("uni_clients", "clients_id=?", [$idUser]);

  if($getUser['clients_balance'] >= $price){

     $Profile->actionBalance(array("id_user"=>$idUser,"summa"=>$price,"title"=>$static_msg["11"]." №".$id,"id_order"=>$id),"-");

     update("update uni_secure set secure_delivery_type=?,secure_delivery_name=?,secure_delivery_surname=?,secure_delivery_patronymic=?,secure_delivery_id_point=?,secure_delivery_phone=?,secure_status=?,secure_balance_payment=? where secure_id_order=?", [$delivery['type'],$delivery['delivery_name'],$delivery['delivery_surname'],$delivery['delivery_patronymic'],$delivery['delivery_id_point'],$delivery['delivery_phone'],1,1,$id]);

     if($findAd["ads_auction"] == 1){

       insert("INSERT INTO uni_ads_auction(ads_auction_id_ad,ads_auction_price,ads_auction_id_user)VALUES(?,?,?)", [$findAd["ads_id"], $price, $idUser]);
       update("update uni_ads set ads_price=?,ads_status=? where ads_id=?", [ $price, 5 , $findAd["ads_id"] ], true);
     
     }else{

       if($settings["main_type_products"] == 'physical'){
          if($findAd["category_board_marketplace"]){
            if(!$findAd["ads_available_unlimitedly"]){
                if($findAd["ads_available"]){
                  update("update uni_ads set ads_available=ads_available-1 where ads_id=?", [$findAd['ads_id']]);
                  if(!($findAd["ads_available"]-1)){
                    update("update uni_ads set ads_status=? where ads_id=?", [5,$findAd['ads_id']], true);
                  }
                }else{
                  update("update uni_ads set ads_status=? where ads_id=?", [5,$findAd['ads_id']], true);
                }
            }
          }else{
            update("update uni_ads set ads_status=? where ads_id=?", [5,$findAd['ads_id']], true);
          }
       }

     }

     $Ads->addSecurePayments( ["id_order"=>$id, "amount"=>$price, "id_user"=>$idUser, "status_pay"=>1, "status"=>0, "amount_percent"=>$price, "from_balance"=>1] );

     if($Ads->getStatusDelivery($findAd) && $delivery["type"] != 'self' && $settings["main_type_products"] == 'physical'){

        $deliveryGoods[] = [
           'id'=>$findAd['ads_id'],
           'title'=>$findAd['ads_title'],
           'cost'=>$price,
        ];   

        $deliveryResults = $Delivery->createOrder(["delivery"=>$delivery,"amount"=>$price, "id_user"=>$findAd["ads_id_user"], "goods"=>$deliveryGoods]);

        update("update uni_secure set secure_status=?,secure_delivery_invoice_number=?,secure_delivery_track_number=?,secure_delivery_errors=? where secure_id_order=?", [ $deliveryResults['invoice_number'] ? 2 : 1, $deliveryResults['invoice_number'], $deliveryResults['track_number'], $deliveryResults['errors'], $id ]);

     }

     $getAd = $Ads->get("ads_id=?", [$findAd['ads_id']]);

     $param      = array("{USER_NAME}"=>$getAd["clients_name"],
                         "{USER_EMAIL}"=>$getAd["clients_email"],
                         "{ADS_TITLE}"=>$getAd["ads_title"],
                         "{ADS_LINK}"=>$Ads->alias($getAd),
                         "{PROFILE_LINK_ORDER}"=>_link("order/".$id),
                         "{UNSUBCRIBE}"=>"",
                         "{EMAIL_TO}"=>$getAd["clients_email"]); 

     $Profile->userNotification( [ "mail"=>["params"=>$param, "code"=>"USER_NEW_BUY", "email"=>$getAd["clients_email"]],"method"=>1 ] );

     $Cache->update( "uni_ads" );

     $Admin->notifications("secure");

     echo json_encode(["status" => true]);

  }else{
     echo json_encode( array( "status" => false, "balance" => false ) );
     exit;
  }

}

?>