<?php

$error = [];

$getUser = findOne("uni_clients", "clients_id=?", [$_SESSION["profile"]["id"]]);

if( !$_POST["user_current_pass"] ){ $error[] = $ULang->t("Пожалуйста, укажите текущий пароль"); }else{

  if (!password_verify($_POST["user_current_pass"].$config["private_hash"], $getUser["clients_pass"])) {
     $error[] = $ULang->t("Неверный текущий пароль");
  }

}

if( mb_strlen($_POST["user_new_pass"], "UTF-8") < 6 || mb_strlen($_POST["user_new_pass"], "UTF-8") > 25 ){
  $error[] = $ULang->t("Пожалуйста, укажите новый пароль от 6-ти до 25 символов.");
}

$password_hash =  password_hash($_POST["user_new_pass"].$config["private_hash"], PASSWORD_DEFAULT);

if(count($error) == 0){

  update("update uni_clients set clients_pass=? where clients_id=?", [ $password_hash, $_SESSION["profile"]["id"] ]);

  echo json_encode( ["status"=>true] );

}else{
  echo json_encode( ["status"=>false, "answer"=>implode("\n", $error)] );
}

?>