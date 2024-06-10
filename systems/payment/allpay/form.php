<?php

function getApiSignature($params, $apikey) { 
    ksort($params);
    $chunks = [];
    foreach($params as $k => $v) {
        $v = trim($v);
        if ($v !== '' && $k != 'sign') {
            $chunks[] = $v;
        }  
    }
    $signature = implode(':', $chunks) . ':' . $apikey;
    $signature = hash('sha256', $signature);
    return $signature;  
}

$api_url = 'https://allpay.to/app/?show=getpayment&mode=api1';

$request = [
    'name' => $paramForm["title"],
    'login' => $param["login"],
    'order_id' => $paramForm["id_order"],
    'amount' => number_format($paramForm["amount"], 2, ".", ""),
    'currency' => $param["curr"],
    'lang' => 'ENG',
    'notifications_url' => $config["urlPath"].'/systems/payment/allpay/callback.php',        
    'client_name' => $paramForm["name"],
    'client_email' => $paramForm["email"],
    'client_phone' => $paramForm["phone"]        
];

$sign = getApiSignature($request, $param["private_key"]);
$request['sign'] = $sign;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $request); 
$result = curl_exec($ch); 
curl_close($ch);
$data = json_decode($result, true);

return ["link"=>$data['payment_url']];
?>