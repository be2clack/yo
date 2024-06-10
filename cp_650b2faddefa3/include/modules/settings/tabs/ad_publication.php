<div class="tab-pane fade <?php if($tab == "ad_publication"){ echo 'active show'; } ?>" id="tab-ad_publication" role="tabpanel" aria-labelledby="tab-ad_publication">

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Запрашивать номер телефона</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="ad_create_phone" value="1" <?php if($settings["ad_create_phone"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Запрещенные слова</label>
    <div class="col-lg-6">
        <textarea class="form-control" name="ad_black_list_words" ><?php echo $settings["ad_black_list_words"]; ?></textarea>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Ручная модерация</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="ads_publication_moderat" value="1" <?php if($settings["ads_publication_moderat"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Премодерация</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="ads_publication_auto_moderat" value="1" <?php if($settings["ads_publication_auto_moderat"]){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>

        <div>
           <small>Объявления будут автоматически отклоняться если в них будет: <br> короткий заголовок, ссылки на сайт и email</small>
        </div>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Смена валюты</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="ad_create_currency" value="1" <?php if($settings["ad_create_currency"]){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Выбор срока публикации</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="ad_create_period" value="1" <?php if($settings["ad_create_period"]){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Сроки</label>
    <div class="col-lg-2">
        <input type="text" name="ad_create_period_list" class="form-control" value="<?php echo $settings["ad_create_period_list"]; ?>" placeholder="1,7,14,30,60" >
        <small>Укажите дни через запятую</small>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Срок размещения по умолчанию</label>
    <div class="col-lg-2">
        <input type="text" name="ads_time_publication_default" class="form-control" value="<?php echo $settings["ads_time_publication_default"]; ?>" >
        <small>Укажите количество дней</small>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Обязательная загрузка фото</label>
    <div class="col-lg-2">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="ad_create_always_image" value="1" <?php if($settings["ad_create_always_image"]){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>                  
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Кол-во загружаемых изображений</label>
    <div class="col-lg-2">
        <input type="text" name="count_images_add_ad" class="form-control" value="<?php echo $settings["count_images_add_ad"]; ?>" >
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Формат фото</label>
    <div class="col-lg-2">
        <select name="ad_format_photo" class="selectpicker" >
            <option value="jpg" <?php if( $settings["ad_format_photo"] == "jpg" ){ echo 'selected=""'; } ?> >jpg</option>
            <option value="webp" <?php if( $settings["ad_format_photo"] == "webp" ){ echo 'selected=""'; } ?> >webp</option>
        </select>                  
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Максимальная длина заголовка объявления</label>
    <div class="col-lg-2">
        <input type="text" name="ad_create_length_title" class="form-control" value="<?php echo $settings["ad_create_length_title"]; ?>" >
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Максимальная длина описания объявления</label>
    <div class="col-lg-2">
        <input type="text" name="ad_create_length_text" class="form-control" value="<?php echo $settings["ad_create_length_text"]; ?>" >
    </div>
 </div>

</div>