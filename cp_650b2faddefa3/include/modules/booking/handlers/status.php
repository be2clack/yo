<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );
$static_msg = require $config["basePath"] . "/static/msg.php";

if( !(new Admin())->accessAdmin($_SESSION['cp_control_transactions']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

$Main = new Main();
$Ads = new Ads();

if(isAjax() == true){

    $id = (int)$_POST["id"];

    update("update uni_ads_booking_prepayments set ads_booking_prepayments_status=? where ads_booking_prepayments_id_order=?", array(1,$id));

    $getOrder = findOne('uni_ads_booking', 'ads_booking_id_order=?', [$id]);
    $getPayment = findOne('uni_ads_booking_prepayments', 'ads_booking_prepayments_id_order=?', [$id]);

    $getAd = $Ads->get("ads_id=?", [$getOrder["ads_booking_id_ad"]]);

    if($settings['booking_prepayment_percent_service']){
       $totalSumm = calcPercent($getPayment['ads_booking_prepayments_amount'], $settings['booking_prepayment_percent_service']);
       $Main->addOrder(["id_ad"=>$getOrder['ads_booking_id_ad'],"price"=>$totalSumm,"title"=>'Комиссия за оплату аренды',"id_user"=>$getOrder['ads_booking_id_user_from'],"status_pay"=>1, "user_name" => $getAd["clients_name"], "id_hash_user" => $getAd["clients_id_hash"], "action_name" => "booking"]);
    }

    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
    echo true;

}  
?>