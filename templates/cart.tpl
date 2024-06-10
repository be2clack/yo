<!doctype html>
<html lang="<?php echo getLang(); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo $ULang->t('Корзина товаров'); ?></title>

    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>" data-template="<?php echo $config["template_folder"]; ?>" >
  
  <header class="header-cart" >
   <div class="container" >
         
         <div class="row" >
             <div class="col-lg-2" >
               
                <a class="h-logo" href="<?php echo _link(); ?>" title="<?php echo $ULang->t($settings["title"]); ?>" >
                    <img src="<?php echo $settings["logotip"]; ?>" data-inv="<?php echo $settings["logo_color_inversion"]; ?>" alt="<?php echo $ULang->t($settings["title"]); ?>">
                </a>

             </div>
             <div class="col-lg-10" >
                

             </div>
         </div>

   </div>   
   </header> 

    <div class="container" >
        
        <?php if(!$displaySuccess){ ?>

        <?php if(count($_SESSION['cart'])){ ?>

          <div class="cart-box-1" >

          <div class="row" >
              <div class="col-lg-12" >
                <h2 class="mb30 title-bold" ><?php echo $ULang->t('Корзина товаров'); ?></h2>
              </div>
              <div class="col-lg-8 order-lg-1  order-2" >

                <?php if(!$_SESSION['profile']['id']){ ?>
                <div class="cart-info-auth" >
                  <h4><?php echo $ULang->t('Войдите или зарегистрируйтесь'); ?></h4>
                  <p><?php echo $ULang->t('Для оформления заказа - Вам нужно войти в личный кабинет.'); ?></p>

                  <button class="open-modal" data-id-modal="modal-auth" ><i class="las la-sign-in-alt"></i> <?php echo $ULang->t('Войти'); ?></button>
                </div>
                <?php } ?>
                
                <div class="cart-page-container" >

                    <div class="row" >
                        <div class="col-lg-9 col-8" >
                            <h4 class="mb30 title-bold" ><?php echo $ULang->t('Товары в заказе'); ?></h4>
                        </div>
                        <div class="col-lg-3 col-4 text-right" >
                            <span class="cart-clear cart-page-link-clear" ><?php echo $ULang->t('Очистить'); ?></span>
                        </div>
                    </div>

                    <?php

                          $cart = $Cart->getCart();

                          foreach ($cart as $id => $value) {

                              $count = $value['count'];

                              $image = $Ads->getImages($value['ad']["ads_images"]);

                              $getShop = $Shop->getShop(['user_id'=>$value['ad']["ads_id_user"],'conditions'=>true]);

                              if( $getShop ){
                                  $link = '<a href="'.$Shop->linkShop($getShop["clients_shops_id_hash"]).'" class="cart-goods-item-content-seller"  >'.$getShop["clients_shops_title"].'</a>';
                              }else{
                                  $link = '<a href="'._link( "user/" . $value["ad"]["clients_id_hash"] ).'" class="cart-goods-item-content-seller"  >'.$Profile->name($value['ad']).'</a>';
                              }

                              $price_info = '
                                  <span class="cart-goods-item-content-price" >'.$count.' x '.$Main->price( $value['ad']["ads_price"] ).'</span>
                                  <span class="cart-goods-item-content-price-total" >'.$Main->price( $value['ad']["ads_price"] * $count ).'</span>
                              ';

                              if($value['ad']['ads_available'] == 1){
                                if($settings["main_type_products"] == 'physical'){
                                  $notification_available = '<div class="mt10" >'.$ULang->t('Остался 1 товар').'</div>';
                                }
                              }else{
                                $notification_available = '
                                  <div class="input-group input-group-sm mt10 input-group-change-count">
                                    <div class="input-group-prepend">
                                      <button class="cart-goods-item-count-change" data-action="minus" ><i class="las la-minus"></i></button>
                                    </div>
                                    <input type="text" class="form-control cart-goods-item-count" value="'.$count.'" >
                                    <div class="input-group-append">
                                      <button class="cart-goods-item-count-change" data-action="plus" ><i class="las la-plus"></i></button>
                                    </div>
                                  </div>
                                ';
                              }

                              if( $value['ad']["ads_status"] != 1 || strtotime($value['ad']["ads_period_publication"]) < time() ){
                                  
                                  $status = $Ads->publicationAndStatus($value['ad']);
                                  $group = '';

                              }else{ 

                                  if(!$value['ad']['ads_available_unlimitedly']){

                                       if($value['ad']['ads_available']){
                                          $group = '<div class="cart-goods-item-box-flex" ><div class="cart-goods-item-content-price-info cart-goods-item-box-flex1" >'.$price_info.'</div><div class="cart-goods-item-box-flex2" >'.$notification_available.'</div></div>';
                                       }else{
                                          $group = '<div class="cart-goods-item-box-flex" ><div class="cart-goods-item-content-price-info cart-goods-item-box-flex1" >'.$price_info.'</div><div class="cart-not-available cart-goods-item-box-flex2" >'.$ULang->t('Нет в наличии').'</div></div>';
                                       }

                                  }else{
                                     $group = '<div class="cart-goods-item-box-flex" ><div class="cart-goods-item-content-price-info cart-goods-item-box-flex1" >'.$price_info.'</div><div class="cart-goods-item-box-flex2" >'.$notification_available.'</div></div>';
                                  } 

                                  $status = '';

                              }

                              if($settings["main_type_products"] == 'physical'){
                                if($Ads->getStatusDelivery($value['ad'])){
                                    $status .= '<div class="delivery-status-label status-green" >'.$ULang->t('Доступна доставка').'</div>';
                                }else{
                                    $status .= '<div class="delivery-status-label status-red" >'.$ULang->t('Доставка недоступна').'</div>';
                                }
                              }

                              $items .= '
                                  <div class="cart-goods-item" data-id="'.$value['ad']["ads_id"].'" >

                                      <div class="row" >
                                          <div class="col-lg-2 col-12" >
                                              <div class="cart-goods-item-image" >
                                                <img alt="'.$value['ad']["ads_title"].'" src="'.Exists($config["media"]["small_image_ads"],$image[0],$config["media"]["no_image"]).'"  >
                                              </div>
                                          </div>
                                          <div class="col-lg-10 col-12" >

                                              <div class="cart-goods-item-content" >
                                                <div class="box-status-labels" >'.$status.'</div>
                                                <a href="'.$Ads->alias($value['ad']).'" >'.$value['ad']["ads_title"].'</a>
                                                '.$link.'
                                                '.$group.'
                                              </div>

                                              <div class="text-right" ><span class="cart-goods-item-delete" >'.$ULang->t('Удалить').'</span></div>

                                          </div>               
                                      </div>
                                    
                                  </div>
                              ';

                          }

                          $container = '

                              <div class="cart-goods" >
                                  '.$items.'
                              </div>

                          ';


                      echo $container;
                    ?>

                    <?php if($settings["main_type_products"] == 'physical'){ ?>

                    <h4 class="mb30 mt30 title-bold" ><?php echo $ULang->t('Выберите способ получения'); ?></h4>

                    <form class="form-delivery" >

                    <div class="cart-delivery-box" >
                       <?php if($settings["delivery_service"] == 'boxberry' && $Cart->checkCartDeliveryAds()){ ?>
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

                    <?php if($settings["delivery_service"] == 'boxberry' && $Cart->checkCartDeliveryAds()){ ?>
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
              <div class="col-lg-4 order-lg-2  order-1" >

                <div class="cart-page-container mb15" >

                    <h4 class="title-bold" ><?php echo $ULang->t('Ваш заказ'); ?></h4>

                    <div class="row" >
                        <div class="col-lg-6 col-6" >
                            <span class="cart-page-sidebar-name" ><?php echo $ULang->t('Товаров'); ?></span>
                        </div>
                        <div class="col-lg-6 col-6" >
                            <span class="cart-page-sidebar-value cart-item-counter" ><?php echo $Cart->totalCount(); ?></span>
                        </div>
                    </div>
                  
                    <h6 class="title-bold mt20" ><?php echo $ULang->t('Итого:'); ?></h6>

                    <div class="row" >
                        <div class="col-lg-6 col-6" >
                            <span class="cart-page-sidebar-name" style="margin-top: 7px" ><?php echo $ULang->t('Сумма заказа'); ?></span>
                        </div>
                        <div class="col-lg-6 col-6" >
                            <span class="cart-page-sidebar-value cart-page-sidebar-value-price-itog cart-itog" ><?php echo $Main->price( $Cart->calcTotalPrice() ); ?></span>
                        </div>
                    </div>

                   <?php
                   
                   if(isset($_SESSION['profile']['id'])){

                   if($settings["secure_payment_service_name"]){
                     if($settings["secure_payment_balance"]){
                       ?>
                       <button class="btn-custom btn-color-green width100 mt30 open-modal" data-id-modal="modal-change-payment" > <span><?php echo $ULang->t("Оплатить"); ?></span> </button>
                       <?php
                     }else{
                       ?>
                       <button class="btn-custom btn-color-green width100 cart-payment-order mt30" > <span><?php echo $ULang->t("Перейти к оплате"); ?></span> </button>
                       <?php
                     }
                   }else{
                     ?>
                     <button class="btn-custom btn-color-green width100 cart-payment-balance-order mt30" > <span><?php echo $ULang->t("Оплатить"); ?></span> </button>
                     <?php
                   }
                   ?>

                    <p style="margin-top: 10px; margin-bottom: 0; font-size: 12px;" ><?php echo $ULang->t('Нажимая кнопку «Перейти к оплате», вы соглашаетесь с заключением Договора купли-продажи товаров с использованием Онлайн сервиса «Безопасная сделка»'); ?></p>
                  <?php } ?>

                </div>


              </div>
          </div>

          </div>

        <?php }else{ ?>

            <div class="cart-page-container" >
            <div class="cart-empty" >
              
                <div class="cart-empty-icon" >
                  <i class="las la-shopping-basket"></i>
                  <h5><strong><?php echo $ULang->t('Корзина пуста'); ?></strong></h5>
                </div>         

            </div>
            </div>

        <?php } ?>
         
        <?php }else{ ?>

        <div class="cart-box-2" >
          
          <div class="cart-page-container" >
          <div class="cart-empty" >
            
              <div class="cart-empty-icon" >
                <i class="las la-check"></i>
                <h5><strong><?php echo $ULang->t('Заказ успешно оформлен!'); ?></strong></h5>

                <a class="btn-custom btn-color-green mt15" href="<?php echo _link("user/" . $_SESSION["profile"]["data"]["clients_id_hash"] . "/orders"); ?>" ><?php echo $ULang->t("Перейти к заказам"); ?></a>

              </div>         

          </div>
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
          
          <div class="modal-change-payment-item cart-payment-order" ><?php echo $ULang->t("Через платежную систему"); ?></div>  
          <div class="modal-change-payment-item cart-payment-balance-order" ><?php echo $ULang->t("С баланса"); ?> <strong><?php echo $Main->price($_SESSION['profile']['data']['clients_balance']); ?></strong> </div>

        </div>
    </div>

    <?php include $config["template_path"] . "/footer.tpl"; ?>

    <?php echo $Geo->vendorMap(); ?>

  </body>
</html>