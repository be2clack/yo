<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$id_shop = (int)$_POST["id_shop"];
$id_page = (int)$_POST["id_page"];

$getShop = findOne("uni_clients_shops", "clients_shops_id=? and clients_shops_id_user=?", [ $id_shop, $idUser ]);

if($getShop){
	update("delete from uni_clients_shops_page where clients_shops_page_id=? and clients_shops_page_id_shop=?", [ $id_page, $id_shop ]);
}

echo json_encode( ["status" => true] );

?>