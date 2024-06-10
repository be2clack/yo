<?php

if( $_POST["delivery_api_key"] ){
  if($_POST["delivery_api_key"] != decrypt($settings['delivery_api_key'])){
     manualRunCron('delivery');
  }
}

if( $_POST["api_id_telegram"] ){
  $_POST["api_id_telegram"] = encrypt($_POST["api_id_telegram"]);
}

if( $_POST["sms_service_pass"] ){
  $_POST["sms_service_pass"] = encrypt($_POST["sms_service_pass"]);
}

if( $_POST["sms_service_id"] ){
  $_POST["sms_service_id"] = encrypt($_POST["sms_service_id"]);
}

$_POST["delivery_available_categories"] = $_POST["delivery_available_categories"] ?: [];

if( $_POST["social_auth_params"] ){
  $_POST["social_auth_params"] = encrypt( json_encode($_POST["social_auth_params"]) );
}

update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["delivery_service"]),'delivery_service'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(implode(",",$_POST["delivery_available_categories"]),'delivery_available_categories'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(encrypt($_POST["delivery_api_key"]),'delivery_api_key'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(round($_POST["delivery_from_price"],2),'delivery_from_price'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(round($_POST["delivery_before_price"],2),'delivery_before_price'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["delivery_weight_min"]),'delivery_weight_min'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["delivery_weight_max"]),'delivery_weight_max'));

update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["map_vendor"]),'map_vendor'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["map_google_key"]),'map_google_key'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["map_yandex_key"]),'map_yandex_key'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["map_openstreetmap_key"]),'map_openstreetmap_key'));

update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["api_id_telegram"]),'api_id_telegram'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["chat_id_telegram"]),'chat_id_telegram'));

update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["sms_service"]),'sms_service'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["sms_service_id"]),'sms_service_id'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["sms_service_login"]),'sms_service_login'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["sms_service_pass"]),'sms_service_pass'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["sms_service_label"]),'sms_service_label'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["sms_service_method_send"]),'sms_service_method_send'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["sms_prefix_confirmation_code"]),'sms_prefix_confirmation_code'));

update("UPDATE uni_settings SET value=? WHERE name=?", array($_POST["social_auth_params"],'social_auth_params'));

update("UPDATE uni_settings SET value=? WHERE name=?", array($_POST["social_share_links"] ? json_encode($_POST["social_share_links"]) : '','social_share_links'));

?>