<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$results = [];

$getUsers = getAll("select * from uni_clients_blacklist where clients_blacklist_user_id=? order by clients_blacklist_id desc", [$idUser]);

if(count($getUsers)){
	 foreach ($getUsers as $key => $value) {
	 	$getUser = findOne('uni_clients','clients_id=?', [$value['clients_blacklist_user_id_locked']]);
	 	if($getUser){
	  		$results[] = ['id'=>$value['clients_blacklist_id'],'user_id'=>$getUser['clients_id'],'name'=>$Profile->name($getUser),'avatar'=>$Profile->userAvatar($getUser['clients_avatar'])];
	  	}
	 }
}

echo json_encode($results);

?>