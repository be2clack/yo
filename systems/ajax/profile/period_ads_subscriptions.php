<?php

$getUser = findOne("uni_clients", "clients_id=?", [ intval($_SESSION['profile']['id']) ] );

if(!$getUser) exit;

update("update uni_ads_subscriptions set ads_subscriptions_period=?,ads_subscriptions_date_update=? where (ads_subscriptions_id_user=? or ads_subscriptions_email=?) and ads_subscriptions_id=?", [intval($_POST["period"]),date("Y-m-d H:i:s"),intval($_SESSION["profile"]["id"]),$getUser["clients_email"],intval($_POST["id"])]);

?>