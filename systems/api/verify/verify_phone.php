<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

$phone = formatPhone(clear($_POST["phone"]));
$code = (int)$_POST["code"];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

if(!$code){
   exit(json_encode(['status'=>false, 'answer'=>apiLangContent('Укажите код')]));
}

$getVerify = findOne('uni_verify_code','user_id=? and phone=?', [$idUser,$phone]);

if($getVerify){
    if($getVerify['code'] == $code){
       update('update uni_clients set clients_phone=? where clients_id=?', [$phone,$idUser]);
       update('delete from uni_verify_code where id=?', [$getVerify['id']]);
       echo json_encode(['status'=>true]);
    }else{
       echo json_encode(['status'=>false, 'answer'=>apiLangContent('Неверный код')]);
    }
}else{
   echo json_encode(['status'=>false, 'answer'=>apiLangContent('Сессия истекла')]);
}

?>