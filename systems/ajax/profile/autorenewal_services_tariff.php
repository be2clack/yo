<?php
    update('update uni_clients set clients_tariff_autorenewal=? where clients_id=?', [intval($_POST['status']),$_SESSION["profile"]["id"]]);
?>