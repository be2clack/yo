<?php

$id_ad = intval($_POST["id_ad"]);

update( "update uni_ads set ads_status=? where ads_id=? and ads_id_user=?", array(5,$id_ad, intval($_SESSION["profile"]["id"]) ), true );

$Cache->update("uni_ads");

$Main->addActionStatistics(['ad_id'=>$id_ad,'from_user_id'=>0,'to_user_id'=>$_SESSION['profile']['id']],"ad_sell");

?>