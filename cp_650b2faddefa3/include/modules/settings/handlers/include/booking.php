<?php

update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["booking_payment_service_name"]),'booking_payment_service_name'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["booking_prepayment_percent_service"]),'booking_prepayment_percent_service'));

?>