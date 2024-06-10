<?php

function sendRequest($options=array(), $url="", $access_token=NULL) {

    $curl = curl_init('https://yoomoney.ru'.$url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Authorization: Bearer " . $access_token
    ));
    curl_setopt ($curl, CURLOPT_USERAGENT, 'Yandex.Money.SDK/PHP');
    curl_setopt ($curl, CURLOPT_POST, 1);
    $query = http_build_query($options);
    curl_setopt ($curl, CURLOPT_POSTFIELDS, $query);
    curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 2);
    $body = curl_exec ($curl);
    $result = new \StdClass();
    $result->status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $result->body = $body;
    curl_close ($curl);
    return $result;
}