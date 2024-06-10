<?php

$idUser = (int)$_POST["id_user_auth"];
$tokenAuth = clear($_POST["token"]);

$idUserTo = (int)$_POST["id_user_to"];
$idAd = (int)$_POST["id_ad"];
$text = clear($_POST["text"]);
$rating = (int)$_POST["rating"];
$deal = (int)$_POST["deal"];
$attach = isset($_POST["attach"]) ? json_decode($_POST["attach"], true) : [];
$attachList = [];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getAd = findOne("uni_ads", "ads_id=?", [$idAd]);

if(!$getAd){
    http_response_code(500); exit('Ad not found');
}

$errors = [];

if(!$deal){ $errors[] = apiLangContent("Пожалуйста, выберите результат сделки"); }
if(!$rating){ $errors[] = apiLangContent("Пожалуйста, поставьте оценку"); }
if(!$text){ $errors[] = apiLangContent("Пожалуйста, напишите отзыв"); }


if(!count($errors)){

   $getReview = findOne("uni_clients_reviews", "clients_reviews_from_id_user=? and clients_reviews_id_user=? and clients_reviews_id_ad=?", [$idUser, $idUserTo, $idAd]);

   if(!$getReview){

      if(count($attach)){
         foreach ($attach as $data) {

            if(file_exists($config["basePath"] . "/" . $config["media"]["temp_images"]."/".$data['name'])){

                if(copy($config["basePath"] . "/" . $config["media"]["temp_images"]."/".$data['name'], $config["basePath"]."/".$config["media"]["user_attach"]."/".$data['name'])){
                  $attachList[] = $data['name'];
                };
          
            }

         }
      }

      smart_insert('uni_clients_reviews', [
        'clients_reviews_id_user'=>$idUserTo,
        'clients_reviews_text'=>$text,
        'clients_reviews_from_id_user'=>$idUser,
        'clients_reviews_rating'=>$rating,
        'clients_reviews_id_ad'=>$idAd,
        'clients_reviews_status_result'=>$deal,
        'clients_reviews_files'=>implode(',',$attachList),
        'clients_reviews_date'=>date("Y-m-d H:i:s"),
      ]);

      $Profile->sendChat(["id_ad" => $idAd, "action" => 4, "user_from" => $idUser, "user_to" => $idUserTo]);
      
      $Admin->notifications("review");

      echo json_encode(["status"=>true]);

   }else{
      echo json_encode(["status"=>false, "answer"=>apiLangContent("Вы уже оставляли отзыв для данного объявления!")]);
   }

}else{
   echo json_encode(["status"=>false, "answer"=> implode("\n", $errors)]);
}

?>