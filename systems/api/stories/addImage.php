<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

$file_name = clear($_POST["file_name"]);
$id = (int)$_POST["id"];
$link = $_POST["link"];
$type = clear($_POST["type"]);

$city_id = (int)$_POST["city_id"];
$region_id = (int)$_POST["region_id"];
$country_id = (int)$_POST["country_id"];
$cat_id = (int)$_POST["cat_id"];

$paymentAmount = 0;

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getStory = findOne('uni_clients_stories', 'clients_stories_user_id=?', [$idUser]);

$getUser = findOne("uni_clients", "clients_id=?", [$idUser]);

$getTariff = $Profile->getTariff($idUser);

$path = $config["basePath"] . "/" . $config["media"]["temp_images"];

if(file_exists($path."/".$file_name)){

   if(copy($path."/".$file_name, $config["basePath"]."/".$config["media"]["user_stories"]."/".$file_name)){


      if($settings["user_stories_paid_add"] && $settings["user_stories_price_add"] && !isset($getTariff['services']['stories'])){

           if($settings["user_stories_free_add"]){
             
             if($getUser['clients_count_story_publication'] >= $settings["user_stories_free_add"]){

               if($getUser['clients_balance'] >= $settings["user_stories_price_add"]){ 

                   $paymentAmount = $settings["user_stories_price_add"];
                 
                   $Main->addOrder( ["price"=>$settings["user_stories_price_add"],"title"=>$static_msg["59"],"id_user"=>$idUser,"status_pay"=>1, "user_name" => $getUser['clients_name'], "id_hash_user" => $getUser['clients_id_hash'], "action_name" => "stories"] );

                   $Profile->actionBalance(array("id_user"=>$idUser,"summa"=>$settings["user_stories_price_add"],"title"=>$static_msg["59"],"id_order"=>generateOrderId()),"-");

               }else{
                 
                   exit(json_encode(["status"=>false, "balance"=>false]));

               }

             }

           }else{

             if($getUser['clients_balance'] >= $settings["user_stories_price_add"]){

                 $paymentAmount = $settings["user_stories_price_add"]; 
               
                 $Main->addOrder( ["price"=>$settings["user_stories_price_add"],"title"=>$static_msg["59"],"id_user"=>$idUser,"status_pay"=>1, "user_name" => $getUser['clients_name'], "id_hash_user" => $getUser['clients_id_hash'], "action_name" => "stories"] );

                 $Profile->actionBalance(array("id_user"=>$idUser,"summa"=>$settings["user_stories_price_add"],"title"=>$static_msg["59"],"id_order"=>generateOrderId()),"-");

             }else{
               
                 exit(json_encode(["status"=>false, "balance"=>false]));

             }

           }

      }


      if($getStory){
         update('update uni_clients_stories set clients_stories_loaded=?,clients_stories_timestamp=? where clients_stories_id=?', [1, date("Y-m-d H:i:s"), $getStory['clients_stories_id']]);
      }else{
         smart_insert('uni_clients_stories', [
           'clients_stories_user_id'=>$idUser,
           'clients_stories_loaded'=>1,
           'clients_stories_timestamp'=>date("Y-m-d H:i:s"),
         ]);   
      }

      smart_insert('uni_clients_stories_media', [
        'clients_stories_media_user_id'=>$idUser,
        'clients_stories_media_name'=>$file_name,
        'clients_stories_media_type'=>$type,
        'clients_stories_media_loaded'=>1,
        'clients_stories_media_payment'=>1,
        'clients_stories_media_payment_amount'=>$paymentAmount,
        'clients_stories_media_duration'=>intval($settings["user_stories_image_length"]),
        'clients_stories_media_ad_id'=>$link == 'ad' ? intval($_POST["id"]) : 0,
        'clients_stories_media_status'=>$settings["user_stories_moderation"] ? 0 : 1,
        'clients_stories_media_timestamp'=>date("Y-m-d H:i:s"),
        'clients_stories_media_city_id'=>$city_id,
        'clients_stories_media_region_id'=>$region_id,
        'clients_stories_media_country_id'=>$country_id,
        'clients_stories_media_cat_id'=>$cat_id,
        'clients_stories_media_not_delete'=>$getTariff['services']['stories_without_limitation'] ? 1 : 0,
        'clients_stories_media_date_completion'=>$Profile->userStoryDateCompletion($idUser),
      ]);

      update('update uni_clients set clients_count_story_publication=clients_count_story_publication+1 where clients_id=?', [$idUser]);

      $Admin->notifications("user_story");

   }

}

echo json_encode(["status"=>true, "balance"=>true]);

?>