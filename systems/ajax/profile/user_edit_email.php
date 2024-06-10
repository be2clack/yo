<?php

$error = [];

if(validateEmail($_POST["user_email"]) == false){
   $error[] = $ULang->t("Пожалуйста, укажите корректный e-mail");
}else{
   if( findOne("uni_clients", "clients_email=?", [ clear($_POST["user_email"]) ]) ){
      $error[] = $ULang->t("Указанный e-mail уже используется в системе");
   }
}

if( count($error) == 0 ){

   $getUser = findOne("uni_clients", "clients_id=?", [$_SESSION["profile"]["id"]]);

   $hash = hash('sha256', $getUser["clients_id"].$config["private_hash"]);

   $getHash = findOne("uni_clients_hash_email","clients_hash_email_email=?",[clear($_POST["user_email"])]);
   if($getHash){
       update("delete from uni_clients_hash_email where clients_hash_email_id=?", [$getHash["clients_hash_email_id"]]);
   }

   insert("INSERT INTO uni_clients_hash_email(clients_hash_email_id_user,clients_hash_email_email,clients_hash_email_hash)VALUES('".$getUser["clients_id"]."','".clear($_POST["user_email"])."','".$hash."')");
   
   $data = array("{USER_EMAIL}"=>$_POST["user_email"],
                 "{ACTIVATION_LINK}"=>_link("user/".$getUser["clients_id_hash"]."/settings")."?activation_hash=$hash",
                 "{EMAIL_TO}"=>$_POST["user_email"]
                 );

   email_notification( array( "variable" => $data, "code" => "ACTIVATION_EMAIL" ) );

   echo json_encode( ["status"=>true, "answer"=>$ULang->t("Мы вам отправили письмо для подтверждения почты") ] );

}else{
   echo json_encode( ["status"=>false, "answer"=>implode("\n", $error)] );
}

?>