<?php

$uniqId = clear($_POST["uniq_id"]);

$getToken = findOne("uni_clients_auth","clients_auth_uniq_id=?", [$uniqId]);

if($getToken){

   $getUser = findOne("uni_clients","clients_id = ?", [$getToken["clients_auth_user_id"]]);

   if($getUser){
     if($getUser['clients_status'] == 2 || $getUser['clients_status'] == 3){
         echo json_encode(["status"=>false, "errors" => apiLangContent("Ваш аккаунт заблокирован!")]);
     }else{
         $totalCount = $Profile->getMessage($getToken["clients_auth_user_id"]);
         update("UPDATE uni_clients SET clients_datetime_view=NOW() WHERE clients_id=?", array($getToken["clients_auth_user_id"]));
         echo json_encode(["status"=>true, "id"=>$getToken["clients_auth_user_id"], 'token'=>$getToken["clients_auth_token"], 'count_messages'=>$totalCount['total'] ?: null]);
     }
   }else{
     echo json_encode(["status"=>false, "errors" => apiLangContent("Пользователь не найден!")]);
   }

}else{
   echo json_encode(["status"=>false, "errors" => apiLangContent("Неверный токен. Авторизуйтесь!")]); 
}

?>