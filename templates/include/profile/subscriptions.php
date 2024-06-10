
<h3 class="mb35 user-title" > <strong><?php echo $data["page_name"]; ?></strong> </h3>

<div class="user-menu-tab" >
 <div data-id-tab="search" class="active" > <?php echo $ULang->t("Поиски"); ?> </div>
 <div data-id-tab="shops" > <?php echo $ULang->t("Магазины"); ?> </div>
</div>

<div class="user-menu-tab-content active" data-id-tab="search" >
 
 <?php
   if($data["subscriptions_search"]){

      ?>
      <div class="bg-container" >
      <?php

      foreach($data["subscriptions_search"] AS $value){
      
      ?>
      <div class="profile-subscriptions-list" >
         <div class="row">
             <div class="col-lg-6 col-8" >
               <a class="profile-subscriptions-name" target="_blank" href="<?php echo _link($value["ads_subscriptions_params"]); ?>"><?php echo $Ads->buildNameSubscribe($value["ads_subscriptions_params"]); ?></a>
               <div>
                 <?php echo datetime_format($value["ads_subscriptions_date"], false); ?>
               </div>
             </div>

             <div class="col-lg-6 col-4" >
               <div class="profile-subscriptions-config" >

                  <div class="btn-group" role="group">
                    <button type="button" class="btn btn-light profile-subscriptions-ad-period <?php if($value["ads_subscriptions_period"] == 2){ echo 'active'; } ?> " data-period="2" data-id="<?php echo $value["ads_subscriptions_id"]; ?>" ><?php echo $ULang->t("Сразу при публикации"); ?></button>
                    <button type="button" class="btn btn-light profile-subscriptions-ad-period <?php if($value["ads_subscriptions_period"] == 1){ echo 'active'; } ?>" data-period="1" data-id="<?php echo $value["ads_subscriptions_id"]; ?>" ><?php echo $ULang->t("Раз в день"); ?></button>
                    <button type="button" class="btn btn-danger profile-subscriptions-ad-delete" data-id="<?php echo $value["ads_subscriptions_id"]; ?>" ><?php echo $ULang->t("Удалить"); ?></button>
                  </div>

               </div>                               
             </div>
          </div>
       </div>
       <?php                                         
      }

      ?>
      </div>
      <?php 
      
   }else{
      ?>
       <div class="user-block-no-result" >

          <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
          <p><?php echo $ULang->t("Все подписки на поиск будут отображаться на этой странице."); ?></p>
         
       </div>                       
      <?php
   }
 ?>

</div>

<div class="user-menu-tab-content <?php if($action == "shops"){ echo 'active'; } ?>" data-id-tab="shops" >
 
 <?php
   if($data["subscriptions_shops"]){

      ?>
      <div class="bg-container" >
      <?php

      foreach($data["subscriptions_shops"] AS $value){
      
      ?>
      <div class="profile-subscriptions-list" >
         <div class="row">
             <div class="col-lg-6 col-8" >
               <a class="profile-subscriptions-name" target="_blank" href="<?php echo $Shop->linkShop( $value["clients_shops_id_hash"] ); ?>"><?php echo $value["clients_shops_title"]; ?></a>
               <div>
                 <?php echo datetime_format($value["clients_subscriptions_date_add"], false); ?>
               </div>
             </div>

             <div class="col-lg-6 col-4" >
               <div class="profile-subscriptions-config" >
                    <button type="button" class="btn-custom-mini-icon btn-color-danger profile-subscriptions-shop-delete" data-id="<?php echo $value["clients_subscriptions_id"]; ?>" ><i class="las la-times"></i></button>
               </div>                               
             </div>
          </div>
       </div>
       <?php                                         
      }

      ?>
      </div>
      <?php 
      
   }else{
      ?>
       <div class="user-block-no-result" >

          <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
          <p><?php echo $ULang->t("Все подписки на магазины будут отображаться на этой странице."); ?></p>
         
       </div>                       
      <?php
   }
 ?>

</div>

<?php