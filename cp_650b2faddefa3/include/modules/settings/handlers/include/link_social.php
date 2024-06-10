<?php

update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["social_link_vk"]),'social_link_vk'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["social_link_ok"]),'social_link_ok'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["social_link_you"]),'social_link_you'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["social_link_telegram"]),'social_link_telegram'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["social_link_facebook"]),'social_link_facebook'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["social_link_instagram"]),'social_link_instagram'));

?>