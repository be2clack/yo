<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_clients']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$id = (int)$_POST['id'];
$error = [];
$additional_phone = array();

$phone = formatPhone( $_POST["user_phone"] );

if($_POST["user_type_person"] == "user") $_POST["user_name_company"] = "";

if(empty($_POST['user_name'])){$error[] = "Укажите имя пользователя!";} 

if($phone){
  if(!validatePhone($phone)){
      $error[] = "Пожалуйста, укажите корректный номер телефона";
  }
}

if($_POST["user_email"]){
  if(validateEmail($_POST["user_email"]) == false){$error[] = "Пожалуйста, укажите корректный e-mail адрес!";}else{
      $getUser = findOne("uni_clients","clients_email = ? AND clients_id != ?", array($_POST['user_email'],$id));
      if($getUser){
          $error[] = "Пользователь с таким e-mail уже зарегестрирован";
      }
  } 
}

if(!empty($_POST['user_pass'])){
    $password =  password_hash($_POST["user_pass"].$config["private_hash"], PASSWORD_DEFAULT);
    $pass = ",clients_pass='$password'";
}

if($_POST["additional_phone"]){
 foreach (array_slice($_POST["additional_phone"], 0,3) as $key => $value) {
   if($value) $additional_phone[] = clear($value);
 }
}

if($_POST["user_type_person"] == "company"){ if(empty($_POST["user_name_company"])){ $error[] = "Пожалуйста, укажите название компании!"; } }

if (!$error) {
    
    update("UPDATE uni_clients SET clients_name='".clear($_POST["user_name"])."',clients_surname='".clear($_POST["user_surname"])."',clients_email='".clear($_POST["user_email"])."',clients_phone='".$phone."',clients_type_person='".clear($_POST["user_type_person"])."',clients_name_company='".clear($_POST["user_name_company"])."',clients_additional_phones='".implode(",",$additional_phone)."' $pass WHERE clients_id='$id'");

    echo true;
    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!"; 

} else {
    $_SESSION["CheckMessage"]["warning"] = implode("<br/>",$error);
}

}
?>