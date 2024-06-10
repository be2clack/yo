<?php

$login = clear($_POST['login']);
$name = clear($_POST['name']);
$pass = clear($_POST['pass']);

$error = [];
$verify = mt_rand(1000,9999);

if($settings["registration_method"] == 1){

  $login_phone = formatPhone($login);
  $validatePhone = apiValidatePhone($login_phone);

  if(!$validatePhone['status']){
      $error[] = $validatePhone['error'];
  }

}elseif($settings["registration_method"] == 2){

  if(!$login){
      $error[] = apiLangContent("Пожалуйста, укажите телефон или почту.");
  }else{
      if( strpos($login, "@") !== false ){

        $login_email = $login;

        if(validateEmail($login) == false){
            $error[] = apiLangContent("Пожалуйста, укажите корректный e-mail адрес.");
        }

      }else{

        $login_phone = formatPhone($login);
        $validatePhone = apiValidatePhone($login);

        if(!$validatePhone['status']){
            $error[] = $validatePhone['error'];
        }

      }         
  }

}elseif($settings["registration_method"] == 3){

  $login_email = $login;

  if(validateEmail($login) == false){
      $error[] = apiLangContent("Пожалуйста, укажите корректный e-mail адрес.");
  }

}

if(mb_strlen($pass, "UTF-8") < 6 || mb_strlen($pass, "UTF-8") > 25){
  $error[] = apiLangContent("Пожалуйста, укажите пароль от 6-ти до 25 символов.");
}

if(!$name){
    $error[] = apiLangContent("Пожалуйста, укажите Ваше имя");
}

if(!count($error)){

     if($login_email){
        $getUser = findOne("uni_clients","clients_email = ?", array($login_email));
     }elseif($login_phone){
        $getUser = findOne("uni_clients","clients_phone = ?", array($login_phone));
     }

     if(!$getUser){

        if($login_email){

             $data = array("{USER_EMAIL}"=>$login_email,
                           "{CODE}"=>$verify,
                           "{EMAIL_TO}"=>$login_email
                           );

             email_notification( array( "variable" => $data, "code" => "SEND_EMAIL_CODE" ) );

             smart_insert('uni_verify_code', [
                'email'=>$login,
                'create_stamp'=>date("Y-m-d H:i:s"),
                'code'=>$verify,
             ]);

             exit(json_encode(array("status"=>true, 'confirmation'=>true, 'confirmation_title' => apiLangContent('Укажите код из email сообщения'))));
             
        }elseif($login_phone){

             if($settings["confirmation_phone"]){
                 $verify = smsVerificationCode($login_phone);

                 if($settings["sms_service_method_send"] == 'call'){ 
                   $confirmation_title = apiLangContent('Укажите 4 последние цифры входящего номера'); 
                 }else{ $confirmation_title = apiLangContent('Укажите код из смс'); }

                 smart_insert('uni_verify_code', [
                     'phone'=>$login,
                     'create_stamp'=>date("Y-m-d H:i:s"),
                     'code'=>$verify,
                 ]);

                 exit(json_encode(array("status"=>true, "confirmation"=>true, "confirmation_title" => $confirmation_title)));
            }else{
                 exit(json_encode(array("status"=>true, "confirmation"=>false)));
            }

        }

     }else{
        echo json_encode(['status'=>false,'errors'=>apiLangContent('Логин уже используется, авторизуйтесь!')]);
     }

}else{
    echo json_encode(["status"=>false,"errors"=>implode("\n", $error)]);
}

?>