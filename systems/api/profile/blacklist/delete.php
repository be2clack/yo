<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$id = (int)$_POST["id"];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

update('delete from uni_clients_blacklist where clients_blacklist_id=? and clients_blacklist_user_id=?', [$id,$idUser]);

echo json_encode(['status'=>true]);

?>