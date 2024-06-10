<?php

include("{$config["basePath"]}/systems/payment/tinkoff/TinkoffMassPaymentsAPI.php");
    
$tinkoffApi = new TinkoffMassPaymentsAPI();

$status = $tinkoffApi->deleteCard($_SESSION['profile']['id'], $_SESSION['profile']['data']["clients_card_id"]);

if($status['status'] == true){
    update('update uni_clients set clients_score=?, clients_card_id=? where clients_id=?', [ '','',$_SESSION['profile']['id'] ]);
    echo json_encode(["status"=>true]);
}else{
    echo json_encode(["status"=>false, "answer"=>$status['answer']]);
}

?>