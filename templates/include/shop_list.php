<?php
$ratings = $Profile->outRating($value["clients_shops_id_user"]);
$count_ads = $Ads->getCount("ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_user='{$value["clients_id"]}'");
$count_reviews = (int)getOne("select count(*) as total from uni_clients_reviews where clients_reviews_id_user=?", [$value["clients_id"]])["total"];
$get_shop_slider = findOne('uni_clients_shops_slider','clients_shops_slider_id_shop=? order by clients_shops_slider_id asc', [$value["clients_shops_id"]]);
?>
<div class="shop-item-card-list" >

    <div class="row no-gutters" >
        
        <div class="col-lg-4 col-12 col-md-4 col-sm-4 text-center" >
          
          <div class="shop-item-card-list-logo-bg" <?php if($get_shop_slider["clients_shops_slider_image"]){ ?> style="background-image: linear-gradient(rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0.24) 75%, rgba(0, 0, 0, 0.64)), url(<?php echo $config["urlPath"] . "/" . $config["media"]["user_attach"] . "/" . $get_shop_slider["clients_shops_slider_image"]; ?>); background-position: center center; background-size: cover;" <?php } ?> >
            
            <div class="shop-item-card-logo" >
               <img class="image-autofocus" src="<?php echo Exists($config["media"]["other"], $value["clients_shops_logo"], $config["media"]["no_image"]); ?>">
            </div>

            <div class="shop-item-card-content" >

               <div class="board-view-stars">
                   
                 <?php echo $ratings; ?> <a href="<?php echo _link("user/".$value["clients_id_hash"] . "/reviews"); ?>" >(<?php echo $count_reviews; ?>)</a>
                 <div class="clr"></div>   

               </div>

            </div>

          </div>

        </div>
        <div class="col-lg-8 col-12 col-md-8 col-sm-8" >

          <div class="shop-item-card-list-content" >

             <a href="<?php echo $Shop->linkShop($value["clients_shops_id_hash"]); ?>" class="shop-item-card-name" ><?php echo $value["clients_shops_title"]; ?></a>

             <div class="shop-item-card-list-count" > <?php echo $count_ads; ?> <?php echo ending($count_ads, $ULang->t("объявление"), $ULang->t("объявления"), $ULang->t("объявлений") ) ?> </div>
             
             <?php if( $value["clients_shops_desc"] ){ ?>
             <div class="shop-item-card-list-desc" > <?php echo $value["clients_shops_desc"]; ?> </div>
             <?php } ?>

             <a href="<?php echo $Shop->linkShop($value["clients_shops_id_hash"]); ?>" class="shop-item-card-button btn-custom-mini btn-color-light width100" ><?php echo $ULang->t("Перейти"); ?></a>
             
          </div>

        </div>

    </div>

</div>