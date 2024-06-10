<?php

$id = (int)$_POST["id"];

if(!$id) exit;

$getAd = $Ads->get('ads_id=?', [$id]);

if($getAd){

$images = $Ads->getImages($getAd["ads_images"]);
$service = $Ads->adServices($getAd["ads_id"]);
$getShop = $Shop->getUserShop($getAd["ads_id_user"]);

?>
  <div class="item-grid item-grid-map" title="<?php echo $getAd["ads_title"]; ?>" >
     <div class="item-grid-img" >
     <a href="<?php echo $Ads->alias($getAd); ?>" target="_blank" title="<?php echo $getAd["ads_title"]; ?>" >

       <div class="item-labels" >
          <?php echo $Ads->outStatus($service, $getAd); ?>
       </div>

       <?php echo $Ads->CatalogOutAdGallery($images, $getAd); ?>

     </a>
     <?php echo $Ads->adActionFavorite($getAd, "catalog", "item-grid-favorite"); ?>
     </div>
     <div class="item-grid-info" >

        <div class="item-grid-price" >
         <?php
               echo $Ads->outPrice( [ "data"=>$getAd,"class_price"=>"item-grid-price-now","class_price_old"=>"item-grid-price-old", "shop"=>$getShop, "abbreviation_million" => true ] );
         ?>        
        </div>
        <a href="<?php echo $Ads->alias($getAd); ?>" target="_blank" ><?php echo custom_substr($getAd["ads_title"], 35, "..."); ?></a>

        <span class="item-grid-city" >
         <?php 
             echo $Ads->outAdAddressArea($getAd);
         ?>
        </span>
        <span class="item-grid-date" ><?php echo datetime_format($getAd["ads_datetime_add"], false); ?></span>

     </div>
  </div>
<?php

}

?>