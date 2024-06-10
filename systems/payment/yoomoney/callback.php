<?php 

define('unisitecms', true);
session_start();

$config = require "../../../config.php";
require_once($config["basePath"]."/systems/unisite.php");

$param = paymentParams('yoomoney');

$sha1_hash = sha1($_POST['notification_type'].'&'.$_POST['operation_id'].'&'.$_POST['amount'].'&'.$_POST['currency'].'&'.$_POST['datetime'].'&'.$_POST['sender'].'&'.$_POST['codepro'].'&'.$param["private_key"].'&'.$_POST['label']);

if($_POST['label'] && $_POST['sha1_hash'] == $sha1_hash){

  $Profile->payCallBack( $_POST['label'] );

  header("HTTP/1.0 200 OK");

}

?>