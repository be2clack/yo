<?php
require_once('PG_Signature.php');

$amount = number_format($paramForm["amount"], 2, ".", "");
$params = [];

$arrFields = array(
    'pg_merchant_id'   => $param["id_merchant"],
    'pg_order_id'     => $paramForm["id_order"],
    'pg_currency'     => $param["curr"],
    'pg_amount'       => $amount,
    'pg_description'    => $paramForm["title"],
    'pg_testing_mode'   => $param["test"],
    'pg_check_url'      => $config["urlPath"]."/systems/payment/paybox/callback.php",
    'pg_result_url'     => $config["urlPath"]."/systems/payment/paybox/callback.php",
    'pg_success_url'    => $param["link_success"],
    'pg_failure_url'    => $param["link_cancel"],
    'pg_request_method'   => 'GET',
    'pg_salt'       => rand(21,43433),
);

$arrFields['pg_sig'] = PG_Signature::make('payment.php', $arrFields, $param["secret_key"]);

foreach($arrFields as $strParamName => $strParamValue){

  $params[] = $strParamName.'='.$strParamValue;

}

$link = "https://api.paybox.money/payment.php?".implode('&',$params);

return ["link"=>$link];

?>


