<?php

if($settings["secure_payment_service"]){
	$user_score_type = clear($_POST['user_score_type']);
	if($user_score_type != 'wallet' && $user_score_type != 'card') exit(json_encode( ["status"=>false, "answer"=>$ULang->t("Пожалуйста, выберите тип счета") ] ));
}else{
	$user_score_type = "card";
}

if($_POST["user_score"]) $user_score = encrypt($_POST["user_score"]);

update("update uni_clients set clients_score=?,clients_score_type=? where clients_id=?", [$user_score,$user_score_type,$_SESSION["profile"]["id"]]);

echo json_encode( ["status"=>true] );

?>