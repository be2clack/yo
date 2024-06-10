<h3 class="mb35 user-title" > <strong><?php echo $ULang->t("Все заказы"); ?></strong> </h3>

<div class="user-menu-tab" >
 <div data-id-tab="buy" class="active" > <?php echo $ULang->t("Покупки"); ?> (<?php echo isset($data["orders"]["buy"]) ? count($data["orders"]["buy"]) : 0; ?>)</div>
 <div data-id-tab="sell" > <?php echo $ULang->t("Продажи"); ?> (<?php echo isset($data["orders"]["sell"]) ? count($data["orders"]["sell"]) : 0; ?>)</div>
</div>

<div class="user-menu-tab-content active" data-id-tab="buy" >
   <?php
      if($data["orders"]["buy"]){
        ?>
        <div class="row" >
        <?php
          foreach ($data["orders"]["buy"] as $key => $value) {
             include $config["template_path"] . "/include/order_list.php";
          }
        ?>
        </div>
        <?php
      }else{
        ?>
           <div class="user-block-no-result" >

              <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
              <p><?php echo $ULang->t("Заказы по купленным товарам будут отображаться на этой странице."); ?></p>
             
           </div>                            
        <?php
      }
   ?>
</div>
<div class="user-menu-tab-content" data-id-tab="sell" >
   <?php
      if($data["orders"]["sell"]){
        ?>
        <div class="row" >
        <?php
          foreach ($data["orders"]["sell"] as $key => $value) {
             include $config["template_path"] . "/include/order_list.php";
          }
        ?>
        </div>
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
