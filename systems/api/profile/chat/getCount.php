<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

$messages = [];
$results = [];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$totalCount = $Profile->getMessage($idUser);

echo json_encode(['count_messages'=>$totalCount['total'] ?: null]); 

?>