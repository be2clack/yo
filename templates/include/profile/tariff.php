
<h3 class="mb35 user-title" > <strong><?php echo $data["page_name"]; ?></strong> </h3>

<div class="user-bg-container" >
 
 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3" >
      <label><?php echo $ULang->t("Тариф"); ?></label>
    </div>
    <div class="col-lg-3" >
      <strong><?php echo $_SESSION["profile"]["tariff"]["services_tariffs_name"]; ?></strong>
    </div>
    <div class="col-lg-6" >
        <div class="profile-button-tariff-box adapt-align-right" >
            <?php echo $Profile->buttonPayTariff(); ?>
        <a class="btn-custom-mini btn-color-blue-light" href="<?php echo _link('tariffs'); ?>" ><?php echo $ULang->t("Изменить тариф"); ?></a>
        </div>
    </div>
 </div>
 </div>

 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3" >
      <label><?php echo $ULang->t("Действует до"); ?></label>
    </div>
    <div class="col-lg-6" >

       <?php
       if($_SESSION["profile"]["tariff"]["services_tariffs_orders_days"]){
          if(strtotime($_SESSION["profile"]["tariff"]["services_tariffs_orders_date_completion"]) > time()){
              ?>
              <span><?php echo date('d.m.Y',strtotime($_SESSION["profile"]["tariff"]["services_tariffs_orders_date_completion"])); ?></span>
              <?php
          }else{
              ?>
              <span style="color: red;" ><?php echo $ULang->t("Срок действия истек"); ?></span>
              <?php
          }
       }else{
          ?>
          <span><?php echo $ULang->t("Срок неограничен"); ?></span>
          <?php
       }
       ?>

    </div>
 </div>
 </div>

 <?php if($_SESSION["profile"]["tariff"]["services_tariffs_orders_days"]){ ?>
 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3" >
      <label><?php echo $ULang->t("Автопродление"); ?></label>
    </div>
    <div class="col-lg-6" >

        <label class="checkbox">
          <input type="checkbox" value="1" class="change-autorenewal-tariff" <?php if($_SESSION["profile"]["data"]["clients_tariff_autorenewal"]){ echo 'checked=""'; } ?> >
          <span></span>
        </label> 

        <div>
            <small><?php echo $ULang->t("Тариф будет автоматически продляться по истечению срока действия, если в кошельке вашего профиля достаточно денег"); ?></small>
        </div>                              

    </div>
 </div>
 </div>
 <?php } ?>

 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3" >
      <label><?php echo $ULang->t("Возможности тарифа"); ?></label>
    </div>
    <div class="col-lg-6" >

        <div class="profile-tariff-features-list" >
            <?php
            if(strtotime($_SESSION["profile"]["tariff"]['services_tariffs_orders_date_completion']) > time() || !$_SESSION["profile"]["tariff"]['services_tariffs_orders_days']){
              if($_SESSION["profile"]["tariff"]["services"]){
                  foreach ($_SESSION["profile"]["tariff"]["services"] as $value) {
                      ?>
                      <span><?php echo $value['services_tariffs_checklist_name']; ?></span>
                      <?php
                  }
              }else{
                  echo $ULang->t("Расширенных возможностей нет");
              }
            }else{
              $getTariff = $Profile->getTariff($_SESSION["profile"]["data"]["clients_tariff_id"]);
              if($getTariff['services']){
                 foreach ($getTariff['services'] as $value) {
                      ?>
                      <span class="line-through" ><?php echo $value['services_tariffs_checklist_name']; ?></span>
                      <?php
                 }
              }
            }
            ?>
        </div>                              

    </div>
 </div>
 </div>

</div>                  

<?