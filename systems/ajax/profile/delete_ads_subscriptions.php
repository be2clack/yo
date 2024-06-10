<?php

$getUser = findOne("uni_clients", "clients_id=?", [intval($_SESSION['profile']['id'])]);

if(!$getUser) exit;

update("delete from uni_ads_subscriptions where (ads_subscriptions_id_user=? or ads_subscriptions_email=?) and ads_subscriptions_id=?", [intval($_SESSION["profile"]["id"]),$getUser["clients_email"],intval($_POST["id"])]);

?>