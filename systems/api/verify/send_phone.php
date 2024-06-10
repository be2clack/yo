<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

$phone = formatPhone(clear($_POST["phone"]));

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

if($settings["sms_service_method_send"] == 'call'){ 
   $confirmation_title = apiLangContent('Укажите 4 последние цифры входящего номера'); 
}else{ $confirmation_title = apiLangContent('Укажите код из смс'); }

if($phone){

  $validatePhone = apiValidatePhone($phone);

  if($validatePhone['status']){
      
     if($settings["confirmation_phone"]){

       $getVerifyPhone = findOne('uni_verify_code','user_id=? and phone=?', [$idUser,$phone]);

       if(strtotime($getVerifyPhone['create_stamp']) + 180 > time()){
          exit(json_encode(['status'=>true, 'verify'=>true, 'title'=>$confirmation_title]));
       }else{
          update('delete from uni_verify_code where user_id=?', [$idUser]);
       }

       $verify = smsVerificationCode($phone);

       if($verify){

          smart_insert('uni_verify_code',[
            'user_id' => $idUser,
            'phone' => $phone,
            'code' => $verify,
            'create_stamp' => date('Y-m-d H:i:s'),
          ]);

       }

       echo json_encode(['status'=>true, 'verify'=>true, 'title'=>$confirmation_title]);

     }else{

       update('update uni_clients set clients_phone=? where clients_id=?', [$phone,$idUser]);
       echo json_encode(['status'=>true, 'verify'=>false]);

     }

  }else{
     echo json_encode(['status'=>false, 'answer'=>$validatePhone['error']]);
  }

}else{
    echo json_encode(['status'=>false, 'answer'=>apiLangContent('Пожалуйста, укажите номер телефона')]);
}

?>