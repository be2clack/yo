<div class="tab-pane fade <?php if($tab == "shops"){ echo 'active show'; } ?>" id="tab-shops" role="tabpanel" aria-labelledby="tab-shops">

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Магазины</label>
  <div class="col-lg-9">
      <label>
        <input class="toggle-checkbox-sm" type="checkbox" name="user_shop_status" value="1" <?php if($settings["user_shop_status"] == 1){ echo ' checked=""'; } ?> >
        <span><span></span></span>
      </label>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Алиас для каталога</label>
  <div class="col-lg-3">
     
     <input type="text" name="user_shop_alias_url_all" class="form-control" value="<?php echo $settings["user_shop_alias_url_all"]; ?>" >
     
     <div class="mt10" >
        <small>Укажите название url строки которая будет вести на все магазины</small>
     </div>

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Алиас для магазина</label>
  <div class="col-lg-3">
     
     <input type="text" name="user_shop_alias_url_page" class="form-control" value="<?php echo $settings["user_shop_alias_url_page"]; ?>" >
     
     <div class="mt10" >
        <small>Укажите название url строки которая будет вести на магазин</small>
     </div>

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Лимит слайдов</label>
  <div class="col-lg-3">
     
     <input type="number" step="any" name="user_shop_count_sliders" class="form-control" value="<?php echo $settings["user_shop_count_sliders"]; ?>" >

     <div class="mt10" >
        <small>Укажите максимальное количество слайдов которые могут добавлять пользователи в слайдер</small>
     </div>

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Лимит страниц</label>
  <div class="col-lg-3">
     
     <input type="number" step="any" name="user_shop_count_pages" class="form-control" value="<?php echo $settings["user_shop_count_pages"]; ?>" >

     <div class="mt10" >
        <small>Укажите максимальное количество страниц которые могут добавлять пользователи</small>
     </div>

  </div>
</div>

</div>