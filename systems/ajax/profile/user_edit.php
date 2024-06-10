<?php

 $error = [];
 $status = ["user", "company"];

 if($_POST["status"] == "user") $_POST["name_company"] = "";

 if(!in_array($_POST["status"], $status)){
    $error["status"] = $ULang->t("Пожалуйста, укажите статус");
 }else{
    if(!$_POST["name_company"] && $_POST["status"] == "company"){
       $error["name_company"] = $ULang->t("Пожалуйста, укажите название компании");
    }
 }

 if(!$_POST["user_name"]){
    $error["user_name"] = $ULang->t("Пожалуйста, укажите имя");
 }

 if(intval($_POST["delivery_status"])){
   if(!$_POST["delivery_id_point_send"]){
       $error["delivery_id_point_send"] = $ULang->t("Пожалуйста, укажите пункт приема");
   }else{
       $getPoint = findOne('uni_boxberry_points', 'boxberry_points_code=?', [clear($_POST["delivery_id_point_send"])]);
       if(!$getPoint){
          $error["delivery_id_point_send"] = $ULang->t("Пункт приема не определен!");
       }
   }
 }else{
    $_POST["delivery_id_point_send"] = '';
 }

 if(!translite($_POST["id_hash"])){
    $error["id_hash"] = $ULang->t("Пожалуйста, укажите короткое имя");
 }else{
    if(findOne("uni_clients", "clients_id_hash=? and clients_id!=?", [translite($_POST["id_hash"]),$_SESSION["profile"]["id"]])){
       $error["id_hash"] = $ULang->t("Указанное имя уже используется");
    }
 }

 if(intval($_POST["secure"])){
   $getUser = findOne("uni_clients", "clients_id=?", [$_SESSION["profile"]["id"]]);
   if(!$getUser["clients_score"]){
       $error["secure"] = $ULang->t("Пожалуйста, укажите счет");
   }
 }

 if(count($error) == 0){

    update("update uni_clients set clients_name=?,clients_surname=?,clients_patronymic=?,clients_type_person=?,clients_name_company=?,clients_city_id=?,clients_id_hash=?,clients_comments=?,clients_secure=?,clients_view_phone=?,clients_delivery_status=?,clients_delivery_id_point_send=?,clients_delivery_id_city=? where clients_id=?", [custom_substr(clear($_POST["user_name"]), 15), custom_substr(clear($_POST["user_surname"]), 20), custom_substr(clear($_POST["user_patronymic"]), 20), $_POST["status"], custom_substr(clear($_POST["name_company"]), 30), intval($_POST["city_id"]), translite($_POST["id_hash"]),intval($_POST["comments"]),intval($_POST["secure"]),intval($_POST["view_phone"]),intval($_POST["delivery_status"]),clear($_POST["delivery_id_point_send"]),clear($_POST["delivery_id_city"]) ?: 0, $_SESSION["profile"]["id"]]);

    echo json_encode( ["status"=>true, "answer"=>$ULang->t("Данные успешно сохранены"), "location"=>_link("user/".translite($_POST["id_hash"])."/settings") ] );

 }else{
    echo json_encode( ["status"=>false, "answer"=>$error] );
 }

?>