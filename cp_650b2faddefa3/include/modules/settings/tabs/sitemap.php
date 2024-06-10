<div class="tab-pane fade <?php if($tab == "sitemap"){ echo 'active show'; } ?>" id="tab-sitemap" role="tabpanel" aria-labelledby="tab-sitemap">

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Sitemap</label>
    <div class="col-lg-9">
       <a href="<?php echo $config["urlPath"]; ?>/sitemap.xml" target="_blank" ><?php echo $config["urlPath"]; ?>/sitemap.xml</a>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Выводить города</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="sitemap_cities" value="1" <?php if($settings["sitemap_cities"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
        <div>
          <small>Будут учитываться только те города, где есть объявления</small>
        </div>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Выводить категории</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="sitemap_category" value="1" <?php if($settings["sitemap_category"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
        <div>
          <small>Будут учитываться только те категории, где есть объявления</small>
        </div>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Выводить именованные фильтры</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="sitemap_alias_filters" value="1" <?php if($settings["sitemap_alias_filters"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
        <div>
          <small>Будут учитываться только те фильтры, где есть объявления</small>
        </div>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Выводить seo фильтры</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="sitemap_seo_filters" value="1" <?php if($settings["sitemap_seo_filters"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Выводить статьи</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="sitemap_blog" value="1" <?php if($settings["sitemap_blog"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Выводить категории статей</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="sitemap_blog_category" value="1" <?php if($settings["sitemap_blog_category"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Выводить сервисные страницы</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="sitemap_services" value="1" <?php if($settings["sitemap_services"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Выводить магазины</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="sitemap_shops" value="1" <?php if($settings["sitemap_shops"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>

</div>