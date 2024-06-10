<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
   http_response_code(500); exit('Authorization token error');
}

$getStory = findOne('uni_clients_stories', 'clients_stories_user_id=?', [$idUser]);

if($getStory){
   update('update uni_clients_stories set clients_stories_loaded=? where clients_stories_id=?', [1, $getStory['clients_stories_id']]);
}else{
   smart_insert('uni_clients_stories', [
     'clients_stories_user_id'=>$idUser,
     'clients_stories_loaded'=>1,
     'clients_stories_timestamp'=>date("Y-m-d H:i:s"),
   ]);   
}

echo json_encode(["status"=>true]);

?>