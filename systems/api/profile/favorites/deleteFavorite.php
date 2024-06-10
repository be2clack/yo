<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$idFavorite = (int)$_POST["id_favorite"];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

update('delete from uni_favorites where favorites_id=? and favorites_from_id_user=?', [$idFavorite,$idUser]);

echo json_encode(['status'=>true]);

?>