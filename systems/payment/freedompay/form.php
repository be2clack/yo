<?php

if($param["receipt"]){

  $request = $requestForSignature = [
    'pg_order_id' => $paramForm["id_order"],
    'pg_merchant_id'=> $param["id_merchant"],
    'pg_amount' => number_format($paramForm["amount"], 2, ".", ""),
    'pg_description' => $paramForm["title"],
    'pg_salt' => rand(21,43433),
    'pg_currency' => $param["curr"],
    'pg_result_url' => $config["urlPath"]."/systems/payment/freedompay/callback.php",
    'pg_request_method' => 'POST',
    'pg_success_url' => $param["link_success"],
    'pg_failure_url' => $param["link_cancel"],
    'pg_testing_mode' => $param["test"],
    'pg_receipt_positions' => [
      [
        // В случае формирования чеков в Республике Узбекистан, в параметре "name" необходимо передавать
        // дополнительные значения в определённой последовательности.
        // Детальную информацию можно найти в разделе "Особенности формирования фискальных чеков"
        'name' => $paramForm["title"],
        'count' => '1',
        'tax_type' => $param["tax_type"],
        'price' => number_format($paramForm["amount"], 2, ".", ""),
      ]
    ],
  ];

}else{

  $request = $requestForSignature = [
    'pg_order_id' => $paramForm["id_order"],
    'pg_merchant_id'=> $param["id_merchant"],
    'pg_amount' => number_format($paramForm["amount"], 2, ".", ""),
    'pg_description' => $paramForm["title"],
    'pg_salt' => rand(21,43433),
    'pg_currency' => $param["curr"],
    'pg_result_url' => $config["urlPath"]."/systems/payment/freedompay/callback.php",
    'pg_request_method' => 'POST',
    'pg_success_url' => $param["link_success"],
    'pg_failure_url' => $param["link_cancel"],
    'pg_testing_mode' => $param["test"],
  ];

}


/**
 * Функция превращает многомерный массив в плоский
 */
function makeFlatParamsArray($arrParams, $parent_name = '')
{
  $arrFlatParams = [];
  $i = 0;
  foreach ($arrParams as $key => $val) {
    $i++;
    /**
     * Имя делаем вида tag001subtag001
     * Чтобы можно было потом нормально отсортировать и вложенные узлы не запутались при сортировке
     */
    $name = $parent_name . $key . sprintf('%03d', $i);
    if (is_array($val)) {
      $arrFlatParams = array_merge($arrFlatParams, makeFlatParamsArray($val, $name));
      continue;
    }
    $arrFlatParams += array($name => (string)$val);
  }

  return $arrFlatParams;
}

// Превращаем объект запроса в плоский массив
$requestForSignature = makeFlatParamsArray($requestForSignature);

// Генерация подписи
ksort($requestForSignature); // Сортировка по ключю
array_unshift($requestForSignature, 'init_payment.php'); // Добавление в начало имени скрипта
array_push($requestForSignature, $param["secret_key_payment"]); // Добавление в конец секретного ключа

$request['pg_sig'] = md5(implode(';', $requestForSignature)); // Полученная подпись

$get = file_get_contents("https://api.freedompay.kz/init_payment.php?".http_build_query($request));

$xml = simplexml_load_string($get, "SimpleXMLElement", LIBXML_NOCDATA);

$data = json_decode(json_encode($xml),true);

return ["link"=>$data["pg_redirect_url"]];

?>


