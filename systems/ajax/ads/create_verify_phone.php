<?php

 $phone = formatPhone( $_POST["phone"] );
 $code = intval( $_POST["code"] );

 if( $_SESSION["create-verify-phone"][$phone]["code"] && $_SESSION["create-verify-phone"][$phone]["code"] == $code ){
    $_SESSION["create-verify-phone"]["phone"] = $phone;
    echo true;
    unset($_SESSION["create-verify-phone-attempts"]);
 }else{
    echo $ULang->t("Неверный код");
 }

?>