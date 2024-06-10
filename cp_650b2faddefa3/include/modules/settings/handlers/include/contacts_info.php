<?php

update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["site_name"]),'site_name'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["title"]),'title'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["contact_phone"]),'contact_phone'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["contact_email"]), 'contact_email'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["contact_address"]),'contact_address'));

?>