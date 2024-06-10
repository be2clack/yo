<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$results = [];

$getHistoryBalance = getAll("select * from uni_history_balance where id_user=? order by id desc", [$idUser]);

if(count($getHistoryBalance)){
	foreach ($getHistoryBalance as $key => $value) {
		$results[$key]['summa'] = apiPrice($value['summa']);
		$results[$key]['name'] = $value['name'];
		$results[$key]['action'] = $value['action'];
	}
}

echo json_encode($results);

?>