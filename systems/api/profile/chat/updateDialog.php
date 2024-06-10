<?php
$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);
$idHash = clear($_GET["id"]);
$support = (int)$_GET["support"];

$results = [];
$groupByUsers = [];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getChatUser = findOne("uni_chat_users","chat_users_id_hash=? and chat_users_id_user=?", array($idHash,$idUser));
$getMessages = getAll('select * from uni_chat_messages where chat_messages_id_hash=? and chat_messages_status=? and chat_messages_id_user!=? order by chat_messages_id desc', [$idHash,0,$idUser]);

if($getMessages){

	foreach ($getMessages as $value) {

		$attachList = [];

	    if($value["chat_messages_attach"]){
	       $chat_messages_attach = json_decode($value["chat_messages_attach"], true);
	       if($chat_messages_attach['images']){
   	   	   foreach ($chat_messages_attach['images'] as $image) {
   	   	   	  $attachList[] = $config["urlPath"] . "/" . $config["media"]["attach"] . "/" . $image;
   	   	   }
	       }
	    }

	    $value["chat_messages_text"] = decrypt($value["chat_messages_text"]);

		if($value["chat_messages_id_user"]){
			$getUser = $Profile->oneUser(" where clients_id=?" , array( $value["chat_messages_id_user"] ) );
   	    $results[] = ['action'=>'message','attach'=>$attachList ?: null,'align'=>'right', 'user'=>['avatar'=>$Profile->userAvatar($getUser["clients_avatar"]),'name'=>$getUser['clients_name']], 'text'=>$value["chat_messages_text"] ?: null, 'date'=>date("H:i", strtotime($value["chat_messages_date"]))];
		}else{
		$results[] = ['action'=>'message','attach'=>$attachList ?: null,'align'=>'left', 'user'=>['avatar'=>$settings["path_tpl_image"].'/supportChat.png','name'=>apiLangContent('Менеджер')], 'text'=>$value["chat_messages_text"] ?: null, 'date'=>date("H:i", strtotime($value["chat_messages_date"]))];
		}

	}

	update("update uni_chat_messages set chat_messages_status=? where chat_messages_id_hash=? and chat_messages_id_user!=?", array(1,$idHash,$idUser));

}

$getMyLocked = findOne( "uni_clients_blacklist", "clients_blacklist_user_id=? and clients_blacklist_user_id_locked=?", array( $getChatUser["chat_users_id_interlocutor"],$idUser) );

if($getChatUser["chat_users_id_interlocutor"]){
	$getInterlocutor = findOne("uni_clients", "clients_id=?", [$getChatUser["chat_users_id_interlocutor"]]);

	if(modeOnline($getInterlocutor["clients_datetime_view"])){
		$statusOnline = apiLangContent('В сети');
	}else{
		$statusOnline = apiLangContent('Был(а) в сети:').' '.date('d.m.Y, H:i', strtotime($getInterlocutor["clients_datetime_view"]));
	}

	echo json_encode(["messages"=>$results ?: null, 'blocked_send'=>$getMyLocked ? true : false, 'user'=>['id'=>$getInterlocutor['clients_id'],'avatar'=>$Profile->userAvatar($getInterlocutor["clients_avatar"]),'name'=>$getInterlocutor['clients_name'].' '.$getInterlocutor['clients_surname'], 'statusOnline'=>$statusOnline]]); 
}else{
	echo json_encode(["messages"=>$results ?: null, 'blocked_send'=>$getMyLocked ? true : false, 'user'=>['avatar'=>$settings["path_tpl_image"].'/supportChat.png','name'=>apiLangContent('Менеджер')]]); 
}

?>