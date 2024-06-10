<?php

require "WebToPay.php";

$amount = round($paramForm["amount"], 2);

try {

    $request = WebToPay::redirectToPayment(array(
    'projectid'     => $param["id_shop"],
    'sign_password' => $param["private_key"],
    'orderid'       => $paramForm["id_order"],
    'amount'        => $amount,
    'currency'      => $param["curr"],
    'accepturl'     => $param["link_success"],
    'paytext'       => $paramForm["title"],
    'cancelurl'     => $param["link_cancel"],
    'callbackurl'   => $config["urlPath"] . "/systems/payment/paysera/callback.php",
    'test'          => $param["test"],
    ));

} catch (WebToPayException $e) {

} 

return ["link"=>$request];
?>