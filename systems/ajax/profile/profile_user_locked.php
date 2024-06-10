<?php

$id_user = (int)$_POST["id"];

$getLocked = findOne("uni_clients_blacklist", "clients_blacklist_user_id = ? and clients_blacklist_user_id_locked = ?", array($_SESSION['profile']['id'],$id_user));
if($getLocked){
update("DELETE FROM uni_clients_blacklist WHERE clients_blacklist_id=?", array($getLocked->clients_blacklist_id));
}else{
smart_insert("uni_clients_blacklist", ['clients_blacklist_user_id'=>$_SESSION['profile']['id'], 'clients_blacklist_user_id_locked'=>$id_user]);
}

echo json_encode( array( "status"=> true ) );

?>