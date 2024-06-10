<?php

$storyId = (int)$_POST["id"];
$ip = clear($_POST['ip']);

$getStory = findOne("uni_clients_stories_media", "clients_stories_media_id=? and clients_stories_media_status=?", [$storyId,1]);

if($getStory){
  $getStoryView = findOne("uni_clients_stories_view", "story_id=? and ip=?", [$storyId,$ip]);
  if(!$getStoryView){
    smart_insert('uni_clients_stories_view', [
      'story_id' =>$storyId,
      'ip'=>$ip,
    ]); 
  }
}

echo json_encode(['status'=>true]);

?>