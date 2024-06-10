<?php

$idUserFrom = (int)$_POST["id_user_from"];
$tokenAuth = clear($_POST["token"]);
$idUserTo = (int)$_POST["id_user_to"];

if(checkTokenAuth($tokenAuth, $idUserFrom) == false){
	http_response_code(500); exit('Authorization token error');
}


$getUserSubscribe = findOne('uni_clients_subscriptions', 'clients_subscriptions_id_user_from=? and clients_subscriptions_id_user_to=?', [$idUserFrom,$idUserTo]);

if($getUserSubscribe){
	update('delete from uni_clients_subscriptions where clients_subscriptions_id=?', [$getUserSubscribe['clients_subscriptions_id']]);
	echo json_encode(['status'=>'delete', 'count'=>intval(getOne("select count(*) as total from uni_clients_subscriptions where clients_subscriptions_id_user_to=?", [$idUserTo])["total"])]);
}else{
	smart_insert('uni_clients_subscriptions', [
		'clients_subscriptions_id_user_from' => $idUserFrom,
		'clients_subscriptions_id_user_to' => $idUserTo,
		'clients_subscriptions_time_update' => date('Y-m-d H:i:s'),
		'clients_subscriptions_date_add' => date('Y-m-d H:i:s')
	]);
	echo json_encode(['status'=>'added', 'count'=>intval(getOne("select count(*) as total from uni_clients_subscriptions where clients_subscriptions_id_user_to=?", [$idUserTo])["total"])]);
}


?>