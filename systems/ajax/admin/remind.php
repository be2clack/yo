<?php

if($settings["block-time-auth-admin"] <= time()){

    $error = array();

    $email = clear($_POST["email"]);
         
    if(validateEmail($email) == false){
        $error[] = "Пожалуйста, укажите корректный E-mail!";
    }
       
    if (count($error) == 0) {
       
       $get = findOne("uni_admin","email = ? Limit ?", array($email,1));
         
         if ($get) {	

            $new_pass =  generatePass(10);
            $password =  password_hash($new_pass.$config["private_hash"], PASSWORD_DEFAULT);

            update("UPDATE uni_admin SET pass=? WHERE id=?", array($password,$get->id));

             $param = array("{USER_NAME}"=>$get->fio,
                            "{USER_EMAIL}"=>$get->email,
                           "{USER_PASS}"=>$new_pass,
                           "{EMAIL_TO}"=>$get->email
                           );

             email_notification( array( "variable" => $param, "code" => "ADMIN_REMIND_PASS" ) );

             echo true;

         }else{

             if($settings["count-password-attempts"] >= $settings["password-attempts"]){
               update("update uni_settings set value=? where name=?", array( time() + 900 ,"block-time-auth-admin"));
               update("update uni_settings set value=? where name=?", array(0,"count-password-attempts"));
             }else{
                update("update uni_settings set value=value+1 where name=?", array("count-password-attempts"));
             }

             echo "Указанный E-mail не найден!";
         }

    } else {
        echo implode("<br/>",$error);
    }

  }else{
       echo 'Внимание! Восстановление пароля заблокировано до '.date("H:i:s", $settings["block-time-auth-admin"] );
  }

?>