<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$idHash = clear($_POST["id"]);

$text = clear($_POST["text"]);
$support = (int)$_POST["support"];
$attach = $_POST["attach"] ? json_decode($_POST["attach"], true) : [];

$results = [];
$attachList = [];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

if($attach){
	foreach ($attach as $value) {
		$attachList[] = $value['name'];
	}
}

if(!$support){
   $getUser = getOne("select * from uni_chat_users where chat_users_id_hash=? and chat_users_id_user=?", array($idHash,$idUser) );
   $Profile->sendChat( array( "id_ad" => $getUser["chat_users_id_ad"], "id_hash" => $idHash, "text" => $text, "user_from" => $idUser, "user_to" => $getUser["chat_users_id_interlocutor"], "attach" => $attachList, "voice" => "", "duration" => 0, "firebase" => true ) );
}else{
   $Profile->sendChat( array( "support" => 1, "id_hash" => $idHash, "text" => $text, "user_from" => $idUser, "user_to" => 0, "attach" => $attachList, "firebase" => true ) );
}

update("UPDATE uni_clients SET clients_datetime_view=NOW() WHERE clients_id=?", array($idUser));

echo json_encode(['status'=>true]); 

?>