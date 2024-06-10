
<h3 class="mb35 user-title" > <strong><?php echo $data["page_name"]; ?></strong> </h3>
<?php
   
   if(count($data["favorites"])){

   ?>
   <div class="row no-gutters gutters10" >
   <?php

   foreach ($data["favorites"] as $key => $value) {
       $value = $Ads->get("ads_id=?", [$value["favorites_id_ad"]]);
       if( $value ){
           include $config["template_path"] . "/include/user_ad_grid.php";
       }
   }

   ?>
   </div>
   <?php

   }else{
      ?>
       <div class="user-block-no-result" >

          <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
          <p><?php echo $ULang->t("Все избранные товары будут отображаться на этой странице."); ?></p>
         
       </div>                      
      <?php
   }