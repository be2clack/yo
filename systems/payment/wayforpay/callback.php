<?php 

define('unisitecms', true);
session_start();

$config = require "../../../config.php";
require_once($config["basePath"]."/systems/unisite.php");

$param = paymentParams('wayforpay');

$json = file_get_contents('php://input');

$obj = json_decode($json, true);

$time = time();

$string = "{$param["id_shop"]};{$obj["orderReference"]};{$obj["amount"]};{$obj["currency"]};{$obj["authCode"]};{$obj["cardPan"]};{$obj["transactionStatus"]};{$obj["reasonCode"]}";

$hash = hash_hmac("md5",$string,$param["private_key"]);

if( $obj["merchantSignature"] == $hash && $obj["transactionStatus"] == "Approved" ){
    
	$Profile->payCallBack( $obj["orderReference"] );

	echo '
	    {
		"orderReference":"'.$obj["orderReference"].'",
		"status":"accept",
		"time":'.$time.',
		"signature":"'.hash_hmac("md5", "{$obj["orderReference"]};accept;{$time}" ,$param["private_key"]).'"
		}
	';

}

?>