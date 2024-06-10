<?php

update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["home_sidebar_status"]),'home_sidebar_status'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["home_stories_status"]),'home_stories_status'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["home_shop_status"]),'home_shop_status'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["home_promo_status"]),'home_promo_status'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["home_vip_status"]),'home_vip_status'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["home_auction_status"]),'home_auction_status'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["home_blog_status"]),'home_blog_status'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["home_category_ads_status"]),'home_category_ads_status'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["home_category_slider_status"]),'home_category_slider_status'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(clear($_POST["home_widget_sorting"]),'home_widget_sorting'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["index_out_content_method"]),'index_out_content_method'));

?>