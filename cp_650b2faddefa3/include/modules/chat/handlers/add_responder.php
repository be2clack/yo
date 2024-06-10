<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_chat']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

$Profile = new Profile();

$errors = [];

$name_responder = clear($_POST['name_responder']);
$text_responder = $_POST['text_responder'];

$countUsers = (int)getOne('select count(*) as total from uni_clients where clients_status=?', [1])['total'];

if(!$name_responder) $errors[] = 'Пожалуйста, укажите название рассылки';
if(!$text_responder) $errors[] = 'Пожалуйста, укажите текст сообщения';
if(!$countUsers) $errors[] = 'Нет активных пользователей для рассылки';

if(count($errors) == 0){

  insert("INSERT INTO uni_chat_responders(chat_responders_name,chat_responders_text,chat_responders_date,chat_responders_count_users)VALUES(?,?,?,?)",[$name_responder,$text_responder,date('Y-m-d H:i:s'),$countUsers]);

  $_SESSION["CheckMessage"]["success"] = "Рассылка успешно создана!";          
  echo true;

}else{

  $_SESSION["CheckMessage"]["warning"] = implode("<br/>", $errors);

}

?>
 

