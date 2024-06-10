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

	$Ads = new Ads();
    
    $id = (int)$_POST["id"];
    $status = (int)$_POST["status"];
    $text = $_POST["text"];

    if(!$text){ echo json_encode( ["status"=>false, "answer"=>"Пожалуйста, укажите комментарий"] ); exit(); }

    $secure = findOne("uni_secure", "secure_id=?", [$id]);

    if($secure){

		$user_buyer = findOne("uni_clients", "clients_id=?", [$secure["secure_id_user_buyer"]]); // Покупатель
		$user_seller = findOne("uni_clients", "clients_id=?", [$secure["secure_id_user_seller"]]); // Продавец

	    update("update uni_secure_disputes set secure_disputes_text_arbitr=?,secure_disputes_status=? where secure_disputes_id_secure=?", array($text,$status,$id));

	    if($status == 0){

	        $Ads->addSecurePayments( ["id_order"=>$secure["secure_id_order"], "amount"=>$secure["secure_price"], "score"=>$user_seller["clients_score"], "id_user"=>$secure["secure_id_user_seller"], "status_pay"=>0, "status"=>1, "amount_percent" => $Ads->secureTotalAmountPercent($secure["secure_price"])] );

	        update("update uni_secure set secure_status=? where secure_id=?", array(3,$id));

	    }elseif($status == 1){

	    	  if(!$secure["secure_balance_payment"]){
	        		$Ads->addSecurePayments( ["id_order"=>$secure["secure_id_order"], "amount"=>$secure["secure_price"], "score"=>$user_buyer["clients_score"], "id_user"=>$secure["secure_id_user_buyer"], "status_pay"=>0, "status"=>2, "amount_percent" => $Ads->secureTotalAmountPercent($secure["secure_price"], false)] );
	        }else{
	        	   $Ads->addSecurePayments( ["id_order"=>$secure["secure_id_order"], "amount"=>$secure["secure_price"], "score"=>$user_buyer["clients_score"], "id_user"=>$secure["secure_id_user_buyer"], "status_pay"=>1, "status"=>2, "amount_percent" => $Ads->secureTotalAmountPercent($secure["secure_price"], false)] );
	        	   $Profile->actionBalance(array("id_user"=>$user_buyer["clients_id"],"summa"=>$secure["secure_price"],"title"=>$static_msg["61"].' '.$secure["secure_id_order"],"id_order"=>generateOrderId(),"email" => $user_buyer["clients_email"],"name" => $user_buyer["clients_name"], "note" => ""),"+");
	        }

	        update("update uni_secure set secure_status=? where secure_id=?", array(5,$id));

	    }elseif($status == 2){

	    	$secure["secure_price"] = $secure["secure_price"] / 2;

	    	if(!$secure["secure_balance_payment"]){
	    		$Ads->addSecurePayments( ["id_order"=>$secure["secure_id_order"], "amount"=>$secure["secure_price"], "score"=>$user_buyer["clients_score"], "id_user"=>$secure["secure_id_user_buyer"], "status_pay"=>0, "status"=>2, "amount_percent" => $Ads->secureTotalAmountPercent($secure["secure_price"], false)] );
	    	}else{
	    		$Ads->addSecurePayments( ["id_order"=>$secure["secure_id_order"], "amount"=>$secure["secure_price"], "score"=>$user_buyer["clients_score"], "id_user"=>$secure["secure_id_user_buyer"], "status_pay"=>1, "status"=>2, "amount_percent" => $Ads->secureTotalAmountPercent($secure["secure_price"], false)] );
	    		$Profile->actionBalance(array("id_user"=>$user_buyer["clients_id"],"summa"=>$secure["secure_price"],"title"=>$static_msg["62"].' '.$secure["secure_id_order"],"id_order"=>generateOrderId(),"email" => $user_buyer["clients_email"],"name" => $user_buyer["clients_name"], "note" => ""),"+");
	    	}

	    	$Ads->addSecurePayments( ["id_order"=>$secure["secure_id_order"], "amount"=>$secure["secure_price"], "score"=>$user_seller["clients_score"], "id_user"=>$secure["secure_id_user_seller"], "status_pay"=>0, "status"=>1, "amount_percent" => $Ads->secureTotalAmountPercent($secure["secure_price"])] );

	        update("update uni_secure set secure_status=? where secure_id=?", array(3,$id));

	    }

    }
            
    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
    echo json_encode( ["status"=>true] );

}  
?>