<?php

$idUser = (int)$_POST["id_user"];
$token = clear($_POST["token"]);
$ip = clear($_POST["ip"]);

$getToken = findOne("uni_clients_fcm_tokens","user_id=?", [$idUser]);

if($getToken){
   update('update uni_clients_fcm_tokens set token=? where id=?', [$token, $getToken['id']]);
}else{
   if($token){
        smart_insert('uni_clients_fcm_tokens',[
            'user_id'=>$idUser,
            'token'=>$token,
        ]);
   }
}

echo json_encode(["status"=>true]); 

?>