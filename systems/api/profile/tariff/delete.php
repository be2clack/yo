<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

update('delete from uni_services_tariffs_orders where services_tariffs_orders_id_user=?', [$idUser]);
update('update uni_clients set clients_tariff_id=? where clients_id=?', [0,$idUser]);

$getShop = findOne("uni_clients_shops", "clients_shops_id_user=?", [$idUser]);

if($getShop) update("update uni_clients_shops set clients_shops_status=? where clients_shops_id=?", [0, $getShop["clients_shops_id"]]);

echo json_encode(["status"=>true]);
?>