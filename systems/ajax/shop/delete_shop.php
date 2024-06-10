<?php

$id = (int)$_POST["id"];

$getShop = findOne("uni_clients_shops", "clients_shops_id_user=? and clients_shops_id=?", [$_SESSION["profile"]["id"], $id]);

if($getShop){
 $Shop->deleteShop($id);
}

echo _link("user/".$_SESSION["profile"]["data"]["clients_id_hash"]);

?>