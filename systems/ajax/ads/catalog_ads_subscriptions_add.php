<?php

if(!$_SESSION["profile"]["id"]){ exit(json_encode([ "auth" => false, "status" => false ])); }

$url = trim($_POST["url"], "/");

if($_SESSION["profile"]["data"]["clients_email"]){

 $findUrl = findOne("uni_ads_subscriptions", "ads_subscriptions_params=? and ads_subscriptions_email=?", [$url,$_SESSION["profile"]["data"]["clients_email"]]);
 
 if(!$findUrl){
    insert("INSERT INTO uni_ads_subscriptions(ads_subscriptions_email,ads_subscriptions_id_user,ads_subscriptions_params,ads_subscriptions_date,ads_subscriptions_period,ads_subscriptions_date_update)VALUES(?,?,?,?,?,?)", [ $_SESSION["profile"]["data"]["clients_email"],intval($_SESSION['profile']['id']),$url,date("Y-m-d H:i:s"), 1, date("Y-m-d H:i:s") ]);
 }

 echo json_encode( [ "status" => true, "auth" => true ] );
}else{
 echo json_encode( [ "auth" => true, "status" => false ] );
}

?>