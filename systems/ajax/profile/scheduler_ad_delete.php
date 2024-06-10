<?php

$id = (int)$_POST['id'];

update('update uni_ads set ads_auto_renewal=? where ads_id=? and ads_id_user=?', [0,$id,$_SESSION["profile"]["id"]]);

$Cache->update("uni_ads");
?>