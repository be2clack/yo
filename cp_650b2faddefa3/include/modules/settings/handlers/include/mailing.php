<?php

$error = [];

if(!$_POST["email_noreply"]){$error[] = "Пожалуйста, укажите ответный E-Mail!";}

if( intval($_POST["variant_send_mail"]) == 1 ){

   $_POST["smtp_host"] = "";
   $_POST["smtp_port"] = "";
   $_POST["smtp_username"] = "";
   $_POST["smtp_password"] = "";

}else{

   if( $_POST["smtp_password"] ){
      $_POST["smtp_password"] = encrypt($_POST["smtp_password"]);
   }else{
      $_POST["smtp_password"] = $settings["smtp_password"];
   }

}

if(!$error){
	update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["smtp_host"]),'smtp_host'));
	update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["smtp_port"]),'smtp_port'));
	update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["smtp_username"]),'smtp_username'));
	update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["smtp_password"]),'smtp_password'));
	update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["smtp_secure"]),'smtp_secure'));
   update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["name_responder"]),'name_responder'));
   update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["email_noreply"]),'email_noreply'));
   update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["variant_send_mail"]),'variant_send_mail'));	
}

?>