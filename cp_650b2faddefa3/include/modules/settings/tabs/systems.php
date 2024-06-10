<div class="tab-pane fade <?php if($tab == "systems"){ echo 'active show'; } ?>" id="tab-systems" role="tabpanel" aria-labelledby="tab-systems">

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Основной логотип</label>
    <div class="col-lg-9">
       <div class="settings-change-img settings-logo" >

         <?php echo img( array( "img1" => array( "class" => "load-defaul-logo", "path" => $config["media"]["other"] . "/" . $settings["logo-image"], "width" => "100px" ), "img2" => array( "class" => "load-defaul-logo", "path" => $config["media"]["other"] . "/icon_photo_add.png", "width" => "60px" ) ) ); ?>

         <input type="file" class="input-defaul-logo" name="logo" >
       </div>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Мобильный логотип</label>
    <div class="col-lg-9">
       <div class="settings-change-img settings-logo" >

         <?php echo img( array( "img1" => array( "class" => "load-mobile-logo", "path" => $config["media"]["other"] . "/" . $settings["logo-image-mobile"], "width" => "32px" ), "img2" => array( "class" => "load-mobile-logo", "path" => $config["media"]["other"] . "/icon_photo_add.png", "width" => "60px" ) ) ); ?>

         <input type="file" class="input-mobile-logo" name="logo-mobile" >
       </div>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Инверсия цвета</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="logo_color_inversion" value="1" <?php if($settings["logo_color_inversion"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
        <div><small>Цвет логотипа отображается белым на темном фоне.</small></div>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Favicon</label>
    <div class="col-lg-9">
       <div class="settings-change-img" >
         
          <?php echo img( array( "img1" => array( "class" => "load-favicon", "path" => $settings["favicon-image"], "width" => "32px" ), "img2" => array( "class" => "load-favicon", "path" => $config["media"]["other"] . "/icon_photo_add.png", "width" => "60px" ) ) ); ?>

         <input type="file" class="input-favicon" name="favicon" >
       </div>
       <div><small>Рекомендуемый размер иконки 120x120 с расширением png.</small></div>
    </div>
 </div>

 <hr>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Тип товаров</label>
    <div class="col-lg-9">
        <select name="main_type_products" class="selectpicker" >
           <option value="physical" <?php if($settings["main_type_products"] == 'physical'){ echo ' selected=""'; } ?> >Классические объявления</option>
           <option value="electron" <?php if($settings["main_type_products"] == 'electron'){ echo ' selected=""'; } ?> >Электронные товары</option>
        </select>

        <div class="mt10" >
           <small>Выберите какой тип товаров будет размещаться на сайте. Электронные товары - это товары которые будут скачиваться по ссылке или к которым будет предоставлен доступ,<br> при таком типе будут отключены: объявления на карте, выбор города и адреса при добавлении объявления, привязанность объявлений к городу.</small>
        </div>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Тип публикации</label>
    <div class="col-lg-9">
        <select name="board_type_ad_publication" class="selectpicker" >
           <option value="free" <?php if($settings["board_type_ad_publication"] == 'free'){ echo ' selected=""'; } ?> >Бесплатная публикация объявлений</option>
           <option value="paid" <?php if($settings["board_type_ad_publication"] == 'paid'){ echo ' selected=""'; } ?> >Платная публикация объявлений</option>
        </select>
    </div>
 </div>

 <div class="container-board-price-ad-publication" <?php if($settings["board_type_ad_publication"] == 'paid'){ echo ' style="display: block"'; } ?> >
    
    <div class="form-group row d-flex align-items-center" style="margin-bottom: 0px;" >
      <label class="col-lg-3 form-control-label">Стоимость публикации</label>
      <div class="col-lg-2">
         
        <div class="input-group mb-2">
           <input type="number" step="any" class="form-control" name="board_price_ad_publication" value="<?php echo $settings["board_price_ad_publication"]; ?>" >
           <div class="input-group-prepend">
              <div class="input-group-text"><?php echo $settings["currency_main"]["sign"]; ?></div>
           </div>                       
        </div>                     

      </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
       <label class="col-lg-3 form-control-label"></label>
       <div class="col-lg-9">
           <small>Цена будет глобально применена ко всем категориям за исключением тех у которых уже указана цена размещения. Так же индивидуально в категориях можно отключать платное размещение.</small>
       </div>
    </div>

 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Язык сайта по умолчанию</label>
    <div class="col-lg-9">
       <select name="lang_site_default" class="selectpicker" >
      <?php 

         $get = getAll("SELECT * FROM uni_languages order by id_position asc");
         
          if (count($get) > 0)
          {             
               foreach($get AS $array_data){
                  if($settings["lang_site_default"] == $array_data["iso"]){ $active = 'selected=""'; }else{ $active = ''; }
                  echo '<option '.$active.' value="'.$array_data["iso"].'" >'.$array_data["name"].'</option>';
               }
          }     

      ?>
       </select>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Безопасные сделки</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="secure_status" value="1" <?php if($settings["secure_status"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>
 
 <?php if($settings["functionality"]["booking"]){ ?>
 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Бронирование/Аренда</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="booking_status" value="1" <?php if($settings["booking_status"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>
 <?php } ?>
 
 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Мультиязычность</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="visible_lang_site" value="1" <?php if($settings["visible_lang_site"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Автоматическое определение языка</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="auto_lang_detection" value="1" <?php if($settings["auto_lang_detection"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>             

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Сокращать цену объявления</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="abbreviation_million" value="1" <?php if($settings["abbreviation_million"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Отображать разметку баннеров</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="banner_markup" value="1" <?php if($settings["banner_markup"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Отображать количество объявлений в категориях</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="display_count_ads_categories" value="1" <?php if($settings["display_count_ads_categories"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Метод вывод контента</label>
    <div class="col-lg-9">
        <select name="type_content_loading" class="selectpicker" >
           <option value="1" <?php if($settings["type_content_loading"] == 1){ echo 'selected=""'; } ?> >По нажатию на кнопку "Показать еще"</option>
           <option value="2" <?php if($settings["type_content_loading"] == 2){ echo 'selected=""'; } ?> >При прокрутки страницы</option>
        </select>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Метод сортировки объявлений</label>
    <div class="col-lg-9">
        <select name="ads_sorting_variant" class="selectpicker" >
           <option value="0" <?php if($settings["ads_sorting_variant"] == 0){ echo 'selected=""'; } ?> >Отображать новые объявления в начале списка</option>
           <option value="1" <?php if($settings["ads_sorting_variant"] == 1){ echo 'selected=""'; } ?> >Отображать новые объявления в конце списка</option>
        </select>
        <div class="mt10" >
          <small>Сортировка объявлений также будет зависеть от подключенных услуг.</small>
        </div>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Часовой пояс</label>
    <div class="col-lg-9">
       <select name="main_timezone" class="selectpicker" data-live-search="true" >
          
          <?php
          if($config["timezone"]){
            foreach ($config["timezone"] as $name => $value) {
               ?>
               <option <?php if($name == $settings["main_timezone"]){ echo 'selected=""'; } ?> value="<?php echo $name; ?>" ><?php echo $name; ?></option>
               <?php
            }
          }
          ?>

       </select>

    </div>
 </div>
 
 <div class="form-group row d-flex mb-5">
    <label class="col-lg-3 form-control-label">Радиус отображения объявлений в ближайших городах</label>
    <div class="col-lg-2">

        <div class="input-group mb-2">
           <input type="number" step="any" class="form-control" name="catalog_city_distance" value="<?php echo $settings["catalog_city_distance"]; ?>" >
           <div class="input-group-prepend">
              <div class="input-group-text">км</div>
           </div>                       
        </div>

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Объявлений в каталоге по умолчанию</label>
    <div class="col-lg-2">
        <input type="number" name="catalog_out_content" class="form-control" value="<?php echo $settings["catalog_out_content"]; ?>"  >
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Магазинов в каталоге по умолчанию</label>
    <div class="col-lg-2">
        <input type="number" name="shops_out_content" class="form-control" value="<?php echo $settings["shops_out_content"]; ?>"  >
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Объявлений на главной странице по умолчанию</label>
    <div class="col-lg-2">
        <input type="number" name="index_out_content" class="form-control" value="<?php echo $settings["index_out_content"]; ?>"  >
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Магазинов на главной странице по умолчанию</label>
    <div class="col-lg-2">
        <input type="number" name="index_out_count_shops" class="form-control" value="<?php echo $settings["index_out_count_shops"]; ?>"  >
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Публикаций в блоге по умолчанию</label>
    <div class="col-lg-2">
        <input type="number" name="blog_out_content" class="form-control" value="<?php echo $settings["blog_out_content"]; ?>"  >
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
   <label class="col-lg-3 form-control-label">Минимальная сумма пополнения баланса</label>
   <div class="col-lg-2">
      
     <div class="input-group mb-2">
        <input type="number" step="any" class="form-control" name="min_deposit_balance" value="<?php echo $settings["min_deposit_balance"]; ?>" >
        <div class="input-group-prepend">
           <div class="input-group-text"><?php echo $settings["currency_main"]["sign"]; ?></div>
        </div>                       
     </div>                     

   </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
   <label class="col-lg-3 form-control-label">Максимальная сумма пополнения баланса</label>
   <div class="col-lg-2">
      
     <div class="input-group mb-2">
        <input type="number" step="any" class="form-control" name="max_deposit_balance" value="<?php echo $settings["max_deposit_balance"]; ?>" >
        <div class="input-group-prepend">
           <div class="input-group-text"><?php echo $settings["currency_main"]["sign"]; ?></div>
        </div>                       
     </div>

   </div>
  </div> 
 
 <hr>
 
 <div class="form-group row d-flex align-items-center">
    <label class="col-lg-3 form-control-label">Версия системы</label>
    <div class="col-lg-5">
          <strong><?php echo $settings["system_version"]; ?></strong>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Кэш</label>
    <div class="col-lg-5">
          <button class="btn btn-primary cache-clear" >Сбросить</button>
    </div>
 </div>

</div>