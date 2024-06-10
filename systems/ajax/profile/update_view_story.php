<?php

$storyId = (int)$_POST["id_story"];
$ip = $_SERVER["REMOTE_ADDR"];

$getStory = findOne("uni_clients_stories_media", "clients_stories_media_id=? and clients_stories_media_status=?", [$storyId,1]);

if($getStory){
  $getStoryView = findOne("uni_clients_stories_view", "story_id=? and ip=?", [$storyId,$ip]);
  if(!$getStoryView){
    smart_insert('uni_clients_stories_view', [
      'story_id' =>$storyId,
      'ip'=>$ip,
    ]); 
  } 
  @setcookie('viewStory'.$getStory["clients_stories_media_user_id"], time(), time()+86400, "/");
}

?>