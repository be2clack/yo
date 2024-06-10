<?php

update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["sitemap_alias_filters"]),'sitemap_alias_filters'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["sitemap_seo_filters"]),'sitemap_seo_filters'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["sitemap_blog"]),'sitemap_blog'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["sitemap_blog_category"]),'sitemap_blog_category'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["sitemap_services"]),'sitemap_services'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["sitemap_cities"]),'sitemap_cities'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["sitemap_category"]),'sitemap_category'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["sitemap_shops"]),'sitemap_shops'));

?>