<?php
$ratings = $Profile->outRating( $value["clients_shops_id_user"] );
$count_ads = $Ads->getCount("ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_user='{$value["clients_id"]}'");
$get_shop_slider = findOne('uni_clients_shops_slider','clients_shops_slider_id_shop=? order by clients_shops_slider_id asc', [$value["clients_shops_id"]]);
?>
<div class="col-lg-3 col-md-4 col-sm-6 col-12" >

  <div <?php if(isset($get_shop_slider["clients_shops_slider_image"])){ ?> style="background-image: linear-gradient(rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0.24) 75%, rgba(0, 0, 0, 0.64)), url(<?php echo $config["urlPath"] . "/" . $config["media"]["user_attach"] . "/" . $get_shop_slider["clients_shops_slider_image"]; ?>); background-position: center center; background-size: cover;" class="shop-item-card shop-item-card-bg" <?php }else{  ?> class="shop-item-card" <?php  } ?> >

      <div class="shop-item-card-logo"  >
         <img class="image-autofocus" src="<?php echo Exists($config["media"]["other"], $value["clients_shops_logo"], $config["media"]["no_image"]); ?>">
      </div>

      <div class="shop-item-card-content" >

         <div class="board-view-stars">
             
           <?php echo $ratings; ?>
           <div class="clr"></div>   

         </div>

         <a href="<?php echo $Shop->linkShop($value["clients_shops_id_hash"]); ?>" class="shop-item-card-name" ><?php echo custom_substr($value["clients_shops_title"], 35, "..."); ?></a>

         <span class="shop-item-card-count" ><?php echo $count_ads; ?> <?php echo ending($count_ads, $ULang->t("объявление"), $ULang->t("объявления"), $ULang->t("объявлений") ) ?></span>

         <a href="<?php echo $Shop->linkShop($value["clients_shops_id_hash"]); ?>" class="shop-item-card-button btn-custom-mini btn-color-light" ><?php echo $ULang->t("Перейти"); ?></a>
      </div>      

  </div>

</div>