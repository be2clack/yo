<?php 

define('unisitecms', true);
session_start();

$config = require "../../../config.php";
require_once($config["basePath"]."/systems/unisite.php");
require_once('PG_Signature.php');

$param = paymentParams('freedompay');

$arrRequest = array();
if(!empty($_POST)) 
	$arrRequest = $_POST;
else
	$arrRequest = $_GET;

$thisScriptName = PG_Signature::getOurScriptName();
if (empty($arrRequest['pg_sig']) || !PG_Signature::check($arrRequest['pg_sig'], $thisScriptName, $arrRequest, $param['secret_key_payout'])){
	exit("Wrong signature");
}

if ($arrRequest['pg_result'] == 1) {

  $Profile->payCallBack( $arrRequest["pg_order_id"] );
  $strResponseDescription = "Оплата принята";

}else{

  $strResponseDescription = "Оплата не принята";

}

if($arrRequest['pg_can_reject'] == 1)
	$strResponseStatus = 'ok';
else
	$strResponseStatus = 'error';
	
$objResponse = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><response/>');
$objResponse->addChild('pg_salt', $arrRequest['pg_salt']);
$objResponse->addChild('pg_status', $strResponseStatus);
$objResponse->addChild('pg_description', $strResponseDescription);
$objResponse->addChild('pg_sig', PG_Signature::makeXML($thisScriptName, $objResponse, $param['secret_key_payout']));

header("Content-type: text/xml");

echo $objResponse->asXML();

?>