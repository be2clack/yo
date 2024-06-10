<?php

session_start();
define('unisitecms', true);

$config = require "./../../config.php";
require_once $config["basePath"] . "/systems/unisite.php";
$static_msg = require $config["basePath"] . "/static/msg.php";

verify_csrf_token(['ad-create','ad-update']);

$Profile->checkAuth();

$action = explode("/", trim($_POST["action"], "/") );

verify_auth($action[1], ['remove_publication','ads_status_sell','ads_publication','ads_delete','auction_cancel_rate','auction_accept_order_reservation','create_accept_phone','load_booking','add_order_booking','order_delete_booking','order_confirm_booking','order_prepayment_booking','order_cancel_booking','play_voice','send_chat','load_chat','delete_chat','chat_user_locked','confirm_transfer_goods','confirm_receive_goods','order_cancel_deal','order_change_status','add_disputes','edit_shop','add_slide','add_page','delete_shop','user-avatar','user_edit_pass','profile_user_locked','balance_payment','user_edit','user_edit_email','user_edit_phone_send','user_edit_phone_save','user_edit_notifications','user_edit_score','add_review_user','delete_ads_subscriptions','period_ads_subscriptions','delete_shop_subscriptions','activate_services_tariff','delete_services_tariff','autorenewal_services_tariff','scheduler_ad_delete','statistics_load_info_user','user_edit_score_booking','profile_add_card','profile_delete_card','delete_review','story_load_add','story_publication','story_search_ads','story_search_location','story_search_category','story_delete','user_requisites_edit','balance_invoice','load_orders_booking_calendar','cancel_date_booking_calendar','load_orders_date_booking_calendar','allow_date_booking_calendar','user_add_verification','open_shop']);

if(isAjax() == true){

    if(isset($_POST["action"])){
        if(file_exists($config["basePath"] . "/systems/ajax/{$_POST["action"]}.php")){
            require_once $config["basePath"] . "/systems/ajax/{$_POST["action"]}.php";
        }
    }

}

?>