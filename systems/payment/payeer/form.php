<?php

$amount = number_format($paramForm["amount"], 2, ".", "");

$m_shop = $param["id_merchant"];
$m_orderid = $paramForm["id_order"];
$m_amount = $amount;
$m_curr = $param["curr"];
$m_desc = base64_encode($paramForm["title"]);
$m_key = $param["secret_key"];

$arHash = array(
   $m_shop,
   $m_orderid,
   $m_amount,
   $m_curr,
   $m_desc
);


$arParams = array(
   'success_url' => $paramForm["link_success"] ? $paramForm["link_success"] : $param["link_success"],
   'fail_url' => $param["link_cancel"],
   'status_url' => $config["urlPath"] . "/systems/payment/payeer/callback.php",   
);

$key = md5($param["secret_key_parameters"].$m_orderid);

$m_params = @urlencode(base64_encode(openssl_encrypt(json_encode($arParams), 'AES-256-CBC', $key, OPENSSL_RAW_DATA)));

$arHash[] = $m_params;

$arHash[] = $m_key;

$sign = strtoupper(hash('sha256', implode(':', $arHash)));

$params["m_shop"] = $m_shop;
$params["m_orderid"] = $m_orderid;
$params["m_amount"] = $m_amount;
$params["m_curr"] = $m_curr;
$params["m_desc"] = $m_desc;
$params["m_sign"] = $sign;
$params["m_params"] = $m_params;
$params["m_cipher_method"] = "AES-256-CBC";

$link = "https://payeer.com/merchant/?".http_build_query($params);

return ["link"=>$link];

?>