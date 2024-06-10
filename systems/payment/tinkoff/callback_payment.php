<?php 

define('unisitecms', true);
session_start();

$config = require "../../../config.php";
require_once($config["basePath"]."/systems/unisite.php");

$param = paymentParams('tinkoff');

function genToken($args)
{
    global $param;
    $token = '';

    unset($args['Token']);
    $args['Success'] = $args['Success'] ? 'true' : 'false';
    $args['Password'] = $param["secret_key"];
    ksort($args);

    foreach ($args as $key => $arg) {
        if (!is_array($arg)) {
            $token .= $arg;
        }
    }

    $token = hash('sha256', $token);

    return $token;
}

$source = file_get_contents('php://input');
$requestBody = json_decode($source, true);

$current_token = $requestBody['Token'];

$token = genToken($requestBody);

if ($token == $current_token) {

    if ($requestBody['Status'] == 'CONFIRMED') {
        $Profile->payCallBack($requestBody['OrderId']);  
    }

}

header("HTTP/1.1 200 OK");

?>