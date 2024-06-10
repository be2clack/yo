<!doctype html>
<html lang="<?php echo getLang(); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo $ULang->t("Оформление и оплата"); ?></title>

    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>" data-template="<?php echo $config["template_folder"]; ?>" >
    
    <?php include $config["template_path"] . "/header.tpl"; ?>

    <div class="container mt35" >
        
        <?php if( !$data["order"] ){ ?>
        <div class="row" >
            <div class="col-lg-8" >
              
              <div class="bg-container mb15" >

                <h3> <strong><?php echo $ULang->t("Оформление и оплата"); ?></strong> </h3>

                <div class="buy-item-product mt30" >
                  <div class="buy-item-product-img" >
                    <img alt="<?php echo $data["ad"]["ads_title"]; ?>" src="<?php echo Exists($config["media"]["big_image_ads"],$data["ad"]["ads_images"][0],$config["media"]["no_image"]); ?>" >
                  </div>
                  <div class="buy-item-product-title" >
                     <strong><?php echo $Main->price( $data["ad"]["ads_price"] ); ?></strong><br>
                     <a href="<?php echo $Ads->alias($data["ad"]); ?>" target="_blank" ><?php echo $data["ad"]["ads_title"]; ?></a>
                  </div>
                </div>

                <div class="clr" ></div>

                <?php if($settings["main_type_products"] == 'physical'){ ?>

                    <h4 class="mb30 mt30 title-bold" ><?php echo $ULang->t('Выберите способ получения'); ?></h4>

                    <form class="form-delivery" >

                    <div class="cart-delivery-box" >
                      <?php if($settings["delivery_service"] == 'boxberry' && $Ads->getStatusDelivery($data["ad"])){ ?>
                      <div class="cart-delivery-box-item" data-type="boxberry" >
                          <div>
                            <div><strong><?php echo $ULang->t('Доставка в пункт Boxberry'); ?></strong></div> 
                            <div><?php echo $ULang->t('Оплата доставки при получении'); ?></div>                         
                          </div>
                      </div>
                      <?php } ?>
                      <div class="cart-delivery-box-item" data-type="self" >
                          <div>
                            <div><strong><?php echo $ULang->t('Заберу сам у продавца'); ?></strong></div>
                            <div><?php echo $ULang->t('Бесплатно'); ?></div>                            
                          </div>
                      </div>                       
                    </div>

                    <?php if($settings["delivery_service"] == 'boxberry' && $Ads->getStatusDelivery($data["ad"])){ ?>
                    <div class="cart-delivery-box-item-boxberry" >

                      <div class="mt30 mb15" ><strong><?php echo $ULang->t('Пункт выдачи'); ?></strong></div>

                      <div class="container-delivery-point" ></div>

                      <div class="btn-custom-mini btn-color-blue open-modal action-change-delivery-point" data-id-modal="modal-delivery-point" ><?php echo $ULang->t('Выбрать'); ?></div>

                      <div class="mt15" ><strong><?php echo $ULang->t('Данные покупателя'); ?></strong></div>

                      <div class="row mt15" >
                          <div class="col-lg-4" ><input type="text" name="delivery_surname" class="form-control" placeholder="<?php echo $ULang->t('Фамилия'); ?>" value="<?php echo $_SESSION['profile']['data']['clients_surname']; ?>"></div>
                          <div class="col-lg-4" ><input type="text" name="delivery_name" class="form-control" placeholder="<?php echo $ULang->t('Имя'); ?>" value="<?php echo $_SESSION['profile']['data']['clients_name']; ?>"></div>
                          <div class="col-lg-4" ><input type="text" name="delivery_patronymic" class="form-control" placeholder="<?php echo $ULang->t('Отчество'); ?>" value="<?php echo $_SESSION['profile']['data']['clients_patronymic']; ?>"></div>
                      </div>

                      <div class="row mt15" >
                          <div class="col-lg-4" ><input type="text" name="delivery_phone" class="form-control" placeholder="<?php echo $ULang->t('Номер телефона'); ?>" value="<?php echo $_SESSION['profile']['data']['clients_phone']; ?>"></div>
                      </div>

                      <div class="mt15" ><?php echo $ULang->t('На указанный номер телефона будут приходить SMS сообщения о статусе доставки и оплаты. Другие пользователи не увидят этот номер телефона.'); ?></div>
                    </div>
                    <?php } ?>

                    <div class="cart-delivery-box-item-self" >
                      <?php echo $ULang->t("Договаривайтесь с продавцом о месте и времени передачи товара самостоятельно. Деньги за оплату товара продавец получит только после того, как вы подтвердите успешное получение товара. А в случае возникновения спорной ситуации, уладить возникшие разногласия поможет сервис «Безопасная сделка»."); ?>
                    </div>

                    <input type="hidden" name="delivery_id_point" value="" >
                    <input type="hidden" name="delivery_type" value="" >
                    </form>

                <?php }else{ ?>

                      <p class="info-text mt30" ><?php echo $ULang->t("Доступ к контенту будет после оплаты в карточке заказа. Деньги за оплату продавец получит только после того, как вы подтвердите успешное получение электронного контента. А в случае возникновения спорной ситуации, уладить возникшие разногласия поможет сервис «Безопасная сделка»."); ?></p>

                <?php } ?>

              </div>

            </div>
            <div class="col-lg-4" >

               <div class="buy-sidebar" >
                   
                   <h3> <strong><?php echo $Main->price( $data["ad"]["ads_price"] ); ?></strong> </h3>

                   <?php
                   if($settings["secure_payment_service_name"]){
                     if($settings["secure_payment_balance"]){
                       ?>
                       <button class="btn-custom btn-color-green mt15 mb15 width100 open-modal" data-id-modal="modal-change-payment" > <span><?php echo $ULang->t("Оплатить"); ?></span> </button>
                       <?php
                     }else{
                       ?>
                       <button class="btn-custom btn-color-green mt15 mb15 go-to-payment-order width100" data-id-ad="<?php echo $data["ad"]["ads_id"]; ?>" > <span><?php echo $ULang->t("Перейти к оплате"); ?></span> </button>
                       <?php
                     }
                   }else{
                     ?>
                     <button class="btn-custom btn-color-green mt15 mb15 go-to-payment-balance-order width100" data-id-ad="<?php echo $data["ad"]["ads_id"]; ?>" > <span><?php echo $ULang->t("Оплатить"); ?></span> </button>
                     <?php
                   }
                   ?>

                   <p class="buy-secure-label" > <i class="las la-shield-alt"></i> <a href="<?php echo _link("promo/secure"); ?>" target="_blank" ><?php echo $ULang->t("Как работает безопасная сделка?"); ?></a> </p>

                   <p><?php echo $ULang->t("Нажимая кнопку «Перейти к оплате», вы соглашаетесь с заключением Договора купли-продажи товаров с использованием Онлайн сервиса «Безопасная сделка»"); ?></p>

               </div>
              
            </div>
        </div>
        <?php }else{ ?>
        <div class="row" >
           <div class="col-lg-12" >
             <h4 class="text-center mt30" ><?php echo $ULang->t("Заказ уже оплачивается другим пользователем!"); ?></h4>
             <p class="text-center" ><?php echo $ULang->t("Если заказ не будет оплачен в течении"); ?> <?php echo $Ads->timeSecureReservation( $data["order"]["secure_date"] ); ?>, <?php echo $ULang->t("то вы сможете оформить заказ."); ?></p>
           </div>
        </div>
        <?php } ?>
         
          
       <div class="mt50" ></div>


    </div>

    <div class="modal-custom-bg" style="display: none;" id="modal-change-payment" >
        <div class="modal-custom animation-modal" style="max-width: 400px" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>
          
          <h4><?php echo $ULang->t("Выберите способ оплаты"); ?></h4>

          <div class="mt20" ></div>    
          
          <div class="modal-change-payment-item go-to-payment-order" data-id-ad="<?php echo $data["ad"]["ads_id"]; ?>" ><?php echo $ULang->t("Через платежную систему"); ?></div>  
          <div class="modal-change-payment-item go-to-payment-balance-order" data-id-ad="<?php echo $data["ad"]["ads_id"]; ?>" ><?php echo $ULang->t("С баланса"); ?> <strong><?php echo $Main->price($_SESSION['profile']['data']['clients_balance']); ?></strong> </div>

        </div>
    </div>

    <?php include $config["template_path"] . "/footer.tpl"; ?>

    <?php echo $Geo->vendorMap(); ?>

  </body>
</html>