<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

$getToken = findOne("uni_clients_auth","clients_auth_user_id=? and clients_auth_token=?", [$idUser,$tokenAuth]);

if($getToken){

   $getUser = findOne("uni_clients","clients_id = ?", [$idUser]);

   if($getUser){
     if($getUser['clients_status'] == 2 || $getUser['clients_status'] == 3){
         echo json_encode(["status"=>false, "errors" => apiLangContent("Ваш аккаунт заблокирован!")]);
     }else{
         $totalCount = $Profile->getMessage($idUser);
         update("UPDATE uni_clients SET clients_datetime_view=NOW() WHERE clients_id=?", array($idUser));
         echo json_encode(["status"=>true, "id"=>$getUser['clients_id'], 'token'=>$tokenAuth, 'count_messages'=>$totalCount['total'] ?: null]);
     }
   }else{
     echo json_encode(["status"=>false, "errors" => apiLangContent("Пользователь не найден!")]);
   }

}else{
   echo json_encode(["status"=>false, "errors" => apiLangContent("Неверный токен. Авторизуйтесь!")]); 
}

?>