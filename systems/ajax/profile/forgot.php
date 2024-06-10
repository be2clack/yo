<?php

$error = array();

if(!isset($_SESSION["auth_captcha"])){
  $_SESSION["auth_captcha"] = [];
}

$user_login = clear($_POST["login"]);
   
if(!$user_login){
  $error['user_recovery_login'] = $ULang->t("Пожалуйста, укажите телефон или электронную почту.");
}else{
  if( strpos($user_login, "@") !== false ){

    if(validateEmail($user_login) == false){
        $error['user_recovery_login'] = $ULang->t("Пожалуйста, укажите корректный e-mail адрес.");
    }else{
        $user_email = $user_login; 
    }

  }else{

    $user_phone = formatPhone($user_login);
    $validatePhone = validatePhone($user_phone);

    if(!$validatePhone['status']){
        $error['user_recovery_login'] = $validatePhone['error'];
    }

  }         
}

if(!$_POST["captcha"]){
  $error['captcha'] = $ULang->t("Пожалуйста, укажите код с картинки");
}elseif($_POST["captcha"] != $_SESSION["captcha"]["auth"]){
  $error['captcha'] = $ULang->t("Неверный код с картинки");
}

if (!$error) {
 
 if($user_email){
   $getUser = findOne("uni_clients","clients_email = ?", array($user_email));
 }elseif($user_phone){
   $getUser = findOne("uni_clients","clients_phone = ?", array($user_phone));
 }
   
   if ($getUser) { 

       $pass =  generatePass(10);
       $password_hash =  password_hash($pass.$config["private_hash"], PASSWORD_DEFAULT);

       update("UPDATE uni_clients SET clients_pass=? WHERE clients_id=?", [$password_hash,$getUser->clients_id]);

       if($user_email){

         $data = array("{USER_NAME}"=>$getUser->clients_name,
                       "{USER_EMAIL}"=>$getUser->clients_email,
                       "{USER_PASS}"=>$pass,
                       "{UNSUBSCRIBE}"=>"",
                       "{EMAIL_TO}"=>$getUser->clients_email
                       );

         email_notification( array( "variable" => $data, "code" => "AUTH_FORGOT" ) );

         echo json_encode(array("status"=>true, "answer"=>$ULang->t("Пароль успешно выслан на Ваш e-mail.")));

         unset($_SESSION["captcha"]["auth"]);

       }elseif($user_phone){

         sms($user_phone,$pass, 'sms');

         echo json_encode(array("status"=>true, "answer"=>$ULang->t("Пароль успешно выслан на Ваш номер телефона.") ));

       }


   }else{
       $_SESSION["auth_captcha"]["count"]++;
       echo json_encode(array("status"=>false, "answer"=> ['user_recovery_login'=>$ULang->t("Пользователь не найден!")], "captcha" => $_SESSION["auth_captcha"]["status"]));
   }

} else {
  $_SESSION["auth_captcha"]["count"]++;
  echo json_encode(array("status"=>false, "answer"=>$error, "captcha" => $_SESSION["auth_captcha"]["status"]));
}

?>