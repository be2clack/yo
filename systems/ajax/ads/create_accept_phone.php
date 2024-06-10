<?php

 $phone = formatPhone($_POST["phone"]);
 $validatePhone = validatePhone($phone);

 if($validatePhone['status']){

     if( $settings["confirmation_phone"] ){

         if( $_SESSION["create-verify-phone-attempts"]["date"] ){

             if( $_SESSION["create-verify-phone-attempts"]["date"] <= time() ){
                 unset($_SESSION["create-verify-phone-attempts"]);
             }else{
                 $time = date("i ".$ULang->t('мин')." s " . $ULang->t('сек'), mktime(0, 0, $_SESSION["create-verify-phone-attempts"]["date"] - time() ) );
                 exit(json_encode([ "status" => false, "answer" => $ULang->t("Повторно отправить сообщение можно через") . ' ' . $time]));
             }

         }else{

             if( intval($_SESSION["create-verify-phone-attempts"]["count"]) >= 3 ){
                 $_SESSION["create-verify-phone-attempts"]["date"] = time() + 180;
                 $time = date("i ".$ULang->t('мин')." s " . $ULang->t('сек'), mktime(0, 0, 180 ) );
                 exit(json_encode(["status" => false, "answer" => $ULang->t("Повторно отправить сообщение можно через") . ' ' . $time]));
             }

         }
        
        $_SESSION["create-verify-phone-attempts"]["count"]++;
        
        $_SESSION["create-verify-phone"][$phone]["code"] = smsVerificationCode($phone);

        echo json_encode(["status" => true]);

     }else{

        update("update uni_clients set clients_phone=? where clients_id=?", [$phone,$_SESSION["profile"]["id"]]);
        echo json_encode(["status"=>true]);

     }

}else{
    echo json_encode(["status"=>false, "answer"=>$validatePhone['error']]);
}

?>