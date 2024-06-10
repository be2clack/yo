<?php

if(isset($_POST["notification_method_new_ads"])){
  $notification_method_new_ads = implode(",",$_POST["notification_method_new_ads"]);
}else{
  $notification_method_new_ads = "";
}


if(isset($_POST["notification_method_new_user"])){
  $notification_method_new_user = implode(",",$_POST["notification_method_new_user"]);
}else{
  $notification_method_new_user = "";
}

if(isset($_POST["notification_method_new_buy"])){
  $notification_method_new_buy = implode(",",$_POST["notification_method_new_buy"]);
}else{
  $notification_method_new_buy = "";
}

if(isset($_POST["notification_method_new_chat_message"])){
  $notification_method_new_chat_message = implode(",",$_POST["notification_method_new_chat_message"]);
}else{
  $notification_method_new_chat_message = "";
}

if(isset($_POST["notification_method_feedback"])){
  $notification_method_feedback = implode(",",$_POST["notification_method_feedback"]);
}else{
  $notification_method_feedback = "";
}

if(isset($_POST["notification_method_complaint"])){
  $notification_method_complaint = implode(",",$_POST["notification_method_complaint"]);
}else{
  $notification_method_complaint = "";
}

if(isset($_POST["notification_method_reviews"])){
  $notification_method_reviews = implode(",",$_POST["notification_method_reviews"]);
}else{
  $notification_method_reviews = "";
}

if(isset($_POST["notification_method_stories"])){
  $notification_method_stories = implode(",",$_POST["notification_method_stories"]);
}else{
  $notification_method_stories = "";
}

if(isset($_POST["notification_method_verification"])){
  $notification_method_verification = implode(",",$_POST["notification_method_verification"]);
}else{
  $notification_method_verification = "";
}

if(isset($_POST["notification_method_secure"])){
  $notification_method_secure = implode(",",$_POST["notification_method_secure"]);
}else{
  $notification_method_secure = "";
}

if(isset($_POST["notification_method_booking"])){
  $notification_method_booking = implode(",",$_POST["notification_method_booking"]);
}else{
  $notification_method_booking = "";
}

if(isset($_POST["notification_method_shops"])){
  $notification_method_shops = implode(",",$_POST["notification_method_shops"]);
}else{
  $notification_method_shops = "";
}

if(isset($_POST["notification_method_ad_package"])){
  $notification_method_ad_package = implode(",",$_POST["notification_method_ad_package"]);
}else{
  $notification_method_ad_package = "";
}

update("UPDATE uni_settings SET value=? WHERE name=?", array($notification_method_new_ads,'notification_method_new_ads'));
update("UPDATE uni_settings SET value=? WHERE name=?", array($notification_method_new_user,'notification_method_new_user'));
update("UPDATE uni_settings SET value=? WHERE name=?", array($notification_method_new_buy,'notification_method_new_buy'));
update("UPDATE uni_settings SET value=? WHERE name=?", array($notification_method_new_chat_message,'notification_method_new_chat_message'));
update("UPDATE uni_settings SET value=? WHERE name=?", array($notification_method_feedback,'notification_method_feedback'));
update("UPDATE uni_settings SET value=? WHERE name=?", array($notification_method_complaint,'notification_method_complaint'));
update("UPDATE uni_settings SET value=? WHERE name=?", array($notification_method_reviews,'notification_method_reviews'));
update("UPDATE uni_settings SET value=? WHERE name=?", array($notification_method_stories,'notification_method_stories'));
update("UPDATE uni_settings SET value=? WHERE name=?", array($notification_method_verification,'notification_method_verification'));
update("UPDATE uni_settings SET value=? WHERE name=?", array($notification_method_secure,'notification_method_secure'));
update("UPDATE uni_settings SET value=? WHERE name=?", array($notification_method_booking,'notification_method_booking'));
update("UPDATE uni_settings SET value=? WHERE name=?", array($notification_method_shops,'notification_method_shops'));
update("UPDATE uni_settings SET value=? WHERE name=?", array($notification_method_ad_package,'notification_method_ad_package'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["email_alert"]),'email_alert'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["phone_alert"]),'phone_alert'));

?>