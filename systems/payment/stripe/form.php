<?php
require $config["basePath"] . '/systems/classes/vendor/autoload.php';
require 'currency.php';

$stripe = new \Stripe\StripeClient($param["private_key"]);

$product = $stripe->products->create(['name' => $paramForm["title"]]);

$curr = $currency[$param["curr"]];

$price = $stripe->prices->create(
  [
    'product' => $product['id'],
    'unit_amount' => $curr['zero-decimal'] ? $paramForm["amount"] : $paramForm["amount"]*100,
    'currency' => $param["curr"],
  ]
);

$result = $stripe->paymentLinks->create(
  [
    'line_items' => [['price' => $price['id'], 'quantity' => 1]],
    'after_completion' => [
      'type' => 'redirect',
      'redirect' => ['url' => $config["urlPath"] . "/pay/status?order=" . $paramForm["id_order"]],
    ],
    'metadata' => ['order_id' => $paramForm["id_order"]],
  ]
);

return ["link"=>urldecode($result['url'])];

?>