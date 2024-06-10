<?php

$idOrder = (int)$_POST['id_order'];
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$text = clear($_POST["text"]);
$attach = isset($_POST["attach"]) ? json_decode($_POST["attach"], true) : [];
$attachList = [];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

if($text){

   $getSecure = findOne("uni_secure", "secure_id_order=? and (secure_status=? or secure_status=? or secure_status=?)", [$idOrder,1,2,3]);

   if($getSecure){

    if(count($attach)){
       foreach ($attach as $data) {

          if(file_exists($config["basePath"] . "/" . $config["media"]["temp_images"]."/".$data['name'])){

              if(copy($config["basePath"] . "/" . $config["media"]["temp_images"]."/".$data['name'], $config["basePath"]."/".$config["media"]["user_attach"]."/".$data['name'])){
                $attachList[] = $data['name'];
              };
        
          }

       }
    }

   insert("INSERT INTO uni_secure_disputes(secure_disputes_id_secure,secure_disputes_text,secure_disputes_date,secure_disputes_id_user,secure_disputes_attach)VALUES(?,?,?,?,?)", [$getSecure['secure_id'], $text, date("Y-m-d H:i:s"), $idUser, json_encode($attachList)]);

   update("update uni_secure set secure_status=? where secure_id=? and secure_id_user_buyer=?", [4,$getSecure['secure_id'],$idUser]);

   echo json_encode( ["status"=>true] );

  }else{
    echo json_encode(["status"=>false, 'answer'=>'Заказ не найден.']);
  }

}else{
   echo json_encode(["status"=>false, 'answer'=>apiLangContent('Пожалуйста, опишите причину спора')]);
}

?>