<?php

include 'TinkoffMerchantAPI.php';

$api = new TinkoffMerchantAPI(
    $param["terminal_key"],
    $param["secret_key"]
);

function balanceAmount($isShipping, $items, $amount)
{
    $itemsWithoutShipping = $items;

    if ($isShipping) {
        $shipping = array_pop($itemsWithoutShipping);
    }

    $sum = 0;

    foreach ($itemsWithoutShipping as $item) {
        $sum += $item['Amount'];
    }

    if (isset($shipping)) {
        $sum += $shipping['Amount'];
    }

    if ($sum != $amount) {
        $sumAmountNew = 0;
        $difference = $amount - $sum;
        $amountNews = [];

        foreach ($itemsWithoutShipping as $key => $item) {
            $itemsAmountNew = $item['Amount'] + floor($difference * $item['Amount'] / $sum);
            $amountNews[$key] = $itemsAmountNew;
            $sumAmountNew += $itemsAmountNew;
        }

        if (isset($shipping)) {
            $sumAmountNew += $shipping['Amount'];
        }

        if ($sumAmountNew != $amount) {
            $max_key = array_keys($amountNews, max($amountNews))[0];    // ключ макс значения
            $amountNews[$max_key] = max($amountNews) + ($amount - $sumAmountNew);
        }

        foreach ($amountNews as $key => $item) {
            $items[$key]['Amount'] = $amountNews[$key];
        }
    }

    return $items;
}

$paramForm["amount"] = $paramForm["amount"] * 100;

$params = [
    'OrderId' => $paramForm["id_order"],
    'Amount'  => $paramForm["amount"],
    'Description' => $paramForm["title"],
    'NotificationURL' => $config["urlPath"]."/systems/payment/tinkoff/callback_payment.php",
    'SuccessURL' => $paramForm["link_success"] ? $paramForm["link_success"] : $config['urlPath'] . '/pay/status/success',
    'FailURL' => $config['urlPath'] . '/pay/status/fail',
    'DATA'    => [
        'Email' => $paramForm["email"]
    ],
];

if($param["receipt"]){

    $receiptItem = [[
        'Name'          => $paramForm["title"],
        'Price'         => $paramForm["amount"],
        'Quantity'      => 1,
        'Amount'        => $paramForm["amount"],
        'PaymentMethod' => 'full_payment',
        'PaymentObject' => 'payment',
        'Tax'           => $param["vat_code"] ?: 'none',
    ]];

    $receipt = [
        'EmailCompany' => $settings['contact_email'],
        'Email'        => $paramForm["email"],
        'Phone'        => $paramForm["phone"],
        'Taxation'     => $param["tax_system_code"],
        'Items'        => balanceAmount(false, $receiptItem, $paramForm["amount"]),
    ];

    $params['Receipt'] = $receipt;
}

$ot = $api->init($params);

return ["link"=>$api->paymentUrl];

?>