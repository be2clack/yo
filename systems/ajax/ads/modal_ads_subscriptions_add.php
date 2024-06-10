<?php

 $error = [];

 $url = trim($_POST["url"], "/");

 if(validateEmail( $_POST["email"] ) == false){

      $error[] = $ULang->t("Пожалуйста, укажите корректный e-mail адрес.");

 }else{

      $findUrl = findOne("uni_ads_subscriptions", "ads_subscriptions_params=? and ads_subscriptions_email=?", [$url,$_POST["email"]]);
      if($findUrl) $error[] = $ULang->t("Сохраненный поиск с такими параметрами уже существует!");

 }

 if( !count($error) ){
     
     insert("INSERT INTO uni_ads_subscriptions(ads_subscriptions_email,ads_subscriptions_id_user,ads_subscriptions_params,ads_subscriptions_date,ads_subscriptions_period,ads_subscriptions_date_update)VALUES(?,?,?,?,?,?)", [ $_POST["email"],intval($_SESSION['profile']['id']),$url,date("Y-m-d H:i:s"), intval($_POST["period"]),date("Y-m-d H:i:s") ]);
     
     $Subscription->add(array("email"=>$_POST["email"],"user_id"=>intval($_SESSION['profile']['id']),"name"=>$_POST["email"],"status" => 1));

     echo json_encode( [ "status" => true ] );

 }else{

     echo json_encode( [ "status" => false, "answer" => implode("\n", $error) ] );

 }

?>