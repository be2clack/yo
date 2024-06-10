<div class="tab-pane fade <?php if($tab == "ad_card"){ echo 'active show'; } ?>" id="tab-ad_card" role="tabpanel" aria-labelledby="tab-ad_card">

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Цена в разных валютах</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="ads_currency_price" value="1" <?php if($settings["ads_currency_price"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>             

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Комментарии в объявлениях</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="ads_comments" value="1" <?php if($settings["ads_comments"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Кто может просматривать номер телефона</label>
    <div class="col-lg-2">
        <select class="selectpicker" name="ad_view_phone" >
           <option value="1" <?php if($settings["ad_view_phone"] == 1){ echo 'selected=""'; } ?> >Только авторизованные пользователи</option>
           <option value="2" <?php if($settings["ad_view_phone"] == 2){ echo 'selected=""'; } ?> >Все</option>
        </select>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Выводить кол-во похожих объявлений</label>
    <div class="col-lg-2">
        <input type="text" name="ad_similar_count" class="form-control" value="<?php echo $settings["ad_similar_count"]; ?>" value="16" >
    </div>
 </div>

</div>