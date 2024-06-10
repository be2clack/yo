<?php

$_POST["user_shop_alias_url_all"] = translite($_POST["user_shop_alias_url_all"]);
$_POST["user_shop_alias_url_page"] = translite($_POST["user_shop_alias_url_page"]);

if( !$_POST["user_shop_count_sliders"] ){
    $_POST["user_shop_count_sliders"] = 1;
}

if(!$_POST["user_shop_alias_url_all"]){
   $_POST["user_shop_alias_url_all"] = 'shops';
}

if(!$_POST["user_shop_alias_url_page"]){
   $_POST["user_shop_alias_url_page"] = 'shop';
}

update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["user_shop_count_sliders"]),'user_shop_count_sliders'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["user_shop_count_pages"]),'user_shop_count_pages'));
update("UPDATE uni_settings SET value=? WHERE name=?", array($_POST["user_shop_alias_url_all"],'user_shop_alias_url_all'));
update("UPDATE uni_settings SET value=? WHERE name=?", array($_POST["user_shop_alias_url_page"],'user_shop_alias_url_page'));
update("UPDATE uni_settings SET value=? WHERE name=?", array(intval($_POST["user_shop_status"]),'user_shop_status'));

?>