<?php 

define('unisitecms', true);
session_start();

$config = require "../../../config.php";
require_once($config["basePath"]."/systems/unisite.php");

$param = paymentParams('allpay');

function getApiSignature($params, $apikey) { 
    ksort($params);
    $chunks = [];
    foreach($params as $k => $v) {
        $v = trim($v);
        if ($v !== '' && $k != 'sign') {
            $chunks[] = $v;
        }  
    }
    $signature = implode(':', $chunks) . ':' . $apikey;
    $signature = hash('sha256', $signature);
    return $signature;  
}

$sign = getApiSignature($_REQUEST, $param["private_key"]);

if($_POST['status'] == 1 && $_POST['sign'] == $sign) {
    $Profile->payCallBack( $_POST['order_id'] );
}

?>