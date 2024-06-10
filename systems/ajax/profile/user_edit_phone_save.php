<?php

$phone = formatPhone($_POST["phone"]);
$code = $_POST["code"];

$validatePhone = validatePhone($phone);

if($validatePhone['status']){

    if( $settings["confirmation_phone"] ){

        if($_SESSION["verify_sms"][$phone]["code"] == $code && $code){
           update("update uni_clients set clients_phone=? where clients_id=?", [$phone,$_SESSION["profile"]["id"]]);
           echo json_encode( ["status"=>true] );
        }else{
           echo json_encode( ["status"=>false, "answer"=>$ULang->t("Неверный код") ] );
        }

    }else{

       update("update uni_clients set clients_phone=? where clients_id=?", [$phone,$_SESSION["profile"]["id"]]);
       echo json_encode( ["status"=>true] );

    }
    
}else{
  echo json_encode(["status"=>false, "answer"=>$validatePhone['error']]);
}

?>