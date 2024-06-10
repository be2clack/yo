<?php

 $error = [];
 $files = [];

 $getUser = findOne("uni_clients", "clients_id=?", [$_SESSION["profile"]["id"]]);

 if(!$getUser["clients_phone"]){
   $error[] = $ULang->t("Пожалуйста, подтвердите номер телефона");
 }

 if(!$getUser["clients_email"]){
   $error[] = $ULang->t("Пожалуйста, подтвердите email адрес");
 }

 if(!$_FILES['doc']['tmp_name']){
   $error[] = $ULang->t("Пожалуйста, прикрепите скрин паспорта");
 }

 if(!$_FILES['photo']['tmp_name']){
   $error[] = $ULang->t("Пожалуйста, прикрепите скрин вашего фото");
 }

 if(count($error) == 0){
     $getUserVerifications = findOne("uni_clients_verifications", "user_id=?", [$_SESSION["profile"]["id"]]);

     if($getUserVerifications){
        if($getUserVerifications["status"] == 2){
            update("delete from uni_clients_verifications where id=?", [$getUserVerifications["id"]]);
        }else{
            $error[] = $ULang->t("Заявка отправлена, дождитесь результата проверки");
        }
     }
 }

 if(count($error) == 0){

      $filenameDoc = md5("verifications_doc_".time()).".jpg";
      if( move_uploaded_file( $_FILES['doc']['tmp_name'], $config["basePath"].'/'.$config["media"]["user_attach"].'/'.$filenameDoc ) ){
       
         resize($config["basePath"].'/'.$config["media"]["user_attach"].'/'.$filenameDoc, $config["basePath"].'/'.$config["media"]["user_attach"].'/'.$filenameDoc, 1024, 768, 100, "jpg");

         file_put_contents($config["basePath"].'/'.$config["media"]["user_attach"].'/'.$filenameDoc, encrypt(file_get_contents($config["basePath"].'/'.$config["media"]["user_attach"].'/'.$filenameDoc)));

         $files[] = $filenameDoc;

      }

      $filenamePhoto = md5("verifications_photo_".time()).".jpg";
      if( move_uploaded_file( $_FILES['photo']['tmp_name'], $config["basePath"].'/'.$config["media"]["user_attach"].'/'.$filenamePhoto ) ){
       
         resize($config["basePath"].'/'.$config["media"]["user_attach"].'/'.$filenamePhoto, $config["basePath"].'/'.$config["media"]["user_attach"].'/'.$filenamePhoto, 1024, 768, 100, "jpg");

         file_put_contents($config["basePath"].'/'.$config["media"]["user_attach"].'/'.$filenamePhoto, encrypt(file_get_contents($config["basePath"].'/'.$config["media"]["user_attach"].'/'.$filenamePhoto)));

         $files[] = $filenamePhoto;

      }

      smart_insert('uni_clients_verifications', [
         'user_id'=>$_SESSION["profile"]["id"],
         'date_create'=>date("Y-m-d H:i:s"),
         'files'=>json_encode($files),
      ]);

      echo json_encode(["status"=>true]);

      $Admin->notifications("verification");

 }else{
    echo json_encode(["status"=>false, "answer"=>implode("\n", $error)]);
 }

?>