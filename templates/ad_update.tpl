<!doctype html>
<html lang="<?php echo getLang(); ?>">
   <head>
      
      <title><?php echo $data["ads_title"]; ?></title>
      
      <?php include $config["template_path"] . "/head.tpl"; ?>

   </head>
   <body data-prefix="<?php echo $config["urlPrefix"]; ?>" data-template="<?php echo $config["template_folder"]; ?>" >
      
      <div class="mt40" ></div>

      <div class="preload" >
          <div class="spinner-grow mt80 preload-spinner" role="status">
            <span class="sr-only"></span>
          </div>
      </div>

      <div class="container display-load-page" >
        
           <div class="row" >
              
              <div class="col-lg-2" ></div>

              <div class="col-lg-6" >

                   <h1 class="h1title" > <a class="a-prev-hover" href="<?php echo $Ads->alias($data); ?>"><i class="las la-arrow-left"></i></a> <?php echo $ULang->t("Редактирование объявления"); ?> </h1>
                             
                   <form class="ads-form-ajax mt30" >

                      <div class="ads-update-main-data" >
                        
                           <div class="ads-create-main-data-box-item" style="margin-top: 0px; margin-bottom: 25px;" >
                                <p class="ads-create-subtitle" ><?php echo $ULang->t("Категория"); ?></p>
                                
                                <div class="ads-update-category-box" >
                                   <span><?php echo $CategoryBoard->breadcrumb($getCategoryBoard,$data["ads_id_cat"],'{NAME}',' › '); ?></span>
                                   <div class="ads-update-category-list" >
                                      <?php echo $data["list_categories"]; ?>
                                   </div>
                                </div>

                                <div class="msg-error" data-name="c_id" ></div>
                           </div>

                           <div class="ads-create-main-data-title" >
                             
                              <?php
                                  if( !$getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_auto_title"] ){

                                     ?>

                                        <div class="ads-create-main-data-box-item" style="margin-top: 0px; margin-bottom: 25px;" >
                                            <p class="ads-create-subtitle" ><?php echo $ULang->t("Название"); ?></p>
                                            <input type="text" name="title" class="ads-create-input" value="<?php echo $data["ads_title"]; ?>" >
                                            <p class="create-input-length" ><?php echo $ULang->t("Символов"); ?> <span><?php echo mb_strlen($data["ads_title"], "UTF-8"); ?></span> <?php echo $ULang->t("из"); ?> <?php echo $settings["ad_create_length_title"]; ?></p>
                                            <div class="msg-error" data-name="title" ></div>
                                        </div>

                                     <?php

                                  }
                              ?>

                           </div>
                           
                           <?php if($settings["main_type_products"] == 'electron'){ ?>
                           <div class="ads-create-main-data-box-item" >
                               <p class="ads-create-subtitle" ><?php echo $ULang->t("Электронный товар"); ?></p>
                               <p class="create-info" > <i class="las la-question-circle"></i> <?php echo $ULang->t("Укажите одну или через запятую несколько ссылок ведущих на ресурс или скачивание электронного товара."); ?></p>
                               <input type="text" name="electron_product_links" class="ads-create-input mt20" value="<?php echo $data["ads_electron_product_links"]; ?>" >
                               <div class="msg-error" data-name="electron_product_links" ></div>
                           </div>

                           <div class="ads-create-main-data-box-item" style="margin-bottom: 15px;" >
                               <p class="ads-create-subtitle" ><?php echo $ULang->t("Дополнительное описание"); ?></p>
                               <p class="create-info" > <i class="las la-question-circle"></i> <?php echo $ULang->t("Пользователь увидит данную информацию только после оплаты."); ?></p>
                               <textarea name="electron_product_text" class="ads-create-textarea mt20" rows="5" ><?php echo $data["ads_electron_product_text"]; ?></textarea>  
                           </div>
                           <?php } ?>

                           <div class="ads-create-main-data-box-item" style="margin-top: 0px;" >
                               <p class="ads-create-subtitle" ><?php echo $ULang->t("Фотографии"); ?></p>
                               <p class="create-info" > <i class="las la-question-circle"></i> <?php echo $ULang->t("Первое фото будет отображаться в результатах поиска, выберите наиболее удачное. Вы можете загрузить до"); ?> <?php echo $settings["count_images_add_ad"]; ?> <?php echo $ULang->t("фотографий в формате JPG или PNG. Максимальный размер фото"); ?> — <?php echo $settings["size_images_add_ad"]; ?>mb.</p>

                               <div id="dropzone" class="dropzone mt20 sortable" id="dropzone" >
                                 
                                   <?php
                                     $gallery = $Ads->getImages($data["ads_images"]);
                                     if($gallery){
                                       foreach ($gallery as $key => $name) {
                                        $uid = uniqid();
                                          ?>
                                            <div class="dz-preview dz-preview-custom">
                                               <div class="dz-image"><img class="image-autofocus" alt="<?php echo $name; ?>" src="<?php echo Exists($config["media"]["big_image_ads"],$name,$config["media"]["no_image"]); ?>?<?php echo $uid; ?>"></div>
                                               <div class="dz-details">
                                                  <div class="dz-size"><span data-dz-size=""><?php echo calcFilesize( filesize( $config["basePath"] . "/" . $config["media"]["big_image_ads"] . "/" . $name ) ); ?></span></div>
                                                  <div class="dz-filename"><span data-dz-name="<?php echo $name; ?>"><?php echo $name; ?></span></div>
                                               </div>                                                                             
                                               <div class="dz-dropzone-delete" ><i class="las la-trash-alt"></i></div>
                                               <div class="dz-dropzone-sortable sortable-handle"><i class="las la-arrows-alt"></i></div>
                                               <input type="hidden" name="gallery[<?php echo $uid; ?>]" value="<?php echo $name; ?>" style="display: none;">
                                            </div>                                  
                                          <?php
                                       }
                                     }
                                   ?>

                               </div>
                               <div class="msg-error" data-name="gallery" ></div>
                           </div>
                           
                           <div class="ads-create-main-data-box-item" >
                               <p class="ads-create-subtitle" ><?php echo $ULang->t("Видео"); ?></p>
                               <p class="create-info" > <i class="las la-question-circle"></i> <?php echo $ULang->t("Можно добавить ссылку на видеоролик в Youtube, Rutube или Vimeo"); ?></p>
                               <input type="text" name="video" class="ads-create-input mt20" value="<?php echo $data["ads_video"]; ?>" >
                           </div>
                           
                           <div class="ads-create-main-data-box-item" >
                               <p class="ads-create-subtitle" ><?php echo $ULang->t("Описание"); ?></p>
                               <textarea name="text" class="ads-create-textarea" rows="7" ><?php echo $data["ads_text"]; ?></textarea>  
                               <p class="create-input-length" ><?php echo $ULang->t("Символов"); ?> <span><?php echo mb_strlen($data["ads_text"], "UTF-8"); ?></span> <?php echo $ULang->t("из"); ?> <?php echo $settings["ad_create_length_text"]; ?></p>
                               <div class="msg-error" data-name="text" ></div> 
                           </div> 

                           <?php if( $settings["ad_create_period"] ){ ?>
                           <div class="ads-create-main-data-box-item" >
                                <p class="ads-create-subtitle" ><?php echo $ULang->t("Срок публикации"); ?></p>
                                <div class="row" >
                                  <div class="col-lg-6" >
                                    
                                       <div class="uni-select" data-status="0">

                                           <div class="uni-select-name" data-name="<?php echo $ULang->t("Не выбрано"); ?>"> <span><?php echo $ULang->t("Не выбрано"); ?></span> <i class="la la-angle-down"></i> </div>
                                           <div class="uni-select-list">
                                               
                                                <?php echo $list_period; ?>
                                
                                           </div>
                                          
                                        </div>

                                  </div>
                                </div>
                            </div>
                            <?php } ?>                 

                           <div class="ads-create-main-data-filters" <?php if( $data["filters"] ){ echo 'style="display: block;"'; } ?> >
                                 
                                 <?php echo $data["filters"]; ?>

                           </div>   

                           <div class="ads-create-main-data-online-view" <?php if($getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_online_view"]){ echo 'style="display: block;"'; } ?> >
                             
                             <?php
                                  if( $getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_online_view"] ){

                                     ?>

                                         <div class="ads-create-main-data-box-item" >
                                            <p class="ads-create-subtitle" ><?php echo $ULang->t("Возможен онлайн-показ"); ?></p>
                                            <div class="create-info" ><i class="las la-question-circle"></i> <?php echo $ULang->t("Выберите, если готовы показать товар/объект с помощью видео-звонка — например, через WhatsApp, Viber, Skype или другой сервис"); ?></div>
                                            <div class="custom-control custom-checkbox mt15">
                                                <input type="checkbox" class="custom-control-input" name="online_view" <?php if($data["ads_online_view"]){ echo 'checked=""'; } ?> id="online_view" value="1">
                                                <label class="custom-control-label" for="online_view"><?php echo $ULang->t("Готовы показать онлайн"); ?></label>
                                            </div>
                                         </div>

                                     <?php

                                  }
                             ?>

                           </div>     

                           <?php if( $getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_booking"] ){ ?>
                            
                           <div class="ads-create-main-data-booking" >
                             
                             <?php
                                  
                                 if($getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_booking_variant"] == 0){

                                 ?>

                                     <div class="ads-create-main-data-box-item" >
                                        <p class="ads-create-subtitle" ><?php echo $ULang->t("Онлайн-бронирование"); ?></p>
                                        <div class="create-info" ><i class="las la-question-circle"></i> <?php echo $ULang->t("Выберите, если хотите сдавать объект в аренду. Пользователи смогут бронировать онлайн."); ?></div>
                                        <div class="custom-control custom-checkbox mt15">
                                            <input type="checkbox" class="custom-control-input" name="booking" <?php if($data["ads_booking"]){ echo 'checked=""'; } ?> id="booking" value="1">
                                            <label class="custom-control-label" for="booking"><?php echo $ULang->t("Онлайн-бронирование"); ?></label>
                                        </div>
                                     </div>

                                 <?php

                                 }elseif($getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_booking_variant"] == 1){

                                 ?>

                                     <div class="ads-create-main-data-box-item" >
                                        <p class="ads-create-subtitle" ><?php echo $ULang->t("Онлайн-аренда"); ?></p>
                                        <div class="create-info" ><i class="las la-question-circle"></i> <?php echo $ULang->t("Выберите, если хотите сдавать товар/объект в аренду. Пользователи смогут брать в аренду онлайн."); ?></div>
                                        <div class="custom-control custom-checkbox mt15">
                                            <input type="checkbox" class="custom-control-input" name="booking" <?php if($data["ads_booking"]){ echo 'checked=""'; } ?> id="booking" value="1">
                                            <label class="custom-control-label" for="booking"><?php echo $ULang->t("Онлайн-аренда"); ?></label>
                                        </div>
                                     </div>

                                 <?php

                                 }

                             ?>

                           </div>

                           <div class="ads-create-main-data-booking-options" <?php if($data["ads_booking"]){ echo 'style="display:block;"'; } ?> >
                               <?php
                                     if($getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_booking_variant"] == 0){

                                     ?>

                                       <div class="ads-create-main-data-box-item" >

                                           <p class="ads-create-subtitle" ><?php echo $ULang->t("Предоплата"); ?></p>

                                           <div class="create-info"><i class="las la-question-circle"></i> <?php echo $ULang->t("Оставьте это поле пустым если предоплата не требуется."); ?></div>

                                           <div class="mb15" ></div>

                                           <div class="row" >
                                            
                                            <div class="col-lg-6" >
                                                <div class="input-dropdown" >
                                                   <input type="number" name="booking_prepayment_percent" placeholder="<?php echo $ULang->t("Процент предоплаты"); ?>" value="<?php echo $data["ads_booking_prepayment_percent"]; ?>" class="ads-create-input" maxlength="3" > 
                                                   <div class="input-dropdown-box">
                                                      <div class="uni-dropdown-align" >
                                                         <span class="input-dropdown-name-display">%</span>
                                                      </div>
                                                   </div>
                                                </div>
                                            </div>
                                             
                                           </div>

                                           <div class="mb25" ></div>

                                           <p class="ads-create-subtitle" ><?php echo $ULang->t("Максимальное количество гостей"); ?></p>

                                           <div class="row" >
                                            
                                            <div class="col-lg-6" >
                                                <input type="number" name="booking_max_guests" class="ads-create-input" maxlength="11" value="<?php echo $data["ads_booking_max_guests"]; ?>" >
                                            </div>
                                             
                                           </div>

                                           <div class="mb25" ></div>

                                           <div class="create-info"><i class="las la-question-circle"></i> <?php echo $ULang->t("Оставьте эти поля пустыми если ограничений нет."); ?></div>

                                           <div class="mb15" ></div>

                                           <p class="ads-create-subtitle" ><?php echo $ULang->t("Минимум дней аренды"); ?></p>

                                           <div class="row" >
                                            
                                            <div class="col-lg-6" >
                                                <input type="number" name="booking_min_days" class="ads-create-input" maxlength="11" value="<?php echo $data["ads_booking_min_days"]; ?>" >
                                            </div>
                                             
                                           </div>

                                           <div class="mb25" ></div>

                                           <p class="ads-create-subtitle" ><?php echo $ULang->t("Максимум дней аренды"); ?></p>

                                           <div class="row" >
                                            
                                            <div class="col-lg-6" >
                                                <input type="number" name="booking_max_days" class="ads-create-input" maxlength="11" value="<?php echo $data["ads_booking_max_days"]; ?>" >
                                            </div>
                                             
                                           </div>

                                           <div class="mb25" ></div>

                                           <p class="ads-create-subtitle data-count-services" data-count-services="<?php echo $settings['count_add_booking_additional_services']; ?>" ><?php echo $ULang->t("Дополнительные услуги"); ?> <span class="booking-additional-services-item-add btn-custom-mini btn-custom-mini-icon btn-color-blue-light" ><i class="las la-plus"></i></span></p>

                                           <div class="booking-additional-services-container" ><?php echo $data['booking_additional_services']; ?></div>

                                           <div class="mb25" ></div>
                                       </div>

                                     <?php

                                     }elseif($getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_booking_variant"] == 1){

                                     ?>

                                       <div class="ads-create-main-data-box-item" >

                                           <p class="ads-create-subtitle" ><?php echo $ULang->t("Предоплата"); ?></p>

                                           <div class="create-info"><i class="las la-question-circle"></i> <?php echo $ULang->t("Оставьте это поле пустым если предоплата не требуется."); ?></div>

                                           <div class="mb15" ></div>

                                           <div class="row" >
                                            
                                            <div class="col-lg-6" >
                                                <div class="input-dropdown" >
                                                   <input type="number" name="booking_prepayment_percent" placeholder="<?php echo $ULang->t("Процент предоплаты"); ?>" value="<?php echo $data["ads_booking_prepayment_percent"]; ?>" class="ads-create-input" maxlength="3" > 
                                                   <div class="input-dropdown-box">
                                                      <div class="uni-dropdown-align" >
                                                         <span class="input-dropdown-name-display">%</span>
                                                      </div>
                                                   </div>
                                                </div>
                                            </div>
                                             
                                           </div>

                                           <div class="mb25" ></div>

                                           <p class="ads-create-subtitle" ><?php echo $ULang->t("Доступно"); ?></p>

                                           <div class="create-info"><i class="las la-question-circle"></i> <?php echo $ULang->t("Укажите сколько единиц доступно для аренды. По истечению лимита аренда будет недоступна. Система автоматически вернет возможность аренды после того, как у пользователя закончится выбранный срок."); ?></div>

                                           <div class="mb15" ></div>

                                           <div class="row" >
                                            
                                            <div class="col-lg-6" >
                                                <input type="number" name="booking_available" placeholder="<?php echo $ULang->t("Доступно"); ?>" value="<?php echo $data["ads_booking_available"]; ?>" class="ads-create-input" maxlength="3" >
                                            </div>
                                             
                                            <div class="col-lg-6" >
                                                <div class="custom-control custom-checkbox mt10">
                                                    <input type="checkbox" class="custom-control-input" name="booking_available_unlimitedly" id="booking_available_unlimitedly" value="1" <?php if($data["ads_booking_available_unlimitedly"]){ echo 'checked=""'; } ?> >
                                                    <label class="custom-control-label" for="booking_available_unlimitedly"><?php echo $ULang->t("Неограниченно"); ?></label>
                                                </div>                                                
                                            </div>

                                           </div>

                                           <div class="mb25" ></div>

                                           <p class="ads-create-subtitle data-count-services" data-count-services="<?php echo $settings['count_add_booking_additional_services']; ?>" ><?php echo $ULang->t("Дополнительные услуги"); ?> <span class="booking-additional-services-item-add btn-custom-mini btn-custom-mini-icon btn-color-blue-light" ><i class="las la-plus"></i></span></p>

                                           <div class="booking-additional-services-container" ><?php echo $data['booking_additional_services']; ?></div>

                                           <div class="mb25" ></div>
                                       </div>

                                     <?php

                                     }
                               ?>
                           </div>

                           <?php } ?>    

                           <div class="ads-create-main-data-price" <?php if( $data["price"] ){ echo 'style="display: block;"'; } ?> >
                             
                                 <?php echo $data["price"]; ?>

                           </div>  

                           <?php if($settings["main_type_products"] == 'physical'){ ?>

                           <div class="ads-create-main-data-available-box-booking" <?php if( $data["ads_booking"] ){ echo 'style="display: none;"'; } ?> >
                           <div class="ads-create-main-data-available" <?php if( $data["available"] ){ echo 'style="display: block;"'; } ?> >

                                <?php if( $Cart->modeAvailableCart($getCategoryBoard,$data["ads_id_cat"],$data["ads_id_user"]) ){ ?>
                                  <div class="ads-create-main-data-box-item" >

                                      <p class="ads-create-subtitle" ><?php echo $ULang->t("В наличии"); ?></p>

                                      <div class="row" >
                                        
                                        <div class="col-lg-6" >
                                            <input type="text" name="available" value="<?php echo $data["ads_available"]; ?>" class="ads-create-input inputNumber" maxlength="5" >
                                            <div class="msg-error" data-name="available" ></div>
                                        </div>
                                        
                                        <div class="col-lg-6" >

                                            <div class="custom-control custom-checkbox mt10">
                                                <input type="checkbox" class="custom-control-input" name="available_unlimitedly" <?php if($data["ads_available_unlimitedly"]){ echo 'checked=""'; } ?> id="available_unlimitedly" value="1">
                                                <label class="custom-control-label" for="available_unlimitedly"><?php echo $ULang->t("Неограниченно"); ?></label>
                                            </div>

                                        </div> 

                                      </div>

                                  </div>
                                <?php } ?>

                           </div>
                           </div>

                           <div class="ads-create-main-data-box-item" >
                              
                              <p class="ads-create-subtitle" ><?php echo $ULang->t("Город"); ?></p>

                              <div class="container-custom-search" >
                                <input type="text" autocomplete="nope" class="ads-create-input action-input-search-city" value="<?php echo $data["city_name"]; ?>" placeholder="<?php echo $ULang->t("Начните вводить город, а потом выберите его из списка"); ?>" >
                                <div class="custom-results SearchCityResults SearchCityOptions" ></div>
                              </div>

                              <div class="msg-error" data-name="city_id" ></div>
                              <input type="hidden" name="city_id" value="<?php echo $data["ads_city_id"]; ?>" > 

                           </div>

                           <div class="ads-create-main-data-city-options" <?php if( $data["city_options"] ){ echo 'style="display: block;"'; } ?> >
                               
                               <?php echo $data["city_options"]; ?>

                           </div> 

                           <div class="ads-create-main-data-box-item" >
                                
                                <p class="ads-create-subtitle" ><?php echo $ULang->t("Адрес"); ?></p>

                                <div class="boxSearchAddress" >
                                   <input type="text" class="ads-create-input searchMapAddress" id="searchMapAddress" value="<?php echo $data["ads_address"]; ?>" autocomplete="nope" name="address" placeholder="<?php echo $ULang->t("Начните вводить адрес, а потом выберите его из списка"); ?>" >
                                   <div class="custom-results SearchAddressResults" ></div>
                                </div>

                                <div class="msg-error" data-name="address" ></div>

                                <div class="mapAddress" id="mapAddress" ></div>
                                <input type="hidden" name="map_lat" value="<?php echo $data["ads_latitude"]; ?>" >
                                <input type="hidden" name="map_lon" value="<?php echo $data["ads_longitude"]; ?>" >

                           </div>
                           <?php }else{ ?>

                           <div class="ads-create-main-data-available" <?php if( $data["available"] ){ echo 'style="display: block;"'; } ?> >

                                <?php if( $Cart->modeAvailableCart($getCategoryBoard,$data["ads_id_cat"],$data["ads_id_user"]) ){ ?>
                                  <div class="ads-create-main-data-box-item" >

                                      <p class="ads-create-subtitle" ><?php echo $ULang->t("В наличии"); ?></p>

                                      <div class="row" >
                                        
                                        <div class="col-lg-6" >
                                            <input type="text" name="available" value="<?php echo $data["ads_available"]; ?>" class="ads-create-input inputNumber" maxlength="5" >
                                            <div class="msg-error" data-name="available" ></div>
                                        </div>
                                        
                                        <div class="col-lg-6" >

                                            <div class="custom-control custom-checkbox mt10">
                                                <input type="checkbox" class="custom-control-input" name="available_unlimitedly" <?php if($data["ads_available_unlimitedly"]){ echo 'checked=""'; } ?> id="available_unlimitedly" value="1">
                                                <label class="custom-control-label" for="available_unlimitedly"><?php echo $ULang->t("Неограниченно"); ?></label>
                                            </div>

                                        </div> 

                                      </div>

                                  </div>
                                <?php } ?>

                           </div>

                           <?php } ?>
                           
                           <?php if($_SESSION['profile']['tariff']['services']['scheduler']){ ?>
                           
                           <div class="ads-create-main-data-box-item" >
                                
                                <p class="ads-create-subtitle" >
                                    <?php echo $ULang->t("Автопубликация"); ?>
                                    <label class="checkbox ml10">
                                      <input type="checkbox" name="renewal" value="1" <?php if($data["ads_auto_renewal"]){ echo 'checked=""'; } ?>  >
                                      <span></span>
                                    </label>                                        
                                </p>

                                <p class="create-info mt10" > <i class="las la-question-circle"></i> <?php echo $ULang->t("Ваше объявление опубликуется повторно, когда закончится срок размещения"); ?></p>
                               
                           </div>

                           <?php } ?> 
                           
                           <div class="ads-create-main-data-delivery" >

                           <?php if($settings["main_type_products"] == 'physical' && $_SESSION['profile']['data']['clients_delivery_status']){ ?>
                           
                           <div class="ads-create-main-data-box-item" >
                                
                                <p class="ads-create-subtitle" >
                                    <?php echo $ULang->t("Доставка"); ?>
                                    <label class="checkbox ml10">
                                      <input type="checkbox" name="delivery_status" value="1" <?php if($data["ads_delivery_status"]){ echo 'checked=""'; } ?>  >
                                      <span></span>
                                    </label>                                        
                                </p>
                                
                                <div class="ads-create-box-delivery" <?php if($data["ads_delivery_status"]){ echo 'style="display: block;"'; } ?> >
                                 <p class="create-info mt10" > <i class="las la-question-circle"></i> <?php echo $ULang->t("Укажите примерный вес товара, необходимо для службы доставки"); ?></p>
                                 
                                 <div class="row no-gutters mt20" >
                                          <div class="col-lg-6" >

                                             <div class="input-dropdown">
                                                
                                                <input type="text" name="delivery_weight" value="<?php echo $data["ads_delivery_weight"]; ?>" class="ads-create-input" maxlength="6" > 
                                          
                                                <div class="input-dropdown-box">
                                                   <div class="uni-dropdown-align">
                                                      <span class="input-dropdown-name-display"><?php echo $ULang->t("грамм"); ?></span>
                                                   </div>
                                                </div>
                                 
                                             </div>                                          
                                             <div class="msg-error" data-name="delivery_weight" ></div>

                                          </div>                              
                                    </div>
                                 </div>
                                 
                           </div>

                           <?php } ?>

                           </div>

                           <button class="ads-create-publish btn-color-blue" data-action="ad_update" > <span class="action-load-span-start" > <i class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></i> </span> <?php echo $ULang->t("Сохранить и опубликовать"); ?></button>
                           
                      </div>


                      <input type="hidden" name="currency" value="<?php echo $data["ads_currency"]; ?>" >
                      <input type="hidden" name="c_id" value="<?php echo $data["ads_id_cat"]; ?>"  >
                      <input type="hidden" name="id_ad" value="<?php echo $data["ads_id"]; ?>"  >
                      <input type="hidden" name="var_price" value="<?php if($data["ads_auction"]){ echo 'auction'; }elseif($data["ads_price_from"]){ echo 'from'; }else{ echo 'fix'; } ?>"  >
                      <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>" >

                   </form>
                                  
              </div>

              <div class="col-lg-4" ></div>

           </div>

      </div>


      <div class="mt45" ></div>

      <?php include $config["template_path"] . "/footer.tpl"; ?>

      <script type="text/javascript">
      Dropzone.autoDiscover = false;
      $(document).ready(function() {

        $( ".sortable" ).sortable({ handle: '.sortable-handle', zIndex: 1000 });

        $(document).on('click','.dz-dropzone-delete', function (e) { 
            
            $(this).parent().find("input").remove();
            $(this).parent().remove().hide();

        });

        var myDrop= new Dropzone("#dropzone", {
          paramName: "file",
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },           
          acceptedFiles: "image/jpeg,image/png",
          maxFiles: <?php echo $settings["count_images_add_ad"]; ?>,
          url: $("body").data("prefix") + 'systems/ajax/dropzone.php',
          maxFilesize: <?php echo $settings["size_images_add_ad"]; ?>,
          timeout: 300000,
          dictDefaultMessage: '<?php echo $ULang->t('Выберите или перетащите изображения'); ?>',
          init: function() {
              this.on("addedfile", function(file) {
                  var removeButton = Dropzone.createElement("<div class='dz-dropzone-delete' ><i class='las la-trash-alt'></i></div>");
                  var sortableButton = Dropzone.createElement("<div class='dz-dropzone-sortable sortable-handle' ><i class='las la-arrows-alt'></i></div>");
                  var _this = this;
                  removeButton.addEventListener("click", function(e) {
                      e.preventDefault();
                      e.stopPropagation();
                      _this.removeFile(file);
                  });
                  file.previewElement.appendChild(removeButton);
                  file.previewElement.appendChild(sortableButton);
              });
              this.on('completemultiple', function(file, json) {
              });        
          },
          success: function(file, response){

            var response = jQuery.parseJSON( response );
            file.previewElement.appendChild( Dropzone.createElement(response["input"]) );

            $( file.previewTemplate ).find("img").attr( "src", response["link"] );
            $( file.previewTemplate ).find("img").addClass( "image-autofocus" );
                   
          }
        });
      });
      </script>

      <?php echo $Geo->vendorMap(); ?>

      <?php echo $Ads->mapAdAddress($data["ads_latitude"],$data["ads_longitude"]); ?>

   </body>
</html>