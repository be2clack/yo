<?php

$error = [];
$captcha_reload = false;

if(!isset($_SESSION["auth_captcha"])){
  $_SESSION["auth_captcha"] = [];
}

$user_login = clear( $_POST["user_login"] );
$code_login = (int)$_POST["user_code_login"];

if($_SESSION["auth_captcha"]["status"]){
  if(!$_POST["captcha"]){
    $error['captcha'] = $ULang->t("Пожалуйста, укажите код с картинки");
  }elseif($_POST["captcha"] != $_SESSION["captcha"]["auth"]){
    $error['captcha'] = $ULang->t("Неверный код с картинки");
  }
}

if($_SESSION["auth_captcha"]["count"] >= 10){ $_SESSION["auth_captcha"]["status"] = true; }else{ $_SESSION["auth_captcha"]["status"] = false; }

if(!$_SESSION["verify_login"][$user_login]["code"] || $_SESSION["verify_login"][$user_login]["code"] != $code_login){
  $error['user_code_login'] = $ULang->t("Неверный код");
  $captcha_reload = true;
}

if(!$error){

 unset($_SESSION["auth_captcha"]);
 echo json_encode( array( "status"=>true ) );

}else{

 $_SESSION["auth_captcha"]["count"]++;
 echo json_encode( array( "status"=>false, "answer" => $error, "captcha" => $_SESSION["auth_captcha"]["status"], "captcha_reload" => $captcha_reload ) );

}

?>