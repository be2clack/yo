<?php

$_POST["fbm_params"] = $_POST["fbm_params"] ? encrypt($_POST["fbm_params"]) : "";

update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["app_available_status"]),'app_available_status'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["app_name_project"]),'app_name_project'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["app_version"]),'app_version'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["app_download_link"]),'app_download_link'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["app_user_agreement_link"]),'app_user_agreement_link'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["app_privacy_policy_link"]),'app_privacy_policy_link'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["app_metrica_api_key"]),'app_metrica_api_key'));

if(isset($_POST["app_widgets_home_screen"])){
  update("UPDATE uni_settings SET value=? WHERE name=?", array(json_encode($_POST["app_widgets_home_screen"]),'app_widgets_home_screen'));
}

update("UPDATE uni_settings SET value=? WHERE name=?", array(json_encode($_POST["app_widgets_home_tabs"]),'app_widgets_home_tabs'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(json_encode($_POST["app_download_links"]),'app_download_links'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["app_home_promo_slider_status"]),'app_home_promo_slider_status'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(json_encode($_POST["app_home_header_banner"]),'app_home_header_banner'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["app_home_header_banner_status"]),'app_home_header_banner_status'));

$app_home_promo_slider_list = [];

if($_POST["app_home_promo_slider_list"]){

  foreach ($_POST["app_home_promo_slider_list"]["title"] as $key => $value) {
     if($value) $app_home_promo_slider_list[$key]["title"] = $value;
  }

  foreach ($_POST["app_home_promo_slider_list"]["image"] as $key => $value) {
     if($value) $app_home_promo_slider_list[$key]["image"] = $value;
  }

  foreach ($_POST["app_home_promo_slider_list"]["desc"] as $key => $value) {
     if($value) $app_home_promo_slider_list[$key]["desc"] = $value;
  }

}

update("UPDATE uni_settings SET value=? WHERE name=?", array(json_encode($app_home_promo_slider_list),'app_home_promo_slider_list'));

$app_home_promo_banner_list = [];

if($_POST["app_home_promo_banner_list"]){

  foreach ($_POST["app_home_promo_banner_list"]["link"] as $key => $value) {
     if($value) $app_home_promo_banner_list[$key]["link"] = $value;
  }

  foreach ($_POST["app_home_promo_banner_list"]["image"] as $key => $value) {
     if($value) $app_home_promo_banner_list[$key]["image"] = $value;
  }

}

update("UPDATE uni_settings SET value=? WHERE name=?", array(json_encode($app_home_promo_banner_list),'app_home_promo_banner_list'));

if($_POST["app_balance_list_amounts"]){
  $amounts_list = [];
  $amounts = explode(',', $_POST["app_balance_list_amounts"]);
  foreach ($amounts as $amount) {
     if(intval($amount)){
        $amounts_list[] = intval($amount);
     }
  }
  if($amounts_list) update("UPDATE uni_settings SET value=? WHERE name=?", array(implode(',', $amounts_list),'app_balance_list_amounts'));
}

update("UPDATE uni_settings SET value=? WHERE name=?", array($_POST["fbm_params"],'fbm_params'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["fbm_project_id"]),'fbm_project_id'));

?>