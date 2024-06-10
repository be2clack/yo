<?php

$merchant = $param["id_shop"];

$time = time();
$price = number_format($paramForm["amount"], 2, ".", "");

$string = "{$merchant};{$config["urlPath"]};{$paramForm["id_order"]};{$time};{$price};{$param["curr"]};{$paramForm["title"]};1;{$price}";

$hash = hash_hmac("md5",$string,$param["private_key"]);

$html = '
<form method="post"  class="form-pay" action="https://secure.wayforpay.com/pay" accept-charset="utf-8">
 <input name="merchantAccount" value="'.$merchant.'">
 <input name="merchantAuthType" value="SimpleSignature">
 <input name="merchantTransactionSecureType" value="AUTO">
 <input name="merchantDomainName" value="'.$config["urlPath"].'">
 <input name="merchantSignature" value="'.$hash.'">
 <input name="orderReference" value="'.$paramForm["id_order"].'">
 <input name="orderDate" value="'.$time.'">
 <input name="amount" value="'.$price.'">
 <input name="currency" value="'.$param["curr"].'">
 <input name="productName[]" value="'.$paramForm["title"].'">
 <input name="productPrice[]" value="'.$price.'">
 <input name="productCount[]" value="1">
 <input name="serviceUrl" value="'.$config["urlPath"].'/systems/payment/wayforpay/callback.php">
 <button type="submit" class="pay-trans" >Pay</button>
</form>
';

return ["form"=>$html];

?>