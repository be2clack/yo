<?php
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$amount = round($_POST['amount'],2);
$code_payment = clear($_POST['code_payment']);

$idOrder = generateOrderId();

if(checkTokenAuth($tokenAuth, $idUser) == false){
  http_response_code(500); exit('Authorization token error');
}

$getUser = findOne('uni_clients', 'clients_id=?', [$idUser]);

if($getUser){

  if(!$amount){
     exit(json_encode(['status'=>false,'error'=>apiLangContent('Пожалуйста, укажите сумму оплаты')]));
  }else{

      if( $amount < round($settings["min_deposit_balance"], 2) ){
         exit(json_encode(['status'=>false,'error'=>apiLangContent("Минимальная сумма пополнения")." ". $Main->price($settings["min_deposit_balance"])]));
      }elseif( $amount > round($settings["max_deposit_balance"], 2) ){
         exit(json_encode(['status'=>false,'error'=>apiLangContent("Максимальная сумма пополнения")." ". $Main->price($settings["max_deposit_balance"])]));
      }

  }      

  $answer = $Profile->payMethod( $code_payment, array( "amount" => $amount, "name" => $getUser["clients_name"], "email" => $getUser["clients_email"], "phone" => $getUser["clients_phone"], "id_order" => $idOrder, "id_user" => $idUser, "action" => "balance", "title" => apiLangContent("Пополнение баланса -")." ". $settings["site_name"] ) );

  if($answer['link']){

    echo json_encode(['status'=>true,'link'=>$answer['link'], 'id_order'=>$idOrder]);

  }else{

    echo json_encode(['status'=>false,'error'=>apiLangContent('Ошибка инициализации оплаты')]);

  }

}else{

  echo json_encode(['status'=>false,'error'=>apiLangContent('Ошибка определения пользователя')]);

}

?>