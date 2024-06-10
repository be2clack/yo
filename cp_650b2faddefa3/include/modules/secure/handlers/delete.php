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

    $getOrder = findOne("uni_secure", "secure_id=?", [$id]);

    update('delete from uni_secure_ads where secure_ads_order_id=?', [$getOrder['secure_id_order']]);
    update('delete from uni_secure_disputes where secure_disputes_id_secure=?', [$id]);
    update('delete from uni_secure_payments where secure_payments_id_order=?', [$getOrder['secure_id_order']]);
    update('delete from uni_secure where secure_id=?', [$id]);
    update('delete from uni_clients_orders where clients_orders_uniq_id=?', [$getOrder['secure_id_order']]);


    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
    echo true;

}  
?>