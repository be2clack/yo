<?php

$idAd = (int)$_POST['id_ad'];
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getAd = $Ads->get("ads_id=? and ads_id_user=?", [$idAd,$idUser]);

if(!$getAd){
	http_response_code(500); exit('Ad not found');
}

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

$price = $getCategoryBoard["category_board_id"][$getAd["ads_id_cat"]]["category_board_price"];

if($getAd["clients_balance"] >= $price){
  
  if($settings["ads_publication_moderat"]){
     update("update uni_ads set ads_status=? where ads_id=?", [0,$idAd], true );
  }else{
  	 $period = date("Y-m-d H:i:s", time() + ($settings["ads_time_publication_default"] * 86400) );
     update("update uni_ads set ads_status=?,ads_period_publication=? where ads_id=?", [1,$period,$idAd], true );
  }

  $Main->addOrder( ["id_ad"=>$idAd,"price"=>$price,"title"=>apiLangContent('Публикация в категорию')." - ".$getAd["category_board_name"],"id_user"=>$idUser,"status_pay"=>1, "link" => $Ads->alias($getAd), "user_name" => $getAd["clients_name"], "id_hash_user" => $getAd["clients_id_hash"], "action_name" => "category"] );

  $Profile->actionBalance(array("id_user"=>$idUser,"summa"=>$price,"title"=>apiLangContent('Публикация в категорию')." - ".$getAd["category_board_name"],"id_order"=>generateOrderId()),"-");

  $images = $Ads->getImages($getAd["ads_images"]);

  $Admin->notifications("ads", ["title" => $getAd["ads_title"], "link" => $Ads->alias($getAd), "image" => $images[0], "user_name" => $getAd["clients_name"], "id_hash_user" => $getAd["clients_id_hash"]]);

  echo json_encode(["status"=>true]);

}else{
  
  echo json_encode(["status"=>false]);

}

?>