<?php

include("{$config["basePath"]}/systems/payment/tinkoff/TinkoffMassPaymentsAPI.php");
    
$tinkoffApi = new TinkoffMassPaymentsAPI();

$status = $tinkoffApi->addCard($_SESSION['profile']['id'], $_SESSION['profile']['data']["clients_card_id"]);

if($status['status'] == true){
    echo json_encode( ["status"=>true, "link"=>$status['link']] );
}else{
    echo json_encode( ["status"=>false,"answer"=>$status['answer']] );
}

?>