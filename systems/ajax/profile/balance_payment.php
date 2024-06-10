<?php

$error = [];

$getUser = findOne("uni_clients", "clients_id=?", [$_SESSION['profile']['id']]);

$amount = 0;

if($_POST["amount"]){
   $amount = round($_POST["amount"],2);
}elseif($_POST["change_amount"]){
   $amount = round($_POST["change_amount"],2);
}

if(!$_POST["payment"]){
   $error[] = $ULang->t("Пожалуйста, выберите способ оплаты");
}

if(!$amount){
   $error[] = $ULang->t("Пожалуйста, укажите сумму пополнения");
}else{

    if( $amount < round($settings["min_deposit_balance"], 2) ){
       $error[] = $ULang->t("Минимальная сумма пополнения") . " " . $Main->price($settings["min_deposit_balance"]);
    }elseif( $amount > round($settings["max_deposit_balance"], 2) ){
       $error[] = $ULang->t("Максимальная сумма пополнения") . " " . $Main->price($settings["max_deposit_balance"]);
    }

}

if(!$error){

 $answer = $Profile->payMethod( $_POST["payment"], array( "amount" => $amount, "name" => $getUser["clients_name"], "email" => $getUser["clients_email"], "phone" => $getUser["clients_phone"], "id_order" => generateOrderId(), "id_user" => $_SESSION['profile']['id'], "action" => "balance", "title" => $static_msg["19"] . " - " . $settings["site_name"] ) );

  echo json_encode( array( "status" => true, "redirect" => $answer ) );

}else{

  echo json_encode( array( "status" => false, "answer" => implode("\n", $error) ) );

}

?>