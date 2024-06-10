<?php

update('delete from uni_services_tariffs_orders where services_tariffs_orders_id_user=?', [$_SESSION["profile"]["id"]]);
update('update uni_clients set clients_tariff_id=? where clients_id=?', [0,$_SESSION["profile"]["id"]]);
if($_SESSION["profile"]['shop']) update("update uni_clients_shops set clients_shops_status=? where clients_shops_id=?", [0, $_SESSION["profile"]['shop']["clients_shops_id"]]);

?>