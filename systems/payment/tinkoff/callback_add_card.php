<?php 

define('unisitecms', true);
session_start();

$config = require "../../../config.php";
require_once($config["basePath"]."/systems/unisite.php");

$param = paymentParams('tinkoff');

$source = file_get_contents('php://input');
$requestBody = json_decode($source, true);

$customer = explode('client_', $requestBody['CustomerKey']);

if($requestBody['Success'] == true && $requestBody['Status'] == 'COMPLETED' && intval($customer[1])){

    update('update uni_clients set clients_score=?, clients_card_id=? where clients_id=?', [ $requestBody['Pan'],$requestBody['CardId'],intval($customer[1]) ]);

    header("HTTP/1.1 200 OK");

}

?>