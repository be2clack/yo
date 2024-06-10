<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_transactions']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

	$Ads = new Ads();
    
    $id = (int)$_POST["id"];
    $status = (int)$_POST["status"];
    $id_user = (int)$_POST["id_user"];

    $secure = findOne("uni_secure", "secure_id=?", [$id]);
    
    if($secure){

	    $user = findOne("uni_clients", "clients_id=?", [$id_user]);

	    update("update uni_secure set secure_status=? where secure_id=?", array($status,$id));

	    $payments = findOne("uni_secure_payments", "secure_payments_id_order=? and secure_payments_id_user=?", [$secure["secure_id_order"],$user['secure_id_user_buyer']]);

	    if( !$payments ){

	    	$Ads->addSecurePayments( ["id_order"=>$secure["secure_id_order"], "amount"=>$secure["secure_price"], "score"=>$user["clients_score"], "id_user"=>$id_user, "status_pay"=>0, "status"=>1, "amount_percent" => $Ads->secureTotalAmountPercent($secure["secure_price"])] );

	    }

    }
              
    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
    echo true;

}  
?>