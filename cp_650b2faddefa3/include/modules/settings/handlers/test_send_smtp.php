<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_settings']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

	if($settings["email_alert"]){

	$settings["SMTPDebug"] = 1;   

	echo $settings["email_alert"].': ';
	echo mailer($settings["email_alert"],"Сообщение пришло! Урааааа!", "Сообщение пришло! Урааааа!" );

	}else{ $_SESSION["CheckMessage"]["error"] = "Укажите e-mail адрес в разделе оповещения"; } 

}     
?>