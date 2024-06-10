<?php

include("LiqPay.php");

if($param["public_key"] && $param["private_key"]){

$liqpay = new LiqPay(trim($param["public_key"]), trim($param["private_key"]));
$html = $liqpay->cnb_form(array(
'action'         => 'pay',
'amount'         => number_format($paramForm["amount"], 2, ".", ""),
'currency'       => $param["curr"],
'description'    => $paramForm["title"],
'order_id'       => $paramForm["id_order"],
'server_url'     => $config["urlPath"]."/systems/payment/liqpay/callback.php",
'result_url'     => $param["link_success"],
'sandbox'        => $param["test"],
'version'        => '3'
));

return ["form"=>$html];

}

?>