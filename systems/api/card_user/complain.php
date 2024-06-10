<?php

$idUserFrom = (int)$_POST["id_user_from"];
$tokenAuth = clear($_POST["token"]);
$idUserTo = (int)$_POST["id_user_to"];
$text = clear($_POST["text"]);

if(checkTokenAuth($tokenAuth, $idUserFrom) == false){
	http_response_code(500); exit('Authorization token error');
}

$getComplain = findOne("uni_ads_complain", "ads_complain_from_user_id=? and ads_complain_to_user_id=? and ads_complain_action=? and ads_complain_status=?", array($idUserFrom,$idUserTo,'user',0));

if(!$getComplain){

	if($text){

		smart_insert('uni_ads_complain', [
			'ads_complain_from_user_id' => $idUserFrom,
			'ads_complain_text' => $text,
			'ads_complain_date' => date("Y-m-d H:i:s"),
			'ads_complain_to_user_id' => $idUserTo,
			'ads_complain_action' => 'user'
		]);

		$Admin->addNotification("complaint");

		echo json_encode(["status"=>true,"answer"=>apiLangContent("Обращение успешно принято.")]);

	}else{
		echo json_encode(["status"=>false,"answer"=>apiLangContent("Опишите причину жалобы.")]);
	}

}else{
 	echo json_encode(["status"=>false,"answer"=>apiLangContent("Ваше обращение уже принято и находится на рассмотрении.")]);
}

?>