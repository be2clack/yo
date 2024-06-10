<?php
define('unisitecms', true);
session_start();

$config = require "../../../config.php";
require_once($config["basePath"]."/systems/unisite.php");

$param = paymentParams('walletone');

function print_answer($result, $description)
{
print "WMI_RESULT=" . strtoupper($result) . "&";
print "WMI_DESCRIPTION=" .urlencode($description);
exit();
}

// Проверка наличия необходимых параметров в POST-запросе

if (!isset($_POST["WMI_SIGNATURE"]))
print_answer("Retry", "Отсутствует параметр WMI_SIGNATURE");

if (!isset($_POST["WMI_PAYMENT_NO"]))
print_answer("Retry", "Отсутствует параметр WMI_PAYMENT_NO");

if (!isset($_POST["WMI_ORDER_STATE"]))
print_answer("Retry", "Отсутствует параметр WMI_ORDER_STATE");

// Извлечение всех параметров POST-запроса, кроме WMI_SIGNATURE

foreach($_POST as $name => $value)
{
if ($name !== "WMI_SIGNATURE") $params[$name] = $value;
}

// Сортировка массива по именам ключей в порядке возрастания
// и формирование сообщения, путем объединения значений формы

uksort($params, "strcasecmp"); $values = "";

foreach($params as $name => $value)
{
//Конвертация из текущей кодировки (UTF-8)
//необходима только если кодировка магазина отлична от Windows-1251
//  $value = iconv("utf-8", "windows-1251", $value);
$values .= $value;
}

// Формирование подписи для сравнения ее с параметром WMI_SIGNATURE

$signature = base64_encode(pack("H*", md5($values . $param["key"])));

//Сравнение полученной подписи с подписью W1

if ($signature == $_POST["WMI_SIGNATURE"])
{
if (strtoupper($_POST["WMI_ORDER_STATE"]) == "ACCEPTED")
{
  
  $Profile->payCallBack( $_POST["WMI_PAYMENT_NO"] );

  print_answer("Ok", "Заказ #" . $_POST["WMI_PAYMENT_NO"] . " оплачен!");
}
else
{
  // Случилось что-то странное, пришло неизвестное состояние заказа

  print_answer("Retry", "Неверное состояние ". $_POST["WMI_ORDER_STATE"]);
}
}
else
{
// Подпись не совпадает, возможно вы поменяли настройки интернет-магазина

print_answer("Retry", "Неверная подпись " . $_POST["WMI_SIGNATURE"]);
}

?>