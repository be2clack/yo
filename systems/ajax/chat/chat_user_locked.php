<?php

$id_hash = clear($_POST["id"]);

$getUser = getOne("select * from uni_chat_users where chat_users_id_hash=? and chat_users_id_user=?", array( $id_hash,intval($_SESSION['profile']['id']) ) );
   
if($getUser){

  $getLocked = findOne("uni_clients_blacklist", "clients_blacklist_user_id = ? and clients_blacklist_user_id_locked = ?", array(intval($_SESSION['profile']['id']),$getUser["chat_users_id_interlocutor"]));
  if($getLocked){
     update("DELETE FROM uni_clients_blacklist WHERE clients_blacklist_id=?", array($getLocked->clients_blacklist_id));
  }else{
     smart_insert('uni_clients_blacklist', [
         'clients_blacklist_user_id'=>intval($_SESSION['profile']['id']),
         'clients_blacklist_user_id_locked'=>$getUser["chat_users_id_interlocutor"],
     ]);
  }

}

echo json_encode( array( "dialog"=> $Profile->chatDialog($id_hash) ) );

?>