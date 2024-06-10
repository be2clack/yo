<?php

if($_POST["count_images_add_ad"] > 100){
  $_POST["count_images_add_ad"] = 100;
}

if(!$_POST["ad_create_period_list"]){
  $_POST["ad_create_period"] = 0;
}

 if($settings["marketplace_status"]){
    
    if($_POST["ad_create_currency"]){
      $error[] = "Нельзя включить смену валюты при включеном маркетплейсе!";
    }

    $_POST["ad_create_currency"] = 0;

 }

update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["ad_create_phone"]),'ad_create_phone'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["ad_black_list_words"]),'ad_black_list_words'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["ads_publication_moderat"]),'ads_publication_moderat'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["ads_publication_auto_moderat"]),'ads_publication_auto_moderat'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["ad_create_currency"]),'ad_create_currency'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["ad_create_period"]),'ad_create_period'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["ad_create_period_list"]),'ad_create_period_list'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["ads_time_publication_default"]),'ads_time_publication_default'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["ad_create_always_image"]),'ad_create_always_image'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["count_images_add_ad"]),'count_images_add_ad'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["ad_format_photo"]),'ad_format_photo'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["ad_create_length_title"]),'ad_create_length_title'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["ad_create_length_text"]),'ad_create_length_text'));


?>