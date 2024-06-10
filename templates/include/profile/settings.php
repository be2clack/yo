<form class="user-form-settings" >

<div class="user-bg-container" >

 <h4 class="mb35" > <strong><?php echo $ULang->t("Личные данные"); ?></strong> </h4>
 
 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3 v-middle" >
      <label><?php echo $ULang->t("Я"); ?></label>
    </div>
    <div class="col-lg-7" >
      
        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" name="status" <?php if($user["clients_type_person"] == "user"){ echo 'checked=""'; } ?> id="status1" value="user" >
            <label class="custom-control-label" for="status1"><?php echo $ULang->t("Частное лицо"); ?></label>
        </div>                        

        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" name="status" <?php if($user["clients_type_person"] == "company"){ echo 'checked=""'; } ?> id="status2" value="company" >
            <label class="custom-control-label" for="status2"><?php echo $ULang->t("Компания"); ?></label>
        </div>

        <div class="msg-error" data-name="status" ></div>

    </div>
 </div>
 </div>

 <div class="user-data-item user-name-company" <?php if($user["clients_type_person"] == "company"){ echo 'style="display: block;"'; } ?> >
 <div class="row" >
    <div class="col-lg-3 v-middle" >
      <label><?php echo $ULang->t("Название компании"); ?></label>
    </div>
    <div class="col-lg-7" >
      <input type="text" name="name_company" class="form-control" value="<?php echo $user["clients_name_company"]; ?>" >
      <div class="msg-error" data-name="name_company" ></div>
    </div>
 </div>                     
 </div>

 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3 v-middle" >
      <label><?php echo $ULang->t("Имя"); ?></label>
    </div>
    <div class="col-lg-7" >
      <input type="text" name="user_name" class="form-control" value="<?php echo $user["clients_name"]; ?>" >
      <div class="msg-error" data-name="user_name" ></div>
    </div>
 </div>
 </div>

 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3 v-middle" >
      <label><?php echo $ULang->t("Фамилия"); ?></label>
    </div>
    <div class="col-lg-7" >
      <input type="text" name="user_surname" class="form-control" value="<?php echo $user["clients_surname"]; ?>" >
      <div class="msg-error" data-name="user_surname" ></div>
    </div>
 </div>
 </div>
 
 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3 v-middle" >
      <label><?php echo $ULang->t("Отчество"); ?></label>
    </div>
    <div class="col-lg-7" >
      <input type="text" name="user_patronymic" class="form-control" value="<?php echo $user["clients_patronymic"]; ?>" >
      <div class="msg-error" data-name="user_patronymic" ></div>
    </div>
 </div>
 </div>

 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3 v-middle" >
      <label><?php echo $ULang->t("Номер телефона"); ?></label>
    </div>
    <div class="col-lg-6" >
      <?php if($user["clients_phone"]){ ?>
        <span><?php echo $user["clients_phone"]; ?></span>
      <?php }else{ ?>
        <span><?php echo $ULang->t("Укажите номер телефона, чтобы покупатели смогли с вами связываться"); ?></span>
      <?php } ?>
    </div>
    <div class="col-lg-3 j-right v-middle" > <span class="user-list-change open-modal" data-id-modal="modal-edit-phone" ><?php echo $ULang->t("Изменить"); ?></span> </div>
 </div>
 </div>
 
 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3 v-middle" >
      <label><?php echo $ULang->t("E-mail"); ?></label>
    </div>
    <div class="col-lg-6" >
      <?php if($user["clients_email"]){ ?>
        <span><?php echo $user["clients_email"]; ?></span>
      <?php }elseif($settings["bonus_program"]["email"]["status"]){ ?>
        <span><?php echo $ULang->t("Укажите e-mail и получите"); ?> <?php echo $Main->price($settings["bonus_program"]["email"]["price"]); ?> <?php echo $ULang->t("на свой бонусный счет."); ?></span>
      <?php }else{ ?>
        <span><?php echo $ULang->t("Укажите e-mail, чтобы не пропустить актуальные новости и акции сервиса"); ?></span>
      <?php } ?>
    </div>
    <div class="col-lg-3 j-right v-middle" > <span class="user-list-change open-modal" data-id-modal="modal-edit-email" ><?php echo $ULang->t("Изменить"); ?></span> </div>
 </div>
 </div>
 
 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3 v-middle" >
      <label><?php echo $ULang->t("Пароль"); ?></label>
    </div>
    <div class="col-lg-6" >
      <span class="user-list-change open-modal" data-id-modal="modal-edit-pass" ><?php echo $ULang->t("Изменить"); ?></span>
    </div>
 </div>
 </div>

 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3 v-middle" >
      <label><?php echo $ULang->t("Город"); ?></label>
    </div>
    <div class="col-lg-7" >

      <div class="container-custom-search" >
        <input type="text" autocomplete="nope" class="form-control action-input-search-city" value="<?php echo $user["city_name"]; ?>" >
        <div class="custom-results SearchCityResults" ></div>
      </div>

      <input type="hidden" name="city_id" value="<?php echo $user["clients_city_id"]; ?>" >

    </div>
 </div>
 </div>

 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3" >
      <label><?php echo $ULang->t("Короткое имя"); ?></label>
    </div>
    <div class="col-lg-7" >
      <input type="text" name="id_hash" class="form-control" value="<?php echo $user["clients_id_hash"]; ?>" >
      <div class="msg-error" data-name="id_hash" ></div>
      <span class="user-info" ><?php echo $ULang->t("Укажите короткий адрес вашей страницы, чтобы он стал более удобным и запоминающимся"); ?></span>
    </div>
 </div>
 </div>

</div>

<?php if($settings["secure_status"]){ ?>
<div class="user-bg-container mt15" >

 <h4 class="mb35" > <strong><?php echo $ULang->t("Онлайн оплата"); ?></strong> </h4>
 
 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3" >
      <label><?php echo $ULang->t("Статус"); ?></label>
    </div>
    <div class="col-lg-7" >
        <label class="checkbox">
          <input type="checkbox" name="secure" value="1" <?php if($user["clients_secure"]){ echo 'checked=""'; } ?> >
          <span></span>
        </label>  
        <span class="user-info mt10"  >
          <?php echo $ULang->t("Активируйте тумблер, чтобы ваши товары были доступны для продажи по безопасной сделке с онлайн оплатой."); ?> <a href="<?php echo _link("promo/secure"); ?>"><?php echo $ULang->t("Подробнее"); ?></a>
        </span>                        
    </div>
 </div>
 </div>

 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3" >
      <label><?php echo $ULang->t("Счет"); ?></label>
    </div>
    <?php 
    if( $user["clients_score"] ){ 

        if( $user["clients_score_type"] == 'card' ){

            ?>
                <div class="col-lg-7" >
                  <span><?php echo $Main->getCardType($user["clients_score"]); ?> <strong><?php echo "xxxx" . substr($user["clients_score"], strlen($user["clients_score"])-4, strlen($user["clients_score"]) ); ?></strong></span>
                </div>

                <div class="col-lg-2 j-right v-middle" > <span class="user-list-change open-modal" data-id-modal="modal-user-score-secure" ><?php echo $ULang->t("Изменить"); ?></span> </div>
            <?php

        }elseif( $user["clients_score_type"] == 'wallet' ){

            ?>
                <div class="col-lg-7" >
                  <span><strong><?php echo $user["clients_score"]; ?></strong></span>
                </div>

                <div class="col-lg-2 j-right v-middle" > <span class="user-list-change open-modal" data-id-modal="modal-user-score-secure" ><?php echo $ULang->t("Изменить"); ?></span> </div>
            <?php

        }elseif( $user["clients_score_type"] == 'add_card' ){

          ?>
              <div class="col-lg-7" >
                <span><strong><?php echo $user["clients_score"]; ?></strong></span>
              </div>

              <div class="col-lg-2 j-right v-middle" > <span class="user-list-change profile-init-delete-card" ><?php echo $ULang->t("Удалить карту"); ?></span> </div>
          <?php

      }
        
    ?>

    <?php }else{ ?>

        <?php if($settings["secure_payment_service"]["secure_add_card"]){ ?>
            <div class="col-lg-7" >
              <span class="user-list-change profile-init-add-card"  ><?php echo $ULang->t("Добавить"); ?></span>
              <div class="msg-error" data-name="secure" ></div>
              <span class="user-info" ><?php echo $ULang->t("Добавьте счет для приема оплаты"); ?></span>
            </div>
        <?php }else{ ?>
            <div class="col-lg-7" >
              <span class="user-list-change open-modal" data-id-modal="modal-user-score-secure" ><?php echo $ULang->t("Добавить"); ?></span>
              <div class="msg-error" data-name="secure" ></div>
              <span class="user-info" ><?php echo $ULang->t("Добавьте счет для приема оплаты"); ?></span>
            </div>                                
        <?php } ?>  

    <?php } ?>
 </div>
 </div>       

</div>
<?php } ?>

<?php if($settings["main_type_products"] == 'physical' && $settings["delivery_service"] == 'boxberry'){ ?>
<div class="user-bg-container mt15" >

 <h4 class="mb35" > <strong><?php echo $ULang->t("Доставка Boxberry"); ?></strong> </h4>
 
 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3" >
      <label><?php echo $ULang->t("Статус"); ?></label>
    </div>
    <div class="col-lg-7" >
        <label class="checkbox">
          <input type="checkbox" name="delivery_status" value="1" <?php if($user["clients_delivery_status"]){ echo 'checked=""'; } ?> >
          <span></span>
        </label>  
        <span class="user-info mt10"  >
          <?php echo $ULang->t("Активируйте тумблер если хотите отправлять товары покупателям через доставку boxberry"); ?> <a href="<?php echo _link("kak-rabotaet-dostavka"); ?>"><?php echo $ULang->t("Подробнее"); ?></a>
        </span>                        
    </div>
 </div>
 </div>

 <div class="user-data-item" >
 <div class="row" >

    <div class="col-lg-3 v-middle" >
      <label><?php echo $ULang->t("Пункт отправки"); ?></label>
    </div>
    <div class="col-lg-7" >

        <div class="container-custom-search" >
          <input type="text" autocomplete="nope" class="form-control mt15 action-input-search-delivery-point-send" placeholder="<?php echo $ULang->t("Адрес"); ?>" value="<?php echo $data["delivery_point_send"]["boxberry_points_address"]; ?>" >
          <div class="custom-results SearchDeliveryPointSendResults" ></div>
        </div> 

        <div class="container-delivery-point mt10" ><span class="user-info mt0" ><?php echo $ULang->t("Укажите адрес пункта отправки boxberry в котором вам будет удобно отправлять посылки."); ?> <a href="https://boxberry.ru/find_an_office" target="_blank" ><?php echo $ULang->t("Пункты на карте"); ?></a></span></div>                               
        <div class="msg-error" data-name="delivery_id_point_send" ></div>

        <input type="hidden" name="delivery_id_point_send" value="<?php echo $data["delivery_point_send"]["boxberry_points_code"]; ?>" >
        <input type="hidden" name="delivery_id_city" value="<?php echo $data["delivery_point_send"]["boxberry_points_city_code"]; ?>" >

    </div>

 </div>
 </div>

</div>
<?php } ?>

<?php if($settings["main_type_products"] == 'physical' && $settings["functionality"]["booking"] && $settings["booking_status"]){ ?>
<div class="user-bg-container mt15" >

 <h4 class="mb35" > <strong><?php echo $ULang->t("Бронирование/Аренда"); ?></strong> </h4>
 
 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3" >
      <label><?php echo $ULang->t("Счет"); ?></label>
    </div>
    <?php 
    if( $user["clients_score_booking"] ){ 
    ?>

    <div class="col-lg-7" >
      <span><?php echo $Main->getCardType($user["clients_score_booking"]); ?> <strong><?php echo "xxxx" . substr($user["clients_score_booking"], strlen($user["clients_score_booking"])-4, strlen($user["clients_score_booking"]) ); ?></strong></span>
    </div>

    <div class="col-lg-2 j-right v-middle" > <span class="user-list-change open-modal" data-id-modal="modal-user-score-booking" ><?php echo $ULang->t("Изменить"); ?></span> </div>

    <?php }else{ ?>
    <div class="col-lg-7" >
      <span class="user-list-change open-modal" data-id-modal="modal-user-score-booking" ><?php echo $ULang->t("Добавить"); ?></span>
      <span class="user-info" ><?php echo $ULang->t("Добавьте счет для приема оплаты онлайн бронирования и аренды"); ?></span>
    </div>                       
    <?php } ?>
 </div>
 </div>       

</div>
<?php } ?>

<div class="user-bg-container mt15" >

 <h4 class="mb35" > <strong><?php echo $ULang->t("Общие настройки"); ?></strong> </h4>
 
 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3" >
      <label><?php echo $ULang->t("Показывать мой телефон в объявлениях"); ?></label>
    </div>
    <div class="col-lg-7" >
        <label class="checkbox">
          <input type="checkbox" name="view_phone" value="1" <?php if($user["clients_view_phone"]){ echo 'checked=""'; } ?> >
          <span></span>
        </label>                          
    </div>
 </div>
 </div>
 
 <?php if($settings["ads_comments"]){ ?>
 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3" >
      <label><?php echo $ULang->t("Комментарии в объявлениях"); ?></label>
    </div>
    <div class="col-lg-7" >
        <label class="checkbox">
          <input type="checkbox" name="comments" value="1" <?php if($user["clients_comments"]){ echo 'checked=""'; } ?> >
          <span></span>
        </label>                          
    </div>
 </div>
 </div>
 <?php } ?>

 <div class="user-data-item" >
 <div class="row" >
    <div class="col-lg-3" >
      <label><?php echo $ULang->t("Уведомления"); ?></label>
    </div>
    <div class="col-lg-7" >
      <span class="user-list-change open-modal" data-id-modal="modal-edit-notifications" ><?php echo $ULang->t("Изменить"); ?></span>
    </div>
 </div>
 </div>       

</div>

<div class="row" >
 <div class="col-lg-4" ></div>
 <div class="col-lg-4" >
   <button class="btn-custom-big btn-color-blue mb5 mt25 width100" ><?php echo $ULang->t("Сохранить"); ?></button>
 </div>
 <div class="col-lg-4" ></div>
</div>

</form>