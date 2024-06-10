<?php

$user_login = clear($_POST['login']);

$errors = [];

if(!$user_login){
    $errors[] = apiLangContent("Пожалуйста, укажите телефон или электронную почту.");
}else{
    if( strpos($user_login, "@") !== false ){

      if(validateEmail($user_login) == false){
          $errors[] = apiLangContent("Пожалуйста, укажите корректный e-mail адрес.");
      }else{
          $login_email = $user_login; 
      }

    }else{

      $login_phone = formatPhone($user_login);
      $validatePhone = apiValidatePhone($login_phone);

      if(!$validatePhone['status']){
          $errors[] = $validatePhone['error'];
      }

    }         
}

if(!count($errors)){

     if($login_email){
        $getUser = findOne("uni_clients","clients_email = ?", array($login_email));
     }elseif($login_phone){
        $getUser = findOne("uni_clients","clients_phone = ?", array($login_phone));
     }

     if($getUser){

           if($getUser->clients_status == 2 || $getUser->clients_status == 3){
                 
             echo json_encode(["status"=>false, "errors" => apiLangContent("Ваш аккаунт заблокирован!")]);

           }else{
           
               $pass =  generatePass(10);
               $password_hash =  password_hash($pass.$config["private_hash"], PASSWORD_DEFAULT);

               update("UPDATE uni_clients SET clients_pass=? WHERE clients_id=?", [$password_hash,$getUser->clients_id]);

               if($login_email){

                 $data = array("{USER_NAME}"=>$getUser->clients_name,
                               "{USER_EMAIL}"=>$getUser->clients_email,
                               "{USER_PASS}"=>$pass,
                               "{UNSUBSCRIBE}"=>"",
                               "{EMAIL_TO}"=>$getUser->clients_email
                               );

                 email_notification( array( "variable" => $data, "code" => "AUTH_FORGOT" ) );

                 echo json_encode(array("status"=>true, "answer"=>apiLangContent("Пароль успешно выслан на Ваш e-mail.")));

                 unset($_SESSION["captcha"]["auth"]);

               }elseif($login_phone){

                 sms($login_phone,$pass, 'sms');

                 echo json_encode(array("status"=>true, "answer"=>apiLangContent("Пароль успешно выслан на Ваш номер телефона.")));

               }

           }

     }else{

         echo json_encode(["status"=>false, "errors" => apiLangContent("Пользователь не найден!")]);

     }

}else{

    echo json_encode(['status'=>false,'errors'=>implode("\n", $errors)]);

}

?>