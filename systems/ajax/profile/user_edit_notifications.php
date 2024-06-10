<?php
    update("update uni_clients set clients_notifications=? where clients_id=?", [json_encode($_POST["notifications"]),$_SESSION["profile"]["id"]]);
?>