<?php

if($settings["block-time-auth-admin"] <= time()){

    $error = array();

    $email = clear($_POST["email"]);
         
    if(validateEmail($email) == false){
        $error[] = "Пожалуйста, укажите корректный E-mail!";
    }

    if(empty($_POST["pass"])){$error[] = "Пожалуйста, укажите пароль!";}         
         
    if (count($error) == 0) {
       
       $get = findOne("uni_admin","email = ?", array($email));
         
         if (password_verify($_POST["pass"].$config["private_hash"], $get->pass)) {	
            
            $_SESSION['cp_auth'][ $config["private_hash"] ] = getOne("select fio,image,role,id from uni_admin where id=?", array( $get->id ));

            $Admin->setPrivileges($get->privileges);
            
            update("update uni_settings set value=? where name=?", array(0,"count-password-attempts"));
            update("update uni_settings set value=? where name=?", array(0,"block-time-auth-admin"));

            echo json_encode( ["status"=>true, "location" => $_SESSION["entry_point"] ? $_SESSION["entry_point"] : $config["urlPath"] . "/" . $config["folder_admin"] . '?route=index' ] );
            
         }else{
             
             if($settings["count-password-attempts"] >= $settings["password-attempts"]){

               update("update uni_settings set value=? where name=?", array( time() + 900 ,"block-time-auth-admin"));
               update("update uni_settings set value=? where name=?", array(0,"count-password-attempts"));

             }else{
                update("update uni_settings set value=value+1 where name=?", array("count-password-attempts"));
             }

            echo json_encode( ["status"=>false, "answer"=>"Не верный логин и(или) пароль!"] );
         }

    } else {
        echo json_encode( ["status"=>false, "answer"=>implode("<br/>",$error)] );
    } 
  
  }else{
     echo json_encode( ["status"=>false, "answer"=>'Внимание! Авторизация заблокирована до '.date("H:i:s", $settings["block-time-auth-admin"]) ] );
  }

?>