<?php

update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["access_site"]),'access_site'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["access_action"]),'access_action'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["access_text"]),'access_text'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["access_redirect_link"]),'access_redirect_link'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["access_allowed_ip"]),'access_allowed_ip'));

?>