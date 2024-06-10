<?php

$error = [];

$user_login = clear( $_POST["user_login"] );
$user_name = clear( $_POST["user_name"] );
$user_code_login = (int)$_POST["user_code_login"];
$user_pass = clear( $_POST["user_pass"] );

if($settings["registration_method"] == 1){

$user_phone = formatPhone($_POST["user_login"]);
$validatePhone = validatePhone($user_phone);

if(!$validatePhone['status']){
  exit(json_encode(array("status"=>false, "reload" => true)));
}

}elseif($settings["registration_method"] == 2){

  if(!$user_login){
      exit(json_encode(array("status"=>false, "reload" => true)));
  }else{
      if( strpos($user_login, "@") !== false ){

        if(validateEmail( $user_login ) == false){
            exit(json_encode(array("status"=>false, "reload" => true)));
        }else{
            $user_email = $user_login; 
        }

      }else{

        $user_phone = formatPhone($_POST["user_login"]);
        $validatePhone = validatePhone($user_phone);

        if(!$validatePhone['status']){
            exit(json_encode(array("status"=>false, "reload" => true)));
        }

      }         
  }

}elseif($settings["registration_method"] == 3){

  if(validateEmail( $user_login ) == false){
      exit(json_encode(array("status"=>false, "reload" => true)));
  }else{
      $user_email = $user_login; 
  }

}

if(!$_SESSION["verify_login"][$user_login]["code"] || $_SESSION["verify_login"][$user_login]["code"] != $user_code_login){
  if($user_email){
     exit(json_encode(array("status"=>false, "reload" => true)));
  }else{
     if($settings["confirmation_phone"]){
        exit(json_encode(array("status"=>false, "reload" => true))); 
     }             
  }
}

if( mb_strlen($user_pass, "UTF-8") < 6 || mb_strlen($user_pass, "UTF-8") > 25 ){
 $error['user_pass'] = $ULang->t("Пожалуйста, укажите пароль от 6-ти до 25 символов.");
}

if(!$user_name){
 $error['user_name'] = $ULang->t("Пожалуйста, укажите Ваше имя");
}

if( !$error ){

 $result = $Profile->auth_reg(array("method"=>$settings["registration_method"],"email"=>$user_email,"phone"=>$user_phone,"name"=>$user_name, "activation" => 1, "pass" => $user_pass));

 echo json_encode( array( "status"=>$result["status"],"answer" => $result["answer"], "reg" => 1, "location" => _link( "user/".$result["data"]["clients_id_hash"] ) ) );

 unset($_SESSION["verify_login"]);

}else{

 echo json_encode(array("status"=>false, "answer" => $error));

}

?>