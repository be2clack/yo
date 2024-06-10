<!doctype html>
<html lang="<?php echo $settings["lang_site_default"]; ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo $ULang->t("Заказ"); ?> №<?php echo $data["order"]["ads_booking_id_order"]; ?></title>

    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>" data-template="<?php echo $config["template_folder"]; ?>" >
    
    <?php include $config["template_path"] . "/header.tpl"; ?>

    <div class="container" >
       
        <h2 class="mt30 mb30" > <strong><?php echo $ULang->t("Заказ"); ?> №<?php echo $data["order"]["ads_booking_id_order"]; ?></strong> </h2>
          
        <div class="row" >
            <div class="col-lg-12" >

              <div class="bg-container" >
                
                <div class="row" >
                   <div class="col-lg-2" > <label><?php echo $ULang->t("Статус"); ?></label> </div>
                   <div class="col-lg-9" >

                    <?php if( $data["order"]["ads_booking_id_user_from"] == $_SESSION["profile"]["id"] ){
                    

                        if($data["order"]["ads_booking_status"] == 0){ 

                          if($data["ad"]["ads_booking_prepayment_percent"]){

                              if(!$data["order"]["ads_booking_status_pay"]){
                                  ?>
                                  <span class="order-status" > <?php echo $ULang->t("Ожидает предоплату"); ?> </span>

                                  <div class="mt10" >
                                      <button class="btn-custom-mini btn-color-green buy-prepayment-booking" data-id="<?php echo $data["order"]["ads_booking_id"]; ?>" > <span><?php echo $ULang->t("Внести предоплату"); ?> <?php echo $Main->price($data["order"]["amount_prepayment"]); ?></span> </button>
                                  </div>

                                  <p class="mt10"><?php echo $ULang->t("Если заказ не будет оплачен, то он автоматически будет удален через 10 минут"); ?></p>
                                  <?php
                              }else{
                                  ?>
                                  <span class="order-status" > <?php echo $ULang->t("Ожидает подтверждения"); ?> </span>
                                  <?php
                              }

                          }else{
                              ?>
                              <span class="order-status" > <?php echo $ULang->t("Ожидает подтверждения"); ?> </span>
                              <?php                              
                          }

                        }elseif($data["order"]["ads_booking_status"] == 1){

                          ?>
                          <span class="order-status" > <?php echo $ULang->t("Заказ подтвержден"); ?> </span>

                          <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["ads_booking_date_add"]); ?> </span>
                          <?php

                        }elseif($data["order"]["ads_booking_status"] == 2){

                          ?>
                          <span class="order-status" > <?php echo $ULang->t("Заказ отменен"); ?> </span>

                          <p><strong><?php echo $ULang->t("Причина:"); ?></strong> <?php echo $data["order"]["ads_booking_reason_cancel"]; ?></p>
                          <?php

                        }

                   }elseif( $data["order"]["ads_booking_id_user_to"] == $_SESSION["profile"]["id"] ){ ?>

                        <?php
                        if($data["order"]["ads_booking_status"] != 2){
                          if($data["ad"]["ads_booking_prepayment_percent"]){

                              if(!$data["order"]["ads_booking_status_pay"]){
                                ?>
                                <span class="order-status" > <?php echo $ULang->t("Ожидает предоплату"); ?> </span>
                                <?php
                              }else{
                                ?>
                                
                                <?php if($data["order"]["ads_booking_status"] == 0){ ?>
                                    <span class="order-status" > <?php echo $ULang->t("Предоплата получена"); ?> </span>
                                    <div class="mt10" >
                                        <button class="btn-custom-mini btn-color-blue order-confirm-booking" data-id="<?php echo $data["order"]["ads_booking_id"]; ?>" ><span><?php echo $ULang->t("Подтвердить заказ"); ?></span></button>
                                    </div>  
                                <?php }else{ ?>
                                    <span class="order-status" > <?php echo $ULang->t("Заказ подтвержден"); ?> </span>
                                <?php } ?> 

                                <?php
                              }
 
                          }else{
                             if($data["order"]["ads_booking_status"] == 0){
                                ?>
                                <button class="btn-custom-mini btn-color-blue order-confirm-booking" data-id="<?php echo $data["order"]["ads_booking_id"]; ?>" ><span><?php echo $ULang->t("Подтвердить заказ"); ?></span></button>
                                <?php
                             }else{
                                ?>
                                <span class="order-status" > <?php echo $ULang->t("Заказ подтвержден"); ?> </span>
                                <?php
                             }
                          }
                        }else{
                          ?>
                          <span class="order-status" > <?php echo $ULang->t("Заказ отменен"); ?> </span>

                          <p><strong><?php echo $ULang->t("Причина:"); ?></strong> <?php echo $data["order"]["ads_booking_reason_cancel"]; ?></p>
                          <?php                            
                        }
                        ?>

                        <span class="order-date" > <?php echo $ULang->t("Заказ создан:"); ?> <?php echo datetime_format($data["order"]["ads_booking_date_add"]); ?> </span>

                   <?php } ?>
                    
                   </div>
                </div>

                <hr>

                <div class="row" >
                   <div class="col-lg-2" > <label><?php echo $ULang->t("Объект/Товар"); ?></label> </div>
                   <div class="col-lg-9" >
                      <?php echo $Ads->cardAdOrder($data); ?>
                   </div>
                </div>

                <hr>

                <div class="row" >
                <?php if( $data["order"]["ads_booking_id_user_from"] == $_SESSION["profile"]["id"] ){ ?>
                
                   <div class="col-lg-2" > <label><?php echo $ULang->t("Арендодатель"); ?></label> </div>
                   <div class="col-lg-9" >

                      <div><a href="<?php echo _link("user/" . $data["user"]["clients_id_hash"]); ?>"><strong><?php echo $data["user"]["clients_name"]; ?> <?php echo $data["user"]["clients_surname"]; ?></strong></a></div>
                      <div><strong><?php echo $data["user"]["clients_phone"]; ?></strong></div>
                      <span class="btn-custom-mini btn-color-blue mt15 open-modal ad-init-message" data-id-ad="<?php echo $data["ad"]["ads_id"]; ?>" data-id-modal="modal-chat-user" > <span><?php echo $ULang->t("Написать"); ?></span> </span>
                   
                   </div>

               <?php }elseif( $data["order"]["ads_booking_id_user_to"] == $_SESSION["profile"]["id"] ){ ?>

                  <div class="col-lg-2" > <label><?php echo $ULang->t("Арендатор"); ?></label> </div>
                  <div class="col-lg-9" >

                    <div><a href="<?php echo _link("user/" . $data["user"]["clients_id_hash"]); ?>"><strong><?php echo $data["user"]["clients_name"]; ?> <?php echo $data["user"]["clients_surname"]; ?></strong></a></div>
                    <div><strong><?php echo $data["user"]["clients_phone"]; ?></strong></div>

                  </div>  

               <?php } ?>
               </div>

                <hr>

                <div class="row" >
                   <div class="col-lg-2" > <label><?php echo $ULang->t("Срок"); ?></label> </div>
                   <div class="col-lg-9" >

                      <?php if($data["order"]["ads_booking_variant"] == 0){ ?> 
                        <span><?php echo $ULang->t("с"); ?> <?php echo date('d.m.Y', strtotime($data["order"]["ads_booking_date_start"])); ?> <?php echo $ULang->t("по"); ?> <?php echo date('d.m.Y', strtotime($data["order"]["ads_booking_date_end"])); ?> (<?php echo $data["order"]['ads_booking_number_days']; ?> <?php echo ending($data["order"]['ads_booking_number_days'], $ULang->t('день'), $ULang->t('дня'), $ULang->t('дней')); ?>)</span>
                      <?php }else{ ?>

                        <?php if($data["order"]["ads_booking_measure"] == 'hour'){ ?> 
                        <span><?php echo $ULang->t("с"); ?> <?php echo date('d.m.Y', strtotime($data["order"]["ads_booking_date_start"])); ?> <?php echo $ULang->t("в"); ?> <?php echo $data["order"]["ads_booking_hour_start"]; ?> (<?php echo $data["order"]['ads_booking_hour_count']; ?> <?php echo ending($data["order"]['ads_booking_hour_count'], $ULang->t('час'), $ULang->t('часа'), $ULang->t('часов')); ?>)</span>
                        <?php }else{ ?> 
                        <span><?php echo $ULang->t("с"); ?> <?php echo date('d.m.Y', strtotime($data["order"]["ads_booking_date_start"])); ?> <?php echo $ULang->t("по"); ?> <?php echo date('d.m.Y', strtotime($data["order"]["ads_booking_date_end"])); ?> (<?php echo $data["order"]['ads_booking_number_days']; ?> <?php echo ending($data["order"]['ads_booking_number_days'], $ULang->t('день'), $ULang->t('дня'), $ULang->t('дней')); ?>)</span>
                        <?php } ?>

                      <?php } ?>

                   </div>
                </div>

                <?php if($data["order"]["ads_booking_guests"]){ ?>
                <hr>

                <div class="row" >
                   <div class="col-lg-2" > <label><?php echo $ULang->t("Гостей"); ?></label> </div>
                   <div class="col-lg-9" >
                      <span><?php echo $data["order"]["ads_booking_guests"]; ?></span>
                   </div>
                </div>
                <?php } ?>

                <?php if($data["order"]["ads_booking_additional_services"]){ ?>
                <hr>

                <div class="row" >
                   <div class="col-lg-2" > <label><?php echo $ULang->t("Дополнительные услуги"); ?></label> </div>
                   <div class="col-lg-9" >
                      <?php
                      foreach ($data["order"]["ads_booking_additional_services"] as $name => $price) {
                          ?>
                          <div class="mb5" ><strong><?php echo $name; ?></strong>  <?php echo $Main->price($price); ?></div>
                          <?php
                      }
                      ?>
                   </div>
                </div>
                <?php } ?>

                <hr>

                <div class="row mb10" >
                   <div class="col-lg-2" > <label><?php echo $ULang->t("Сумма заказа"); ?></label> </div>
                   <div class="col-lg-9" >
                      <h7><?php echo $Main->price($data["order"]['ads_booking_total_price']); ?></h7>
                   </div>
                </div>   

                <?php
                if($data["ad"]["ads_booking_prepayment_percent"]){
                ?>
                    <div class="row mb10" >
                       <div class="col-lg-2" > <label><?php echo $ULang->t("Предоплата"); ?></label> </div>
                       <div class="col-lg-9" >
                          <h7><?php echo $Main->price($data["order"]["amount_prepayment"]); ?></h7>
                       </div>
                    </div>

                    <div class="row mb10" >
                       <div class="col-lg-2" > <label><?php echo $ULang->t("Остаток"); ?></label> </div>
                       <div class="col-lg-9" >
                          <h6><?php echo $Main->price($data["order"]['ads_booking_total_price'] - $data["order"]["amount_prepayment"]); ?></h6>
                       </div>
                    </div>
                <?php
                }

                if($data["order"]["ads_booking_id_user_from"] == $_SESSION["profile"]["id"]){
                    if($data["order"]["ads_booking_status"] != 2){

                        if($data["ad"]["ads_booking_prepayment_percent"]){
                            if($data["order"]["ads_booking_status_pay"]){
                                ?>
                                <div class="row" >
                                  <div class="col-lg-6" ></div>
                                  <div class="col-lg-6 text-right" > 
                                    <span class="order-cancel-deal open-modal" style="color: red; margin-right: 15px;" data-id-modal="modal-cancel-order" data-id="<?php echo $data["order"]["ads_booking_id"]; ?>" ><?php echo $ULang->t("Отменить заказ"); ?></span> 
                                  </div>
                                </div>
                                <?php
                            }
                        }else{
                            ?>
                            <div class="row" >
                              <div class="col-lg-6" ></div>
                              <div class="col-lg-6 text-right" > 
                                <span class="order-cancel-deal open-modal" style="color: red; margin-right: 15px;" data-id-modal="modal-cancel-order" data-id="<?php echo $data["order"]["ads_booking_id"]; ?>" ><?php echo $ULang->t("Отменить заказ"); ?></span> 
                              </div>
                            </div>
                            <?php
                        }

                    }
                }elseif($data["order"]["ads_booking_id_user_to"] == $_SESSION["profile"]["id"]){
                    if($data["order"]["ads_booking_status"] == 0){
                    ?>
                    <div class="row" >
                      <div class="col-lg-6" ></div>
                      <div class="col-lg-6 text-right" > 
                        <span class="order-cancel-deal open-modal" style="color: red; margin-right: 15px;" data-id-modal="modal-cancel-order" data-id="<?php echo $data["order"]["ads_booking_id"]; ?>" ><?php echo $ULang->t("Отменить заказ"); ?></span> 
                      </div>
                    </div>
                    <?php
                    }elseif($data["order"]["ads_booking_status"] == 1){
                    ?>
                    <div class="row" >
                      <div class="col-lg-6" ></div>
                      <div class="col-lg-6 text-right" > 
                        <span class="order-cancel-deal open-modal" style="color: red; margin-right: 15px;" data-id-modal="modal-cancel-order" data-id="<?php echo $data["order"]["ads_booking_id"]; ?>" ><?php echo $ULang->t("Отменить заказ"); ?></span> 
                      </div>
                    </div>
                    <?php
                    }elseif($data["order"]["ads_booking_status"] == 2){
                    ?>
                    <div class="row" >
                      <div class="col-lg-6" ></div>
                      <div class="col-lg-6 text-right" > 
                        <span class="order-cancel-deal open-modal" style="color: red; margin-right: 15px;" data-id-modal="modal-delete-order" data-id="<?php echo $data["order"]["ads_booking_id"]; ?>" ><?php echo $ULang->t("Удалить заказ"); ?></span> 
                      </div>
                    </div>
                    <?php                        
                    }
                }                

                ?>

              </div>

            </div>
        </div>
          
       <div class="mt50" ></div>


    </div>

    <div class="modal-custom-bg bg-click-close" style="display: none;" id="modal-delete-order" >
        <div class="modal-custom animation-modal" style="max-width: 400px" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>
          
          <div class="modal-confirm-content" >
              <h4><?php echo $ULang->t("Вы действительно хотите удалить заказ?"); ?></h4>            
          </div>

          <div class="mt30" ></div>

          <div class="modal-custom-button" >
             <div>
               <button class="button-style-custom color-blue confirm-delete-order-booking" data-id="<?php echo $data["order"]["ads_booking_id"]; ?>" ><?php echo $ULang->t("Удалить"); ?></button>
             </div> 
             <div>
               <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Закрыть"); ?></button>
             </div>                                       
          </div>

        </div>
    </div>

    <div class="modal-custom-bg bg-click-close" style="display: none;" id="modal-cancel-order" >
        <div class="modal-custom animation-modal" style="max-width: 400px" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>
          
          <div class="modal-confirm-content" >
              <h4><?php echo $ULang->t("Вы действительно хотите отменить заказ?"); ?></h4> 
              <?php if($data["order"]["ads_booking_id_user_from"] == $_SESSION["profile"]["id"]){ ?>    
              <p><?php echo $ULang->t("Если вы делали предоплату, то свяжитесь с владельцем о возможности возврата денежных средств."); ?></p>
              <?php } ?>  
              <p><strong><?php echo $ULang->t("Опишите причину отмены"); ?></strong></p> 
              <div class="mt15" ></div>
              <textarea name="cancel_order_reason" class="form-control" ></textarea>    
          </div>

          <div class="mt30" ></div>

          <div class="modal-custom-button" >
             <div>
               <button class="button-style-custom color-blue confirm-cancel-order-booking" data-id="<?php echo $data["order"]["ads_booking_id"]; ?>" ><?php echo $ULang->t("Отменить заказ"); ?></button>
             </div> 
             <div>
               <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Закрыть"); ?></button>
             </div>                                       
          </div>

        </div>
    </div>

    <?php include $config["template_path"] . "/footer.tpl"; ?>

  </body>
</html>