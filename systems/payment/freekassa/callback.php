<?php 

define('unisitecms', true);
session_start();

$config = require "../../../config.php";
require_once($config["basePath"]."/systems/unisite.php");

$param = paymentParams('freekassa');

function getIP() {
if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
return $_SERVER['REMOTE_ADDR'];
}

if (!in_array(getIP(), array('168.119.157.136', '168.119.60.227', '138.201.88.124', '178.154.197.79'))) die("hacking attempt!");

$sign = md5($param["id_shop"].':'.$_REQUEST['AMOUNT'].':'.$param["secret_word2"].':'.$_REQUEST['MERCHANT_ORDER_ID']);

if ($sign != $_REQUEST['SIGN']) {
die('wrong sign');
}

$Profile->payCallBack( $_REQUEST['MERCHANT_ORDER_ID'] );

die('YES');

?>