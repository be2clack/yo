<?php

$_POST["menu"] = $_POST["menu"] ? $_POST["menu"] : [];
$menu = [];

if( count($_POST["menu"]) ){

    foreach ( array_slice($_POST["menu"], 0, 10)  as $key => $value) {
        if( $value["name"] ){
            $menu[$key]["name"] = $value["name"];
            $menu[$key]["link"] = $value["link"];
        }
    }

}

update("UPDATE uni_settings SET value=? WHERE name=?", array( json_encode($menu) ,'site_frontend_menu'));

?>