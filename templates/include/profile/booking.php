
<h3 class="mt35 mb35 user-title" > <strong><?php echo $ULang->t("Бронирования"); ?></strong> </h3>

<?php
if($data["orders"]["booking"]){
  ?>
  <div class="row" >
  <?php
    foreach ($data["orders"]["booking"] as $key => $value) {
       include $config["template_path"] . "/include/booking_order_list.php";
    }
  ?>
  </div>
  <?php
}else{
  ?>
     <div class="user-block-no-result" >

        <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
        <p><?php echo $ULang->t("Заказы по бронированию будут отображаться на этой странице."); ?></p>
       
     </div>                            
  <?php
}