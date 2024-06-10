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

    $Main = new Main();

    $secure_payment = findOne("uni_secure_payments", "secure_payments_id=?", [$id]);
    $secure = findOne("uni_secure", "secure_id_order=?", [$secure_payment['secure_payments_id_order']]);
    $user = findOne("uni_clients", "clients_id=?", [$secure["secure_id_user_seller"]]);

    $profit = calcPercent( $secure_payment["secure_payments_amount"], $settings["secure_percent_service"] );

    if($profit && $secure_payment["secure_payments_amount"]){
       $Main->addOrder( ["id_ad"=>$secure["secure_id_ad"],"price"=>$profit,"title"=>$static_msg["52"],"id_user"=>$secure["secure_id_user_seller"],"status_pay"=>1, "user_name" => $user["clients_name"], "id_hash_user" => $user["clients_id_hash"], "action_name" => "secure"] );
    }

    update("update uni_secure_payments set secure_payments_status_pay=?,secure_payments_errors=? where secure_payments_id=?", array(1,"",$id));
    update("update uni_secure set secure_status_payment_user=? where secure_id_order=?", [1,$secure_payment['secure_payments_id_order']]);

    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
    echo true;

}  
?>