<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$id = (int)$_POST["id"];

update('update uni_ads set ads_auto_renewal=? where ads_id=? and ads_id_user=?', [0,$id,$idUser]);

echo json_encode(["status"=>true]);
?>