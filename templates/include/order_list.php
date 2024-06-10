<div class="col-lg-12" >
  <div class="item-list-order" >

     <div class="row" >
       <div class="col-lg-12" >
          <div class="item-list-content" >
            
            <div class="row" >
              <div class="col-lg-8" >
                  
                  <?php $getSecure = findOne("uni_secure", "secure_id_order=?", [$value["clients_orders_uniq_id"]]); ?>

                  <span class="item-list-order-status secure-status-label-<?php echo $getSecure['secure_status']; ?>" >
                    <?php     
                      echo $Ads->secureStatusLabel($getSecure);                   
                    ?>
                  </span>
              
              </div>
              <div class="col-lg-4 text-right" >
                  <span class="item-list-order-secure" ><i class="las la-shield-alt"></i> <?php echo $ULang->t("Безопасная сделка"); ?></span>
              </div>
            </div>

            <div class="mt10" >
              <a href="<?php echo _link( "order/" . $value["clients_orders_uniq_id"] ); ?>">
              <span class="item-list-order-number" ><?php echo $ULang->t("Заказ"); ?> №<?php echo $value["clients_orders_uniq_id"]; ?></span>
              </a>
              <span class="item-list-order-date" ><?php echo $ULang->t("создан"); ?> <?php echo datetime_format($value["clients_orders_date"]); ?></span>
            </div>

            <?php  
            $getAds = getAll('select * from uni_secure_ads where secure_ads_order_id=?', [$value['clients_orders_uniq_id']]);
            ?>
            <div class="item-list-content-goods" >
              <?php
                  if(count($getAds)){
                    foreach ($getAds as $ad) {
                      $z_index = 1;
                      $getAd = findOne('uni_ads', 'ads_id=?', [$ad['secure_ads_ad_id']]);
                        if($getAd){
                          $image = $Ads->getImages($getAd["ads_images"]);
                          ?>
                          <div style="z-index: <?php echo $z_index; ?>;" title="<?php echo $getAd["ads_title"]; ?>" ><img alt="<?php echo $getAd["ads_title"]; ?>" class="image-autofocus" src="<?php echo Exists($config["media"]["small_image_ads"],$image[0],$config["media"]["no_image"]); ?>" ></div>
                          <?php
                        }
                        $z_index++;
                    }
                  }
              ?>
            </div>

          </div>
       </div>
     </div>


  </div>
</div>