<?php

update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["ads_currency_price"]),'ads_currency_price'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["ads_comments"]),'ads_comments'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["ad_view_phone"]),'ad_view_phone'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["ad_similar_count"]),'ad_similar_count'));

?>