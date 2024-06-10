<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

$errors = [];

$current_pass = clear($_POST["current_pass"]);
$new_pass = clear($_POST["new_pass"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getUser = $Profile->oneUser("where clients_id=?", [$idUser]);

if(!$getUser){
	http_response_code(500); exit('User not found'); 
}

if(!$current_pass){ $errors[] = apiLangContent("Пожалуйста, укажите текущий пароль"); }else{

  if (!password_verify($current_pass.$config["private_hash"], $getUser["clients_pass"])) {
     $errors[] = apiLangContent("Неверный текущий пароль");
  }

}

if( mb_strlen($new_pass, "UTF-8") < 6 || mb_strlen($new_pass, "UTF-8") > 25 ){
  $errors[] = apiLangContent("Пожалуйста, укажите новый пароль от 6-ти до 25 символов");
}

$password_hash =  password_hash($new_pass.$config["private_hash"], PASSWORD_DEFAULT);

if(!$errors){

  update("update uni_clients set clients_pass=? where clients_id=?", [$password_hash, $idUser]);

  echo json_encode( ["status"=>true] );

}else{
  echo json_encode( ["status"=>false, "errors"=>implode("\n", $errors)] );
}

?>