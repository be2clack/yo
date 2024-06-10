<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$idHash = clear($_POST["id"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

update("DELETE FROM uni_chat_users WHERE chat_users_id_hash=? and (chat_users_id_user=? or chat_users_id_interlocutor=?)", array($idHash,$idUser,$idUser));

echo json_encode(['status'=>true]); 

?>