<?php

$error = [];

$user_login = clear($_POST["user_login"]);

if($settings["registration_method"] == 1){

  $user_phone = formatPhone($_POST["user_login"]);
  $validatePhone = validatePhone($user_phone);

  if(!$validatePhone['status']){
      $error["user_login"] = $validatePhone['error'];
  }

}elseif($settings["registration_method"] == 2){

  if(!$user_login){
      $error["user_login"] = $ULang->t("Пожалуйста, укажите телефон или электронную почту.");
  }else{
      if( strpos($user_login, "@") !== false ){

        if(validateEmail( $user_login ) == false){
            $error["user_login"] = $ULang->t("Пожалуйста, укажите корректный e-mail адрес.");
        }else{
            $user_email = $user_login; 
        }

      }else{

        $user_phone = formatPhone($_POST["user_login"]);
        $validatePhone = validatePhone($user_phone);

        if(!$validatePhone['status']){
            $error["user_login"] = $validatePhone['error'];
        }

      }         
  }

}elseif($settings["registration_method"] == 3){

  if(validateEmail( $user_login ) == false){
      $error["user_login"] = $ULang->t("Пожалуйста, укажите корректный e-mail адрес.");
  }else{
      $user_email = $user_login; 
  }

}

if(!$_POST["captcha"]){
$error["captcha"] = $ULang->t("Пожалуйста, укажите код с картинки");
}elseif($_POST["captcha"] != $_SESSION["captcha"]["auth"]){
$error["captcha"] = $ULang->t("Неверный код с картинки");
}

if(!$error){

 if($user_email){
    $getUser = findOne("uni_clients","clients_email = ?", array( $user_email ));
 }elseif($user_phone){
    $getUser = findOne("uni_clients","clients_phone = ?", array( $user_phone ));
 }
 
 if($getUser){
       echo json_encode( array( "status" => false, "answer" => array("user_login"=>$ULang->t("Указанный логин уже используется на сайте!"))) );
 }else{
        
       if( $_SESSION["verify_login"]["time"] ){
           if( $_SESSION["verify_login"]["time"] < time() ){
               unset($_SESSION["verify_login"]["count"]);
               unset($_SESSION["verify_login"]["time"]);
           }
       }

       if( intval($_SESSION["verify_login"]["count"]) < 5 ){
           
           if($user_email){

             $_SESSION["verify_login"][$user_login]["code"] = mt_rand(1000,9999);

             $data = array("{USER_EMAIL}"=>$user_email,
                           "{CODE}"=>$_SESSION["verify_login"][$user_login]["code"],
                           "{EMAIL_TO}"=>$user_email
                           );

             email_notification( array( "variable" => $data, "code" => "SEND_EMAIL_CODE" ) );
             echo json_encode(array("status"=>true, 'confirmation'=>true, 'confirmation_title' => $ULang->t("Укажите код из email сообщения")));
             
             $_SESSION["verify_login"]["count"]++;

           }elseif($user_phone){

             if($settings["confirmation_phone"]){
                $_SESSION["verify_login"][$user_login]["code"] = smsVerificationCode($user_phone);

                if($settings["sms_service_method_send"] == 'call'){ 
                  $confirmation_title = $ULang->t("Укажите 4 последние цифры номера"); 
                }else{ $confirmation_title = $ULang->t("Укажите код из смс"); }

                echo json_encode(array("status"=>true, 'confirmation'=>true, 'confirmation_title' => $confirmation_title));
                $_SESSION["verify_login"]["count"]++;                        
             }else{
                echo json_encode(array("status"=>true, 'confirmation'=>false));
             }

           }
           
       }else{
           
           if(!$_SESSION["verify_login"]["time"]) $_SESSION["verify_login"]["time"] = time() + 300;

           echo json_encode( array( "status"=>false, "answer" => array( "user_login" => $ULang->t("Достигнут лимит отправки сообщений. Попробуйте чуть позже") ) ) );

       }

 }

}else{
 echo json_encode(array("status" => false, "answer" => $error));
}

?>