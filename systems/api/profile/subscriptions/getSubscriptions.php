<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$results = [];

$getSubscriptions = getAll("select * from uni_clients_subscriptions where clients_subscriptions_id_user_from=? order by clients_subscriptions_id desc", [$idUser]);

if(count($getSubscriptions)){
	 foreach ($getSubscriptions as $key => $value) {
	 	$getUser = findOne('uni_clients','clients_id=?', [$value['clients_subscriptions_id_user_to']]);
	 	if($getUser){
	  		$results[] = ['id'=>$value['clients_subscriptions_id'],'user_id'=>$getUser['clients_id'],'name'=>$Profile->name($getUser),'avatar'=>$Profile->userAvatar($getUser)];
	  	}
	 }
}

echo json_encode($results);

?>