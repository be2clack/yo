<?php
$error = [];

if(!$_POST["subject"]) $error[] = $ULang->t("Пожалуйста, укажите тему обращения");
if(!$_POST["text"]) $error[] = $ULang->t("Пожалуйста, укажите текст обращения");
if(!$_POST["email"]) $error[] = $ULang->t("Пожалуйста, укажите ваш e-mail");

if(!$_POST["code"] || $_POST["code"] != $_SESSION['captcha']['feedback']) $error[] = $ULang->t("Пожалуйста, укажите корректный код проверки");

if( count($error) == 0 ){

	$text = '
	<p style="margin-bottom: 0px;" >'.$static_msg["12"].': '.$_POST["subject"].'</p>
	<p style="margin-bottom: 0px;" >'.$static_msg["13"].': '.$_POST["name"].'</p>
	<p>'.$static_msg["14"].': '.$_POST["email"].'</p>
	<hr>
	<p><strong>'.$static_msg["15"].'</strong></p>
	<p>'.$_POST["text"].'</p>
	';

	mailer($settings["email_alert"],$static_msg["16"]." - " . $settings["site_name"],$text);

	echo json_encode( ["status"=>true] );

	unset($_SESSION['csrf_token'][$_POST['csrf_token']]);

	$Admin->notifications("feedback", ["text"=>clear($_POST["text"]), "name"=>clear($_POST["name"]), "email"=>clear($_POST["email"])]);

}else{
	echo json_encode( ["status"=>false, "answer"=> implode("\n", $error) ] );
}
?>