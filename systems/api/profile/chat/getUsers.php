<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

$results = [];
$listUsers = [];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getUsers = getAll("select * from uni_chat_users where chat_users_id_user=? order by chat_users_id desc",[$idUser]);

if(count($getUsers)){
   foreach ($getUsers as $value) {
   	$get = findOne("uni_clients", "clients_id=?", [$value["chat_users_id_interlocutor"]]);
      if($get) $listUsers[$value["chat_users_id_hash"]] = $value;
   }
}

if($listUsers){
  foreach ($listUsers as $key => $value) {

    $getMsg = findOne("uni_chat_messages","chat_messages_id_hash=? order by chat_messages_date desc", array($value["chat_users_id_hash"]));

    if($getMsg){

	    $getMsg["chat_messages_text"] = decrypt($getMsg["chat_messages_text"]);

	    if($getMsg["chat_messages_text"]){
	         $text = custom_substr($getMsg["chat_messages_text"], 60);
	    }else{

	         if($getMsg["chat_messages_attach"]){
	            $attach = json_decode($getMsg["chat_messages_attach"], true);
	            if($attach['voice']){
	                $text = apiLangContent("Голосовое");
	            }elseif($attach['images']){
	                $text = apiLangContent("Фото");
	            }
	         }

	    }

	  	 if($value["chat_users_id_ad"]){

	  	 	   $getAd = $Ads->get("ads_id=?",[$value["chat_users_id_ad"]]);
	  	 	   $getAd["ads_images"] = $Ads->getImages($getAd["ads_images"]);

	  	 	   $getUser = findOne("uni_clients", "clients_id=?", [ $value["chat_users_id_interlocutor"] == $idUser ? $value["chat_users_id_user"] : $value["chat_users_id_interlocutor"] ]);

			      if($getMsg["chat_messages_action"] == 0){

			      }elseif($getMsg["chat_messages_action"] == 1 && $idUser != $getMsg["chat_messages_id_user"]){
			          $text = apiLangContent("Добавлено в избранное");
			      }elseif($getMsg["chat_messages_action"] == 2 && $idUser != $getMsg["chat_messages_id_user"]){
			          $text = apiLangContent("Ваш номер просмотрели");
			      }elseif($getMsg["chat_messages_action"] == 3 && $idUser != $getMsg["chat_messages_id_user"]){
			          $text = apiLangContent("Оформление заказа");
			      }elseif($getMsg["chat_messages_action"] == 4 && $idUser != $getMsg["chat_messages_id_user"]){
			          $text = apiLangContent("У вас новый отзыв");
			      }elseif($getMsg["chat_messages_action"] == 5 && $idUser != $getMsg["chat_messages_id_user"]){
			          $text = apiLangContent("Вы победили в аукционе");
			      }elseif($getMsg["chat_messages_action"] == 6 && $idUser != $getMsg["chat_messages_id_user"]){
			          $text = apiLangContent("Ваша ставка перебита");
			      }

				 $results[] = [
					'id' => $value["chat_users_id_hash"],
					'status_last_message' => $idUser == $getMsg["chat_messages_id_user"] ? $getMsg["chat_messages_status"] : null, 
					'view' => 'ad',
					'date' => date('d.m, H:i', strtotime($getMsg["chat_messages_date"])),
					'text' => $text ?: null,
					'user' => ['name'=>$Profile->name($getUser, false)],
					'countMessages' => $Profile->countChatMessages($value["chat_users_id_hash"],$idUser, false),
					'image' => Exists($config["media"]["small_image_ads"],$getAd["ads_images"][0],$config["media"]["no_image"]),
					'title' => $getAd["ads_title"]
				 ]; 

	  	 }else{

	        $getUser = findOne("uni_clients", "clients_id=?", [ $value["chat_users_id_interlocutor"] == $idUser ? $value["chat_users_id_user"] : $value["chat_users_id_interlocutor"] ]);
	 
				$results[] = [
					'id' => $value["chat_users_id_hash"],
					'status_last_message' => $idUser == $getMsg["chat_messages_id_user"] ? $getMsg["chat_messages_status"] : null, 
					'view' => 'user',
					'date' => date('d.m, H:i', strtotime($getMsg["chat_messages_date"])),
					'text' => $text ?: null,
					'countMessages' => $Profile->countChatMessages($value["chat_users_id_hash"],$idUser, false),
					'image' => $Profile->userAvatar($getUser),
					'title' => $Profile->name($getUser, false)
				];

	  	 }

  	 }


  }
}

echo json_encode(['dialogs'=>$results ?: null, 'count_dialogs'=>count($results), 'support'=>['title1'=>apiLangContent('Поддержка'), 'title2'=>apiLangContent('Будем рады помочь'), 'image'=>$settings["path_tpl_image"].'/supportChat.png', 'countMessages'=>$Profile->countChatMessages(md5('support'.$idUser),$idUser, false)]]);

?>