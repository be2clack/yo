<?php
defined('unisitecms') or exit();

if( !$settings["secure_payment_service_name"] ) exit;

$param = paymentParams( $settings["secure_payment_service_name"] );
$payment = findOne("uni_payments","code=?", array( $settings["secure_payment_service_name"] ));

function actionVarPay( $value = [] ){
	global $config,$settings,$param,$payment,$static_msg;
    
    $Main = new Main();

    if(!$value["clients_score"]){
        update("update uni_secure_payments set secure_payments_errors=?, secure_payments_status_pay=? where secure_payments_id=?", [ $static_msg["51"], 2, $value["payments_id"] ]);
        update("update uni_secure set secure_status_payment_user=? where secure_id_order=?", [2,$value["secure_id_order"]]);
        return false;
    }

	if( $settings["secure_payment_service_name"] == "liqpay" ){

		include("{$config["basePath"]}/systems/payment/liqpay/LiqPay.php");

	   $score = decrypt($value["clients_score"]);

		$liqpay = new LiqPay(trim($param["public_key"]), trim($param["private_key"]));
		$result = $liqpay->api("request", array(
		'action'         => 'p2pcredit',
		'version'        => '3',
		'amount'         => round($value["total_amount"],2),
		'currency'       => $settings["currency_main"]["code"],
		'description'    => $value["secure_desc"],
		'order_id'       => $value["secure_id_order"],
		'receiver_card'  => $score,
		'receiver_last_name'  => $value["user_name"]
		));
        
        $result = json_decode( json_encode($result) , true);

		if ( $result["result"] == "ok" && $result["status"] == "success" )
		{
			update("update uni_secure_payments set secure_payments_status_pay=?,secure_payments_errors=? where secure_payments_id=?", [ 1, "", $value["payments_id"] ]);
			update("update uni_secure set secure_status_payment_user=? where secure_id_order=?", [1,$value["secure_id_order"]]);

			$profit = calcPercent( $value["amount"], $settings["secure_percent_service"] );

            if($profit && $value["amount"]){
               $Main->addOrder( ["id_ad"=>$value["id_ad"],"price"=>$profit,"title"=>$static_msg["52"],"id_user"=>$value["id_user"],"status_pay"=>1, "user_name" => $value["user_name"], "id_hash_user" => $value["id_hash_user"], "action_name" => "secure"] );
            }
		}
		else
		{
			if(!$result["err_description"]) $result["err_description"] = 'Неизвестная ошибка';
			update("update uni_secure_payments set secure_payments_errors=?,secure_payments_status_pay=? where secure_payments_id=?", [ $result["err_description"], 2 , $value["payments_id"] ]);
			update("update uni_secure set secure_status_payment_user=? where secure_id_order=?", [2,$value["secure_id_order"]]);
		}      

	}elseif( $settings["secure_payment_service_name"] == "yoomoney" ){

		include("{$config["basePath"]}/systems/payment/yoomoney/sendRequest.php");

	   $score = decrypt($value["clients_score"]);

	   $options =  array(
             'client_id'=>$param["client_id"],
             'pattern_id'=>'p2p',
             'to'=>trim($score),
             'amount_due'=>round($value["total_amount"],2),
             'message'=>$value["secure_desc"],
             'comment'=>$value["secure_desc"],
             'label'=>$value["secure_id_order"],
      );

      $data = sendRequest($options, '/api/request-payment', $param["access_token"]);
      $result = json_decode( $data->body , true);

      if($result['status'] == "success"){

	      $request_id = $result['request_id'];

		   $options =  array(
	             'request_id'=>$request_id,
	             'money_source'=>'wallet',
	      );

	      $data = sendRequest($options, '/api/process-payment', $param["access_token"]);
	      $result = [];
	      $result = json_decode( $data->body , true);

      }

		if ( $result['status'] == "success" )
		{
			update("update uni_secure_payments set secure_payments_status_pay=?,secure_payments_errors=? where secure_payments_id=?", [ 1, "", $value["payments_id"] ]);
			update("update uni_secure set secure_status_payment_user=? where secure_id_order=?", [1,$value["secure_id_order"]]);

			$profit = calcPercent( $value["amount"], $settings["secure_percent_service"] );

            if($profit && $value["amount"]){
               $Main->addOrder( ["id_ad"=>$value["id_ad"],"price"=>$profit,"title"=>$static_msg["52"],"id_user"=>$value["id_user"],"status_pay"=>1, "user_name" => $value["user_name"], "id_hash_user" => $value["id_hash_user"], "action_name" => "secure"] );
            }
		}
		else
		{
			if(!$result["error_description"]) $result["error_description"] = 'Неизвестная ошибка';
			update("update uni_secure_payments set secure_payments_errors=?,secure_payments_status_pay=? where secure_payments_id=?", [ $result['error_description'], 2 , $value["payments_id"] ]);
			update("update uni_secure set secure_status_payment_user=? where secure_id_order=?", [2,$value["secure_id_order"]]);
		}      

	}elseif( $settings["secure_payment_service_name"] == "tinkoff" ){

		include("{$config["basePath"]}/systems/payment/tinkoff/TinkoffMassPaymentsAPI.php");

		$tinkoffApi = new TinkoffMassPaymentsAPI();

		$status = $tinkoffApi->payment($value["secure_id_order"], $value["clients_card_id"], round($value["total_amount"],2));

		if($status['status'] == true){

          update("update uni_secure_payments set secure_payments_status_pay=?,secure_payments_errors=? where secure_payments_id=?", [ 1, "", $value["payments_id"] ]);
          update("update uni_secure set secure_status_payment_user=? where secure_id_order=?", [1,$value["secure_id_order"]]);

          $profit = calcPercent( $value["amount"], $settings["secure_percent_service"] );

          if($profit && $value["amount"]){

             $Main->addOrder( ["price"=>$profit,"title"=>$static_msg["52"],"id_user"=>$value["id_user"],"status_pay"=>1, "user_name" => $value["user_name"], "id_hash_user" => $value["id_hash_user"], "action_name" => "secure"] );
          }

		}else{

			if(!$status["answer"]) $status["answer"] = 'Неизвестная ошибка';
			update("update uni_secure_payments set secure_payments_errors=?,secure_payments_status_pay=? where secure_payments_id=?", [$status['answer'], 2 , $value["payments_id"]]);
			update("update uni_secure set secure_status_payment_user=? where secure_id_order=?", [2,$value["secure_id_order"]]);

		}

	}elseif( $settings["secure_payment_service_name"] == "freedompay" ){

		include("{$config["basePath"]}/systems/payment/freedompay/PG_Signature.php");

		$score = decrypt($value["clients_score"]);

		$params = [];

		$arrFields = array(
		    'pg_merchant_id'   => $param["id_merchant"],
		    'pg_order_id'     => $value["secure_id_order"],,
		    'pg_currency'     => $param["curr"],
		    'pg_amount'       => round($value["total_amount"],2),
		    'pg_description'    => $value["secure_desc"],
		    'pg_testing_mode'   => 0,
		    'pg_payment_to'     => $score,
		    'pg_post_link'      => $config["urlPath"]."/systems/payment/freedompay/callback_payout.php",
		    'pg_request_method'   => 'GET',
		    'pg_salt'       => rand(21,43433),
		);

		$arrFields['pg_sig'] = PG_Signature::make('payment.php', $arrFields, $param["secret_key"]);

		foreach($arrFields as $strParamName => $strParamValue){

		  $params[] = $strParamName.'='.$strParamValue;

		}

		if($param["test"]){
			$get = file_get_contents("https://test-api.freedompay.money/g2g/p2p2nonreg?".implode('&',$params));
		}else{
			$get = file_get_contents("https://api.freedompay.money/g2g/p2p2nonreg?".implode('&',$params));
		}
		
		debug($get);

	}



}


$getSecurePayments = getAll("select * from uni_secure_payments where secure_payments_status_pay=?", [0]);

if( count($getSecurePayments) ){

   foreach ($getSecurePayments as $key => $value) {

   	   $secure = findOne("uni_secure", "secure_id_order=?", [$value["secure_payments_id_order"]]);

   	   if($secure){
		
			$user_buyer = findOne("uni_clients", "clients_id=?", [$secure["secure_id_user_buyer"]]);
			$user_seller = findOne("uni_clients", "clients_id=?", [$secure["secure_id_user_seller"]]);

			$disputes = findOne("uni_secure_disputes", "secure_disputes_id_secure=?", [$secure["secure_id"]]);

			if($disputes){
				if($disputes["secure_disputes_status"] == 0){

					actionVarPay( [ "clients_card_id"=>$user_seller["clients_card_id"],"clients_score"=>$user_seller["clients_score"],"total_amount"=>$value["secure_payments_amount"] - calcPercent( $value["secure_payments_amount"], $settings["secure_percent_service"] ),"secure_id"=>$secure["secure_id"],"secure_id_order"=>$secure["secure_id_order"],"payments_id"=>$value["secure_payments_id"], "amount" => $value["secure_payments_amount"], "id_ad" => $secure["secure_id_ad"], "id_user" => $user_seller["clients_id"], "user_name" => $user_seller["clients_name"], "id_hash_user" => $user_seller["clients_id_hash"], "secure_desc" => $static_msg["53"] ] );

				}elseif($disputes["secure_disputes_status"] == 1){

					actionVarPay( [ "clients_card_id"=>$user_buyer["clients_card_id"],"clients_score"=>$user_buyer["clients_score"],"total_amount"=>$value["secure_payments_amount"],"secure_id"=>$secure["secure_id"],"secure_id_order"=>$secure["secure_id_order"],"payments_id"=>$value["secure_payments_id"], "secure_desc" => $static_msg["54"] ] );

				}elseif($disputes["secure_disputes_status"] == 2){

					$value["secure_payments_amount"] = $value["secure_payments_amount"] / 2;
					
					actionVarPay( [ "clients_card_id"=>$user_seller["clients_card_id"],"clients_score"=>$user_seller["clients_score"],"total_amount"=>$value["secure_payments_amount"] - calcPercent( $value["secure_payments_amount"], $settings["secure_percent_service"] ),"secure_id"=>$secure["secure_id"],"secure_id_order"=>$secure["secure_id_order"],"payments_id"=>$value["secure_payments_id"], "amount" => $value["secure_payments_amount"], "id_ad" => $secure["secure_id_ad"], "id_user" => $user_seller["clients_id"], "user_name" => $user_seller["clients_name"], "id_hash_user" => $user_seller["clients_id_hash"], "secure_desc" => $static_msg["53"] ] );

					actionVarPay( [ "clients_card_id"=>$user_buyer["clients_card_id"],"clients_score"=>$user_buyer["clients_score"],"total_amount"=>$value["secure_payments_amount"],"secure_id"=>$secure["secure_id"],"secure_id_order"=>$secure["secure_id_order"],"payments_id"=>$value["secure_payments_id"], "secure_desc" => $static_msg["55"] ] );

				}
			}else{

				actionVarPay( [ "clients_card_id"=>$user_seller["clients_card_id"],"clients_score"=>$user_seller["clients_score"],"total_amount"=>$value["secure_payments_amount_percent"],"secure_id"=>$secure["secure_id"],"secure_id_order"=>$secure["secure_id_order"],"payments_id"=>$value["secure_payments_id"], "amount" => $value["secure_payments_amount"], "id_ad" => $secure["secure_id_ad"], "id_user" => $user_seller["clients_id"], "user_name" => $user_seller["clients_name"], "id_hash_user" => $user_seller["clients_id_hash"], "secure_desc" => $static_msg["53"] ] );

			}

	    }

   }

}

?>