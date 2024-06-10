<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

$email = clear($_POST["email"]);
$code = (int)$_POST["code"];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

if(!$code){
   exit(json_encode(['status'=>false, 'answer'=>apiLangContent('Укажите код')]));
}

$getVerify = findOne('uni_verify_code','user_id=? and email=?', [$idUser,$email]);

if($getVerify){
    if($getVerify['code'] == $code){

       $getUser = findOne("uni_clients", "clients_id=?", [$idUser]);

       if(!$getUser["clients_email"]){

           if($settings["bonus_program"]["email"]["status"] && $settings["bonus_program"]["email"]["price"]){
               $Profile->actionBalance(array("id_user"=>$idUser,"summa"=>$settings["bonus_program"]["email"]["price"],"title"=>$settings["bonus_program"]["email"]["name"],"id_order"=>generateOrderId(),"email" => $idUser,"name" => $getUser->clients_name, "note" => $settings["bonus_program"]["email"]["name"]),"+");            
           }

       }

       update('update uni_clients set clients_email=? where clients_id=?', [$email,$idUser]);
       update('delete from uni_verify_code where id=?', [$getVerify['id']]);
       
       echo json_encode(['status'=>true]);

    }else{
       echo json_encode(['status'=>false, 'answer'=>apiLangContent('Неверный код')]);
    }
}else{
   echo json_encode(['status'=>false, 'answer'=>apiLangContent('Сессия истекла')]);
}

?>