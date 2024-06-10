<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$idSubscription = (int)$_POST["id_subscription"];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

update('delete from uni_clients_subscriptions where clients_subscriptions_id=? and clients_subscriptions_id_user_from=?', [$idSubscription,$idUser]);

echo json_encode(['status'=>true]);

?>