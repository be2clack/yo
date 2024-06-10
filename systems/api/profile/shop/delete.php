<?php

$id = (int)$_POST["id"];
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getShop = findOne("uni_clients_shops", "clients_shops_id_user=? and clients_shops_id=?", [$idUser, $id]);

if($getShop){
  $Shop->deleteShop($id);
}

echo json_encode(['status'=>true]);

?>