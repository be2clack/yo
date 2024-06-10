<?php

$id_ad = (int)$_POST["id_ad"];
$id_s = (int)$_POST["id_s"];
$pay_v = (int)$_POST["pay_v"];

$error = [];
$adsImages = [];
$adsCollage = [];

$getService = findOne("uni_services_ads", "services_ads_uid=?", array($id_s)); 
$getAd = $Ads->get("ads_id=? and ads_id_user=?", [$id_ad,intval($_SESSION['profile']['id'])] );

if(!$getService){ $error[] = $ULang->t("Пожалуйста, выберите услугу");}
if(!$getAd){ $error[] = $ULang->t("Товар не найден");}

if($getService["services_ads_onetime"]){

  $price = $getService["services_ads_new_price"] ? $getService["services_ads_new_price"] : $getService["services_ads_price"];

  if( $getAd["clients_balance"] >= $price ){

    $Profile->addCollageAdsToStory($getAd,$_SESSION['profile']['id'],$price);

    $Main->addOrder( ["price"=>$price,"title"=>$static_msg["59"],"id_user"=>$_SESSION['profile']['id'],"status_pay"=>1, "user_name" => $getAd['clients_name'], "id_hash_user" => $getAd['clients_id_hash'], "action_name" => "stories"] );

    $Profile->actionBalance(array("id_user"=>$_SESSION['profile']['id'],"summa"=>$price,"title"=>$static_msg["59"],"id_order"=>generateOrderId()),"-");

    echo json_encode( ["status"=>true] );

  }else{
    
    echo json_encode( ["status"=>false, "balance"=> $Main->price($getAd["clients_balance"]) ] );

  }

}else{

  if($getService["services_ads_variant"] == 1){
  $services_order_count_day = $getService["services_ads_count_day"];
  $price = $getService["services_ads_new_price"] ? $getService["services_ads_new_price"] : $getService["services_ads_price"];
  }else{
  $services_order_count_day = abs($_POST["service"][$id_s]) ? abs($_POST["service"][$id_s]) : 1;
  $price = $getService["services_ads_new_price"] ? $getService["services_ads_new_price"] * $services_order_count_day : $getService["services_ads_price"] * $services_order_count_day;
  }

  $services_order_time_validity = date( "Y-m-d H:i:s", strtotime("+".$services_order_count_day." days", time()) );

  $title = $getService["services_ads_name"] . " " . $ULang->t("на срок") . " " . $services_order_count_day . " " . ending($services_order_count_day, $ULang->t("день"), $ULang->t("дня"), $ULang->t("дней"));

  if(count($error) == 0){

  $getServiceOrder = findOne("uni_services_order", "services_order_id_service=? and services_order_id_ads=?", array($id_s,$id_ad));

  if(!$getServiceOrder){

    $getOrderServiceIds = $Ads->getOrderServiceIds( $id_ad );

     if( in_array(1, $getOrderServiceIds) || in_array(2, $getOrderServiceIds) ){

        if($id_s == 3){
           echo json_encode( ["status"=>false, "answer"=>$ULang->t("Данная услуга уже подключена к вашему объявлению!")] );
           exit;
        }

     }elseif( in_array(3, $getOrderServiceIds) ){
         echo json_encode( ["status"=>false, "answer"=>$ULang->t("Данная услуга уже подключена к вашему объявлению!")] );
         exit;
     }


    if( $getAd["clients_balance"] >= $price ){

      insert("INSERT INTO uni_services_order(services_order_id_ads,services_order_time_validity,services_order_id_service,services_order_count_day,services_order_status,services_order_time_create)VALUES('$id_ad','$services_order_time_validity','$id_s','$services_order_count_day','{$getAd["ads_status"]}','".date("Y-m-d H:i:s")."')");

      $Main->addOrder( ["id_ad"=>$id_ad,"price"=>$price,"title"=>$title,"id_user"=>$_SESSION["profile"]["id"],"status_pay"=>1, "link" => $Ads->alias($getAd), "user_name" => $getAd["clients_name"], "id_hash_user" => $getAd["clients_id_hash"], "action_name" => "services"] );

      $Profile->actionBalance(array("id_user"=>$_SESSION['profile']['id'],"summa"=>$price,"title"=>$title,"id_order"=>generateOrderId()),"-");

      $Profile->addCollageAdsToStory($getAd,$_SESSION['profile']['id']);

      echo json_encode( ["status"=>true] );

    }else{
      
      echo json_encode( ["status"=>false, "balance"=> $Main->price($getAd["clients_balance"]) ] );

    }



  }elseif( strtotime($getServiceOrder["services_order_time_validity"]) < time() ){
    
    if( $getAd["clients_balance"] >= $price ){

      update("UPDATE uni_services_order SET services_order_time_validity=?,services_order_count_day=?,services_order_status=? WHERE services_order_id=?", array($services_order_time_validity,$services_order_count_day,$getAd["ads_status"],$getServiceOrder["services_order_id"]));

      $Main->addOrder( ["id_ad"=>$id_ad,"price"=>$price,"title"=>$title,"id_user"=>$_SESSION["profile"]["id"],"status_pay"=>1, "link" => $Ads->alias($getAd), "user_name" => $getAd["clients_name"], "id_hash_user" => $getAd["clients_id_hash"], "action_name" => "services"] );

      $Profile->actionBalance(array("id_user"=>$_SESSION['profile']['id'],"summa"=>$price,"title"=>$title,"id_order"=>generateOrderId()),"-");

      $Profile->addCollageAdsToStory($getAd,$_SESSION['profile']['id']);

      echo json_encode( ["status"=>true] );

    }else{
      
      echo json_encode( ["status"=>false, "balance" => $Main->price($getAd["clients_balance"]) ] );

    }

  }else{

      echo json_encode( ["status"=>false, "answer"=>$ULang->t("Данная услуга уже подключена к вашему объявлению!")] );

  }


  }else{

    echo json_encode( ["status"=>false, "answer"=>implode("\n", $error)] );

  }


}

?>