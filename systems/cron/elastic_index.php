<?php
defined('unisitecms') or exit();

$Elastic = new ELastic();

$param_search["sort"]["ads_id"] = [ "order" => "desc" ];
$results = $Elastic->search( [ "index" => "uni_ads", "type" => "ad", "size" => 1, "body" => $param_search ] );
$results = $Elastic->array_map( $results["hits"]["hits"] );

if( intval($results[0]["ads_id"]) ){
    
    $getAds = $Ads->getAll( [ "query"=>"ads_id > " . intval($results[0]["ads_id"]), "sort" => "limit 1000" ] );

    if( count($getAds["all"]) ){

        foreach ($getAds["all"] as $key => $value) {
           
           $Elastic->index( [ "index" => "uni_ads", "type" => "ad", "id" => $value["ads_id"], "body" => $Elastic->prepareFields( $value ) ] );

        }

    }

}


?>