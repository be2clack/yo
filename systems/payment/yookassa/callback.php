<?php 

define('unisitecms', true);
session_start();

require 'lib/autoload.php'; 

$config = require "../../../config.php";
require_once($config["basePath"]."/systems/unisite.php");

$param = paymentParams('yookassa');

$source = file_get_contents('php://input');
$requestBody = json_decode($source, true);

use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;
use YooKassa\Model\NotificationEventType;
use YooKassa\Model\PaymentStatus;

if(isset($requestBody)){

  try {

    $notification = ($requestBody['event'] === NotificationEventType::PAYMENT_SUCCEEDED) ? new NotificationSucceeded($requestBody) : new NotificationWaitingForCapture($requestBody);

    $payment = $notification->getObject();

    if($payment->getStatus() === PaymentStatus::SUCCEEDED) {
        
        $Profile->payCallBack( $payment->metadata->order_id );

        header("HTTP/1.0 200 OK");

    }

  } catch (Exception $e) {
      
  }

}


?>