<div class="modal-custom-bg" style="display: none;" id="modal-edit-pass" >
    <div class="modal-custom animation-modal" style="max-width: 400px" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>
      
      <div class="modal-confirm-content" >
          <h4><?php echo $ULang->t("Смена пароля"); ?></h4>    
          <input type="text" name="user_current_pass" class="form-control mt25" placeholder="<?php echo $ULang->t("Текущий пароль"); ?>" >
          <input type="text" name="user_new_pass" class="form-control mt10" placeholder="<?php echo $ULang->t("Новый пароль"); ?>" >                    
      </div>

      <div class="mt30" ></div>

      <div class="modal-custom-button" >
         <div>
           <button class="button-style-custom color-blue user-edit-pass" ><?php echo $ULang->t("Изменить"); ?></button>
         </div> 
         <div>
           <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Отменить"); ?></button>
         </div>                                       
      </div>

    </div>
</div>

<div class="modal-custom-bg" style="display: none;" id="modal-edit-email" >
    <div class="modal-custom animation-modal" style="max-width: 400px" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>
      
      <div class="modal-confirm-content" >
          <h4><?php echo $ULang->t("E-mail"); ?></h4>   
          <p class="mt15 confirm-edit-email" ></p> 
          <input type="text" name="email" class="form-control mt25" placeholder="<?php echo $ULang->t("Укажите e-mail"); ?>" >                    
      </div>

      <div class="mt30" ></div>

      <div class="modal-custom-button modal-custom-button-show" >
         <div>
           <button class="button-style-custom color-blue user-edit-email" ><?php echo $ULang->t("Продолжить"); ?></button>
         </div> 
         <div>
           <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Отменить"); ?></button>
         </div>                                       
      </div>

      <div class="modal-custom-button modal-custom-button-hide" >
           <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Закрыть"); ?></button>                                     
      </div>

    </div>
</div>

<div class="modal-custom-bg" style="display: none;" id="modal-edit-phone" >
    <div class="modal-custom animation-modal" style="max-width: 400px" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>
      
      <div class="modal-confirm-content" >
          <h4><?php echo $ULang->t("Смена телефона"); ?></h4>   

          <div class="input-phone-format" >
          <input type="text" name="phone" class="form-control mt25 phone-mask" placeholder="<?php echo $ULang->t("Номер телефона"); ?>" data-format="<?php echo getFormatPhone(); ?>" >
          <?php echo outBoxChangeFormatPhone(); ?> 
          </div>

          <input type="text" name="code" class="form-control mt25" placeholder="<?php if($settings["sms_service_method_send"] == 'call'){ echo $ULang->t("Укажите 4 последние цифры номера"); }else{ echo $ULang->t("Укажите код из смс"); } ?>" maxlength="4" >
      </div>

      <div class="mt30" ></div>

      <div class="modal-custom-button" >
         <div>
           <?php if($settings["confirmation_phone"]){ ?>
           <button class="button-style-custom color-blue user-edit-phone-send" ><?php echo $ULang->t("Продолжить"); ?></button>
           <button class="button-style-custom color-blue user-edit-phone-save" ><?php echo $ULang->t("Сохранить"); ?></button>
           <?php }else{ ?>
           <button class="button-style-custom color-blue user-edit-phone-save" style="display: block;" ><?php echo $ULang->t("Сохранить"); ?></button>
           <?php } ?>
         </div> 
         <div>
           <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Отменить"); ?></button>
         </div>                                       
      </div>

    </div>
</div>

<div class="modal-custom-bg"  id="modal-notification-email" style="display: none;"  >
    <div class="modal-custom animation-modal no-padding" style="max-width: 500px;" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>

      <div class="modal-notification-email-content" >

         <div class="modal-notification-email-content-icon" >
         </div>

         <div class="modal-notification-email-content-title" >
           <h4><?php echo $ULang->t("Укажите e-mail и получите"); ?> <?php echo $Main->price($settings["bonus_program"]["email"]["price"]); ?> <?php echo $ULang->t("на свой бонусный счет."); ?></h4>
         </div>
        
        <div class="modal-custom-button" >
           <div>
             <button class="button-style-custom color-green mb25 open-modal" data-id-modal="modal-edit-email" ><?php echo $ULang->t("Указать e-mail"); ?></button>
           </div> 
           <div>
             <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Закрыть"); ?></button>
           </div>                                       
        </div>

      </div>


    </div>
</div>

<div class="modal-custom-bg"  id="modal-edit-notifications" style="display: none;"  >
    <div class="modal-custom animation-modal" style="max-width: 500px;" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>

      <div class="modal-edit-notifications-content" >
        
        <form class="form-edit-notifications" >
        <h4 class="mb25" > <strong><?php echo $ULang->t("Уведомления"); ?></strong> </h4>

          <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" name="notifications[messages]" id="notifications1" <?php if($data["notifications_param"]["messages"]){ echo 'checked=""'; } ?> value="1" >
              <label class="custom-control-label" for="notifications1"><?php echo $ULang->t("Сообщения"); ?></label>
              <p><?php echo $ULang->t("Уведомлять меня о получении новых сообщений"); ?></p>
          </div>              

          <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" name="notifications[answer_comments]" id="notifications2" <?php if($data["notifications_param"]["answer_comments"]){ echo 'checked=""'; } ?> value="1" >
              <label class="custom-control-label" for="notifications2"><?php echo $ULang->t("Ответы на комментарии"); ?></label>
              <p><?php echo $ULang->t("Уведомлять меня о получении новых ответов на мои комментарии"); ?></p>
          </div> 

          <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" name="notifications[services]" id="notifications3" <?php if($data["notifications_param"]["services"]){ echo 'checked=""'; } ?> value="1" >
              <label class="custom-control-label" for="notifications3"><?php echo $ULang->t("Окончание услуг"); ?></label>
              <p><?php echo $ULang->t("Уведомлять меня о завершении платных услуг"); ?></p>
          </div>

          <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" name="notifications[answer_ad]" id="notifications4" <?php if($data["notifications_param"]["answer_ad"]){ echo 'checked=""'; } ?> value="1" >
              <label class="custom-control-label" for="notifications4"><?php echo $ULang->t("Объявления"); ?></label>
              <p><?php echo $ULang->t("Уведомлять меня об окончании срока размещения объявлений"); ?></p>
          </div>

          <small><?php echo $settings["site_name"]; ?> <?php echo $ULang->t("оставляет за собой право отправлять пользователям информационные сообщения"); ?></small>
          </form>

      </div>


    </div>
</div>

<div class="modal-custom-bg"  id="modal-user-score-secure" style="display: none;"  >
    <div class="modal-custom animation-modal" style="max-width: 400px;" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>

      <div class="modal-confirm-content" >
        
        <h4> <?php echo $ULang->t("Счет"); ?> </h4>

        <div class="text-left" >
        <div class="mt25" ></div>
        <?php
        if($settings["secure_payment_service"]){
            
            if(count($settings["secure_payment_service"]["secure_score_type"]) > 1){

                foreach ($settings["secure_payment_service"]["secure_score_type"] as $key => $value) {

                    if($value == 'wallet'){
                        ?>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" <?php if($user["clients_score_type"] == 'wallet'){ echo 'checked=""'; } ?> name="user_score_type" id="user_score_type_<?php echo $value; ?>" value="wallet">
                            <label class="custom-control-label" for="user_score_type_<?php echo $value; ?>"><?php echo $ULang->t("Счет кошелька"); ?> <?php echo $settings["secure_payment_service"]["name"]; ?></label>
                        </div>                
                        <?php                    
                    }elseif($value == 'card'){
                        ?>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" <?php if($user["clients_score_type"] == 'card'){ echo 'checked=""'; } ?> name="user_score_type" id="user_score_type_<?php echo $value; ?>" value="card">
                            <label class="custom-control-label" for="user_score_type_<?php echo $value; ?>"><?php echo $ULang->t("Счет банковской карты"); ?></label>
                        </div>                
                        <?php                    
                    }

                }

            }else{

                if($settings["secure_payment_service"]["secure_score_type"][0] == 'wallet'){
                    ?>
                        <p class="text-center" ><?php echo $ULang->t("Укажите счет кошелька"); ?> <?php echo $settings["secure_payment_service"]["name"]; ?></p>           
                    <?php                    
                }elseif($settings["secure_payment_service"]["secure_score_type"][0] == 'card'){
                    ?>
                        <p class="text-center" ><?php echo $ULang->t("Укажите счет банковской карты"); ?></p>            
                    <?php                    
                }

                ?>
                <input type="hidden" name="user_score_type" value="<?php echo $settings["secure_payment_service"]["secure_score_type"][0]; ?>" >
                <?php
            }

        }else{
            ?>
            <p class="text-center" ><?php echo $ULang->t("Укажите счет банковской карты"); ?></p>
            <?php
        }

        ?>
        </div>

        <input type="text" name="user_score" class="form-control mt25" value="<?php echo $user["clients_score"]; ?>" >

      </div>

      <div class="mt30" ></div>

      <div class="modal-custom-button" >
         <div>
           <button class="button-style-custom color-blue user-edit-score" ><?php echo $ULang->t("Сохранить"); ?></button>
         </div> 
         <div>
           <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Отменить"); ?></button>
         </div>                                       
      </div>

    </div>
</div>

<div class="modal-custom-bg"  id="modal-user-score-booking" style="display: none;"  >
    <div class="modal-custom animation-modal" style="max-width: 400px;" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>

      <div class="modal-confirm-content" >
        
        <h4> <?php echo $ULang->t("Счет"); ?> </h4>

        <div class="text-left" >
        <div class="mt25" ></div>

        <p class="text-center" ><?php echo $ULang->t("Укажите счет банковской карты"); ?></p>

        </div>

        <input type="text" name="user_score_booking" class="form-control mt25" value="<?php echo $user["clients_score_booking"]; ?>" >

      </div>

      <div class="mt30" ></div>

      <div class="modal-custom-button" >
         <div>
           <button class="button-style-custom color-blue user-edit-score-booking" ><?php echo $ULang->t("Сохранить"); ?></button>
         </div> 
         <div>
           <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Отменить"); ?></button>
         </div>                                       
      </div>

    </div>
</div>

<div class="modal-custom-bg bg-click-close" style="display: none;" id="modal-confirm-block" >
    <div class="modal-custom animation-modal" style="max-width: 400px" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>
      
      <div class="modal-confirm-content" >
          <h4><?php echo $ULang->t("Внести пользователя в чёрный список?"); ?></h4>    
          <p class="mt15" ><?php echo $ULang->t("Пользователь не сможет писать вам в чатах и оставлять комментарии к объявлениям."); ?></p>        
      </div>

      <div class="mt30" ></div>

      <div class="modal-custom-button" >
         <div>
           <button class="button-style-custom color-blue profile-user-block" data-id="<?php echo $user["clients_id"]; ?>" ><?php echo $ULang->t("Внести"); ?></button>
         </div> 
         <div>
           <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Отменить"); ?></button>
         </div>                                       
      </div>

    </div>
</div>

<div class="modal-custom-bg bg-click-close" style="display: none;" id="modal-confirm-delete-review" >
    <div class="modal-custom animation-modal" style="max-width: 400px" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>
      
      <div class="modal-confirm-content" >
          <h4><?php echo $ULang->t("Вы действительно хотите удалить отзыв?"); ?></h4>            
      </div>

      <div class="mt30" ></div>

      <div class="modal-custom-button" >
         <div>
           <button class="button-style-custom color-blue user-delete-review" ><?php echo $ULang->t("Удалить"); ?></button>
         </div> 
         <div>
           <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Отменить"); ?></button>
         </div>                                       
      </div>

    </div>
</div>

<div class="modal-custom-bg bg-click-close"  id="modal-seller-safety" style="display: none;"  >
    <div class="modal-custom animation-modal" style="max-width: 480px;" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>
      
      <h4 class="mb25" > <strong><?php echo $ULang->t("Берегитесь мошенников"); ?></strong> </h4>

      <p><?php echo $ULang->t("1. Не переходите в другие мессенджеры, общайтесь только на"); ?> <?php echo $settings["site_name"]; ?>.</p>
      <p><?php echo $ULang->t("2. Не делитесь своими данными с другими людьми. Ваши почта, паспорт и трёхзначный код с карты нужны только злоумышленникам."); ?></p>
      <p><?php echo $ULang->t("3. Не переходите по ссылкам собеседника."); ?></p>

      <button class="button-style-custom color-blue button-click-close mt25" ><?php echo $ULang->t("Я все понял!"); ?></button>

    </div>
</div>

<div class="modal-custom-bg bg-click-close"  id="modal-user-verification" style="display: none;"  >
    <div class="modal-custom animation-modal" style="max-width: 480px;" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>
      
      <div class="modal-user-verification-tab-1" >

          <h4 class="mb15" > <strong><?php echo $ULang->t("Подтверждение профиля"); ?></strong> </h4>

          <p><?php echo $ULang->t("Подтверждение профиля повысит ваш статус и доверие покупателей."); ?></p>

          <p class="mb5" ><?php if($user["clients_phone"]){ echo '<span class="modal-user-verification-check" ><i class="las la-check"></i></span> '.$ULang->t("Номер телефона подтвержден"); }else{ echo '<span class="modal-user-verification-times" ><i class="las la-times"></i></span> '.$ULang->t("Номер телефона не подтвержден"); } ?></p>

          <p><?php if($user["clients_email"]){ echo '<span class="modal-user-verification-check" ><i class="las la-check"></i></span> '.$ULang->t("Email подтвержден"); }else{ echo '<span class="modal-user-verification-times" ><i class="las la-times"></i></span> '.$ULang->t("Email не подтвержден"); } ?></p>

          <h5 class="mb15" > <strong><?php echo $ULang->t("Документы и фото"); ?></strong> </h5>

          <div class="modal-user-verification-change-doc" > <?php echo $ULang->t("Прикрепить фото паспорта"); ?> </div>
          <p><?php echo $ULang->t("Сделайте фото главной страницы паспорта"); ?></p>

          <div class="modal-user-verification-change-doc-img" > <img src=""> </div>

          <div class="modal-user-verification-change-photo" > <?php echo $ULang->t("Прикрепить своё фото"); ?> </div>
          <p><?php echo $ULang->t("Сделайте селфи вашего лица вместе с листочком на котором напишите цифры"); ?> <strong><?php echo $user["clients_verification_code"]; ?></strong> </p>

          <div class="modal-user-verification-change-photo-img" > <img src=""> </div>

          <form class="modal-user-verification-form" >
            <input type="file" name="doc">
            <input type="file" name="photo">
          </form>

          <button class="button-style-custom color-blue mt25 send-user-verification" ><?php echo $ULang->t("Отправить на проверку"); ?></button>

      </div>

      <div class="modal-user-verification-tab-2" >
          <h4 class="mb15" > <strong><?php echo $ULang->t("Заявка на подтверждение профиля успешно отправлена!"); ?></strong> </h4>
          <p><?php echo $ULang->t("После проверки мы отправим Вам уведомление."); ?></p>
      </div>

    </div>
</div>

<div class="modal-custom-bg"  id="modal-user-add-review" style="display: none;"  >
    <div class="modal-custom animation-modal" style="max-width: 600px;" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>
      
      <form class="form-user-add-review" >

      <div class="user-add-review-tab-1 user-add-review-tab mb10" >
         
        <h4 class="mb25" > <strong><?php echo $ULang->t("Вы:"); ?></strong> </h4>

        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" name="status_user" value="seller" id="status_user_seller">
            <label class="custom-control-label" for="status_user_seller"><?php echo $ULang->t("Продавец"); ?> </label>
        </div>

        <div class="custom-control custom-radio mt10">
            <input type="radio" class="custom-control-input" name="status_user" value="buyer" id="status_user_buyer">
            <label class="custom-control-label" for="status_user_buyer"><?php echo $ULang->t("Покупатель"); ?> </label>
        </div>

      </div>

      <div class="user-add-review-tab-2 user-add-review-tab" >
         
         <h4 class="mb25" > <strong><?php echo $ULang->t("Выберите товар:"); ?></strong> </h4>

         <div class="user-add-review-box-seller" >
             
             <div class="user-add-review-list-ads" >

                <?php 
                if( $data["ad_list_reviews_seller"]["count"] ){

                   foreach ($data["ad_list_reviews_seller"]["all"] as $key => $value) {
                       $image = $Ads->getImages($value["ads_images"]);
                       ?>
                       <div class="mini-list-ads" data-id="<?php echo $value["ads_id"]; ?>" >
                          <div class="mini-list-ads-img" > <img class="image-autofocus" src="<?php echo Exists($config["media"]["small_image_ads"],$image[0],$config["media"]["no_image"]); ?>" > </div>
                          <div class="mini-list-ads-content" >
                            <div>
                            <?php echo $value["ads_title"]; ?>
                            <div class="mini-list-ads-price" >
                             <?php
                                   echo $Ads->outPrice( ["data"=>$value,"class_price"=>"mini-list-ads-price-now","class_price_old"=>"mini-list-ads-price-old"] );
                             ?>        
                            </div>  
                            </div>                            
                          </div>
                          <div class="clr" ></div>
                       </div>
                       <?php
                   }

                }else{ 
                  ?>
                     <p><?php echo $ULang->t("У продавца объявлений нет"); ?></p>
                  <?php 
                } 
                ?>
             </div>

         </div>

         <div class="user-add-review-box-buyer" >
             
             <div class="user-add-review-list-ads" >

                <?php 
                if( $data["ad_list_reviews_buyer"]["count"] ){

                   foreach ($data["ad_list_reviews_buyer"]["all"] as $key => $value) {
                       $image = $Ads->getImages($value["ads_images"]);
                       ?>
                       <div class="mini-list-ads" data-id="<?php echo $value["ads_id"]; ?>" >
                          <div class="mini-list-ads-img" > <img class="image-autofocus" src="<?php echo Exists($config["media"]["small_image_ads"],$image[0],$config["media"]["no_image"]); ?>" > </div>
                          <div class="mini-list-ads-content" >
                            <div>
                            <?php echo $value["ads_title"]; ?>
                            <div class="mini-list-ads-price" >
                             <?php
                                   echo $Ads->outPrice( ["data"=>$value,"class_price"=>"mini-list-ads-price-now","class_price_old"=>"mini-list-ads-price-old"] );
                             ?>        
                            </div>  
                            </div>                            
                          </div>
                          <div class="clr" ></div>
                       </div>
                       <?php
                   }

                }else{ 
                  ?>
                     <p><?php echo $ULang->t("У Вас нет объявлений"); ?></p>
                  <?php 
                } 
                ?>
             </div>

         </div>

         <div class="button-style-custom color-light user-add-review-tab-prev mt25" ><?php echo $ULang->t("Назад"); ?></div>

      </div>

      <div class="user-add-review-tab-3 user-add-review-tab" >

        <h4 class="mb25" > <strong><?php echo $ULang->t("Чем всё закончилось?"); ?></strong> </h4>

        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" name="status_result" value="1" id="status_result1">
            <label class="custom-control-label" for="status_result1"><strong><?php echo $ULang->t("Сделка состоялась"); ?></strong> <br> <?php echo $ULang->t("Продавец получил деньги"); ?> </label>
        </div>

        <div class="custom-control custom-radio mt10">
            <input type="radio" class="custom-control-input" name="status_result" value="2" id="status_result2">
            <label class="custom-control-label" for="status_result2"><strong><?php echo $ULang->t("Сделка сорвалась"); ?></strong> <br> <?php echo $ULang->t("При встрече, осмотре товара"); ?> </label>
        </div>

        <div class="custom-control custom-radio mt10">
            <input type="radio" class="custom-control-input" name="status_result" value="3" id="status_result3">
            <label class="custom-control-label" for="status_result3"><strong><?php echo $ULang->t("Не договорились"); ?></strong> <br> <?php echo $ULang->t("По телефону или в переписке"); ?> </label>
        </div>

        <div class="custom-control custom-radio mt10">
            <input type="radio" class="custom-control-input" name="status_result" value="4" id="status_result4">
            <label class="custom-control-label" for="status_result4"><strong><?php echo $ULang->t("Не общались"); ?></strong> <br> <?php echo $ULang->t("Не удалось связаться"); ?> </label>
        </div>

        <div class="button-style-custom color-light user-add-review-tab-prev mt25" ><?php echo $ULang->t("Назад"); ?></div>
        
      </div>

      <div class="user-add-review-tab-4 user-add-review-tab" >
      
        <h4 class="mb15" > <strong><?php echo $ULang->t("Оценка и детали"); ?></strong> </h4>

        <div class="star-rating star-rating-js">
          <span class="ion-ios-star" data-rating="1"></span>
          <span class="ion-ios-star-outline" data-rating="2"></span>
          <span class="ion-ios-star-outline" data-rating="3"></span>
          <span class="ion-ios-star-outline" data-rating="4"></span>
          <span class="ion-ios-star-outline" data-rating="5"></span>
          <input type="hidden" name="rating" value="1">
        </div>

        <textarea class="mt10 form-control" rows="6" name="text" placeholder="<?php echo $ULang->t("Поделитесь впечатлениями: что понравилось, а что — не очень. В отзыве не должно быть оскорблений и мата."); ?>" ></textarea>

        <div class="user-add-review-attach" >
           
           <span class="user-add-review-attach-change" ><?php echo $ULang->t("Прикрепить фото"); ?></span>

           <div class="user-add-review-attach-files" ></div>

        </div>

        <div class="row mt25" >
           <div class="col-lg-6" >
             <div class="button-style-custom color-light user-add-review-tab-prev mt5" ><?php echo $ULang->t("Назад"); ?></div>
           </div>
           <div class="col-lg-6" >
             <button class="button-style-custom color-green mt5" ><?php echo $ULang->t("Отправить отзыв"); ?></button>
           </div>
        </div>

      </div>

      <input type="hidden" name="id_ad" value="0" >
      <input type="hidden" name="id_user" value="<?php echo $user["clients_id"]; ?>" >
      <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>" >
      
      </form>

      <input type="file" accept=".jpg,.jpeg,.png" multiple="true" style="display: none;" class="input_attach_files" />


    </div>
</div>

<div class="modal-custom-bg bg-click-close" style="display: none;" id="modal-remove-publication" >
    <div class="modal-custom animation-modal" style="max-width: 450px" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>
      
      <div class="modal-confirm-content" >
          <h4><?php echo $ULang->t("Снять с публикации"); ?></h4>   
          <p><?php echo $ULang->t("Выберите причину"); ?></p>         
      </div>

      <div class="mt30" ></div>

      <div class="modal-custom-button-list" >
        <button class="button-style-custom schema-color-button color-blue profile-ads-status-sell" ><?php echo $ULang->t("Я продал на"); ?> <?php echo $settings["site_name"]; ?></button>
        <button class="button-style-custom color-light profile-ads-remove-publication mt5" ><?php echo $ULang->t("Другая причина"); ?></button>
      </div>

    </div>
</div>

<div class="modal-custom-bg bg-click-close" style="display: none;" id="modal-delete-ads" >
    <div class="modal-custom animation-modal" style="max-width: 400px" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>
      
      <div class="modal-confirm-content" >
          <h4><?php echo $ULang->t("Вы действительно хотите удалить объявление?"); ?></h4> 
          <p><?php echo $ULang->t("Ваше объявление будет безвозвратно удалено"); ?></p>           
      </div>

      <div class="modal-custom-button" >
         <div>
           <button class="button-style-custom btn-color-danger profile-ads-delete" ><?php echo $ULang->t("Удалить"); ?></button>
         </div> 
         <div>
           <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Отменить"); ?></button>
         </div>                                       
      </div>

    </div>
</div>

<div class="modal-custom-bg bg-click-close" style="display: none;" id="modal-statistics-load-info-user" >
    <div class="modal-custom animation-modal" style="max-width: 600px" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>

      <h4 class="mb15" > <strong><?php echo $ULang->t("Активность"); ?></strong> </h4>
      
      <div class="modal-statistics-load-info-user-content" ></div>

      <button class="button-style-custom color-light button-click-close width100" ><?php echo $ULang->t("Закрыть"); ?></button>

    </div>
</div>

<div class="modal-custom-bg bg-click-close" style="display: none;" id="modal-booking-calendar-orders" >
    <div class="modal-custom animation-modal" style="max-width: 600px" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>

      <h4 class="mb15" > <strong><?php echo $ULang->t("Заказы"); ?></strong> <?php echo $ULang->t("на"); ?> <span class="modal-booking-calendar-orders-date" ></span> </h4>
      
      <div class="modal-booking-calendar-orders-load-content mt25" ></div>

    </div>
</div>

<div class="modal-custom-bg bg-click-close" style="display: none;" id="modal-statistics-filter-date" >
    <div class="modal-custom animation-modal" style="max-width: 400px" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>

      <h4 class="mb20" > <strong><?php echo $ULang->t("Фильтрация по дате"); ?></strong> </h4>
      
      <form method="get" >

           <div>
               <input type='date' name="date_start" value="<?php if($_GET['date_start']){ echo $_GET['date_start']; } ?>" class="form-control statistics-datepicker" autocomplete="off" />
           </div>
           <div class="mt10" >
               <input type='date' name="date_end" value="<?php if($_GET['date_end']){ echo $_GET['date_end']; } ?>" class="form-control statistics-datepicker" autocomplete="off" />
           </div> 

           <button class="button-style-custom color-blue width100 mt20" ><?php echo $ULang->t("Применить"); ?></button>
      </form>

    </div>
</div>

<div class="modal-custom-bg"  id="modal-user-requisites" style="display: none;"  >
    <div class="modal-custom animation-modal" style="max-width: 700px;" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>

      <h4 class="mb15" > <strong><?php echo $ULang->t("Реквизиты"); ?></strong> </h4>
                 
      <span class="user-info mb15"  >
         <?php echo $ULang->t("Укажите реквизиты если хотите пополнять баланс через расчетный счет"); ?>
      </span>

      <form class="user-requisites-form" >
          
         <div class="user-data-item" >
         <div class="row" >
            <div class="col-lg-4" >
              <label><?php echo $ULang->t("ИНН"); ?></label>
            </div>
            <div class="col-lg-8" >
              <input type="text" name="requisites_company[inn]" maxlength="64" class="form-control" value="<?php if(isset($data["requisites_company"]["inn"])) echo $data["requisites_company"]["inn"]; ?>" >
            </div>
         </div>
         </div>

         <div class="user-data-item" >
         <div class="row" >
            <div class="col-lg-4" >
              <label><?php echo $ULang->t("Правовая форма"); ?></label>
            </div>
            <div class="col-lg-8" >
              <select class="form-control user-change-legal-form" name="requisites_company[legal_form]" >
                  <option value="0" ><?php echo $ULang->t("Не выбрано"); ?></option>
                  <option value="1" <?php if($data["requisites_company"]["legal_form"] == 1) echo 'selected=""'; ?> ><?php echo $ULang->t("Юридическое лицо"); ?></option>
                  <option value="2" <?php if($data["requisites_company"]["legal_form"] == 2) echo 'selected=""'; ?> ><?php echo $ULang->t("ИП"); ?></option>
              </select>
            </div>
         </div>
         </div>       

         <div class="user-data-item" >
         <div class="row" >
            <div class="col-lg-4" >
              <label><?php echo $ULang->t("Название организации"); ?></label>
            </div>
            <div class="col-lg-8" >
              <input type="text" name="requisites_company[name_company]" maxlength="64" class="form-control" value="<?php if(isset($data["requisites_company"]["name_company"])) echo $data["requisites_company"]["name_company"]; ?>" >
            </div>
         </div>
         </div>

         <div class="user-requisites-legal-form-1" <?php if($data["requisites_company"]["legal_form"] == 1) echo 'style="display: block;"'; ?> >
             <div class="user-data-item" >
             <div class="row" >
                <div class="col-lg-4" >
                  <label><?php echo $ULang->t("КПП"); ?></label>
                </div>
                <div class="col-lg-8" >
                  <input type="text" name="requisites_company[kpp]" maxlength="64" class="form-control" value="<?php if(isset($data["requisites_company"]["kpp"])) echo $data["requisites_company"]["kpp"]; ?>" >
                </div>
             </div>
             </div> 
             <div class="user-data-item" >
             <div class="row" >
                <div class="col-lg-4" >
                  <label><?php echo $ULang->t("ОГРН"); ?></label>
                </div>
                <div class="col-lg-8" >
                  <input type="text" name="requisites_company[ogrn]" maxlength="64" class="form-control" value="<?php if(isset($data["requisites_company"]["ogrn"])) echo $data["requisites_company"]["ogrn"]; ?>" >
                </div>
             </div>
             </div>                                                 
         </div>

         <div class="user-requisites-legal-form-2" <?php if($data["requisites_company"]["legal_form"] == 2) echo 'style="display: block;"'; ?> >
             <div class="user-data-item" >
             <div class="row" >
                <div class="col-lg-4" >
                  <label><?php echo $ULang->t("ОГРНИП"); ?></label>
                </div>
                <div class="col-lg-8" >
                  <input type="text" name="requisites_company[ogrnip]" maxlength="64" class="form-control" value="<?php if(isset($data["requisites_company"]["ogrnip"])) echo $data["requisites_company"]["ogrnip"]; ?>" >
                </div>
             </div>
             </div>                         
         </div>

         <div class="user-data-item mt15 mb15" >
         <div class="row" >
            <div class="col-lg-4" >
            </div>
            <div class="col-lg-8" >
                <strong><h5><?php echo $ULang->t("Информация о банке"); ?></h5></strong>
            </div>
         </div>
         </div>

         <div class="user-data-item" >
         <div class="row" >
            <div class="col-lg-4" >
              <label><?php echo $ULang->t("Название банка"); ?></label>
            </div>
            <div class="col-lg-8" >
              <input type="text" name="requisites_company[name_bank]" maxlength="64" class="form-control" value="<?php if(isset($data["requisites_company"]["name_bank"])) echo $data["requisites_company"]["name_bank"]; ?>" >
            </div>
         </div>
         </div>

         <div class="user-data-item" >
         <div class="row" >
            <div class="col-lg-4" >
              <label><?php echo $ULang->t("Расчетный счет в банке"); ?></label>
            </div>
            <div class="col-lg-8" >
              <input type="text" name="requisites_company[payment_account_bank]" maxlength="64" class="form-control" value="<?php if(isset($data["requisites_company"]["payment_account_bank"])) echo $data["requisites_company"]["payment_account_bank"]; ?>" >
            </div>
         </div>
         </div>

         <div class="user-data-item" >
         <div class="row" >
            <div class="col-lg-4" >
              <label><?php echo $ULang->t("Корреспондентский счёт"); ?></label>
            </div>
            <div class="col-lg-8" >
              <input type="text" name="requisites_company[correspondent_account_bank]" maxlength="64" class="form-control" value="<?php if(isset($data["requisites_company"]["correspondent_account_bank"])) echo $data["requisites_company"]["correspondent_account_bank"]; ?>" >
            </div>
         </div>
         </div>

         <div class="user-data-item" >
         <div class="row" >
            <div class="col-lg-4" >
              <label><?php echo $ULang->t("БИК"); ?></label>
            </div>
            <div class="col-lg-8" >
              <input type="text" name="requisites_company[bik_bank]" maxlength="64" class="form-control" value="<?php if(isset($data["requisites_company"]["bik_bank"])) echo $data["requisites_company"]["bik_bank"]; ?>" >
            </div>
         </div>
         </div>

         <div class="user-data-item mt15 mb15" >
         <div class="row" >
            <div class="col-lg-4" >
            </div>
            <div class="col-lg-8" >
                <strong><h5><?php echo $ULang->t("Юридический адрес"); ?></h5></strong>
            </div>
         </div>
         </div>

         <div class="user-data-item" >
         <div class="row" >
            <div class="col-lg-4" >
              <label><?php echo $ULang->t("Почтовый индекс"); ?></label>
            </div>
            <div class="col-lg-8" >
              <input type="text" name="requisites_company[address_index]" maxlength="20" class="form-control" value="<?php if(isset($data["requisites_company"]["address_index"])) echo $data["requisites_company"]["address_index"]; ?>" >
            </div>
         </div>
         </div>

         <div class="user-data-item" >
         <div class="row" >
            <div class="col-lg-4" >
              <label><?php echo $ULang->t("Регион"); ?></label>
            </div>
            <div class="col-lg-8" >
              <input type="text" name="requisites_company[address_region]" maxlength="64" class="form-control" value="<?php if(isset($data["requisites_company"]["address_region"])) echo $data["requisites_company"]["address_region"]; ?>" >
            </div>
         </div>
         </div>

         <div class="user-data-item" >
         <div class="row" >
            <div class="col-lg-4" >
              <label><?php echo $ULang->t("Город"); ?></label>
            </div>
            <div class="col-lg-8" >
              <input type="text" name="requisites_company[address_city]" maxlength="64" class="form-control" value="<?php if(isset($data["requisites_company"]["address_city"])) echo $data["requisites_company"]["address_city"]; ?>" >
            </div>
         </div>
         </div>

         <div class="user-data-item" >
         <div class="row" >
            <div class="col-lg-4" >
              <label><?php echo $ULang->t("Улица"); ?></label>
            </div>
            <div class="col-lg-8" >
              <input type="text" name="requisites_company[address_street]" maxlength="64" class="form-control" value="<?php if(isset($data["requisites_company"]["address_street"])) echo $data["requisites_company"]["address_street"]; ?>" >
            </div>
         </div>
         </div>

         <div class="user-data-item" >
         <div class="row" >
            <div class="col-lg-4" >
              <label><?php echo $ULang->t("Дом"); ?></label>
            </div>
            <div class="col-lg-8" >
              <input type="text" name="requisites_company[address_house]" maxlength="10" class="form-control" value="<?php if(isset($data["requisites_company"]["address_house"])) echo $data["requisites_company"]["address_house"]; ?>" >
            </div>
         </div>
         </div>

         <div class="user-data-item" >
         <div class="row" >
            <div class="col-lg-4" >
              <label><?php echo $ULang->t("Офис"); ?></label>
            </div>
            <div class="col-lg-8" >
              <input type="text" name="requisites_company[address_office]" maxlength="10" class="form-control" value="<?php if(isset($data["requisites_company"]["address_office"])) echo $data["requisites_company"]["address_office"]; ?>" >
            </div>
         </div>
         </div>

         <div class="user-data-item mt15 mb15" >
         <div class="row" >
            <div class="col-lg-4" >
            </div>
            <div class="col-lg-8" >
                <strong><h5><?php echo $ULang->t("Информация о контактном лице"); ?></h5></strong>
            </div>
         </div>
         </div>

         <div class="user-data-item" >
         <div class="row" >
            <div class="col-lg-4" >
              <label><?php echo $ULang->t("ФИО контактного лица"); ?></label>
            </div>
            <div class="col-lg-8" >
              <input type="text" name="requisites_company[fio]" maxlength="64" class="form-control" value="<?php if(isset($data["requisites_company"]["fio"])) echo $data["requisites_company"]["fio"]; ?>" >
            </div>
         </div>
         </div>

         <div class="user-data-item" >
         <div class="row" >
            <div class="col-lg-4" >
              <label><?php echo $ULang->t("Телефон"); ?></label>
            </div>
            <div class="col-lg-8" >
              <input type="text" name="requisites_company[phone]" maxlength="30" class="form-control" value="<?php if(isset($data["requisites_company"]["phone"])) echo $data["requisites_company"]["phone"]; ?>" >
            </div>
         </div>
         </div>

         <div class="user-data-item" >
         <div class="row" >
            <div class="col-lg-4" >
              <label><?php echo $ULang->t("Email"); ?></label>
            </div>
            <div class="col-lg-8" >
              <input type="text" name="requisites_company[email]" maxlength="64" class="form-control" value="<?php if(isset($data["requisites_company"]["email"])) echo $data["requisites_company"]["email"]; ?>" >
            </div>
         </div>
         </div>

      </form>

      <div class="mt30" ></div>

      <div class="modal-custom-button" >
         <div>
           <button class="button-style-custom color-blue user-requisites-save" ><?php echo $ULang->t("Сохранить"); ?></button>
         </div> 
         <div>
           <button class="button-style-custom color-light button-click-close" ><?php echo $ULang->t("Закрыть"); ?></button>
         </div>                                       
      </div>

    </div>
</div>

<div class="modal-custom-bg bg-click-close" style="display: none;" id="modal-ad-packages" >
    <div class="modal-custom animation-modal" style="max-width: 630px" >

      <span class="modal-custom-close" ><i class="las la-times"></i></span>

      <h5 class="mb20" > <strong><?php echo $ULang->t("Выберите категорию"); ?></strong> </h5>
      
        <div class="packages-ads-container" >
          
        <?php

        $getPackagesCategories = $Profile->getPackagesCategories();

        ?>

        <select name="cat_id" class="form-control">
            <?php
               if( $getPackagesCategories["category_board_id_parent"][0] ){
                   foreach ($getPackagesCategories["category_board_id_parent"][0] as $value) {
                      ?>
                      <option value="<?php echo $value["category_board_id"]; ?>" > <?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?> </option>
                      <?php
                   }
               }
            ?>          
        </select>
          
        </div>

    </div>
</div>