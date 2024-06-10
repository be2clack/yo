<?php

require 'lib/autoload.php'; 

use YooKassa\Client;

$client = new Client();

if(!$param["id_shop"] || !$param["private_key"]){
	
	echo json_encode( ["link"=>""] );

}else{

	$client->setAuth($param["id_shop"], $param["private_key"]);

	if($param["receipt"]){

		$payment =  $client->createPayment(
		    array(
		        "amount" => array(
		            "value" => number_format($paramForm["amount"], 2, ".", ""),
		            "currency" => $param["curr"]
		        ),	        
		        "confirmation" => array(
		            "type" => "redirect",
		            'return_url' => $config["urlPath"] . "/pay/status?order=" . $paramForm["id_order"],
		        ),
		        'capture' => true,
		        'description' => $paramForm["title"],
	            'metadata' => array(
	                'order_id' => $paramForm["id_order"],
	            ),	        
		        "receipt" => array(
		            "customer" => array(
		                "full_name" => $paramForm["name"],
		                "phone" => $paramForm["phone"],
		                "email" => $paramForm["email"],
		            ),
		            "items" => array(
		                array(
		                    "description" => $paramForm["title"],
		                    "quantity" => "1.00",
		                    "amount" => array(
		                        "value" => number_format($paramForm["amount"], 2, ".", ""),
		                        "currency" => $param["curr"]
		                    ),
		                    "vat_code" => $param["vat_code"],
		                    "tax_system_code" => $param["tax_system_code"],
		                    "payment_mode" => "full_prepayment",
		                    "payment_subject" => "payment"
		                )
		            )
		        )
		    ),
		    uniqid('', true)
		);

	}else{

	    $payment = $client->createPayment(
	        array(
	            'amount' => array(
	                'value' => number_format($paramForm["amount"], 2, ".", ""),
	                'currency' => $param["curr"],
	            ),
	            'confirmation' => array(
	                'type' => 'redirect',
	                'return_url' => $config["urlPath"] . "/pay/status?order=" . $paramForm["id_order"],
	            ),
	            'capture' => true,
	            'description' => $paramForm["title"],
	            'metadata' => array(
	                'order_id' => $paramForm["id_order"],
	            )
	        ),
	        uniqid('', true)
	    );   

	}


	return ["link"=>$payment["confirmation"]["confirmation_url"]];

}

?>

