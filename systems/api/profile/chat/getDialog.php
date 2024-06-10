<?php

$support = (int)$_GET["support"];
$idHash = clear($_GET["id"]);
$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

$idAd = (int)$_GET['id_ad'];
$idUserTo = (int)$_GET['id_user_to'];

$results = [];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}


if($idAd){

	$getAd = findOne("uni_ads", "ads_id=?", [$idAd]);

	if($getAd){ 

	   $interlocutor = $Profile->oneUser(" where clients_id=?" , array($getAd['ads_id_user']));
	   if($interlocutor){ 

	       if(findOne("uni_ads", "ads_id=? and (ads_id_user=? or ads_id_user=?)", [$idAd,$idUser,$interlocutor["clients_id"]])){

	           $getUserChat = findOne("uni_chat_users", "chat_users_id_ad=? and chat_users_id_user=? and chat_users_id_interlocutor=?", [$idAd, $idUser, $interlocutor["clients_id"] ]);
	           if(!$getUserChat){
	               $idHash = md5($idAd.$idUser);
	               insert("INSERT INTO uni_chat_users(chat_users_id_ad,chat_users_id_user,chat_users_id_hash,chat_users_id_interlocutor)VALUES(?,?,?,?)", array($idAd, $idUser, $idHash, $interlocutor["clients_id"]));
	           }else{
	               $idHash =  $getUserChat["chat_users_id_hash"];
	           }

	       }
	       
	   }
	}

}elseif($idUserTo){

   $interlocutor = $Profile->oneUser(" where clients_id=?" , array($idUserTo));
   if($interlocutor){ 

       $getUserChat = findOne("uni_chat_users", "chat_users_id_ad=? and ((chat_users_id_user=? and chat_users_id_interlocutor=?) or (chat_users_id_interlocutor=? and chat_users_id_user=?))", [0,$idUser,$idUserTo,$idUser,$idUserTo]);

       if(!$getUserChat){
           $idHash = md5($idUser.$idUserTo);
           insert("INSERT INTO uni_chat_users(chat_users_id_ad,chat_users_id_user,chat_users_id_hash,chat_users_id_interlocutor)VALUES(?,?,?,?)", array(0,$idUser,$idHash,$idUserTo));
       }else{
       		$idHash = $getUserChat["chat_users_id_hash"];
       		if($getUserChat['chat_users_id_interlocutor'] == $idUser){
       			insert("INSERT INTO uni_chat_users(chat_users_id_ad,chat_users_id_user,chat_users_id_hash,chat_users_id_interlocutor)VALUES(?,?,?,?)", array(0,$idUser,$idHash,$idUserTo));
       		}
       }
       
   }			

}


$getUser = findOne("uni_clients", "clients_id=?", [$idUser]);

if(!$support){

   $getChatUser = findOne("uni_chat_users","chat_users_id_hash=? and chat_users_id_user=?", array($idHash,$idUser));

   if($getChatUser["chat_users_id_ad"]){

       $getAd = $Ads->get("ads_id=?", [$getChatUser["chat_users_id_ad"]]);
       $getShop = $Shop->getUserShop($getAd["ads_id_user"]);

       $getAd["ads_images"] = $Ads->getImages($getAd["ads_images"]);

       if( $idHash == md5( $getChatUser["chat_users_id_ad"] . $getChatUser["chat_users_id_interlocutor"] ) || $idHash == md5( $getChatUser["chat_users_id_ad"] . $getChatUser["chat_users_id_user"] ) ){

         update("update uni_chat_messages set chat_messages_status=? where chat_messages_id_hash=? and chat_messages_id_user!=?", array(1,$idHash,$idUser));

         $getDialog = getAll("select * from uni_chat_messages where chat_messages_id_hash=? order by chat_messages_date asc", array($idHash));

         $getLocked = findOne( "uni_clients_blacklist", "clients_blacklist_user_id=? and clients_blacklist_user_id_locked=?", array($idUser,$getChatUser["chat_users_id_interlocutor"]) );

         $getMyLocked = findOne( "uni_clients_blacklist", "clients_blacklist_user_id=? and clients_blacklist_user_id_locked=?", array( $getChatUser["chat_users_id_interlocutor"],$idUser) );

         if(count($getDialog)){

			   foreach ($getDialog as $key => $value) {
		          
		          if($value["chat_messages_action"] == 0){
		              $list[ date("d.m.Y", strtotime( $value["chat_messages_date"] ) ) ][] = $value;
		          }else{
		              if($idUser != $value["chat_messages_id_user"]){
		              	 $list[ date("d.m.Y", strtotime( $value["chat_messages_date"] ) ) ][] = $value;
		              }
		          }
			   	  
			   }

			   foreach ($list as $date => $array) {

			   	$results[] = ['date'=>$date, 'action'=>'date'];

			   	foreach ($array as $key => $value) {

			   		$attachList = [];

			   		$value["chat_messages_text"] = decrypt($value["chat_messages_text"]);
			   		$get = $Profile->oneUser(" where clients_id=?" , array( $value["chat_messages_id_user"] ) );

			   	    if($value["chat_messages_attach"]){
			   	       $chat_messages_attach = json_decode($value["chat_messages_attach"], true);
			   	       if($chat_messages_attach['images']){
				   	   	   foreach ($chat_messages_attach['images'] as $image) {
				   	   	   	  $attachList[] = $config["urlPath"] . "/" . $config["media"]["attach"] . "/" . $image;
				   	   	   }
			   	       }
			   	    }			   		

			   		if($value["chat_messages_action"] == 0){ 

				   	   $results[] = ['action'=>'message','attach'=>$attachList ?: null,'align'=>$value["chat_messages_id_user"] == $idUser ? 'right' : 'left', 'user'=>['avatar'=>$Profile->userAvatar($get),'name'=>$get['clients_name']], 'text'=>$value["chat_messages_text"] ?: null, 'date'=>date("H:i", strtotime($value["chat_messages_date"]))];

			   		}elseif($value["chat_messages_action"] == 1){

			   			$results[] = ['action'=>'notification','attach'=>$attachList ?: null,'align'=>'left', 'image'=>'https://cdn-icons-png.flaticon.com/512/2589/2589175.png', 'user'=>['avatar'=>$Profile->userAvatar($get),'name'=>$get['clients_name']], 'text'=>apiLangContent('Покупатель добавил объявление в избранное'), 'date'=>date("H:i", strtotime($value["chat_messages_date"]))];
			   			
			   		}elseif($value["chat_messages_action"] == 2){

			   			$results[] = ['action'=>'notification','attach'=>$attachList ?: null,'align'=>'left', 'user'=>['avatar'=>$Profile->userAvatar($get),'name'=>$get['clients_name']], 'text'=>apiLangContent('Ваш номер просмотрели'), 'date'=>date("H:i", strtotime($value["chat_messages_date"]))];
			   			
			   		}elseif($value["chat_messages_action"] == 3){

			   			$results[] = ['action'=>'notification','attach'=>$attachList ?: null,'align'=>'left', 'user'=>['avatar'=>$Profile->userAvatar($get),'name'=>$get['clients_name']], 'text'=>apiLangContent('Оформление заказа'), 'date'=>date("H:i", strtotime($value["chat_messages_date"]))];
			   			
			   		}elseif($value["chat_messages_action"] == 4){

			   			$results[] = ['action'=>'notification','attach'=>$attachList ?: null,'align'=>'left', 'image'=>'https://cdn-icons-png.flaticon.com/512/2107/2107957.png', 'user'=>['avatar'=>$Profile->userAvatar($get),'name'=>$get['clients_name']], 'text'=>apiLangContent('У вас новый отзыв'), 'date'=>date("H:i", strtotime($value["chat_messages_date"]))];
			   			
			   		}elseif($value["chat_messages_action"] == 5){

			   			$results[] = ['action'=>'notification','attach'=>$attachList ?: null,'align'=>'left', 'user'=>['avatar'=>$Profile->userAvatar($get),'name'=>$get['clients_name']], 'text'=>apiLangContent('Вы победили в аукционе'), 'date'=>date("H:i", strtotime($value["chat_messages_date"]))];
			   			
			   		}elseif($value["chat_messages_action"] == 6){

			   			$results[] = ['action'=>'notification','attach'=>$attachList ?: null,'align'=>'left', 'user'=>['avatar'=>$Profile->userAvatar($get),'name'=>$get['clients_name']], 'text'=>apiLangContent('Ваша ставка перебита'), 'date'=>date("H:i", strtotime($value["chat_messages_date"]))];
			   			
			   		}elseif($value["chat_messages_action"] == 7){

			   			$results[] = ['action'=>'notification','attach'=>$attachList ?: null,'align'=>'left', 'user'=>['avatar'=>$Profile->userAvatar($get),'name'=>$get['clients_name']], 'text'=>apiLangContent('Оформление заказа на бронирование'), 'date'=>date("H:i", strtotime($value["chat_messages_date"]))];
			   			
			   		}elseif($value["chat_messages_action"] == 8){

			   			$results[] = ['action'=>'notification','attach'=>$attachList ?: null,'align'=>'left', 'user'=>['avatar'=>$Profile->userAvatar($get),'name'=>$get['clients_name']], 'text'=>apiLangContent('Оформление заказа на аренду'), 'date'=>date("H:i", strtotime($value["chat_messages_date"]))];
			   			
			   		}

			   	}

			   }

         }

         $getInterlocutor = findOne("uni_clients", "clients_id=?", [$getChatUser["chat_users_id_interlocutor"]]);

		 if(modeOnline($getInterlocutor["clients_datetime_view"])){
			$statusOnline = apiLangContent('В сети');
		 }else{
			$statusOnline = apiLangContent('Был(а) в сети:').' '.date('d.m.Y, H:i', strtotime($getInterlocutor["clients_datetime_view"]));
		 }

         exit(json_encode(['dialog'=>$results, 'id_hash'=>$idHash, 'blocked_send'=>$getMyLocked ? true : false, 'blocked_user'=>$getLocked ? true : false, 'ad'=> ['id'=>$getAd["ads_id"],'cat_id'=>$getAd["ads_id_cat"],'title'=>$getAd["ads_title"],'price'=>apiOutPrice(['data'=>$getAd, 'shop'=>$getShop]),'status'=>$getAd["ads_status"], 'status_name'=>apiPublicationAndStatus($getAd),'image'=>Exists($config["media"]["small_image_ads"],$getAd["ads_images"][0],$config["media"]["no_image"])], 'user'=>['id'=>$getInterlocutor['clients_id'],'avatar'=>$Profile->userAvatar($getInterlocutor),'name'=>$Profile->name($getInterlocutor, false), 'statusOnline'=>$statusOnline]]));

       }

   }else{

       if( $idHash == md5( $getChatUser["chat_users_id_user"] . $getChatUser["chat_users_id_interlocutor"] ) || $idHash == md5( $getChatUser["chat_users_id_interlocutor"] . $getChatUser["chat_users_id_user"] ) ){

         update("update uni_chat_messages set chat_messages_status=? where chat_messages_id_hash=? and chat_messages_id_user!=?", array(1,$idHash,$idUser));

         $getDialog = getAll("select * from uni_chat_messages where chat_messages_id_hash=? order by chat_messages_date asc", array($idHash) );

         $getLocked = findOne( "uni_clients_blacklist", "clients_blacklist_user_id=? and clients_blacklist_user_id_locked=?", array($idUser,$getChatUser["chat_users_id_interlocutor"]) );

         $getMyLocked = findOne( "uni_clients_blacklist", "clients_blacklist_user_id=? and clients_blacklist_user_id_locked=?", array( $getChatUser["chat_users_id_interlocutor"],$idUser) );

         if(count($getDialog)){

			   foreach ($getDialog as $key => $value) {
		          
		          if($value["chat_messages_action"] == 0){
		              $list[ date("d.m.Y", strtotime( $value["chat_messages_date"] ) ) ][] = $value;
		          }else{
		              if($idUser != $value["chat_messages_id_user"]){
		              	 $list[ date("d.m.Y", strtotime( $value["chat_messages_date"] ) ) ][] = $value;
		              }
		          }
			   	  
			   }

			   foreach ($list as $date => $array) {

			   	$results[] = ['date'=>$date, 'action'=>'date'];

			   	foreach ($array as $key => $value) {

			   		$attachList = [];

			   		$value["chat_messages_text"] = decrypt($value["chat_messages_text"]);
			   		$get = $Profile->oneUser(" where clients_id=?" , array( $value["chat_messages_id_user"] ) );

			   	    if($value["chat_messages_attach"]){
			   	       $chat_messages_attach = json_decode($value["chat_messages_attach"], true);
			   	       if($chat_messages_attach['images']){
				   	   	   foreach ($chat_messages_attach['images'] as $image) {
				   	   	   	  $attachList[] = $config["urlPath"] . "/" . $config["media"]["attach"] . "/" . $image;
				   	   	   }
			   	       }
			   	    }

				   	$results[] = ['action'=>'message','attach'=>$attachList ?: null,'align'=>$value["chat_messages_id_user"] == $idUser ? 'right' : 'left', 'user'=>['avatar'=>$Profile->userAvatar($get),'name'=>$get['clients_name']], 'text'=>$value["chat_messages_text"] ?: null, 'date'=>date("H:i", strtotime($value["chat_messages_date"]))];			   		

			   	}

			   }

         }

         $getInterlocutor = findOne("uni_clients", "clients_id=?", [$getChatUser["chat_users_id_interlocutor"]]);

		 if(modeOnline($getInterlocutor["clients_datetime_view"])){
			$statusOnline = apiLangContent('В сети');
		 }else{
			$statusOnline = apiLangContent('Был(а) в сети:').' '.date('d.m.Y, H:i', strtotime($getInterlocutor["clients_datetime_view"]));
		 }

         exit(json_encode(['dialog'=>$results, 'id_hash'=>$idHash, 'blocked_send'=>$getMyLocked ? true : false, 'blocked_user'=>$getLocked ? true : false, 'user'=>['id'=>$getInterlocutor['clients_id'],'avatar'=>$Profile->userAvatar($getInterlocutor),'name'=>$Profile->name($getInterlocutor, false), 'statusOnline'=>$statusOnline]]));

       }                

   }

}else{

   $getChatUser = findOne("uni_chat_users","chat_users_id_hash=? and chat_users_id_user=?", array($idHash,$idUser));

   if($idHash == md5('support' . $idUser)){

      update("update uni_chat_messages set chat_messages_status=? where chat_messages_id_hash=? and chat_messages_id_user!=?", array(1,$idHash,$idUser));

      $getDialog = getAll("select * from uni_chat_messages where chat_messages_id_hash=? order by chat_messages_date asc", array($idHash) );

      $getMyLocked = findOne( "uni_clients_blacklist", "clients_blacklist_user_id=? and clients_blacklist_user_id_locked=?", array( $getChatUser["chat_users_id_interlocutor"],$idUser) );

      if(count($getDialog)){

		   foreach ($getDialog as $key => $value) {
	          
	          if($value["chat_messages_action"] == 0){
	              $list[ date("d.m.Y", strtotime( $value["chat_messages_date"] ) ) ][] = $value;
	          }else{
	              if($idUser != $value["chat_messages_id_user"]){
	              	 $list[ date("d.m.Y", strtotime( $value["chat_messages_date"] ) ) ][] = $value;
	              }
	          }
		   	  
		   }

		   foreach ($list as $date => $array) {

		   	$results[] = ['date'=>$date, 'action'=>'date'];

		   	foreach ($array as $key => $value) {

		   		$attachList = [];

		   		$value["chat_messages_text"] = decrypt($value["chat_messages_text"]);

		   	    if($value["chat_messages_attach"]){
		   	       $chat_messages_attach = json_decode($value["chat_messages_attach"], true);
		   	       if($chat_messages_attach['images']){
			   	   	   foreach ($chat_messages_attach['images'] as $image) {
			   	   	   	  $attachList[] = $config["urlPath"] . "/" . $config["media"]["attach"] . "/" . $image;
			   	   	   }
		   	       }
		   	    }

		   		if($value["chat_messages_id_user"]){
		   			$get = $Profile->oneUser(" where clients_id=?" , array( $value["chat_messages_id_user"] ) );
			   		$results[] = ['action'=>'message','attach'=>$attachList ?: null,'align'=>'right', 'user'=>['avatar'=>$Profile->userAvatar($get),'name'=>$get['clients_name']], 'text'=>$value["chat_messages_text"] ?: null, 'date'=>date("H:i", strtotime($value["chat_messages_date"]))];	
			   	}else{
			   		$results[] = ['action'=>'message','attach'=>$attachList ?: null,'align'=>'left', 'user'=>['avatar'=>$settings["path_tpl_image"].'/supportChat.png','name'=>apiLangContent('Менеджер')], 'text'=>$value["chat_messages_text"] ?: null, 'date'=>date("H:i", strtotime($value["chat_messages_date"]))];	
			   	}		   		

		   	}

		   }


      }

   }

   exit(json_encode(['dialog'=>$results, 'id_hash'=>$idHash, 'blocked_send'=>$getMyLocked ? true : false, 'user'=>['avatar'=>$settings["path_tpl_image"].'/supportChat.png','name'=>apiLangContent('Поддержка')]]));

}

echo json_encode(['dialog'=>$results, 'user'=>null]);

?>