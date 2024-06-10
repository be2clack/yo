<?php

$id = (int)$_POST['id_ad'];
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$findAd = $Ads->get("ads_id=? and ads_status IN(1,4)", [$id]);

$getOrder = $Main->getSecureAdOrder("secure_ads_ad_id=? and secure_status NOT IN(3,5)", [$id]);

if($getOrder){

 exit(json_encode(["order_id"=>$getOrder["secure_id_order"]]));

}else{

 $orderId = generateOrderId();

}

if($findAd){

    if($findAd["ads_auction"]){

        if($findAd["ads_status"] == 1){

            if($findAd["ads_auction_price_sell"]){
                $findAd["ads_price"] = $findAd["ads_auction_price_sell"];
            }else{
                exit(json_encode(["order_id"=>0]));
            }

        }elseif($findAd["ads_status"] == 4){

            $auction_user_winner = $Ads->getAuctionWinner($id);

            if(!$auction_user_winner || $idUser != $auction_user_winner["ads_auction_id_user"]){
                exit(json_encode(["order_id"=>0]));
            }

        }

    }

    if($Ads->getStatusSecure($findAd)){

      smart_insert('uni_secure', ['secure_date'=>date("Y-m-d H:i:s"),'secure_id_user_buyer'=>$idUser,'secure_id_user_seller'=>$findAd["ads_id_user"],'secure_id_order'=>$orderId,'secure_price'=>$findAd["ads_price"]]);

      smart_insert('uni_secure_ads', ['secure_ads_ad_id'=>$findAd["ads_id"],'secure_ads_count'=>1,'secure_ads_total'=>$findAd["ads_price"],'secure_ads_order_id'=>$orderId,'secure_ads_user_id'=>$findAd["ads_id_user"]]);

      smart_insert('uni_clients_orders', ['clients_orders_from_user_id'=>$idUser,'clients_orders_uniq_id'=>$orderId,'clients_orders_date'=>date('Y-m-d H:i:s'),'clients_orders_to_user_id'=>$findAd["ads_id_user"],'clients_orders_secure'=>1]);

      if($settings["main_type_products"] == 'physical'){
        if($findAd["category_board_marketplace"]){
          if(!$findAd["ads_available_unlimitedly"]){
              if(!$findAd["ads_available"] || $findAd["ads_available"] == 1){
                update("update uni_ads set ads_status=? where ads_id=?", [4,$id], true);
              }
          }
        }else{
          update("update uni_ads set ads_status=? where ads_id=?", [4,$id], true);
        }
      }

      $Profile->sendChat( array("id_ad" => $id, "action" => 3, "user_from" => $idUser, "user_to" => $findAd["ads_id_user"] ) );

    }  

}

echo json_encode(["order_id"=>$orderId]);

?>