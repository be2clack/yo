<?php
defined('unisitecms') or exit();

$now = date("Y-m-d");

$x=0;
while ($x++<6){
   $week[ date('Y-m-d', strtotime("-".$x." day")) ] = date('Y-m-d', strtotime("-".$x." day"));
}

$week[ date('Y-m-d') ] = date('Y-m-d');

ksort($week);

foreach ($week as $key => $value) {

   $key = "ads_status!='8' and date(ads_datetime_add) = '".$value."'";

   $getAds = getOne("select count(*) as total from uni_ads where $key");

   $Cache->set( [ "table" => "uni_ads", "key" => $key, "data" => intval($getAds["total"]) ] );

}

$count = (int)getOne("select count(*) as total from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user where ads_status!='8' and clients_status!='3'")["total"];

$Cache->set( [ "table" => "uni_ads", "key" => "ads_status!='8' and clients_status!='3'", "data" => $count ] );

$count = (int)getOne("select count(*) as total from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user where ads_status!='8' and clients_status!='3' and date(ads_datetime_add)='$now'")["total"];

$Cache->set( [ "table" => "uni_ads", "key" => "ads_status!='8' and clients_status!='3' and date(ads_datetime_add)='$now'", "data" => $count ] );

?>