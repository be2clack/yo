<?php 

update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["secure_payment_service_name"]),'secure_payment_service_name'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["secure_payment_balance"]),'secure_payment_balance'));

update("UPDATE uni_settings SET value=? WHERE name=?", array(round($_POST["secure_percent_service"],2),'secure_percent_service'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(round($_POST["secure_percent_payment"],2),'secure_percent_payment'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(round($_POST["secure_other_payment"],2),'secure_other_payment'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(round($_POST["secure_min_amount_payment"],2),'secure_min_amount_payment'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(round($_POST["secure_max_amount_payment"],2),'secure_max_amount_payment'));

?>