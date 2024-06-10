<?php

$login = clear($_POST['login']);
$name = clear($_POST['name']);
$pass = clear($_POST['pass']);
$ip = clear($_POST['ip']);
$verify_code = clear($_POST['verify_code']);

$error = [];

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

        if($login_phone){
            if($settings["confirmation_phone"]){
                if(!$verify_code){
                    exit(json_encode(array("status"=>false, "errors"=>apiLangContent('Укажите код подтверждения'))));
                }else{
                    $getVerifyCode = findOne('uni_verify_code', 'phone=? and code=?', [$login, $verify_code]);
                    if($getVerifyCode){
                        update('delete from uni_verify_code where phone=?', [$login]);
                    }else{
                        exit(json_encode(array("status"=>false, "errors"=>apiLangContent('Неверный код подтверждения'))));
                    }                    
                }
            }
        }elseif($login_email){
            if(!$verify_code){
                exit(json_encode(array("status"=>false, "errors"=>apiLangContent('Укажите код подтверждения'))));
            }else{
                $getVerifyCode = findOne('uni_verify_code', 'email=? and code=?', [$login, $verify_code]);
                if($getVerifyCode){
                    update('delete from uni_verify_code where email=?', [$login]);
                }else{
                    exit(json_encode(array("status"=>false, "errors"=>apiLangContent('Неверный код подтверждения'))));
                }                    
            }
        }


        $password_hash =  password_hash($pass.$config["private_hash"], PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(32));
        $clients_id_hash = md5($login);
         
        $insert_id = smart_insert('uni_clients', [
            'clients_pass'=>$password_hash,
            'clients_email'=>$login_email,
            'clients_phone'=>$login_phone,
            'clients_name'=>$name,
            'clients_ip'=>$ip,
            'clients_id_hash'=>$clients_id_hash,
            'clients_status'=>1,
            'clients_datetime_add'=>date("Y-m-d H:i:s"),
            'clients_notifications'=>'{"messages":"1","answer_comments":"1","services":"1","answer_ad":"1"}',
            'clients_ref_id'=>genRefId(),
            'clients_reg_mobile'=>1,
            'clients_verification_code'=>genVerificationCode(),
        ]);

        if($ip){
            $getReferrer = findOne('uni_clients_ref_transitions', 'ip=?', [$ip]);
            if($getReferrer){
                smart_insert('uni_clients_ref', [
                    'timestamp' => date("Y-m-d H:i:s"),
                    'id_user_referral' => $insert_id,
                    'id_user_referrer' => $getReferrer['id_user_referrer'],
                ]);
            }
        }

        if($settings["bonus_program"]["register"]["status"] && $settings["bonus_program"]["register"]["price"]){
             $Profile->actionBalance(array("id_user"=>$insert_id,"summa"=>$settings["bonus_program"]["register"]["price"],"title"=>$settings["bonus_program"]["register"]["name"],"id_order"=>generateOrderId(),"email" => $login_email,"name" => $name, "note" => $settings["bonus_program"]["register"]["name"]),"+");             
        }

        insert("INSERT INTO uni_clients_auth(clients_auth_token,clients_auth_expiration,clients_auth_user_id)VALUES(?,?,?)", [$token,null,$insert_id]);

        $Admin->notifications("user", array("user_name" => $name, "user_email" => $login_email, "user_phone" => $login_phone));

        $Subscription->add(array("email"=>$login_email,"user_id"=>$insert_id,"name"=>$name,"status" => 1));

        echo json_encode(["status"=>true, "id"=>$insert_id, 'token'=>$token]);

     }else{
        echo json_encode(['status'=>false,'errors'=>apiLangContent('Логин уже используется, авторизуйтесь!')]);
     }

}else{
    echo json_encode(['status'=>false,'errors'=>implode("\n", $error)]);
}

?>