<?php 
define('unisitecms', true);

session_start();
$config = require "../../../config.php";

require_once($config["basePath"]."/systems/unisite.php");

$param = paymentParams('stripe');

$endpoint_secret = $param["secret_webhook"];
$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {

    $event = \Stripe\Webhook::constructEvent(

        $payload, $sig_header, $endpoint_secret

    );

} catch(\UnexpectedValueException $e) {

    http_response_code(400);

    exit();

} catch(\Stripe\Exception\SignatureVerificationException $e) {

    http_response_code(400);

    exit();

}

switch ($event->type) {

    case 'checkout.session.completed':

        $paymentIntent = $event->data->object;

        $Profile->payCallBack( $paymentIntent->metadata->order_id );

    break;

    default:

        echo 'Received unknown event type ' . $event->type;

}

http_response_code(200);

?>