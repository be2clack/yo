<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$idOrder = (int)$_POST['id_order'];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getOrderParam = findOne("uni_orders_parameters","orders_parameters_id_uniq=? and orders_parameters_status=?", [$idOrder,1]);

if($getOrderParam){
   echo json_encode(['status'=>true]);
}else{
   echo json_encode(['status'=>false]);
}

?>