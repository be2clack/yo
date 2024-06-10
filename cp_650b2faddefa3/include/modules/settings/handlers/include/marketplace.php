<?php

update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["marketplace_status"]),'marketplace_status'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["marketplace_view_cart"]),'marketplace_view_cart'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["marketplace_available_cart"]),'marketplace_available_cart'));

if($settings["ad_create_currency"]){
	update("UPDATE uni_settings SET value=? WHERE name=?", array(0,'ad_create_currency'));
}

?>