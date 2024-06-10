<h3 class="mb35 user-title" > <strong><?php echo $data["page_name"]; ?></strong> </h3>

<div><?php echo $ULang->t('Удобный способ продать и заработать больше, для тех, кто публикует много. Покупая пакет на большое количество объявлений, вы платите меньше, чем при покупке единичного размещения.'); ?></div>

<div class="btn-custom btn-color-green mt15 open-modal" data-id-modal="modal-ad-packages" ><?php echo $ULang->t('Купить пакет'); ?></div>

<div class="user-menu-tab mt25" >

  <div data-id-tab="all" class="active" ><?php echo $ULang->t('Активные'); ?></div>

  <div data-id-tab="completed" ><?php echo $ULang->t('Завершенные');  ?></div>

</div>

<div class="user-menu-tab-content active" data-id-tab="all" >

    <?php
        if($data["packages_orders"]){
            foreach ($data["packages_orders"] as $key => $value) {
                include $config["template_path"] . "/include/packages_orders.php";
            }
        }else{
            ?>
            <div class="user-block-no-result" >

               <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
               <p><?php echo $ULang->t("Все активные пакеты будут отображаться на этой странице."); ?></p>
              
            </div>                
            <?php
        }
    ?>     

</div>

<div class="user-menu-tab-content <?php if($action == "sold"){ echo 'active'; } ?>" data-id-tab="completed" >
   
    <?php
        if($data["packages_orders_completion"]){
            foreach ($data["packages_orders_completion"] as $key => $value) {
                include $config["template_path"] . "/include/packages_orders_completion.php";
            }
        }else{
            ?>
            <div class="user-block-no-result" >

               <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
               <p><?php echo $ULang->t("Все завершенные пакеты будут отображаться на этой странице."); ?></p>
              
            </div>                
            <?php
        }
    ?> 
  
</div>