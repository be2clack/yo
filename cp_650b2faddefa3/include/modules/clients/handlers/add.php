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

$Subscription = new Subscription();
$Main = new Main();

$error = [];
$phone = formatPhone( $_POST["user_phone"] );

if(empty($_POST['user_name'])){ $error[] = "Пожалуйста, укажите имя"; }    

if(!validatePhone($phone)){
    $error[] = "Пожалуйста, укажите корректный номер телефона";
}else{
    $getUser = findOne("uni_clients","clients_phone = ?", array($phone));
    if($getUser){
        $error[] = "Пользователь с таким номером телефона уже зарегистрирован.";
    }    
}

if(validateEmail($_POST["user_email"]) == false){ $error[] = "Пожалуйста, укажите корректный e-mail"; }else{
    $getUser = findOne("uni_clients","clients_email = ?", array($_POST['user_email']));
    if($getUser){
        $error[] = "Пользователь с таким e-mail адресом уже зарегистрирован.";
    }
} 
if(empty($_POST['user_pass'])){ $error[] = "Пожалуйста, укажите пароль"; }

if(!$error){
    $image = $Main->uploadedImage( ["files"=>$_FILES["image"], "path"=>$config["media"]["avatar"], "name"=>uniqid()] );
    if($image["error"]){
        $error = array_merge($error,$image["error"]);
    }    
}


if (!$error) {
    
    $password =  password_hash($_POST["user_pass"].$config["private_hash"], PASSWORD_DEFAULT);
    $clients_id_hash = md5( $_POST['user_email'] . $phone );

    $notifications = '{"messages":"1","answer_comments":"1","services":"1"}';

    $insert = insert("INSERT INTO uni_clients(clients_name,clients_surname,clients_pass,clients_email,clients_phone,clients_avatar,clients_status,clients_city_id,clients_datetime_add,clients_datetime_view,clients_id_hash,clients_notifications,clients_ref_id,clients_verification_code)
                    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)", array($_POST['user_name'],$_POST['user_surname'],$password,$_POST['user_email'],$phone, $image["name"],1,intval($_POST["city_id"]), date("Y-m-d H:i:s"), date("Y-m-d H:i:s"),$clients_id_hash,$notifications,genRefId(),genVerificationCode()));    
    
    echo json_encode(array("status" => true, "id" => $insert ));

    $Subscription->add(array("email" => $_POST['user_email'], "name" => $_POST['user_name'], "type"=> "user", "user_id"=>$insert, "status" => 1));

    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!"; 

} else {
    echo json_encode(array("status" => false ));
    $_SESSION["CheckMessage"]["error"] = implode("<br/>",$error);
}

}
?>