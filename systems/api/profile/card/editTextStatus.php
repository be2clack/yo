<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$text = clear(custom_substr($_POST['text'], 100));

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

update('update uni_clients set clients_note_status=? where clients_id=?', [$text,$idUser]);


echo json_encode(['status'=>true]);

?>