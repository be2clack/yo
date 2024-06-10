<?php
$error = [];

if(validateEmail( $_POST["email"] ) == false){
  $error[] = $ULang->t("Пожалуйста, укажите корректный e-mail адрес.");
}

if( count($error) == 0 ){

	$hash = hash('sha256', $_POST["email"].$config["private_hash"]);
	$subscribe = $config["urlPath"].'/subscribe?hash='.$hash.'&email='.$_POST["email"];

	$data = array("{ACTIVATION_LINK}"=>$subscribe,
	              "{UNSUBSCRIBE}"=>"",
	              "{EMAIL_TO}"=>$_POST["email"]
	              );

	email_notification( array( "variable" => $data, "code" => "SUBSCRIBE_ACTIVATION_EMAIL" ) );

	echo json_encode( ["status"=>true] );
	
}else{
	echo json_encode( ["status"=>false, "answer"=> implode("\n", $error) ] );
}
?>