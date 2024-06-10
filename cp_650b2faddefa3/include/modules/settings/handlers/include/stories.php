<?php

if(intval($_POST["user_stories_video_length"]) > 300){
   $_POST["user_stories_video_length"] = 300;
}

if(intval($_POST["user_stories_image_length"]) > 300){
   $_POST["user_stories_image_length"] = 300;
}

if(intval($_POST["user_stories_period_add"]) > 720){
   $_POST["user_stories_period_add"] = 720;
}

update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["user_stories_status"]),'user_stories_status'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["user_stories_moderation"]),'user_stories_moderation'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["user_stories_paid_add"]),'user_stories_paid_add'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(round($_POST["user_stories_price_add"],2),'user_stories_price_add'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["user_stories_free_add"]),'user_stories_free_add'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["user_stories_video_length"]),'user_stories_video_length'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["user_stories_image_length"]),'user_stories_image_length'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["user_stories_period_add"]),'user_stories_period_add'));

?>