<!doctype html>
<html lang="<?php echo getLang(); ?>">
  <head>
    <meta charset="utf-8">
    
    <title><?php echo $ULang->t("Настройка тарифа"); ?></title>
    
    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>" data-template="<?php echo $config["template_folder"]; ?>">

    <?php include $config["template_path"] . "/header.tpl"; ?>

    <div class="container" >
       
       <nav aria-label="breadcrumb" class="mt15" >
 
          <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">

            <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
              <a itemprop="item" href="<?php echo _link(); ?>">
              <span itemprop="name"><?php echo $ULang->t("Главная"); ?></span></a>
              <meta itemprop="position" content="1">
            </li>
            
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
              <span itemprop="name"><?php echo $ULang->t("Настройка тарифа"); ?></span>
              <meta itemprop="position" content="3">
            </li>
                             
          </ol>

        </nav>
        
        <div class="mt40" ></div>

        <div class="row" >
           <div class="col-lg-9 order-lg-1 order-2" >

              <form class="settings-tariff-form" >

                   <h3 class="mb35 user-title" > <strong><?php echo $ULang->t("Настройка тарифа"); ?></strong> </h3>

                   <div class="row mb35 mt35" >
                       <div class="col-lg-8 col-12" >
                           <h4 class="user-podtitle" > <strong><?php echo $ULang->t("Выберите тариф"); ?></strong> </h4>
                       </div>
                       <div class="col-lg-4 col-12 adapt-align-right" >
                           <?php if($_SESSION['profile']['data']['clients_tariff_id']){ ?>
                           <span class="btn-custom-mini btn-color-light open-modal" data-id-modal="modal-tariff-confirm-delete" ><?php echo $ULang->t("Удалить мой тариф"); ?></span>
                           <?php } ?>
                       </div>
                   </div>

                   <?php if(count($data['tariffs'])){ ?>
                   <div class="user-tariff-box" >
                       <div class="init-slider-tariff" >

                           <?php
                           foreach ($data['tariffs'] as $value) {

                               $services = json_decode($value['services_tariffs_services'], true);
                               ?>

                                   <div class="user-tariff-box-item <?php if($_SESSION['profile']['data']['clients_tariff_id'] == $value['services_tariffs_id']){ echo 'user-tariff-box-item-gray active'; } ?>" data-id="<?php echo $value['services_tariffs_id']; ?>" >
                                       <h5><?php echo $ULang->t($value['services_tariffs_name']); ?></h5>
                                       <?php if($value['services_tariffs_price']){ ?>

                                       <p><strong><?php echo $Main->outPrices( array("new_price"=> array("price"=>$value["services_tariffs_new_price"], "tpl"=>'<span class="tariff_newPrice" >{price}</span>'), "price"=>array("price"=>$value["services_tariffs_price"], "tpl"=>'<span class="tariff_oldPrice" >{price}</span>') ) ); ?></strong> <?php if($value['services_tariffs_days']){ ?> <span><?php echo $ULang->t('за'); ?> <?php echo $value['services_tariffs_days']; ?> <?php echo ending($value['services_tariffs_days'],$ULang->t('день'),$ULang->t('дня'),$ULang->t('дней')); ?></span> <?php }else{ ?><span><?php echo $ULang->t('на неограниченный срок'); ?></span><?php } ?> </p>
                                       <?php }else{ ?>
                                       <p><strong><?php echo $ULang->t("Бесплатно"); ?></strong> <?php if($value['services_tariffs_days']){ ?><span><?php echo $ULang->t("на"); ?> <?php echo $value['services_tariffs_days']; ?> <?php echo ending($value['services_tariffs_days'],$ULang->t('день'),$ULang->t('дня'),$ULang->t('дней')); ?></span><?php }else{ ?> <span><?php echo $ULang->t('на неограниченный срок'); ?></span> <?php } ?></p>
                                       <?php } ?>
                                       <p><?php echo $ULang->t($value['services_tariffs_desc']); ?></p>
                                       <?php if($value['services_tariffs_bonus'] || count($services)){ ?>
                                       <hr>
                                       <ul>
                                           <?php if($value['services_tariffs_bonus']){ ?>
                                           <li><span class="user-tariff-box-item-service-icon" ><i class="las la-plus"></i></span><span class="user-tariff-box-item-service-name" ><strong><?php echo $Main->price($value['services_tariffs_bonus']); ?></strong> <?php echo $ULang->t("на бонусный счет"); ?></span></li>
                                           <?php
                                           }
                                           if(count($services)){
                                               foreach ($services as $service_id) {
                                                   $checklist = findOne('uni_services_tariffs_checklist', 'services_tariffs_checklist_id=?', [$service_id]);
                                                   ?>
                                                   <li><span class="user-tariff-box-item-service-icon" ><i class="las la-check"></i></span><span class="user-tariff-box-item-service-name" ><?php echo $ULang->t($checklist['services_tariffs_checklist_name'], [ "table"=>"uni_services_tariffs_checklist", "field"=>"services_tariffs_checklist_name" ]); ?>
                                                   <?php if($checklist['services_tariffs_checklist_desc']){ ?>
                                                   <i data-tippy-placement="top" title="<?php echo $ULang->t($checklist['services_tariffs_checklist_desc'], [ "table"=>"uni_services_tariffs_checklist", "field"=>"services_tariffs_checklist_desc" ]); ?>" class="las la-info-circle"></i>
                                                   <?php } ?>
                                                   </span></li>
                                                   <?php
                                               }
                                           }
                                           ?>
                                       </ul>
                                       <?php } ?>
                                   </div>

                               <?php
                           }
                           ?>
                      
                       </div>
                   </div>
                   <?php
                   }else{
                      ?>
                      <p><strong><?php echo $ULang->t("Тарифов нет"); ?></strong></p>
                      <?php
                   }
                   ?>

                   <input type="hidden" name="tariff_id" value="<?php echo $_SESSION['profile']['data']['clients_tariff_id']; ?>" >
             
             </form>

           </div>
           <div class="col-lg-3 order-lg-2 order-1" >

              <div class="settings-tariff-sidebar" >
                  <div class="settings-tariff-sidebar-calc" >

                      <h5><?php echo $ULang->t("Стоимость"); ?></h5>

                      <div class="settings-tariff-sidebar-calc-item" >
                          <div class="settings-tariff-sidebar-calc-item1" ><?php echo $ULang->t("Тариф"); ?></div>
                          <div class="settings-tariff-sidebar-calc-item2 settings-tariff-sidebar-calc-item-price" >-</div>
                      </div>

                      <hr>

                      <div class="settings-tariff-sidebar-calc-item" >
                          <div class="settings-tariff-sidebar-calc-item1" ><strong><?php echo $ULang->t("Итого"); ?></strong></div>
                          <div class="settings-tariff-sidebar-calc-item2" ><strong class="settings-tariff-sidebar-calc-item-itog" >-</strong></div>
                      </div>                       

                      <span class="btn-custom-mini btn-color-blue mt15 width100 settings-tariff-sidebar-calc-activate" ></span>

                      <?php if($_SESSION["profile"]["id"]){ ?>
                      <div class="custom-control custom-checkbox mt15">
                          <input type="checkbox" class="custom-control-input change-autorenewal-tariff" id="renewal" <?php if($_SESSION["profile"]["data"]["clients_tariff_autorenewal"]){ echo 'checked=""'; } ?> value="1" >
                          <label class="custom-control-label" for="renewal"><strong><?php echo $ULang->t("Автопродление тарифа"); ?></strong></label>
                          <div class="mt5" ><small><?php echo $ULang->t("Тариф будет автоматически продляться по истечению срока действия, если в кошельке вашего профиля достаточно денег"); ?></small></div>
                      </div>
                      <?php } ?>

                  </div>
              </div>

           </div>    


    </div>

    </div>
    
    <div class="mt35" ></div>
 
    <div class="modal-custom-bg" style="display: none;" id="modal-tariff-confirm-delete" >
        <div class="modal-custom animation-modal" style="max-width: 400px" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>
          
          <div class="modal-confirm-content" >
              <h4><?php echo $ULang->t("Вы действительно хотите удалить тариф?"); ?></h4>   
              <p><?php echo $ULang->t("Деньги за оставшийся период не возвращаются!"); ?></p>         
          </div>

          <div class="mt30" ></div>

          <div class="modal-custom-button" >
             <div>
               <button class="button-style-custom btn-color-danger settings-tariff-delete" ><?php echo $ULang->t("Удалить"); ?></button>
             </div> 
             <div>
               <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Отменить"); ?></button>
             </div>                                       
          </div>

        </div>
    </div>

    <?php include $config["template_path"] . "/footer.tpl"; ?>

  </body>
</html>