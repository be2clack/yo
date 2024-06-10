<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

$mediaId = (int)$_POST["media_id"];
$file_name = clear($_POST["file_name"]).'.mp4';

$new_file_name = '';
$video_preview = '';

if(checkTokenAuth($tokenAuth, $idUser) == false){
  http_response_code(500); exit('Authorization token error');
}

$video_duration = intval($_POST["video_duration"] / 1000);
if($video_duration <= $settings["user_stories_video_length"]){
   $duration = $video_duration;
}else{
   $duration = $settings["user_stories_video_length"];
}

if($_POST['file_base64']){
  if(file_put_contents($config["basePath"] . "/" . $config["media"]["user_stories"]. "/" . $file_name, base64_decode($_POST['file_base64']))){

      if(checkAvailableFfmpeg()){

        $new_file_name = md5($idUser . uniqid()).".mp4";

        exec("ffmpeg -i ".$config["basePath"] . "/" . $config["media"]["user_stories"]. "/" . $file_name . " -b:v 2500k -bufsize 2500k ".$config["basePath"] . "/" . $config["media"]["user_stories"]. "/" .$new_file_name);
        exec("ffmpeg -ss 00:00:01.00 -i ".$config["basePath"] . "/" . $config["media"]["user_stories"]. "/" . $new_file_name." -vf 'scale=1024:720:force_original_aspect_ratio=decrease' -vframes 1 ". $config["basePath"] . "/" . $config["media"]["user_stories"]. "/" . $video_preview);

        $video_preview = md5('video_preview_'.uniqid()).'.jpg';

      }

      update('update uni_clients_stories set clients_stories_loaded=?,clients_stories_timestamp=? where clients_stories_user_id=?', [1, date("Y-m-d H:i:s"), $idUser]);

      smart_update('uni_clients_stories_media', [
        'clients_stories_media_name'=>$new_file_name ? $new_file_name : $file_name,
        'clients_stories_media_loaded'=>1,     
        'clients_stories_media_preview'=>$video_preview,
        'clients_stories_media_duration'=>intval($duration),
      ], "clients_stories_media_id={$mediaId} and clients_stories_media_user_id={$idUser}");

      if(checkAvailableFfmpeg()){
        unlink($config["basePath"] . "/" . $config["media"]["user_stories"]. "/" . $file_name);
      }

      update('update uni_clients set clients_count_story_publication=clients_count_story_publication+1 where clients_id=?', [$idUser]);

      $Admin->notifications("user_story");

   }
}

echo json_encode(["status"=>true]);

?>