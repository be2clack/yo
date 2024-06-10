<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

$id = (int)$_POST["id"];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getStory = findOne("uni_clients_stories_media", "clients_stories_media_id=? and clients_stories_media_user_id=?", array($id,$idUser));

if($getStory){

    $Main->returnPaymentStory($id);

    if($getStory['clients_stories_media_status'] == 0 && $settings["user_stories_free_add"]){
      $getUser = findOne('uni_clients', 'clients_id=?', [$idUser]);
      if($getUser['clients_count_story_publication']!=0){
         update('update uni_clients set clients_count_story_publication=clients_count_story_publication-1 where clients_id=?', [$idUser]);
      }
    }

    $Profile->deleteUserStory($id, $idUser);

}

echo json_encode(["status"=>true]);

?>