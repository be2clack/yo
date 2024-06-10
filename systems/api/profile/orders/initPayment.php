<?php

$id = (int)$_POST["id"];
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
  http_response_code(500); exit('Authorization token error');
}

if(!$settings["secure_payment_service_name"]){
   exit(json_encode(array("status" => false, "answer" => apiLangContent("Платежная система не определена!"))));
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

  update("update uni_secure set secure_delivery_type=?,secure_delivery_name=?,secure_delivery_surname=?,secure_delivery_patronymic=?,secure_delivery_id_point=?,secure_delivery_phone=? where secure_id_order=?", [$delivery['type'],$delivery['delivery_name'],$delivery['delivery_surname'],$delivery['delivery_patronymic'],$delivery['delivery_id_point'],$delivery['delivery_phone'],$id]);

  $answer = $Profile->payMethod( $settings["secure_payment_service_name"] , array( "amount" => $price, "id_order" => $id, "id_user" => $idUser, "auction" => $findAd["ads_auction"], "id_ad" => $getOrderAd['secure_ads_ad_id'], "ad_price" => $price, "action" => "secure", "title" => $static_msg["11"]." №".$id, 'delivery' => $delivery) );

  echo json_encode(["status" => true, "link" => $answer['link']]);

}

?>