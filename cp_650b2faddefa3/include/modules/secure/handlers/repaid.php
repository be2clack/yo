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

if(isAjax() == true){

    $id = (int)$_POST["id"];

    $getPayment = findOne('uni_secure_payments', 'secure_payments_id=?', [$id]);

    update("update uni_secure_payments set secure_payments_status_pay=?,secure_payments_errors=? where secure_payments_id=?", array(0,"",$id));

    update("update uni_secure set secure_status_payment_user=? where secure_id_order=?", [0,$getPayment["secure_payments_id_order"]]);

    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
    echo true;

}  
?>