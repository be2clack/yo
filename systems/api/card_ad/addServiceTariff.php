<?php

$idAd = (int)$_POST['id_ad'];
$idTariff = (int)$_POST['id_tariff'];
$countDay = (int)$_POST['count_day'];
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

$adsImages = [];
$adsCollage = [];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getService = findOne("uni_services_ads", "services_ads_uid=?", array($idTariff));

if(!$getService){
	exit(json_encode(["status"=>false, "answer" => apiLangContent("Услуга не найдена.")]));
}

$getAd = $Ads->get("ads_id=? and ads_id_user=?", [$idAd,$idUser] );

if(!$getAd){
	exit(json_encode(["status"=>false, "answer" => apiLangContent("Объявление не найдено.")]));
}

if($getService["services_ads_onetime"]){

  $price = $getService["services_ads_new_price"] ? $getService["services_ads_new_price"] : $getService["services_ads_price"];

  if( $getAd["clients_balance"] >= $price ){

    $Profile->addCollageAdsToStory($getAd,$idUser,$price);

    $Main->addOrder( ["price"=>$price,"title"=>$static_msg["59"],"id_user"=>$idUser,"status_pay"=>1, "user_name" => $getAd['clients_name'], "id_hash_user" => $getAd['clients_id_hash'], "action_name" => "stories"] );

    $Profile->actionBalance(array("id_user"=>$idUser,"summa"=>$price,"title"=>$static_msg["59"],"id_order"=>generateOrderId()),"-");

    echo json_encode( ["status"=>true] );

  }else{
    
    echo json_encode(["status"=>false, "balance"=>false, "answer"=>apiLangContent("Недостаточно средств")]);

  }

  exit;
}

if($getService["services_ads_variant"] == 1){
  $services_order_count_day = $getService["services_ads_count_day"];
  $price = $getService["services_ads_new_price"] ? $getService["services_ads_new_price"] : $getService["services_ads_price"];
}else{
  $services_order_count_day = abs($countDay) ? abs($countDay) : 1;
  $price = $getService["services_ads_new_price"] ? $getService["services_ads_new_price"] * $services_order_count_day : $getService["services_ads_price"] * $services_order_count_day;
}

$services_order_time_validity = date( "Y-m-d H:i:s", strtotime("+".$services_order_count_day." days", time()) );
$title = $getService["services_ads_name"] . " ".apiLangContent("на срок")." " . $services_order_count_day . " " . ending($services_order_count_day, apiLangContent("день"), apiLangContent("дня"), apiLangContent("дней"));

$getServiceOrder = findOne("uni_services_order", "services_order_id_service=? and services_order_id_ads=?", array($idTariff,$idAd));

if(!$getServiceOrder){

	 $getOrderServiceIds = $Ads->getOrderServiceIds($idAd);

	  if( in_array(1, $getOrderServiceIds) || in_array(2, $getOrderServiceIds) ){

	     if($id_s == 3){
	        exit(json_encode(["status"=>false, "answer"=>apiLangContent("Данная услуга уже подключена к вашему объявлению.")]));
	     }

	  }elseif( in_array(3, $getOrderServiceIds) ){
	      exit(json_encode(["status"=>false, "answer"=>apiLangContent("Данная услуга уже подключена к вашему объявлению.")]));
	  }


	 if($getAd["clients_balance"] >= $price){

	 	smart_insert('uni_services_order', ['services_order_id_ads'=>$idAd, 'services_order_time_validity'=>$services_order_time_validity, 'services_order_id_service'=>$idTariff, 'services_order_count_day'=>$countDay, 'services_order_status'=>$getAd["ads_status"], 'services_order_time_create'=>date("Y-m-d H:i:s")]);

	   $Main->addOrder(["id_ad"=>$idAd,"price"=>$price,"title"=>$title,"id_user"=>$getAd['ads_id_user'],"status_pay"=>1, "link" => $Ads->alias($getAd), "user_name" => $getAd["clients_name"], "id_hash_user" => $getAd["clients_id_hash"], "action_name" => "services"]);

	   $Profile->actionBalance(array("id_user"=>$getAd['ads_id_user'],"summa"=>$price,"title"=>$title,"id_order"=>generateOrderId()),"-");

     $Profile->addCollageAdsToStory($getAd,$idUser);

	   exit(json_encode(["status"=>true]));

	 }else{
	   
	   exit(json_encode(["status"=>false, "balance"=>false, "answer"=>apiLangContent("Недостаточно средств")]));

	 }

}elseif(strtotime($getServiceOrder["services_order_time_validity"]) < time()){

	 if($getAd["clients_balance"] >= $price){

	   update("UPDATE uni_services_order SET services_order_time_validity=?,services_order_count_day=?,services_order_status=? WHERE services_order_id=?", array($services_order_time_validity,$services_order_count_day,$getAd["ads_status"],$getServiceOrder["services_order_id"]));

	   $Main->addOrder( ["id_ad"=>$idAd,"price"=>$price,"title"=>$title,"id_user"=>$getAd['ads_id_user'],"status_pay"=>1, "link" => $Ads->alias($getAd), "user_name" => $getAd["clients_name"], "id_hash_user" => $getAd["clients_id_hash"], "action_name" => "services"] );

	   $Profile->actionBalance(array("id_user"=>$getAd['ads_id_user'],"summa"=>$price,"title"=>$title,"id_order"=>generateOrderId()),"-");

     $Profile->addCollageAdsToStory($getAd,$idUser);

	   exit(json_encode(["status"=>true]));

	 }else{
	    exit(json_encode(["status"=>false, "balance"=>false, "answer"=>apiLangContent("Недостаточно средств")]));
	 }

}else{

   exit(json_encode(["status"=>false, "answer"=>apiLangContent("Данная услуга уже подключена к вашему объявлению.")]));

}


?>