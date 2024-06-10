<div class="ads-services-tariffs" data-id="<?php echo $value["services_ads_uid"]; ?>" >
     
     <?php if($value["services_ads_recommended"]){ ?>
     <span class="ads-services-tariffs-discount" ><?php echo $ULang->t("Рекомендуем"); ?></span>
     <?php } ?>

     <div class="ads-services-tariffs-icon" >
         <span> <img src="<?php echo Exists($config["media"]["other"],$value["services_ads_image"],$config["media"]["no_image"]); ?>" height="55" > </span>
     </div>

     <p><strong><?php echo $ULang->t( $value["services_ads_name"], [ "table"=>"uni_services_ads", "field"=>"services_ads_name" ] ); ?></strong></p>

     <?php
        if($value["services_ads_onetime"]){
            ?>
            <p><?php echo $ULang->t("Единоразовая публикация"); ?></p>
            <?php
        }else{
            if($value["services_ads_variant"] == 1){
              ?>
              <p><?php echo $ULang->t("Действует"); ?> <?php echo $value["services_ads_count_day"]; ?> <?php echo ending($value["services_ads_count_day"],$ULang->t("день"),$ULang->t("дня"),$ULang->t("дней")) ?></p>
              <?php
            }else{
              ?>
              <div class="input-group input-group-sm">
                <input type="number" class="form-control" name="service[<?php echo $value["services_ads_uid"]; ?>]" data-id-service="<?php echo $value["services_ads_uid"]; ?>" value="1" >
                <div class="input-group-append">
                  <span class="input-group-text"><?php echo $ULang->t("дней"); ?></span>
                </div>
              </div>              
              <?php
            }
        }
     ?>
     
     <p><?php echo $ULang->t( $value["services_ads_text"], [ "table"=>"uni_services_ads", "field"=>"services_ads_text" ] ); ?></p>

     <div class="ads-services-tariffs-price-container" data-id-service="<?php echo $value["services_ads_uid"]; ?>" >
       <?php echo $Main->outPrices( array("new_price"=> array("price"=>$value["services_ads_new_price"], "tpl"=>'<p class="ads-services-tariffs-price-now" > <strong>{price}</strong> </p>'), "price"=>array("price"=>$value["services_ads_price"], "tpl"=>'<p class="ads-services-tariffs-price-old" >'.$ULang->t("Цена без скидки").' <span>{price}</span></p>') ) ); ?>
     </div>

</div>
