<?php
defined('unisitecms') or exit();

if( !$settings["demo_view"] ){
$getModules = file_get_contents_curl("http://api.unisitecloud.ru/get_modules_list.php?uniq_id_order=".$settings["uniq_id_order"]);
update("UPDATE uni_settings SET value=? WHERE name=?", array($getModules,'available_functionality'));
}
?>