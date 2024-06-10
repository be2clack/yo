<div class="tab-pane fade <?php if($tab == "main_page"){ echo 'active show'; } ?>" id="tab-main_page" role="tabpanel" aria-labelledby="tab-main_page">

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Sidebar</label>
  <div class="col-lg-9">
      <label>
        <input class="toggle-checkbox-sm" type="checkbox" name="home_sidebar_status" value="1" <?php if($settings["home_sidebar_status"] == 1){ echo ' checked=""'; } ?> >
        <span><span></span></span>
      </label>
  </div>
</div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Отображать объявления</label>
    <div class="col-lg-9">
        <select name="index_out_content_method" class="selectpicker" >
           <option value="0" <?php if($settings["index_out_content_method"] == 0){ echo 'selected=""'; } ?> >Все</option>
           <option value="1" <?php if($settings["index_out_content_method"] == 1){ echo 'selected=""'; } ?> >С учетом геопозиции</option>
        </select>
    </div>
 </div>

<div class="form-group row d-flex mb-5">
  <label class="col-lg-3 form-control-label">Порядок отображения</label>
  <div class="col-lg-9">

     <div class="settings-widget-sorting settings-widget-sorting-home" >
       <?php
       if(isset($settings["home_widget_sorting"])){
          foreach ($settings["home_widget_sorting"] as $value) {
             if($value == 'stories'){
                ?>
                 <div id="stories" >
                    <span class="settings-widget-sorting-move" >Истории пользователей</span>
                 </div>                           
                <?php
             }elseif($value == 'shop'){
                ?>
                 <div id="shop" >
                    <span class="settings-widget-sorting-move" >Магазины</span>
                 </div>                           
                <?php
             }elseif($value == 'promo'){
                ?>
                 <div id="promo" >
                    <span class="settings-widget-sorting-move" >Промо слайдер</span>
                 </div>                           
                <?php
             }elseif($value == 'vip'){
                ?>
                 <div id="vip" >
                    <span class="settings-widget-sorting-move" >Vip объявления</span>
                 </div>                           
                <?php
             }elseif($value == 'blog'){
                ?>
                 <div id="blog" >
                    <span class="settings-widget-sorting-move" >Статьи блога</span>
                 </div>                            
                <?php
             }elseif($value == 'category_ads'){
                ?>
                 <div id="category_ads" >
                    <span class="settings-widget-sorting-move" >Объявления категорий</span>
                 </div>                           
                <?php
             }elseif($value == 'category_slider'){
                ?>
                 <div id="category_slider" >
                    <span class="settings-widget-sorting-move" >Слайдер категорий</span>
                 </div>                           
                <?php
             }elseif($value == 'auction'){
                ?>
                 <div id="auction" >
                    <span class="settings-widget-sorting-move" >Аукционы</span>
                 </div>                           
                <?php
             }
          }
       }
       ?>
                                          
     </div>

     <input type="hidden" name="home_widget_sorting" value="<?php echo implode(",", $settings["home_widget_sorting"]); ?>" >

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Слайдер категорий</label>
  <div class="col-lg-9">
      <label>
        <input class="toggle-checkbox-sm" type="checkbox" name="home_category_slider_status" value="1" <?php if($settings["home_category_slider_status"] == 1){ echo ' checked=""'; } ?> >
        <span><span></span></span>
      </label>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Истории пользователей</label>
  <div class="col-lg-9">
      <label>
        <input class="toggle-checkbox-sm" type="checkbox" name="home_stories_status" value="1" <?php if($settings["home_stories_status"] == 1){ echo ' checked=""'; } ?> >
        <span><span></span></span>
      </label>
  </div>
</div>  

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Магазины</label>
  <div class="col-lg-9">
      <label>
        <input class="toggle-checkbox-sm" type="checkbox" name="home_shop_status" value="1" <?php if($settings["home_shop_status"] == 1){ echo ' checked=""'; } ?> >
        <span><span></span></span>
      </label>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Промо слайдер</label>
  <div class="col-lg-9">
      <label>
        <input class="toggle-checkbox-sm" type="checkbox" name="home_promo_status" value="1" <?php if($settings["home_promo_status"] == 1){ echo ' checked=""'; } ?> >
        <span><span></span></span>
      </label>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Vip объявления</label>
  <div class="col-lg-9">
      <label>
        <input class="toggle-checkbox-sm" type="checkbox" name="home_vip_status" value="1" <?php if($settings["home_vip_status"] == 1){ echo ' checked=""'; } ?> >
        <span><span></span></span>
      </label>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Аукционы</label>
  <div class="col-lg-9">
      <label>
        <input class="toggle-checkbox-sm" type="checkbox" name="home_auction_status" value="1" <?php if($settings["home_auction_status"] == 1){ echo ' checked=""'; } ?> >
        <span><span></span></span>
      </label>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Статьи блога</label>
  <div class="col-lg-9">
      <label>
        <input class="toggle-checkbox-sm" type="checkbox" name="home_blog_status" value="1" <?php if($settings["home_blog_status"] == 1){ echo ' checked=""'; } ?> >
        <span><span></span></span>
      </label>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Объявления категорий</label>
  <div class="col-lg-9">
      <label>
        <input class="toggle-checkbox-sm" type="checkbox" name="home_category_ads_status" value="1" <?php if($settings["home_category_ads_status"] == 1){ echo ' checked=""'; } ?> >
        <span><span></span></span>
      </label>
  </div>
</div>

</div>