<!doctype html>
<html lang="<?php echo $settings["lang_site_default"]; ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo $ULang->t("Заказ"); ?> №<?php echo $data["order"]["secure_id_order"]; ?></title>

    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>" data-template="<?php echo $config["template_folder"]; ?>" >
    
    <?php include $config["template_path"] . "/header.tpl"; ?>

    <div class="container" >
       
        <h2 class="mt30 mb30" > <strong><?php echo $ULang->t("Заказ"); ?> №<?php echo $data["order"]["secure_id_order"]; ?></strong> </h2>
          
        <div class="row" >
            <div class="col-lg-12" >

              <div class="bg-container" >
                
                <div class="row" >
                   <div class="col-lg-2" > <label><?php echo $ULang->t("Статус"); ?></label> </div>
                   <div class="col-lg-9" >
                    
                    <?php if($data["order"]["secure_status"] == 0){ ?>

                      <span class="order-status" > <?php echo $ULang->t("Ожидается оплата"); ?> </span>
                      <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["secure_date"]); ?> </span>
                      
                      <?php if( $data["order"]["secure_id_user_buyer"] == $_SESSION["profile"]["id"] ){ ?>

                      <?php if($settings["main_type_products"] == 'physical'){ ?>
                        <p class="mt10" ><?php echo $ULang->t("Деньги будут зарезервированы до получения товара"); ?></p>
                      <?php }else{ ?>
                        <p class="mt10" ><?php echo $ULang->t("Деньги будут зарезервированы до получения контента"); ?></p>
                      <?php } ?>

                       <?php
                       if($settings["secure_payment_service_name"]){
                         if($settings["secure_payment_balance"]){
                           ?>
                           <button class="btn-custom-mini btn-color-blue mt5 open-modal" data-id-modal="modal-change-payment" > <span><?php echo $ULang->t("Оплатить"); ?></span> </button>
                           <?php
                         }else{
                           ?>
                           <button class="btn-custom-mini btn-color-blue mt5 go-to-payment-order" data-id-ad="<?php echo $data["order"]["ad"]["secure_ads_ad_id"]; ?>" > <span><?php echo $ULang->t("Перейти к оплате"); ?></span> </button>
                           <?php
                         }
                       }else{
                         ?>
                         <button class="btn-custom-mini btn-color-blue mt5 go-to-payment-balance-order" data-id-ad="<?php echo $data["order"]["ad"]["secure_ads_ad_id"]; ?>" > <span><?php echo $ULang->t("Оплатить"); ?></span> </button>
                         <?php
                       }
                       ?>
                      
                      <p class="mt10" ><?php echo $ULang->t("Произведите оплату, или заказ будет удален через"); ?> <?php echo $Ads->timeSecureReservation( $data["order"]["secure_date"] ); ?></p>
                      <?php }else{ ?>
                      <p class="mt10" ><?php echo $ULang->t("Если заказ не будет оплачен, то он будет удален через"); ?> <?php echo $Ads->timeSecureReservation( $data["order"]["secure_date"] ); ?></p>
                      <?php } ?>

                    <?php 
                    }elseif($data["order"]["secure_status"] == 1){

                      if($data["order"]["secure_id_user_buyer"] == $_SESSION["profile"]["id"]){

                        if($settings["main_type_products"] == 'physical'){

                            if($data["order"]["secure_delivery_type"] == 'self'){
                                ?>
                                <span class="order-status" > <?php echo $ULang->t("Заказ оплачен"); ?> </span>
                                <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["secure_date"]); ?> </span>
                                <p class="mt10"><?php echo $Main->price( $data["order"]["secure_price"] ); ?> <?php echo $ULang->t("зарезервированы до получения товара/услуги."); ?></p>
                                <?php
                            }else{
                                ?>
                                <span class="order-status" > <?php echo $ULang->t("Заказ оплачен. Товар будет отправлен через службу доставки"); ?> </span>
                                <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["secure_date"]); ?> </span>
                                <p class="mt10"><?php echo $Main->price( $data["order"]["secure_price"] ); ?> <?php echo $ULang->t("зарезервированы до получения товара."); ?></p>

                                <div class="info-text mt10"><?php echo $ULang->t("Трек номер"); ?> <strong><?php echo $data["order"]["secure_delivery_track_number"]; ?></strong> <?php echo $ULang->t("по нему вы можете отслеживать статус доставки"); ?> <a href="https://boxberry.ru/tracking-page" target="_blank" ><?php echo $ULang->t("Проверить статус"); ?></a></div>
                                <?php                                
                            }

                        }else{
                            ?>
                            <span class="order-status" > <?php echo $ULang->t("Заказ оплачен."); ?> </span>
                            <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["secure_date"]); ?> </span>

                            <p class="mt15"><?php echo $ULang->t("Подтвердите выполнение заказа или откройте спор. После подтверждения продавец получит деньги. Автоматическое подтверждение через 24 часа."); ?></p>    

                            <div class="row" >
                               <div class="col-lg-3 col-md-6 col-sm-6 col-12" > <button class="btn-custom-mini btn-color-green mt5 open-modal width100 mb5" data-id-modal="modal-confirm-receive-goods" > <span><?php echo $ULang->t("Подтвердить"); ?></span> </button> </div>
                               <div class="col-lg-3 col-md-6 col-sm-6 col-12" > <button class="btn-custom-mini btn-color-light mt5 open-modal width100 mb5" data-id-modal="modal-dispute-secure" > <span><?php echo $ULang->t("Открыть спор"); ?></span> </button> </div>
                            </div>
                            <?php                            
                        }

                      }elseif($data["order"]["secure_id_user_seller"] == $_SESSION["profile"]["id"]){
                        
                        if($settings["main_type_products"] == 'physical'){

                            if($data["order"]["secure_delivery_type"] == 'self'){

                                ?>
                                <span class="order-status" > <?php echo $ULang->t("Заказ оплачен. Ждет выполнения заказа"); ?> </span>
                                <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["secure_date"]); ?> </span>
                                <p class="mt10"><?php echo $Main->price( $data["order"]["commission_and_price"] ); ?> <?php echo $ULang->t("зарезервированы до передачи товара или выполнения услуги"); ?></p>
                                <button class="btn-custom-mini btn-color-green mt5 open-modal" data-id-modal="modal-confirm-transfer-goods" > <span><?php echo $ULang->t("Подтвердить"); ?></span> </button>
                                <?php

                            }else{

                                ?>
                                <span class="order-status" > <?php echo $ULang->t("Заказ оплачен. Отправьте товар покупателю."); ?> </span>
                                <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["secure_date"]); ?> </span>
                                <p class="mt10"><?php echo $Main->price( $data["order"]["commission_and_price"] ); ?> <?php echo $ULang->t("зарезервированы до получения товара."); ?></p>
                                
                                <div class="info-text mt10"><?php echo $ULang->t("Номер накладной"); ?> <strong><?php echo $data["order"]["secure_delivery_invoice_number"]; ?></strong> <?php echo $ULang->t("предъявите его вместе с товаром в пункте приема по адресу"); ?> <strong><?php echo $data["user"]["delivery_point_send"]["boxberry_points_address"]; ?></strong></div>

                                <button class="btn-custom-mini btn-color-green mt15 open-modal" data-id-modal="modal-confirm-transfer-goods" > <span><?php echo $ULang->t("Подтвердить отправку товара"); ?></span> </button>
                                <?php

                            }

                        }else{
                            ?>
                            <span class="order-status" > <?php echo $ULang->t("Заказ оплачен."); ?> </span>
                            <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["secure_date"]); ?> </span>
                            <p class="mt10"><?php echo $Main->price( $data["order"]["commission_and_price"] ); ?> <?php echo $ULang->t("будут зачислены на ваш счет в течении 24 часа, после того как покупатель подтвердит получение товара/услуги."); ?></p>
                            <?php                            
                        }

                      }

                    }elseif($data["order"]["secure_status"] == 2){

                      if( $data["order"]["secure_id_user_buyer"] == $_SESSION["profile"]["id"] ){

                        if($data["order"]["secure_delivery_type"] == 'self'){

                            ?>
                            <span class="order-status" > <?php echo $ULang->t("Заказ выполнен"); ?> </span>
                            <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["secure_date"]); ?> </span>
                            <p class="mt10"><?php echo $ULang->t("Продавец выполнил заказ? Подтвердите или откройте спор."); ?></p>    

                            <div class="row" >
                               <div class="col-lg-3 col-md-6 col-sm-6 col-12" > <button class="btn-custom-mini btn-color-green mt5 open-modal width100 mb5" data-id-modal="modal-confirm-receive-goods" > <span><?php echo $ULang->t("Подтвердить"); ?></span> </button> </div>
                               <div class="col-lg-3 col-md-6 col-sm-6 col-12" > <button class="btn-custom-mini btn-color-light mt5 open-modal width100 mb5" data-id-modal="modal-dispute-secure" > <span><?php echo $ULang->t("Открыть спор"); ?></span> </button> </div>
                            </div>                    
                            <?php

                        }else{

                            ?>
                            <span class="order-status" > <?php echo $ULang->t("Заказ оплачен. Товар будет отправлен через службу доставки"); ?> </span>
                            <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["secure_date"]); ?> </span>
                            <p class="mt10"><?php echo $ULang->t("Как получите посылку подтвердите получение или откройте спор."); ?></p>   

                            <div class="info-text mt10 mb10"><?php echo $ULang->t("Трек номер"); ?> <strong><?php echo $data["order"]["secure_delivery_track_number"]; ?></strong> <?php echo $ULang->t("по нему вы можете отслеживать статус доставки"); ?> <a href="https://boxberry.ru/tracking-page" target="_blank" ><?php echo $ULang->t("Проверить статус"); ?></a></div>

                            <div class="row" >
                               <div class="col-lg-3 col-md-6 col-sm-6 col-12" > <button class="btn-custom-mini btn-color-green mt5 open-modal width100 mb5" data-id-modal="modal-confirm-receive-goods" > <span><?php echo $ULang->t("Подтвердить"); ?></span> </button> </div>
                               <div class="col-lg-3 col-md-6 col-sm-6 col-12" > <button class="btn-custom-mini btn-color-light mt5 open-modal width100 mb5" data-id-modal="modal-dispute-secure" > <span><?php echo $ULang->t("Открыть спор"); ?></span> </button> </div>
                            </div>                    
                            <?php

                        }

                      }elseif( $data["order"]["secure_id_user_seller"] == $_SESSION["profile"]["id"] ){
                        
                        if($data["order"]["secure_delivery_type"] == 'self'){
                            ?>
                            <span class="order-status" > <?php echo $ULang->t("Заказ выполнен. Ожидаем подтверждение покупателя."); ?> </span>
                            <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["secure_date"]); ?> </span>
                            <p class="mt10"><?php echo $Main->price( $data["order"]["commission_and_price"] ); ?> <?php echo $ULang->t("будут зачислены на ваш счет в течении 24 часа, после того как покупатель подтвердит выполнение заказа."); ?></p>                        
                            <?php
                        }else{
                            ?>
                            <span class="order-status" > <?php echo $ULang->t("Заказ оплачен. Отправьте товар покупателю."); ?> </span>
                            <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["secure_date"]); ?> </span>
                            <p class="mt10"><?php echo $Main->price( $data["order"]["commission_and_price"] ); ?> <?php echo $ULang->t("будут зачислены на ваш счет в течении 24 часа, после того как покупатель подтвердит получение товара."); ?></p>
                            
                            <div class="info-text mt10"><?php echo $ULang->t("Номер накладной"); ?> <strong><?php echo $data["order"]["secure_delivery_invoice_number"]; ?></strong> <?php echo $ULang->t("предъявите его вместе с товаром в пункте приема по адресу"); ?> <strong><?php echo $data["user"]["delivery_point_send"]["boxberry_points_address"]; ?></strong></div>
                            <?php
                        }

                      }

                    }elseif($data["order"]["secure_status"] == 3){

                      if( $data["order"]["secure_id_user_buyer"] == $_SESSION["profile"]["id"] ){

                        ?>
                        <span class="order-status" style="color: rgb(119, 192, 38);" > <?php echo $ULang->t("Заказ завершён."); ?> </span>
                        <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["secure_date"]); ?> </span>  

                        <a class="btn-custom-mini btn-color-green mt15" href="<?php echo _link( "user/" . $data["user"]["clients_id_hash"] . "/reviews" ); ?>" > <span><?php echo $ULang->t("Оставить отзыв о продавце"); ?></span> </a>  
                        <?php
                        
                        if( $data["disputes"]["secure_disputes_status"] == 1 || $data["disputes"]["secure_disputes_status"] == 2 ){
                            echo $Ads->secureResultPay( [ "id_user"=>$data["order"]["secure_id_user_buyer"],"id_order"=>$data["order"]["secure_id_order"] ] );
                        }

                      }elseif( $data["order"]["secure_id_user_seller"] == $_SESSION["profile"]["id"] ){
                        
                        ?>
                        <span class="order-status" style="color: rgb(119, 192, 38);" > <?php echo $ULang->t("Заказ завершён."); ?> </span>
                        <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["secure_date"]); ?> </span>

                        <a class="btn-custom-mini btn-color-green mt15" href="<?php echo _link( "user/" . $data["user"]["clients_id_hash"] . "/reviews" ); ?>" > <span><?php echo $ULang->t("Оставить отзыв о покупателе"); ?></span> </a>
                        <?php

                        echo $Ads->secureResultPay( [ "id_user"=>$data["order"]["secure_id_user_seller"],"id_order"=>$data["order"]["secure_id_order"] ] );

                      }

                    }elseif($data["order"]["secure_status"] == 4){
                        ?>

                         <span class="order-status" > <?php echo $ULang->t("Открыт спор"); ?> </span>
                         <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["secure_date"]); ?> </span>

                         <p class="mt10"><?php echo $ULang->t("Открыт спор с участием арбитра. Арбитр приступил к изучению деталей спора."); ?></p>
                         
                         <?php if($data["disputes"]["secure_disputes_text"]){ ?>
                         <div><strong><?php echo $ULang->t("Комментарий"); ?></strong></div>
                         <p class="mt10"><?php echo $data["disputes"]["secure_disputes_text"]; ?></p>
                         <?php } ?>
                         
                         <?php 

                    }elseif($data["order"]["secure_status"] == 5){
                        ?>

                         <span class="order-status" > <?php echo $ULang->t("Заказ отменен"); ?> </span>
                         <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["secure_date"]); ?> </span>

                        <?php

                        if( $data["order"]["secure_id_user_buyer"] == $_SESSION["profile"]["id"] ){
                           
                           echo $Ads->secureResultPay( [ "id_user"=>$data["order"]["secure_id_user_buyer"],"id_order"=>$data["order"]["secure_id_order"] ] );

                        }

                    }

                    if( $data["disputes"]["secure_disputes_text_arbitr"] && $data["order"]["secure_status"] != 4 ){
                        ?>
                        <div style="margin-top: 15px;" >
                        <strong><?php echo $ULang->t("Решение арбитра:"); ?></strong>
                        <p><?php echo $data["disputes"]["secure_disputes_text_arbitr"]; ?></p>
                        </div>
                        <?php
                    }
                    ?>

                   </div>
                </div>

                <hr>
                
                <div class="row" >
                <?php if($data["order"]["secure_id_user_seller"] == $_SESSION["profile"]["id"]){ ?>
                
                   <div class="col-lg-2" > <label><?php echo $ULang->t("Покупатель"); ?></label> </div>
                   <div class="col-lg-9" >
                      <?php echo $Profile->cardUserOrder($data); ?>
                      <span class="btn-custom-mini btn-color-blue mt15 open-modal ad-init-message" data-id-user="<?php echo $data["order"]["secure_id_user_buyer"]; ?>" data-id-modal="modal-chat-user" > <span><?php echo $ULang->t("Написать покупателю"); ?></span> </span>
                   </div>

               <?php }elseif($data["order"]["secure_id_user_buyer"] == $_SESSION["profile"]["id"]){ ?>

                  <div class="col-lg-2" > <label><?php echo $ULang->t("Продавец"); ?></label> </div>
                  <div class="col-lg-9" >
                    <?php echo $Profile->cardUserOrder($data); ?>
                    <span class="btn-custom-mini btn-color-blue mt15 open-modal ad-init-message" data-id-user="<?php echo $data["order"]["secure_id_user_seller"]; ?>" data-id-modal="modal-chat-user" > <span><?php echo $ULang->t("Написать продавцу"); ?></span> </span>
                  </div>  

               <?php } ?>
               </div>

               <hr>

                <div class="row" >
                   <div class="col-lg-2" > <label><?php echo $ULang->t("Товары"); ?></label> </div>
                   <div class="col-lg-9" >
                      <?php
                        $getAds = getAll('select * from uni_secure_ads where secure_ads_order_id=?', [$data["order"]["secure_id_order"]]);
                        if(count($getAds)){
                            foreach ($getAds as $value) {

                                $getAd = $Ads->get("ads_id=?", [$value['secure_ads_ad_id']]);
                                
                                if($getAd){
                                    $image = $Ads->getImages($getAd["ads_images"]);
                                    ?>
                                    <div style="margin-bottom: 10px;" >

                                      <div class="order-product-box-left2" >

                                        <div class="board-view-ads-img" >
                                          <img src="<?php echo Exists($config["media"]["big_image_ads"],$image[0],$config["media"]["no_image"]); ?>">
                                        </div>

                                      </div>

                                      <div class="order-product-box-right" >

                                        <a href="<?php echo $Ads->alias($getAd); ?>"  ><?php echo $getAd["ads_title"]; ?></a>
                                        <br>
                                        <div class="order-product-box-right-span" >

                                            <?php if($settings["main_type_products"] == 'physical'){ ?>
                                            <span><?php echo $ULang->t("Количество"); ?>: <?php echo $value["secure_ads_count"]; ?></span>
                                            <?php } ?>

                                            <span><?php echo $ULang->t("Сумма"); ?>: <?php echo $Main->price($value["secure_ads_total"]); ?></span>
                                        </div>

                                      </div>

                                      <div class="clr" ></div>

                                      <?php if($data["order"]["secure_status"] != 0 && $data["order"]["secure_id_user_buyer"] == $_SESSION["profile"]["id"] && $settings["main_type_products"] == 'electron'){ ?>
                                      <div class="order-electron-box-content info-text mt10" >
                                            <?php
                                            $getAd["ads_electron_product_links"] = explode(',', $getAd["ads_electron_product_links"]);
                                            foreach ($getAd["ads_electron_product_links"] as $link) {
                                                ?>
                                                <a href="<?php echo $link; ?>"><?php echo $link; ?></a>
                                                <?php
                                            }
                                            if($getAd["ads_electron_product_text"]){
                                                ?>
                                                <div class="mt10" ></div>
                                                <?php
                                                echo $getAd["ads_electron_product_text"];
                                            }
                                            ?>
                                      </div>
                                      <?php } ?>

                                    </div>
                                    <?php
                                }

                            }
                        }
                      ?>                    
                   </div>
                </div>

                <hr>

                <div class="row mb10" >
                   <div class="col-lg-2" > <label><?php echo $ULang->t("Сумма заказа"); ?></label> </div>
                   <div class="col-lg-9" >
                      <h6><?php echo $Main->price( $data["order"]["secure_price"] ); ?></h6>
                   </div>
                </div>   

                <?php if( $data["order"]["secure_id_user_seller"] == $_SESSION["profile"]["id"] ){ ?>             
                
                <?php if($data["order"]["commission"]){ ?>
                <div class="row mb10" >
                   <div class="col-lg-2" > <label><?php echo $ULang->t("Комиссия сервиса"); ?></label> </div>
                   <div class="col-lg-9" >
                      <?php echo "-" . $Main->price( $data["order"]["commission"] ); ?>
                   </div>
                </div>
                <?php } ?>

                <div class="row" >
                   <div class="col-lg-2" > <label><strong><?php echo $ULang->t("Итого"); ?></strong></label> </div>
                   <div class="col-lg-9" >
                      <strong><?php echo $Main->price( $data["order"]["commission_and_price"] ); ?></strong>
                   </div>
                </div>

                <?php } ?>
                
                <?php if($data["order"]["secure_id_user_buyer"] == $_SESSION["profile"]["id"] && $data["order"]["secure_status"] == 1 && $settings["main_type_products"] == 'physical'){ ?>
                <div class="row" >
                  <div class="col-lg-6" ></div>
                  <div class="col-lg-6 text-right" > <span class="order-cancel-deal open-modal" data-id-modal="modal-confirm-cancel-order" data-id="<?php echo $data["order"]["secure_id"]; ?>" ><?php echo $ULang->t("Отменить заказ"); ?></span> </div>
                </div>
                <?php } ?>


              </div>

            </div>
        </div>
         
          
       <div class="mt50" ></div>


    </div>

    <div class="modal-custom-bg bg-click-close" style="display: none;" id="modal-confirm-transfer-goods" >
        <div class="modal-custom animation-modal" style="max-width: 400px" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>
          
          <div class="modal-confirm-content" >
              <h4><?php echo $ULang->t("Подтвердить выполнение заказа?"); ?></h4>    
              <p class="mt15" ><?php echo $ULang->t("Вы действительно выполнили заказ? Если заказ не выполнен, покупатель сможет оспорить сделку."); ?></p>        
          </div>

          <div class="mt30" ></div>

          <div class="modal-custom-button" >
             <div>
               <button class="button-style-custom color-blue confirm-transfer-goods schema-color-button" data-id="<?php echo $data["order"]["secure_id"]; ?>" ><?php echo $ULang->t("Подтвердить"); ?></button>
             </div> 
             <div>
               <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Отменить"); ?></button>
             </div>                                       
          </div>

        </div>
    </div>

    <div class="modal-custom-bg bg-click-close" style="display: none;" id="modal-confirm-receive-goods" >
        <div class="modal-custom animation-modal" style="max-width: 400px" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>
          
          <div class="modal-confirm-content" >
              <h4><?php echo $ULang->t("Подтвердить выполнение заказа?"); ?></h4>    
              <p class="mt15" ><?php echo $ULang->t("Подтверждая заказ, вы соглашаетесь с тем, что товар или услуга полностью вас устраивает."); ?></p>        
          </div>

          <div class="mt30" ></div>

          <div class="modal-custom-button" >
             <div>
               <button class="button-style-custom color-blue confirm-receive-goods schema-color-button" data-id="<?php echo $data["order"]["secure_id"]; ?>" ><?php echo $ULang->t("Подтвердить"); ?></button>
             </div> 
             <div>
               <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Отменить"); ?></button>
             </div>                                       
          </div>

        </div>
    </div>

    <div class="modal-custom-bg bg-click-close" style="display: none;" id="modal-confirm-cancel-order" >
        <div class="modal-custom animation-modal" style="max-width: 400px" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>
          
          <div class="modal-confirm-content" >
              <h4><?php echo $ULang->t("Вы действительно хотите отменить сделку?"); ?></h4>            
          </div>

          <div class="mt30" ></div>

          <div class="modal-custom-button" >
             <div>
               <button class="button-style-custom color-blue confirm-cancel-order schema-color-button" data-id="<?php echo $data["order"]["secure_id"]; ?>" ><?php echo $ULang->t("Отменить"); ?></button>
             </div> 
             <div>
               <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Закрыть"); ?></button>
             </div>                                       
          </div>

        </div>
    </div>

    <div class="modal-custom-bg" style="display: none;" id="modal-dispute-secure" >
        <div class="modal-custom animation-modal" style="max-width: 600px;" >

         <span class="modal-custom-close" ><i class="las la-times"></i></span>

         <h4> <strong><?php echo $ULang->t("Открытие спора"); ?></strong> </h4>
         
         <form class="form-dispute-secure mt25" >

           <div class="row mt20" >
             <div class="col-lg-3" > <strong><?php echo $ULang->t("Комментарий"); ?></strong> </div>
             <div class="col-lg-9" >
               
               <textarea style="min-height: 150px;" placeholder="<?php echo $ULang->t("Опишите проблему максимально подробно"); ?>" name="text" class="form-control" ></textarea>

               <p style="margin-bottom: 0px; margin-top: 5px;" ><?php echo $ULang->t("Продавец будет видеть ваш комментарий."); ?></p>

             </div>
           </div>

           <div class="row mt20" >
             <div class="col-lg-3" > <strong><?php echo $ULang->t("Вложения"); ?></strong> </div>
             <div class="col-lg-9" > 
                <span class="dispute-secure-attach" ><?php echo $ULang->t("Добавить"); ?></span> 
                <p><?php echo $ULang->t("Прикрепите дополнительные материалы которые помогут в споре (скрины переписок, фото товара и.т д). Не больше 5-ти файлов."); ?></p>
             </div>
           </div>

            <div class="mt30" ></div>

            <div class="modal-custom-button" >
               <div>
                 <button class="button-style-custom color-blue schema-color-button form-dispute-secure-button" ><?php echo $ULang->t("Открыть спор"); ?></button>
               </div> 
               <div>
                 <span class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Отменить"); ?></span>
               </div>                                       
            </div>

            <input type="hidden" name="id" value="<?php echo $data["order"]["secure_id"]; ?>" >
            <input type="file" name="files[]" accept=".jpg,.jpeg,.png" multiple="true" class="file-dispute-attach" />

         </form>

        </div>
    </div>

    <div class="modal-custom-bg" style="display: none;" id="modal-change-payment" >
        <div class="modal-custom animation-modal" style="max-width: 400px" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>
          
          <h4><?php echo $ULang->t("Выберите способ оплаты"); ?></h4>

          <div class="mt20" ></div>    
          
          <div class="modal-change-payment-item go-to-payment-order" data-id-ad="<?php echo $data["order"]["ad"]["secure_ads_ad_id"]; ?>" ><?php echo $ULang->t("Через платежную систему"); ?></div>  
          <div class="modal-change-payment-item go-to-payment-balance-order" data-id-ad="<?php echo $data["order"]["ad"]["secure_ads_ad_id"]; ?>" ><?php echo $ULang->t("С баланса"); ?> <strong><?php echo $Main->price($_SESSION['profile']['data']['clients_balance']); ?></strong> </div>

        </div>
    </div>

    <?php include $config["template_path"] . "/footer.tpl"; ?>

  </body>
</html>