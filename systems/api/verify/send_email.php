<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

$email = clear($_POST["email"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

if(validateEmail($email)){

   $verify = mt_rand(1000,9999);
   
   $getVerify = findOne('uni_verify_code','user_id=? and email=?', [$idUser,$email]);

   if(strtotime($getVerify['create_stamp']) + 180 > time()){
      exit(json_encode(['status'=>true, 'verify'=>true, 'title'=>apiLangContent('Укажите код из email сообщения')]));
   }else{
      update('delete from uni_verify_code where user_id=?', [$idUser]);
   }

   $data = array("{USER_EMAIL}"=>$email,"{CODE}"=>$verify,"{EMAIL_TO}"=>$email);

   email_notification( array( "variable" => $data, "code" => "SEND_EMAIL_CODE" ) );

   smart_insert('uni_verify_code', [
      'user_id'=>$idUser,
      'email'=>$email,
      'create_stamp'=>date("Y-m-d H:i:s"),
      'code'=>$verify,
   ]);

   echo json_encode(["status"=>true, 'title' => apiLangContent('Укажите код из email сообщения')]);

}else{
   echo json_encode(['status'=>false, 'answer'=>apiLangContent('Пожалуйста, укажите корректный e-mail адрес.')]);
}

?>