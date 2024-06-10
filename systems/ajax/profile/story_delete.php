<?php

$id = (int)$_POST["story_id"];

$getStory = findOne("uni_clients_stories_media", "clients_stories_media_id=? and clients_stories_media_user_id=?", array($id,$_SESSION["profile"]["id"]));

if($getStory){

    $Main->returnPaymentStory($id);

    if($getStory['clients_stories_media_status'] == 0 && $settings["user_stories_free_add"]){
        $getUser = findOne('uni_clients', 'clients_id=?', [$_SESSION["profile"]["id"]]);
        if($getUser['clients_count_story_publication']!=0){
            update('update uni_clients set clients_count_story_publication=clients_count_story_publication-1 where clients_id=?', [$_SESSION["profile"]["id"]]);
        }
    }

    $Profile->deleteUserStory($id, $_SESSION["profile"]["id"]);

}

?>