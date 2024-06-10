<?php 

define('unisitecms', true);
session_start();

$config = require "../../../config.php";
include_once( $config["basePath"] . "/systems/unisite.php");

$Profile = new Profile();

$param = paymentParams('payme');

if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = '';
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

include_once($config["basePath"] . "/systems/classes/Profile.php");
include_once($config["basePath"] . "/systems/payment/payme/paycom/Application.php");
include_once($config["basePath"] . "/systems/payment/payme/paycom/Format.php");
include_once($config["basePath"] . "/systems/payment/payme/paycom/Merchant.php");
include_once($config["basePath"] . "/systems/payment/payme/paycom/Order.php");
include_once($config["basePath"] . "/systems/payment/payme/paycom/PaycomException.php");
include_once($config["basePath"] . "/systems/payment/payme/paycom/Request.php");
include_once($config["basePath"] . "/systems/payment/payme/paycom/Response.php");
include_once($config["basePath"] . "/systems/payment/payme/paycom/Transaction.php");

$paycomConfig = [
    'merchant_id' => $param['merchant_id'],
    'login' => 'Paycom',
    'key' => $param['secret_key'],
];

$application = new Application($paycomConfig);
$application->run();

?>