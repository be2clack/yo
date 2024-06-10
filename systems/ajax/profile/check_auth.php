<?php

$error = array();

if(!isset($_SESSION["auth_captcha"])){
  $_SESSION["auth_captcha"] = [];
}

$user_login = clear($_POST["user_login"]);
$user_pass = $_POST["user_pass"];

$save_auth = (int)$_POST["save_auth"];

if($settings["authorization_method"] == 1){

  $user_phone = formatPhone($_POST["user_login"]);
  $validatePhone = validatePhone($user_phone);

  if(!$validatePhone['status']){
      $error["user_login"] = $validatePhone['error'];
  }

}elseif($settings["authorization_method"] == 2){

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

}elseif($settings["authorization_method"] == 3){

  if(validateEmail( $user_login ) == false){
      $error["user_login"] = $ULang->t("Пожалуйста, укажите корректный e-mail адрес.");
  }else{
      $user_email = $user_login; 
  }

}

if( mb_strlen($user_pass, "UTF-8") < 6 || mb_strlen($user_pass, "UTF-8") > 25 ){
  $error["user_pass"] = $ULang->t("Пожалуйста, укажите пароль от 6-ти до 25 символов.");
}

if($_SESSION["auth_captcha"]["status"]){
  if(!$_POST["captcha"]){
    $error["captcha"] = $ULang->t("Пожалуйста, укажите код с картинки");
  }elseif($_POST["captcha"] != $_SESSION["captcha"]["auth"]){
    $error["captcha"] = $ULang->t("Неверный код с картинки");
  }
}

if($_SESSION["auth_captcha"]["count"] >= 10){ $_SESSION["auth_captcha"]["status"] = true; }else{ $_SESSION["auth_captcha"]["status"] = false; }

if(!$error){
 
 if($user_email){
    $getUser = findOne("uni_clients","clients_email = ?", array( $user_email ));
 }elseif($user_phone){
    $getUser = findOne("uni_clients","clients_phone = ?", array( $user_phone ));
 }
 
 if($getUser){

       if($getUser->clients_status == 2 || $getUser->clients_status == 3){
             
         $_SESSION["auth_captcha"]["count"]++;
         echo json_encode( array( "status" => false, "status_user" => $getUser->clients_status, "captcha"=>$_SESSION["auth_captcha"]["status"] ) );

       }else{
       
         if (password_verify($user_pass.$config["private_hash"], $getUser->clients_pass)) {  
            
              $_SESSION['profile']['id'] = $getUser->clients_id;

              if($save_auth){
                 $token = hash('sha256', $_SESSION['profile']['id'].uniqid());
                 setcookie("tokenAuth", $token, time() + 2592000);
                 update('update uni_clients set clients_cookie_token=? where clients_id=?',[$token,$getUser->clients_id]);
              }

              if(isset($_SESSION['point-auth-location'])){
                echo json_encode( array( "status"=>true, "location" => $_SESSION['point-auth-location'] ) );
              }else{
                echo json_encode( array( "status"=>true, "location" => _link( "user/".$getUser["clients_id_hash"] ) ) );
              }
              
         }else{

              $_SESSION["auth_captcha"]["count"]++;
              echo json_encode( array( "status" => false, "answer" => array("user_pass"=>$ULang->t("Неверный логин и(или) пароль!")), "captcha"=>$_SESSION["auth_captcha"]["status"] ) );

         }

       }

 }else{
     $_SESSION["auth_captcha"]["count"]++;
     echo json_encode( array( "status" => false, "answer" => array("user_pass"=>$ULang->t("Неверный логин и(или) пароль!")), "captcha"=>$_SESSION["auth_captcha"]["status"] ) );    
 }

}else{
 $_SESSION["auth_captcha"]["count"]++;
 echo json_encode(array("status" => false, "answer" => $error, "captcha"=>$_SESSION["auth_captcha"]["status"]));
}

?>