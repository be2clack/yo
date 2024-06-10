<?php 

define('unisitecms', true);
session_start();

$config = require "../../../config.php";
require_once($config["basePath"]."/systems/unisite.php");

$param = paymentParams('liqpay');

$post_data = $_POST["data"];
$signature = $_POST["signature"];

$data = json_decode(base64_decode($post_data), true);

$sign = base64_encode( sha1( 
trim($param["private_key"]) .  
$post_data . 
trim($param["private_key"]) 
, 1 ));


if($sign == $signature && ($data["status"] == "success" || $data["status"] == "wait_accept" || $data["status"] == "sandbox")){
    
	$Profile->payCallBack( $data["order_id"] );

	echo "ok";

}





 ?>