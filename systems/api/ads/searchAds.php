<?php

$query = clearSearchBack($_GET["query"]);

$user_id = (int)$_GET["user_id"];

$results = [];

if($user_id){

  if(mb_strlen($query, 'UTF-8') >= 2){

      $getAds = $Ads->getAll(["query"=>"ads_status!='8' and clients_status IN(0,1) and ads_id_user=".$user_id." and ".$Filters->explodeSearch($query), "sort"=>"ORDER By ads_datetime_add DESC limit 100"]);

      if($getAds["count"]){

        foreach ($getAds["all"] as $key => $value) {
          $image = $Ads->getImages($value["ads_images"]);
          $getShop = $Shop->getUserShop($value["ads_id_user"]);
          $results[] = ['id'=>$value["ads_id"], 'title'=>$value["ads_title"], 'image'=>Exists($config["media"]["small_image_ads"],$image[0],$config["media"]["no_image"]), 'price'=>apiOutPrice(['data'=>$value, 'shop'=>$getShop])];
        }

      }


  }else{

      $getAds = $Ads->getAll(["query"=>"ads_status!='8' and clients_status IN(0,1) and ads_id_user=".$user_id, "sort"=>"ORDER By ads_datetime_add DESC limit 5"]);

      if($getAds["count"]){

        foreach ($getAds["all"] as $key => $value) {
          $image = $Ads->getImages($value["ads_images"]);
          $getShop = $Shop->getUserShop($value["ads_id_user"]);
          $results[] = ['id'=>$value["ads_id"], 'title'=>$value["ads_title"], 'image'=>Exists($config["media"]["small_image_ads"],$image[0],$config["media"]["no_image"]), 'price'=>apiOutPrice(['data'=>$value, 'shop'=>$getShop])];
        }

      }

  }

}

echo json_encode(['data'=>$results]);

?>