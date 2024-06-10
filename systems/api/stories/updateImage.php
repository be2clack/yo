<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

$mediaId = (int)$_POST["media_id"];
$file_name = clear($_POST["file_name"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$path = $config["basePath"] . "/" . $config["media"]["temp_images"];

if(file_exists($path."/".$file_name)){

   if(copy($path."/".$file_name, $config["basePath"]."/".$config["media"]["user_stories"]."/".$file_name)){

      update('update uni_clients_stories set clients_stories_loaded=?,clients_stories_timestamp=? where clients_stories_user_id=?', [1, date("Y-m-d H:i:s"), $idUser]);

      smart_update('uni_clients_stories_media', [
        'clients_stories_media_name'=>$file_name,
        'clients_stories_media_loaded'=>1,
        'clients_stories_media_duration'=>intval($settings["user_stories_image_length"]),
      ], "clients_stories_media_id={$mediaId} and clients_stories_media_user_id={$idUser}");

      $Admin->notifications("user_story");

   }

}

echo json_encode(["status"=>true]);

?>