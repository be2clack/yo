<?php 

$value = $Ads->getDataAd($value);

$image = $Ads->getImages($value["ads_images"]);
?>
<div class="col-lg-3 col-md-3 col-sm-6 col-6" >
  <div class="item-grid" title="<?php echo $value["ads_title"]; ?>" >
     <div class="item-grid-label-status" > <?php echo $Ads->publicationAndStatus($value); ?> </div>
     <a class="item-grid-img" href="<?php echo $Ads->alias($value); ?>" >
       <img alt="<?php echo $value["ads_title"]; ?>" class="image-autofocus" src="<?php echo Exists($config["media"]["small_image_ads"],$image[0],$config["media"]["no_image"]); ?>" >
     </a>
     <div class="item-grid-info" >
        
        <?php echo $Ads->adActionFavorite($value, "catalog", "item-grid-favorite"); ?>

        <div class="item-grid-price" >
         <?php
               echo $Ads->outPrice( ["data"=>$value,"class_price"=>"item-grid-price-now","class_price_old"=>"item-grid-price-old", "abbreviation_million" => true] );
         ?>        
        </div> 

        <a href="<?php echo $Ads->alias($value); ?>" ><?php echo custom_substr($value["ads_title"], 35, "..."); ?></a>
     </div>
  </div>
</div>