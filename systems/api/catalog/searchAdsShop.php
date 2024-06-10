<?php

$query = clearSearchBack($_GET["query"]);

$shop_id = (int)$_GET["shop_id"];

$results = [];

$getShop = findOne("uni_clients_shops", "clients_shops_id=?", [$shop_id]);

if(mb_strlen($query, 'UTF-8') >= 2 && $getShop){

    $getAds = $Ads->getAll(["query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_user=".$getShop["clients_shops_id_user"]." and ".$Filters->explodeSearch($query), "sort"=>"ORDER By ads_datetime_add DESC limit 100"]);

    if($getAds["count"]){

      foreach ($getAds["all"] as $key => $value) {
        $image = $Ads->getImages($value["ads_images"]);
        $getShop = $Shop->getUserShop($value["ads_id_user"]);
        $results['ads'][] = ['id'=>$value["ads_id"], 'title'=>$value["ads_title"], 'image'=>Exists($config["media"]["small_image_ads"],$image[0],$config["media"]["no_image"]), 'price'=>apiOutPrice(['data'=>$value, 'shop'=>$getShop])];
      }

    }


}

echo json_encode(['data'=>$results]);

?>