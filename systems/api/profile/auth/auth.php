<?php

$login = clear($_POST['login']);
$pass = clear($_POST['pass']);

$errors = [];

if($settings["authorization_method"] == 1){

  $login_phone = formatPhone($login);
  $validatePhone = apiValidatePhone($login_phone);

  if(!$validatePhone['status']){
      $errors[] = $validatePhone['error'];
  }

}elseif($settings["authorization_method"] == 2){

  if(!$login){
      $errors[] = apiLangContent("Пожалуйста, укажите телефон или почту.");
  }else{
      if( strpos($login, "@") !== false ){

        $login_email = $login;

        if(validateEmail($login) == false){
            $errors[] = apiLangContent("Пожалуйста, укажите корректный e-mail адрес.");
        }

      }else{

        $login_phone = formatPhone($login);
        $validatePhone = apiValidatePhone($login);

        if(!$validatePhone['status']){
            $errors[] = $validatePhone['error'];
        }

      }         
  }

}elseif($settings["authorization_method"] == 3){

  $login_email = $login;

  if(validateEmail($login) == false){
      $errors[] = apiLangContent("Пожалуйста, укажите корректный e-mail адрес.");
  }

}

if(mb_strlen($pass, "UTF-8") < 6 || mb_strlen($pass, "UTF-8") > 25){
  $errors[] = apiLangContent("Пожалуйста, укажите пароль от 6-ти до 25 символов.");
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
           
             if (password_verify($pass.$config["private_hash"], $getUser->clients_pass)) {  

                  $token = bin2hex(random_bytes(32));

                  update('delete from uni_clients_auth where clients_auth_user_id=?', [$getUser->clients_id]);
                  insert("INSERT INTO uni_clients_auth(clients_auth_token,clients_auth_expiration,clients_auth_user_id)VALUES(?,?,?)", [$token,null,$getUser->clients_id]);

                  update("UPDATE uni_clients SET clients_datetime_view=NOW() WHERE clients_id=?", array($getUser->clients_id));
                
                  echo json_encode(["status"=>true, "id"=>$getUser['clients_id'], 'token'=>$token]);
                
             }else{

                  echo json_encode(["status"=>false, "errors" => apiLangContent("Неверный логин и(или) пароль!")]);

             }

           }

     }else{

         echo json_encode(["status"=>false, "errors" => apiLangContent("Неверный логин и(или) пароль!")]);

     }

}else{

    echo json_encode(['status'=>false,'errors'=>implode("\n", $errors)]);

}

?>