<?php

$_POST["authorization_social"] = $_POST["authorization_social"] ?: [];

update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["confirmation_phone"]),'confirmation_phone'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["authorization_method"]),'authorization_method'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["registration_method"]),'registration_method'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(implode(",",$_POST["authorization_social"]),'authorization_social'));

?>