<?php

if(!$_SESSION["profile"]["id"]){ exit(json_encode(["status" => false, "auth" => false])); }

$id_user = (int)$_POST["id_user"];
$id_shop = (int)$_POST["id_shop"];

$get = findOne("uni_clients_subscriptions", "clients_subscriptions_id_user_from=? and clients_subscriptions_id_user_to=?", [$_SESSION["profile"]["id"],$id_user]);

if( $get ){
  update("delete from uni_clients_subscriptions where clients_subscriptions_id=?", [ $get["clients_subscriptions_id"] ]);
  echo json_encode( ["status" => false, "auth" => true] );
}else{
  insert("INSERT INTO uni_clients_subscriptions(clients_subscriptions_id_user_from,clients_subscriptions_id_user_to,clients_subscriptions_id_shop,clients_subscriptions_date_add)VALUES(?,?,?,?)", [$_SESSION["profile"]["id"],$id_user,$id_shop, date("Y-m-d H:i:s") ]);
  echo json_encode( ["status" => true, "auth" => true] );
}

?>