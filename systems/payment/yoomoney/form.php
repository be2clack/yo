<?php

$successURL = $paramForm["link_success"] ? $paramForm["link_success"] : $config['urlPath'] . '/pay/status/success';

$params = 'sum='.number_format($paramForm["amount"], 2, ".", "").'&receiver='.$param["wallet_number"].'&label='.$paramForm["id_order"].'&quickpay-form=donate&targets='.trim($paramForm["title"]).'&successURL='.$successURL.'&paymentType=PC';

$url = 'https://yoomoney.ru/quickpay/confirm.xml?'.$params;

return ["link"=>$url];

?>