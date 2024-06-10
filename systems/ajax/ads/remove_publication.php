<?php

$id_ad = intval($_POST["id_ad"]);

update( "update uni_ads set ads_status=? where ads_id=? and ads_id_user=?", array(2,$id_ad, intval($_SESSION["profile"]["id"]) ), true );

$Cache->update("uni_ads");

?>