<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$score = clear($_POST["score"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

if($score){

  $type = $settings["secure_payment_service"]["secure_score_type"][0];

  update("update uni_clients set clients_score=?,clients_secure=?,clients_score_type=? where clients_id=?", [encrypt($score),1,$type,$idUser]);

  echo json_encode( ["status"=>true] );

}else{
  echo json_encode( ["status"=>false, "errors"=>apiLangContent("Пожалуйста, укажите счет")] );
}

?>