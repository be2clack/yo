<h3 class="mb35 user-title" > <strong><?php echo $data["page_name"]; ?></strong> </h3>

<div class="user-menu-tab" >
  <?php if($data["advanced"]){ ?>
  <div data-id-tab="all" <?php if($action == "ad" || !$action){ echo 'class="active"'; } ?> > <?php echo $ULang->t('Все объявления'); ?></div>
  <?php } ?>
  <div data-id-tab="ad" <?php if(!$data["advanced"]){ if($action == "ad" || !$action){ echo 'class="active"'; } } ?> > <?php echo $ULang->t('Активные');  ?></div>
  <div data-id-tab="sold" > <?php echo $ULang->t('Проданные'); ?> </div>
  <?php if($data["advanced"]){ ?>
  <div data-id-tab="moderation" > <?php echo $ULang->t('На модерации'); ?></div>
  <?php } ?>
  <?php if($data["advanced"]){ ?>
  <div data-id-tab="archive" > <?php echo $ULang->t('В архиве'); ?> </div>
  <?php } ?>
</div>

<?php if($data["advanced"]){ ?>
<div class="user-menu-tab-content <?php if($action == "ad" || !$action){ echo 'active'; } ?>" data-id-tab="all" >
   
   <div <?php if(!$data["advanced"]){ ?> class="row no-gutters gutters10" <?php } ?> >
   <?php
     if($data["all"]["all"]){

         foreach ($data["all"]["all"] as $key => $value) {
            if($data["advanced"]){
               include $config["template_path"] . "/include/user_ad_list.php";
            }else{
               include $config["template_path"] . "/include/user_ad_grid.php";
            }
         }

         ?>
         
         <ul class="pagination justify-content-center mt15">  
            <?php echo out_navigation( array("count"=>$data["all"]["count"], "output" => $settings["catalog_out_content"], "url"=>"", "prev"=>'<i class="la la-long-arrow-left"></i>', "next"=>'<i class="la la-arrow-right"></i>', "page_count" => $_GET["page"], "page_variable" => "page") );?>
         </ul>

         <?php

     }else{
        ?>
        <div class="user-block-no-result" >

           <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
           <p><?php echo $ULang->t("Все ваши объявления будут отображаться на этой странице."); ?></p>
          
        </div>
        <?php
     }
   ?>
   </div>
  
</div>
<?php } ?>

<div class="user-menu-tab-content <?php if(!$data["advanced"]){ if($action == "ad" || !$action){ echo 'active'; } } ?>" data-id-tab="ad" >
     
   <div <?php if(!$data["advanced"]){ ?> class="row no-gutters gutters10" <?php } ?> >
   <?php
     if($data["ad"]["all"]){

         foreach ($data["ad"]["all"] as $key => $value) {
            if($data["advanced"]){
               include $config["template_path"] . "/include/user_ad_list.php";
            }else{
               include $config["template_path"] . "/include/user_ad_grid.php";
            }
         }

         ?>
           <ul class="pagination justify-content-center mt15">  
            <?php echo out_navigation( array("count"=>$data["ad"]["count"], "output" => $settings["catalog_out_content"], "url"=>"", "prev"=>'<i class="la la-long-arrow-left"></i>', "next"=>'<i class="la la-arrow-right"></i>', "page_count" => $_GET["page"], "page_variable" => "page") );?>
           </ul>
         <?php

     }else{
        ?>
        <div class="user-block-no-result" >
           <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
           <p><?php echo $ULang->t("Все созданные объявления будут отображаться на этой странице."); ?></p>
        </div>
        <?php
     }
   ?>
   </div>
  
</div>

<div class="user-menu-tab-content <?php if($action == "sold"){ echo 'active'; } ?>" data-id-tab="sold" >
   
   <div <?php if(!$data["advanced"]){ ?> class="row no-gutters gutters10" <?php } ?> >
   <?php
     if($data["sold"]["all"]){

         foreach ($data["sold"]["all"] as $key => $value) {
            if($data["advanced"]){
               include $config["template_path"] . "/include/user_ad_list.php";
            }else{
               include $config["template_path"] . "/include/user_ad_grid.php";
            }
         }

         ?>
         
         <ul class="pagination justify-content-center mt15">  
            <?php echo out_navigation( array("count"=>$data["sold"]["count"], "output" => $settings["catalog_out_content"], "url"=>"", "prev"=>'<i class="la la-long-arrow-left"></i>', "next"=>'<i class="la la-arrow-right"></i>', "page_count" => $_GET["page"], "page_variable" => "page") );?>
         </ul>

         <?php

     }else{
        ?>
        <div class="user-block-no-result" >

           <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
           <p><?php echo $ULang->t("Все проданные товары будут отображаться на этой странице."); ?></p>
          
        </div>
        <?php
     }
   ?>
   </div>
  
</div>

<?php if($data["advanced"]){ ?>
<div class="user-menu-tab-content <?php if($action == "archive"){ echo 'active'; } ?>" data-id-tab="archive" >
   
   <?php
     if($data["archive"]["all"]){

         foreach ($data["archive"]["all"] as $key => $value) {
            include $config["template_path"] . "/include/user_ad_list.php";
         }

         ?>
         
         <ul class="pagination justify-content-center mt15">  
            <?php echo out_navigation( array("count"=>$data["archive"]["count"], "output" => $settings["catalog_out_content"], "url"=>"", "prev"=>'<i class="la la-long-arrow-left"></i>', "next"=>'<i class="la la-arrow-right"></i>', "page_count" => $_GET["page"], "page_variable" => "page") );?>
         </ul>

         <?php

     }else{
        ?>
        <div class="user-block-no-result" >

           <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
           <p><?php echo $ULang->t("Все объявления помещенные в архив будут отображаться на этой странице."); ?></p>
          
        </div>
        <?php
     }
   ?>
  
</div>

<div class="user-menu-tab-content <?php if($action == "moderation"){ echo 'active'; } ?>" data-id-tab="moderation" >
   
   <div <?php if(!$data["advanced"]){ ?> class="row no-gutters gutters10" <?php } ?> >
   <?php
     if($data["moderation"]["all"]){

         foreach ($data["moderation"]["all"] as $key => $value) {
            if($data["advanced"]){
               include $config["template_path"] . "/include/user_ad_list.php";
            }else{
               include $config["template_path"] . "/include/user_ad_grid.php";
            }
         }

         ?>
         
         <ul class="pagination justify-content-center mt15">  
            <?php echo out_navigation( array("count"=>$data["moderation"]["count"], "output" => $settings["catalog_out_content"], "url"=>"", "prev"=>'<i class="la la-long-arrow-left"></i>', "next"=>'<i class="la la-arrow-right"></i>', "page_count" => $_GET["page"], "page_variable" => "page") );?>
         </ul>

         <?php

     }else{
        ?>
        <div class="user-block-no-result" >

           <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
           <p><?php echo $ULang->t("Все объявления на модерации будут отображаться на этой странице."); ?></p>
          
        </div>
        <?php
     }
   ?>
   </div>
  
</div>

<?php } ?>