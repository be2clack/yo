<div class="tab-pane fade <?php if($tab == "robots"){ echo 'active show'; } ?>" id="tab-robots" role="tabpanel" aria-labelledby="tab-robots">

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Ручная настройка</label>
  <div class="col-lg-9">
      <label>
        <input class="toggle-checkbox-sm" type="checkbox" name="robots_manual_setting" value="1" <?php if($settings["robots_manual_setting"] == 1){ echo ' checked=""'; } ?> >
        <span><span></span></span>
      </label>
  </div>
</div>

<div class="robots_index_site" <?php if($settings["robots_manual_setting"]){ echo 'style="display: none;"'; } ?> >
 
   <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Индексировать сайт</label>
      <div class="col-lg-9">
          <label>
            <input class="toggle-checkbox-sm" type="checkbox" name="robots_index_site" value="1" <?php if($settings["robots_index_site"] == 1){ echo ' checked=""'; } ?> >
            <span><span></span></span>
          </label>
      </div>
   </div>

   <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Исключенные страницы</label>
      <div class="col-lg-9">
          <textarea  class="form-control" rows="3" name="robots_exclude_link" ><?php echo $settings["robots_exclude_link"]; ?></textarea>
          <small>Укажите с новой строки ссылки которые не будут учавствовать в индексации.</small>
      </div>
   </div>

</div>

<div class="robots_manual_setting" <?php if(!$settings["robots_manual_setting"]){ echo 'style="display: none;"'; } ?> >

<textarea  class="form-control robots-textarea" name="robots" ><?php echo getFile($config["basePath"]."/robots.txt"); ?></textarea>

</div>

</div>