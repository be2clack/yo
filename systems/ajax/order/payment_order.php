<?php

if(!$_SESSION["profile"]["id"]){ exit(json_encode(["status" => false])); }

$errors = [];

$idAd = (int)$_POST["id_ad"];
$from_balance = (int)$_POST["from_balance"];

$findAd = $Ads->get("ads_id=? and ads_status IN(1,4)", [$idAd]);

$getOrder = $Main->getSecureAdOrder("secure_ads_ad_id=? and secure_status NOT IN(3,5)", [$idAd]);

if($getOrder){

if( $getOrder["secure_status"] == 0 ){
  if( $getOrder["secure_id_user_buyer"] != $_SESSION["profile"]["id"] ){
      echo json_encode( ["status" => false] ); exit;
  }
}else{
  echo json_encode( ["status" => false] ); exit; 
}

$orderId = $getOrder["secure_id_order"];

$delivery['type'] = $getOrder['secure_delivery_type'];
$delivery['delivery_surname'] = $getOrder['secure_delivery_surname'];
$delivery['delivery_name'] = $getOrder['secure_delivery_name'];
$delivery['delivery_patronymic'] = $getOrder['secure_delivery_patronymic'];
$delivery['delivery_phone'] = $getOrder['secure_delivery_phone'];
$delivery['delivery_id_point'] = $getOrder['secure_delivery_id_point'];

}else{

$orderId = generateOrderId();

if($settings["main_type_products"] == 'physical'){

if(!$_POST['delivery_type']){
  $errors[] = $ULang->t("Выберите способ получения");
}else{

    if($_POST['delivery_type'] != 'self'){

      if(!$_POST['delivery_id_point']){
        $errors[] = $ULang->t("Выберите пункт выдачи");
      }

      if(!$_POST['delivery_surname']){
        $errors[] = $ULang->t("Укажите фамилию");
      }

      if(!$_POST['delivery_name']){
        $errors[] = $ULang->t("Укажите имя");
      }

      if(!$_POST['delivery_patronymic']){
        $errors[] = $ULang->t("Укажите отчество");
      }

      if(!$_POST['delivery_phone']){
        $errors[] = $ULang->t("Укажите номер телефона");
      }else{

        $validatePhone = validatePhone($_POST['delivery_phone']);
          if(!$validatePhone['status']){
              $errors[] = $validatePhone['error'];
          }

      }

    }

}

$delivery['type'] = clear($_POST['delivery_type']);
$delivery['delivery_surname'] = clear($_POST['delivery_surname']);
$delivery['delivery_name'] = clear($_POST['delivery_name']);
$delivery['delivery_patronymic'] = clear($_POST['delivery_patronymic']);
$delivery['delivery_phone'] = clear($_POST['delivery_phone']);
$delivery['delivery_id_point'] = clear($_POST['delivery_id_point']);

}

}

if($findAd){

  if($findAd["ads_auction"]){

    if($findAd["ads_status"] == 1){

          if( $findAd["ads_auction_price_sell"] ){
            $price = $findAd["ads_auction_price_sell"];
          }else{
            echo json_encode( ["status" => false] ); exit;
          }

    }elseif($findAd["ads_status"] == 4){

          $auction_user_winner = $Ads->getAuctionWinner($idAd);

          if($_SESSION["profile"]["id"] == $auction_user_winner["ads_auction_id_user"]){
            $price = $findAd["ads_price"];
          }else{
            echo json_encode( ["status" => false] ); exit;
          }

    }

  }else{
     $price = $findAd["ads_price"];
  }

  if($Ads->getStatusSecure($findAd,$price)){

    if(!$errors){
    
        if(!$getOrder){

          if($from_balance){

            if($_SESSION['profile']['data']['clients_balance'] < $price){
               echo json_encode( array( "status" => false, "balance" => false, "balance_total" => $Main->price($_SESSION['profile']['data']['clients_balance']) ) );
               exit;
            }

          }

          smart_insert('uni_secure', ['secure_date'=>date("Y-m-d H:i:s"),'secure_id_user_buyer'=>$_SESSION['profile']['id'],'secure_id_user_seller'=>$findAd["ads_id_user"],'secure_id_order'=>$orderId,'secure_price'=>$price,'secure_delivery_type'=>$delivery['type'],'secure_delivery_name'=>$delivery['delivery_name'],'secure_delivery_surname'=>$delivery['delivery_surname'],'secure_delivery_patronymic'=>$delivery['delivery_patronymic'],'secure_delivery_phone'=>$delivery['delivery_phone'],'secure_delivery_id_point'=>$delivery['delivery_id_point']]);
          smart_insert('uni_secure_ads', ['secure_ads_ad_id'=>$findAd["ads_id"],'secure_ads_count'=>1,'secure_ads_total'=>$price,'secure_ads_order_id'=>$orderId,'secure_ads_user_id'=>$findAd["ads_id_user"]]);
          smart_insert('uni_clients_orders', ['clients_orders_from_user_id'=>$_SESSION["profile"]["id"],'clients_orders_uniq_id'=>$orderId,'clients_orders_date'=>date('Y-m-d H:i:s'),'clients_orders_to_user_id'=>$findAd["ads_id_user"],'clients_orders_secure'=>1]);

          if($settings["main_type_products"] == 'physical'){
            if($findAd["category_board_marketplace"]){
              if(!$findAd["ads_available_unlimitedly"]){
                  if(!$findAd["ads_available"] || $findAd["ads_available"] == 1){
                    update("update uni_ads set ads_status=? where ads_id=?", [4,$idAd], true);
                  }
              }
            }else{
              update("update uni_ads set ads_status=? where ads_id=?", [4,$idAd], true);
            }
          }

          $Profile->sendChat( array("id_ad" => $idAd, "action" => 3, "user_from" => $_SESSION["profile"]["id"], "user_to" => $findAd["ads_id_user"] ) );

        }

        update("update uni_secure set secure_date=? where secure_id_order=?", [date("Y-m-d H:i:s"), $orderId], true);

        if($from_balance){

           $Profile->actionBalance(array("id_user"=>$_SESSION['profile']['id'],"summa"=>$price,"title"=>$static_msg["11"]." №".$orderId,"id_order"=>$orderId),"-");

           if($Ads->getStatusDelivery($findAd) && $delivery["type"] != 'self' && $settings["main_type_products"] == 'physical'){

              $deliveryGoods[] = [
                 'id'=>$findAd['ads_id'],
                 'title'=>$findAd['ads_title'],
                 'cost'=>$price,
              ];   
 
              $deliveryResults = $Delivery->createOrder(["delivery"=>$delivery,"amount"=>$price, "id_user"=>$findAd["ads_id_user"], "goods"=>$deliveryGoods]);

              update("update uni_secure set secure_status=?,secure_delivery_type=?,secure_delivery_invoice_number=?,secure_delivery_track_number=?,secure_delivery_errors=?,secure_balance_payment=? where secure_id_order=?", [ $deliveryResults['invoice_number'] ? 2 : 1, $delivery["type"], $deliveryResults['invoice_number'], $deliveryResults['track_number'], $deliveryResults['errors'], 1, $orderId ]);

           }else{
              update("update uni_secure set secure_status=?,secure_delivery_type=?,secure_balance_payment=? where secure_id_order=?", [ 1, $delivery["type"], 1, $orderId ]);
           }

           if($findAd["ads_auction"] == 1){

             insert("INSERT INTO uni_ads_auction(ads_auction_id_ad,ads_auction_price,ads_auction_id_user)VALUES(?,?,?)", [$findAd["ads_id"], $price, $_SESSION['profile']['id']]);
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

           $Ads->addSecurePayments( ["id_order"=>$orderId, "amount"=>$price, "id_user"=>$_SESSION['profile']['id'], "status_pay"=>1, "status"=>0, "amount_percent"=>$price, "from_balance"=>1] );

           $getAd = $Ads->get("ads_id=?", [$findAd['ads_id']]);

           $param      = array("{USER_NAME}"=>$getAd["clients_name"],
                               "{USER_EMAIL}"=>$getAd["clients_email"],
                               "{ADS_TITLE}"=>$getAd["ads_title"],
                               "{ADS_LINK}"=>$Ads->alias($getAd),
                               "{PROFILE_LINK_ORDER}"=>_link("order/".$orderId),
                               "{UNSUBCRIBE}"=>"",
                               "{EMAIL_TO}"=>$getAd["clients_email"]); 

           $Profile->userNotification( [ "mail"=>["params"=>$param, "code"=>"USER_NEW_BUY", "email"=>$getAd["clients_email"]],"method"=>1 ] );

           echo json_encode( array("status" => true) );

           $Cache->update( "uni_ads" );

           $Admin->notifications("secure");

        }else{

          $html = $Profile->payMethod( $settings["secure_payment_service_name"] , array( "amount" => $price, "id_order" => $orderId, "id_user" => $_SESSION['profile']['id'], "id_user_ad" => $findAd["ads_id_user"], "action" => "secure", "title" => $static_msg["11"]." №".$orderId, "auction" => $findAd["ads_auction"], "id_ad" => $idAd, "ad_price" => $price, 'delivery' => $delivery, "link_success" => _link("order/".$orderId) ) );

          echo json_encode( array( "status" => true, "redirect" => $html ) );

        }

    }else{
        echo json_encode( array( "status" => false, "answer" => implode("\n", $errors) ) );
    }

  }else{
    echo json_encode(["status" => false]);
  }

}else{
 echo json_encode(["status" => false]);
}

?>