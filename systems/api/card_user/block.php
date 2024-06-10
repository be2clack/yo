<?php

$idUserFrom = (int)$_POST["id_user_from"];
$tokenAuth = clear($_POST["token"]);
$idUserTo = (int)$_POST["id_user_to"];

if(checkTokenAuth($tokenAuth, $idUserFrom) == false){
	http_response_code(500); exit('Authorization token error');
}

$getLocked = findOne("uni_clients_blacklist", "clients_blacklist_user_id = ? and clients_blacklist_user_id_locked = ?", array($idUserFrom,$idUserTo));

if($getLocked){
	update("DELETE FROM uni_clients_blacklist WHERE clients_blacklist_id=?", array($getLocked->clients_blacklist_id));
	echo json_encode(['status'=>'delete']);
}else{
	smart_insert("uni_clients_blacklist", ['clients_blacklist_user_id'=>$idUserFrom, 'clients_blacklist_user_id_locked'=>$idUserTo]);
	echo json_encode(['status'=>'added']);
}

?>