<?php 

define('unisitecms', true);
session_start();

$config = require "../../../config.php";
include_once( $config["basePath"] . "/systems/unisite.php");

require "sendRequest.php";

$param = paymentParams('yoomoney');

$options = array(
    'client_id'=>$param["client_id"],
    'code'=>$_GET['code'],
    'grant_type'=>'authorization_code',
    'redirect_uri'=>$config["urlPath"].'/systems/payment/yoomoney/OAuth.php',
    'scope'=>'account-info operation-history',
    'client_secret'=>$param["client_secret"]
);

if($_GET['code']){

	$result = sendRequest($options, '/oauth/token', $_GET['code']);

	$token = json_decode($result->body);

	if($token->access_token){

		$param["access_token"] = $token->access_token;
	    $param = json_encode($param);
	    $param = encrypt($param);
		update('update uni_payments set param=? where code=?', [$param, 'yoomoney']);
		header('Location: '.$config["urlPath"].'/'.$config["folder_admin"].'?route=settings&tab=payments');
		exit;

	}

}

?>
