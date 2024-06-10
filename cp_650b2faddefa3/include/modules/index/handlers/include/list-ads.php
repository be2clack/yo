<div class="widget-list-ads" >

   <?php
     if($getAds["count"]){

        ?>
        <h3 class="widget-title" > Ждут модерации (<?php echo $getAds["count"]; ?>)</h3>
        <?php

        foreach (array_slice($getAds["all"], 0,5) as $key => $value) {
           $value = $Ads->getDataAd($value);
           $value["ads_images"] = $Ads->getImages($value["ads_images"]);
           ?>
             <div class="widget-list-ads-item" >
                <div class="widget-list-ads-item-img" ><img src="<?php echo Exists($config["media"]["small_image_ads"],$value["ads_images"][0],$config["media"]["no_image"]); ?>"></div>
                <div class="widget-list-ads-item-title" ><a target="_blank" href="<?php echo $Ads->alias($value); ?>"><?php echo $value["ads_title"]; ?></a> <span class="ad-status-label ad-status-label-<?php echo $value["ads_status"]; ?>" ><?php echo $Ads->status($value["ads_status"]); ?></span> </div>
                <div class="clr" ></div>
             </div>                       
           <?php
        }

        ?>

        <div class="widget-list-ads-actions" >
          <a href="?route=board&sort=1" >Перейти к объявлениям</a>
        </div>

        <?php

     }else{
       ?>
         <div class="infoIcon" >
           <span><i class="la la-exclamation-circle"></i></span>
           <p>Новых объявлений нет</p>
         </div>
       <?php
     }
   ?>
   
                 
</div>