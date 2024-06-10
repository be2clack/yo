<?php
$app_widgets_home_screen = $settings["app_widgets_home_screen"] ? json_decode($settings["app_widgets_home_screen"], true) : [];
$app_widgets_home_tabs = $settings["app_widgets_home_tabs"] ? json_decode($settings["app_widgets_home_tabs"]) : ['feed'=>1,'recommendations_ads'=>1,'fresh_ads'=>1,'auction'=>1,'shops'=>1];
$app_home_header_banner = $settings["app_home_header_banner"] ? json_decode($settings["app_home_header_banner"], true) : [];
?>
<div class="tab-pane fade <?php if($tab == "app"){ echo 'active show'; } ?>" id="tab-app" role="tabpanel" aria-labelledby="tab-app">

<div class="form-group row d-flex align-items-center mb-5">
   <label class="col-lg-3 form-control-label">Мобильное приложение</label>
   <div class="col-lg-9">
         <label>
         <input class="toggle-checkbox-sm" type="checkbox" name="app_available_status" value="1" <?php if($settings["app_available_status"]){ echo ' checked=""'; } ?> >
         <span><span></span></span>
         </label>
   </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">Название проекта</label>
  <div class="col-lg-6">

      <input type="text" class="form-control" name="app_name_project" value="<?php echo $settings['app_name_project']; ?>" >

  </div>
</div>           

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">Версия приложения</label>
  <div class="col-lg-6">

      <input type="text" class="form-control" name="app_version" value="<?php echo $settings['app_version']; ?>" >

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">Ссылка на apk новой версии</label>
  <div class="col-lg-6">

      <input type="text" class="form-control" name="app_download_link" value="<?php echo $settings['app_download_link']; ?>" >

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label"></label>
  <div class="col-lg-6">
      <h3 style="margin-top: 15px"> <strong>Скачивание</strong> </h3>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">Ссылка на файл apk</label>
  <div class="col-lg-6">

      <input type="text" class="form-control" name="app_download_links[apk]" value="<?php if(isset($settings['app_download_links']['apk'])) echo $settings['app_download_links']['apk']; ?>" >

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">Play Market</label>
  <div class="col-lg-6">

      <input type="text" class="form-control" name="app_download_links[play_market]" value="<?php if(isset($settings['app_download_links']['play_market'])) echo $settings['app_download_links']['play_market']; ?>" >

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">AppStore</label>
  <div class="col-lg-6">

      <input type="text" class="form-control" name="app_download_links[app_store]" value="<?php if(isset($settings['app_download_links']['app_store'])) echo $settings['app_download_links']['app_store']; ?>" >

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">RuStore</label>
  <div class="col-lg-6">

      <input type="text" class="form-control" name="app_download_links[ru_store]" value="<?php if(isset($settings['app_download_links']['ru_store'])) echo $settings['app_download_links']['ru_store']; ?>" >

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">AppGallery</label>
  <div class="col-lg-6">

      <input type="text" class="form-control" name="app_download_links[app_gallery]" value="<?php if(isset($settings['app_download_links']['app_gallery'])) echo $settings['app_download_links']['app_gallery']; ?>" >

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">Ссылка на пользовательское соглашение</label>
  <div class="col-lg-6">

      <input type="text" class="form-control" name="app_user_agreement_link" value="<?php echo $settings['app_user_agreement_link']; ?>" >

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">Ссылка на политику конфиденциальности</label>
  <div class="col-lg-6">

      <input type="text" class="form-control" name="app_privacy_policy_link" value="<?php echo $settings['app_privacy_policy_link']; ?>" >

  </div>
</div>

<hr>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">Отображать на главном экране</label>
  <div class="col-lg-6">

      <select class="selectpicker" title="Не выбрано" multiple name="app_widgets_home_screen[]" >
         <option value="categories" <?php if( in_array( "categories" , $app_widgets_home_screen ) ){ echo ' selected=""'; } ?> >Категории</option>
         <option value="stories" <?php if( in_array( "stories" , $app_widgets_home_screen ) ){ echo ' selected=""'; } ?> >Истории пользователей</option>
         <option value="map_ads" <?php if( in_array( "map_ads" , $app_widgets_home_screen ) ){ echo ' selected=""'; } ?> >Объявления рядом со мной</option>
         <option value="bonus_register" <?php if( in_array( "bonus_register" , $app_widgets_home_screen ) ){ echo ' selected=""'; } ?> >Бонус за регистрацию</option>
         <option value="promo_banners" <?php if( in_array( "promo_banners" , $app_widgets_home_screen ) ){ echo ' selected=""'; } ?> >Промо баннеры</option>
      </select>

  </div>
</div>           

<div class="form-group row d-flex mb-5">
 <label class="col-lg-3 form-control-label">Вкладки</label>
 <div class="col-lg-9">

    <div class="settings-widget-sorting settings-widget-sorting-home-app" >
      <?php
      if($app_widgets_home_tabs){
         foreach ($app_widgets_home_tabs as $key => $check) {
            if($key == 'feed'){
               ?>
                <div id="feed" >                             
                    <span class="settings-widget-sorting-move" >

                     <input type="hidden" name="app_widgets_home_tabs[feed]" value="0" >
                       
                     <div class="form-check">
                       <input class="form-check-input" type="checkbox" name="app_widgets_home_tabs[feed]" value="1" id="flexCheckFeed" <?php if($check == 1){ echo 'checked=""'; } ?> >
                       <label class="form-check-label" for="flexCheckFeed">
                         Лента
                       </label>
                     </div>   

                    </span>
                </div>                           
               <?php
            }elseif($key == 'recommendations_ads'){
               ?>
                <div id="recommendations_ads" >
                   <span class="settings-widget-sorting-move" >

                     <input type="hidden" name="app_widgets_home_tabs[recommendations_ads]" value="0" >

                     <div class="form-check">
                       <input class="form-check-input" type="checkbox" name="app_widgets_home_tabs[recommendations_ads]" value="1" id="flexCheckAds" <?php if($check == 1){ echo 'checked=""'; } ?> >
                       <label class="form-check-label" for="flexCheckAds">
                         Рекомендации
                       </label>
                     </div> 
                     
                  </span>
                </div>                           
               <?php
            }elseif($key == 'fresh_ads'){
               ?>
                <div id="fresh_ads" >
                   <span class="settings-widget-sorting-move" >

                     <input type="hidden" name="app_widgets_home_tabs[fresh_ads]" value="0" >

                     <div class="form-check">
                       <input class="form-check-input" type="checkbox" name="app_widgets_home_tabs[fresh_ads]" value="1" id="flexCheckAds" <?php if($check == 1){ echo 'checked=""'; } ?> >
                       <label class="form-check-label" for="flexCheckAds">
                         Свежие объявления
                       </label>
                     </div> 
                     
                  </span>
                </div>                           
               <?php
            }elseif($key == 'auction'){
               ?>
                <div id="auction" >
                   <span class="settings-widget-sorting-move" >

                     <input type="hidden" name="app_widgets_home_tabs[auction]" value="0" >

                     <div class="form-check">
                       <input class="form-check-input" type="checkbox" name="app_widgets_home_tabs[auction]" value="1" id="flexCheckAuction" <?php if($check == 1){ echo 'checked=""'; } ?> >
                       <label class="form-check-label" for="flexCheckAuction">
                         Аукционы
                       </label>
                     </div>

                   </span>
                </div>                           
               <?php
            }elseif($key == 'shops'){
               ?>
                <div id="shops" >
                   <span class="settings-widget-sorting-move" >

                     <input type="hidden" name="app_widgets_home_tabs[shops]" value="0" >

                     <div class="form-check">
                       <input class="form-check-input" type="checkbox" name="app_widgets_home_tabs[shops]" value="1" id="flexCheckShops" <?php if($check == 1){ echo 'checked=""'; } ?> >
                       <label class="form-check-label" for="flexCheckShops">
                         Магазины
                       </label>
                     </div>

                   </span>
                </div>                           
               <?php
            }
         }
      }
      ?>
                                         
    </div>

 </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">Быстрые сообщения в чате</label>
  <div class="col-lg-6">

      <div data-toggle="modal" data-target="#modal-chat-snippets-message" class="btn btn-primary" >Изменить</div>

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">Рекомендуемые суммы пополнения</label>
  <div class="col-lg-6">

      <input class="form-control" name="app_balance_list_amounts" value="<?php echo $settings["app_balance_list_amounts"]; ?>" />

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label"></label>
  <div class="col-lg-6">
      <h3 style="margin-top: 15px"> <strong>AppMetrica</strong> </h3>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">Api ключ</label>
  <div class="col-lg-6">

      <input type="text" class="form-control" name="app_metrica_api_key" value="<?php echo $settings['app_metrica_api_key']; ?>" >

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label"></label>
  <div class="col-lg-6">
      <h3 style="margin-top: 15px"> <strong>FireBase</strong> </h3>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">Project ID</label>
  <div class="col-lg-6">

      <input type="text" class="form-control" name="fbm_project_id" value="<?php echo $settings['fbm_project_id']; ?>" >

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">Параметры авторизации</label>
  <div class="col-lg-6">

      <textarea name="fbm_params" class="form-control" rows="6" ><?php echo decrypt($settings['fbm_params']); ?></textarea>

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label"></label>
  <div class="col-lg-6">
      <h3 style="margin-top: 15px"> <strong>Промо слайдер</strong> </h3>
      <div class="alert alert-primary alert-dissmissible fade show" style="margin-top: 10px;">Промо слайдер будет отображаться перед загрузкой главного экрана.</div>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
   <label class="col-lg-3 form-control-label">Статус</label>
   <div class="col-lg-9">
         <label>
         <input class="toggle-checkbox-sm" type="checkbox" name="app_home_promo_slider_status" value="1" <?php if($settings["app_home_promo_slider_status"]){ echo ' checked=""'; } ?> >
         <span><span></span></span>
         </label>
   </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
   <label class="col-lg-3 form-control-label">Слайдеры</label>
   <div class="col-lg-6">
      <div class="text-right" ><span class="btn btn-sm btn-gradient-02 settings-app-promo-slider-add"><i class="la la-plus"></i> Добавить</span></div>
      <div class="settings-app-promo-slider-container" >
         
         <?php
            if($settings["app_home_promo_slider_list"]){
               $app_home_promo_slider_list = json_decode($settings["app_home_promo_slider_list"], true);
               foreach ($app_home_promo_slider_list as $key => $value) {
                  ?>
                  <div class="settings-app-promo-slider-item" >
                     <div class="row" >
                        <div class="col-lg-6" ><input type="text" class="form-control" name="app_home_promo_slider_list[title][]" placeholder="Заголовок" value="<?php echo $value["title"]; ?>" ></div>
                        <div class="col-lg-6" ><input type="text" class="form-control" name="app_home_promo_slider_list[image][]" placeholder="Ссылка на изображение" value="<?php echo $value["image"]; ?>" ></div>
                        <div class="col-lg-12" >
                           <textarea name="app_home_promo_slider_list[desc][]" class="form-control" rows="6" placeholder="Краткое описание" ><?php echo $value["desc"]; ?></textarea>
                        </div>
                     </div>
                     <div class="text-right" ><span class="btn btn-sm btn-gradient-01 settings-app-promo-slider-remove"><i class="la la-trash"></i> Удалить</span></div>
                  </div>
                  <?php
               }
            }
         ?>

      </div>
   </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label"></label>
  <div class="col-lg-6">
      <h3 style="margin-top: 15px"> <strong>Промо баннеры</strong> </h3>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
   <label class="col-lg-3 form-control-label">Баннеры</label>
   <div class="col-lg-6">
      <div class="text-right" ><span class="btn btn-sm btn-gradient-02 settings-app-promo-banner-add"><i class="la la-plus"></i> Добавить</span></div>
      <div class="settings-app-promo-banner-container" >
         
         <?php
            if($settings["app_home_promo_banner_list"]){
               $app_home_promo_banner_list = json_decode($settings["app_home_promo_banner_list"], true);
               foreach ($app_home_promo_banner_list as $key => $value) {
                  ?>
                  <div class="settings-app-promo-banner-item" >
                     <div class="row" >
                        <div class="col-lg-6" ><input type="text" class="form-control" name="app_home_promo_banner_list[link][]" placeholder="Ссылка на источник" value="<?php echo $value["link"]; ?>" ></div>
                        <div class="col-lg-6" ><input type="text" class="form-control" name="app_home_promo_banner_list[image][]" placeholder="Ссылка на изображение" value="<?php echo $value["image"]; ?>" ></div>
                     </div>
                     <div class="text-right" ><span class="btn btn-sm btn-gradient-01 settings-app-promo-banner-remove"><i class="la la-trash"></i> Удалить</span></div>
                  </div>
                  <?php
               }
            }
         ?>

      </div>
   </div>
</div>

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label"></label>
  <div class="col-lg-6">
      <h3 style="margin-top: 15px"> <strong>Баннер в шапке</strong> </h3>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
   <label class="col-lg-3 form-control-label">Статус</label>
   <div class="col-lg-9">
         <label>
         <input class="toggle-checkbox-sm" type="checkbox" name="app_home_header_banner_status" value="1" <?php if($settings["app_home_header_banner_status"]){ echo ' checked=""'; } ?> >
         <span><span></span></span>
         </label>
   </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
   <label class="col-lg-3 form-control-label">Баннер</label>
   <div class="col-lg-6">
        <div class="row" >
           <div class="col-lg-6" >
              <input type="text" class="form-control" name="app_home_header_banner[image]" placeholder="Ссылка на баннер" value="<?php echo $app_home_header_banner["image"]; ?>" >
           </div>
           <div class="col-lg-6" >
              <input type="text" class="form-control" name="app_home_header_banner[link]" placeholder="Ссылка на источник" value="<?php echo $app_home_header_banner["link"]; ?>" >
           </div>           
        </div>
   </div>
</div>

</div>