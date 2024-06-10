<?php

$id_ad = (int)$_POST["id_ad"];

$getAd = $Ads->get("ads_id=? and ads_id_user=?", [$id_ad,intval($_SESSION['profile']['id'])] );

if($getAd){

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

$price = $getCategoryBoard["category_board_id"][$getAd["ads_id_cat"]]["category_board_price"];

if( $getAd["clients_balance"] >= $price ){
  
  if($settings["ads_publication_moderat"]){
     update("update uni_ads set ads_status=? where ads_id=?", [0,$id_ad], true );
  }else{
     $period = date("Y-m-d H:i:s", time() + ($settings["ads_time_publication_default"] * 86400) );
     update("update uni_ads set ads_status=?,ads_period_publication=? where ads_id=?", [1,$period,$id_ad], true );
  }

  $Main->addOrder( ["id_ad"=>$id_ad,"price"=>$price,"title"=>$static_msg["10"]." - ".$getAd["category_board_name"],"id_user"=>$_SESSION["profile"]["id"],"status_pay"=>1, "link" => $Ads->alias($getAd), "user_name" => $getAd["clients_name"], "id_hash_user" => $getAd["clients_id_hash"], "action_name" => "category"] );

  $Profile->actionBalance(array("id_user"=>$_SESSION['profile']['id'],"summa"=>$price,"title"=>$static_msg["10"]." - ".$getAd["category_board_name"],"id_order"=>generateOrderId()),"-");

  $images = $Ads->getImages($getAd["ads_images"]);

  $Admin->notifications("ads", ["title" => $getAd["ads_title"], "link" => $Ads->alias($getAd), "image" => $images[0], "user_name" => $getAd["clients_name"], "id_hash_user" => $getAd["clients_id_hash"]]);

  echo json_encode( ["status"=>true, "location" => $Ads->alias($getAd)] );

  $Cache->update("uni_ads");

}else{
  
  echo json_encode( ["status"=>false, "balance"=> $Main->price($getAd["clients_balance"]) ] );

}

}

?>