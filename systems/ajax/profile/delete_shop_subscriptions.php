<?php
update("delete from uni_clients_subscriptions where clients_subscriptions_id_user_from=? and clients_subscriptions_id=?", [intval($_SESSION["profile"]["id"]),intval($_POST["id"])]);
?>