<?php

$id_ad = intval($_POST["id_ad"]);

$getAd = $Ads->get("ads_id=?",[$id_ad]);

$period = date("Y-m-d H:i:s", time() + ($settings["ads_time_publication_default"] * 86400) );

$getCategories = (new CategoryBoard())->getCategories("where category_board_visible=1");

if(strtotime($getAd["ads_period_publication"]) <= time()){

if( $getCategories["category_board_id"][$getAd["ads_id_cat"]]["category_board_status_paid"] ){

    if(intval($Ads->userCountAvailablePaidAddCategory($getAd["ads_id_cat"], $getAd['ads_id_user'])) > intval($getCategories["category_board_id"][$getAd["ads_id_cat"]]["category_board_count_free"])){

        update( "update uni_ads set ads_period_publication=?,ads_status=? where ads_id=? and ads_id_user=?", array($period,6,$id_ad,intval($_SESSION["profile"]["id"]) ), true );

    }else{
       	update( "update uni_ads set ads_status=?, ads_period_publication=? where ads_id=? and ads_id_user=?", array(1,$period,$id_ad,intval($_SESSION["profile"]["id"]) ), true ); 
    }

}else{

	update( "update uni_ads set ads_status=?, ads_period_publication=? where ads_id=? and ads_id_user=?", array(1,$period,$id_ad,intval($_SESSION["profile"]["id"]) ), true );

}


}else{

	update( "update uni_ads set ads_status=? where ads_id=? and ads_id_user=?", array(1,$id_ad, intval($_SESSION["profile"]["id"]) ), true );

}

$Cache->update("uni_ads");

echo $Ads->alias($getAd) . "?modal=new_ad";

?>