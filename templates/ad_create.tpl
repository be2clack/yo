<!doctype html>
<html lang="<?php echo getLang(); ?>">
   <head>
      
      <title><?php echo $ULang->t("Публикация объявления"); ?></title>
      
      <?php include $config["template_path"] . "/head.tpl"; ?>

   </head>
   <body data-prefix="<?php echo $config["urlPrefix"]; ?>"  data-template="<?php echo $config["template_folder"]; ?>" >

      <div class="mt40" ></div>

      <div class="container mb100" >
        
           <div class="row" >
              
              <div class="col-lg-2" ></div>

              <div class="col-lg-6" >
                             
                   <h1 class="h1title" > <a class="a-prev-hover" href="<?php echo _link(); ?>"><i class="las la-arrow-left"></i></a> <?php echo $ULang->t("Публикация объявления"); ?> </h1>

                   <form class="ads-form-ajax" >

                      <div class="ads-create-main-category mt30" >
                          
                          <div class="row" >
                             <?php
                                if(count($getCategoryBoard["category_board_id_parent"][0])){
                                  foreach ($getCategoryBoard["category_board_id_parent"][0] as $key => $value) {
                                    ?>
                                      <div class="col-lg-3 col-6" >
                                        <div class="ads-create-main-category-list-item" data-id="<?php echo $value["category_board_id"]; ?>" >

                                          <div class="ads-create-main-category-icon-circle" style="background-color: <?php echo generateRandomColor(); ?>" ></div>

                                            <span class="ads-create-main-category-icon" >
                                              
                                            <img alt="<?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?>" src="<?php echo Exists($config["media"]["other"],$value["category_board_image"],$config["media"]["no_image"]); ?>">

                                            </span>
                                            <span class="ads-create-main-category-name" ><?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?></span>

                                        </div>
                                      </div>
                                    <?php
                                  }
                                }
                             ?>
                          </div>

                      </div>

                      <div class="ads-create-subcategory" ></div>

                      <div class="msg-error" data-name="c_id" ></div>

                      <div class="ads-create-main-data" >
                        
                           <div class="ads-create-main-data-title" ></div>
                           
                           <?php if($settings["main_type_products"] == 'electron'){ ?>
                           <div class="ads-create-main-data-box-item" >
                               <p class="ads-create-subtitle" ><?php echo $ULang->t("Электронный товар"); ?></p>
                               <p class="create-info" > <i class="las la-question-circle"></i> <?php echo $ULang->t("Укажите одну или через запятую несколько ссылок ведущих на ресурс или скачивание электронного товара."); ?></p>
                               <input type="text" name="electron_product_links" class="ads-create-input mt20" >
                               <div class="msg-error" data-name="electron_product_links" ></div>
                           </div>

                           <div class="ads-create-main-data-box-item" style="margin-bottom: 15px;" >
                               <p class="ads-create-subtitle" ><?php echo $ULang->t("Дополнительное описание"); ?></p>
                               <p class="create-info" > <i class="las la-question-circle"></i> <?php echo $ULang->t("Пользователь увидит данную информацию только после оплаты."); ?></p>
                               <textarea name="electron_product_text" class="ads-create-textarea mt20" rows="5" ></textarea>  
                           </div>
                           <?php } ?>

                           <div class="ads-create-main-data-box-item" style="margin-top: 0px;" >
                               <p class="ads-create-subtitle" ><?php echo $ULang->t("Фотографии"); ?></p>
                               <p class="create-info" > <i class="las la-question-circle"></i> <?php echo $ULang->t("Первое фото будет отображаться в результатах поиска, выберите наиболее удачное. Вы можете загрузить до"); ?> <?php echo $settings["count_images_add_ad"]; ?> <?php echo $ULang->t("фотографий в формате JPG или PNG. Максимальный размер фото"); ?> — <?php echo $settings["size_images_add_ad"]; ?>mb.</p>

                               <div id="dropzone" class="dropzone mt20 sortable" id="dropzone" ></div>
                               <div class="msg-error" data-name="gallery" ></div>
                           </div>
                           
                           <div class="ads-create-main-data-box-item" >
                               <p class="ads-create-subtitle" ><?php echo $ULang->t("Видео"); ?></p>
                               <p class="create-info" > <i class="las la-question-circle"></i> <?php echo $ULang->t("Можно добавить ссылку на видеоролик в Youtube, Rutube или Vimeo"); ?></p>
                               <input type="text" name="video" class="ads-create-input mt20" >
                           </div>
                           
                           <div class="ads-create-main-data-box-item" >
                               <p class="ads-create-subtitle" ><?php echo $ULang->t("Описание"); ?></p>
                               <textarea name="text" class="ads-create-textarea" rows="5" ></textarea>  
                               <p class="create-input-length" ><?php echo $ULang->t("Символов"); ?> <span>0</span> <?php echo $ULang->t("из"); ?> <?php echo $settings["ad_create_length_text"]; ?></p>
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

                           <div class="ads-create-main-data-filters" ></div>   

                           <div class="ads-create-main-data-online-view" ></div>     
                           <div class="ads-create-main-data-booking" ></div> 
                           <div class="ads-create-main-data-booking-options" ></div>    

                           <div class="ads-create-main-data-price" ></div>  
                           
                           <?php if($settings["main_type_products"] == 'physical'){ ?>
                           <div class="ads-create-main-data-available-box-booking" > 
                             <div class="ads-create-main-data-available" ></div>
                           </div>

                           <div class="ads-create-main-data-box-item" >
                              
                              <p class="ads-create-subtitle" ><?php echo $ULang->t("Город"); ?></p>

                              <div class="container-custom-search" >
                                <input type="text" autocomplete="nope" class="ads-create-input action-input-search-city" placeholder="<?php echo $ULang->t("Начните вводить город, а потом выберите его из списка"); ?>" value="<?php echo $data["user_geo"]["city_name"]; ?>" >
                                <div class="custom-results SearchCityResults SearchCityOptions" ></div>
                              </div>

                              <div class="msg-error" data-name="city_id" ></div>
                              <input type="hidden" name="city_id" value="<?php echo (int)$data["user_geo"]["city_id"]; ?>" > 

                           </div>

                           <div class="ads-create-main-data-city-options" ></div> 

                           <div class="ads-create-main-data-box-item" >
                                
                                <p class="ads-create-subtitle" ><?php echo $ULang->t("Адрес"); ?></p>

                                <div class="boxSearchAddress" >
                                   <input type="text" class="ads-create-input searchMapAddress" id="searchMapAddress" autocomplete="nope" placeholder="<?php echo $ULang->t("Начните вводить адрес, а потом выберите его из списка"); ?>" >
                                   <div class="custom-results SearchAddressResults" ></div>
                                </div>

                                <div class="msg-error" data-name="address" ></div>

                                <div class="mapAddress" id="mapAddress" ></div>
                                <input type="hidden" name="map_lat" value="0" >
                                <input type="hidden" name="map_lon" value="0" >

                           </div>
                           <?php }else{ ?>
                            <div class="ads-create-main-data-available" ></div>
                           <?php } ?>
                           
                           <?php if( $data["display_phone"] ){ ?>
                           <div class="ads-create-main-data-box-item" >

                              <p class="ads-create-subtitle" ><?php echo $ULang->t("Номер телефона"); ?></p>

                              <div class="ads-create-main-data-user-options" >
                                  
                                  <?php echo $ULang->t("Для публикации объявления необходимо указать номер телефона. Скрыть его или изменить Вы сможете в настройках профиля."); ?>
                                  
                                  <div class="ads-create-main-data-user-options-phone-1" >
                                  <div class="mt15" >
                                      <div class="row no-gutters" >
                                         <div class="col-lg-7" >
                                            <div class="input-phone-format" >
                                            <input type="text" name="phone" class="ads-create-input phone-mask create-phone" data-format="<?php echo getFormatPhone(); ?>" placeholder="<?php echo $ULang->t("Номер телефона"); ?>" >
                                            <?php echo outBoxChangeFormatPhone(); ?>
                                            </div>

                                            <div class="msg-error" data-name="phone" ></div>
                                         </div>
                                         <div class="col-lg-5" >
                                            <div class="ads-create-main-data-user-options-phone-buttons" >
                                            <?php if($settings["confirmation_phone"]){ ?>
                                            <button class="btn-custom-mini btn-color-green create-accept-phone" ><?php echo $ULang->t("Подтвердить"); ?></button>
                                            <?php }else{ ?>
                                            <button class="btn-custom-mini btn-color-green create-save-phone" ><?php echo $ULang->t("Сохранить"); ?></button>
                                            <button class="btn-custom-mini btn-color-blue create-save-phone-cancel" ><?php echo $ULang->t("Изменить номер"); ?></button>
                                            <?php } ?>
                                            </div>
                                         </div>                               
                                      </div>
                                  </div>
                                  </div>

                                  <div class="ads-create-main-data-user-options-phone-2" >
                                  <div class="mt15" >
                                      <div class="row no-gutters" >
                                         <div class="col-lg-7" >
                                            <input type="text" class="ads-create-input create-verify-phone" maxlength="4" placeholder="<?php if($settings["sms_service_method_send"] == 'call'){ echo $ULang->t("Укажите 4 последние цифры номера"); }else{ echo $ULang->t("Укажите код из смс"); } ?>" >
                                            <div class="msg-error" data-name="verify-code" ></div>
                                         </div>
                                         <div class="col-lg-5" >
                                            <div class="ads-create-main-data-user-options-phone-buttons" >
                                            <button class="btn-custom-mini btn-color-blue create-cancel-phone" ><?php echo $ULang->t("Изменить номер"); ?></button>
                                            </div>
                                         </div>                               
                                      </div>
                                  </div>
                                  </div>                        


                              </div>
                           </div>
                           <?php } ?>

                           <?php if($_SESSION['profile']['tariff']['services']['scheduler']){ ?>
                           
                           <div class="ads-create-main-data-box-item" >
                                
                                <p class="ads-create-subtitle" >
                                    <?php echo $ULang->t("Автопубликация"); ?>
                                    <label class="checkbox ml10">
                                      <input type="checkbox" name="renewal" value="1"  >
                                      <span></span>
                                    </label>                                        
                                </p>

                                <p class="create-info mt10" > <i class="las la-question-circle"></i> <?php echo $ULang->t("Ваше объявление опубликуется повторно, когда закончится срок размещения"); ?></p>
                               
                           </div>

                           <?php } ?>

                           <div class="ads-create-main-data-delivery" ></div>

                           <button class="ads-create-publish btn-color-blue" data-action="ad_create" > <span class="action-load-span-start" > <i class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></i> </span> <?php echo $ULang->t("Опубликовать"); ?></button>
                           
                      </div>


                      <input type="hidden" name="currency" value="<?php echo $settings["currency_main"]["code"]; ?>" >
                      <input type="hidden" name="c_id" value="0"  >
                      <input type="hidden" name="var_price" value=""  >
                      <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>" >

                   </form>
                                  
              </div>

              <div class="col-lg-4" >
                
                <div class="mt30 ad-create-sidebar" ></div>

              </div>

           </div>

      </div>


      <div class="mt45" ></div>

      <?php include $config["template_path"] . "/footer.tpl"; ?>

      <script type="text/javascript">
      
       Dropzone.autoDiscover = false;
       $(document).ready(function() {

            $( ".sortable" ).sortable({ handle: '.sortable-handle', zIndex: 1000 });

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
                  thisDropzone = this;
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

       $(function(){  

            if($('input[name=city_id]').val() != 0){
                  $.ajax({
                    type: "POST",
                    url: $("body").data("prefix") + "systems/ajax/controller.php",
                    data: "id=" + $('input[name=city_id]').val() + "&action=geo/city_options",
                    dataType: "html",
                    cache: false,
                    success: function (data) {

                      $(".ads-create-main-data-city-options").html( data ).show();

                    }
                  });
            }

       });

      </script>

      <?php echo $Geo->vendorMap(); ?>

      <?php echo $Ads->mapAdAddress(); ?>

   </body>
</html>