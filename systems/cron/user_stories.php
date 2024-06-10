<?php
defined('unisitecms') or exit();

$getUserStories = getAll("select * from uni_clients_stories_media where clients_stories_media_not_delete=? and unix_timestamp(clients_stories_media_date_completion) <= unix_timestamp(NOW())", [0]);

if($getUserStories){
  foreach ($getUserStories as $value) {

     update('delete from uni_clients_stories_media where clients_stories_media_id=?', [$value['clients_stories_media_id']]);

     update('delete from uni_clients_stories_view where story_id=?', [$value['clients_stories_media_id']]);

     unlink($config["basePath"]."/".$config["media"]["user_stories"]."/".$value['clients_stories_media_name']);

     if($value['clients_stories_media_preview']) unlink($config['basePath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_preview']);

     $getStories = getAll("select * from uni_clients_stories_media where clients_stories_media_user_id=?", [$value['clients_stories_media_user_id']]);
     
     if(!count($getStories)){
        update('delete from uni_clients_stories where clients_stories_user_id=?', [$value['clients_stories_media_user_id']]);
     }

  }
}

?>