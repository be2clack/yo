<div class="tab-pane fade <?php if($tab == "pwa"){ echo 'active show'; } ?>" id="tab-pwa" role="tabpanel" aria-labelledby="tab-pwa">

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Статус активности</label>
    <div class="col-lg-2">
        
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="pwa_status" value="1" <?php if($settings["pwa_status"]){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>

    </div>
  </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Иконка приложения</label>
    <div class="col-lg-9">
       <div class="settings-change-img" >
         
          <?php echo img( array( "img1" => array( "class" => "load-pwa", "path" => "/templates/icons_pwa/" . $settings["pwa_image"], "width" => "32px" ), "img2" => array( "class" => "load-pwa", "path" => $config["media"]["other"] . "/icon_photo_add.png", "width" => "60px" ) ) ); ?>

         <input type="file" class="input-pwa" name="pwa_icon" >
       </div>
       <div><small>Рекомендуемый размер иконки 512x512 с расширением png.</small></div>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Название приложения</label>
    <div class="col-lg-5">
          
          <input type="text" class="form-control" value="<?php echo $settings["pwa_name"]; ?>" name="pwa_name" >

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Короткое название приложения</label>
    <div class="col-lg-5">
          
          <input type="text" class="form-control" value="<?php echo $settings["pwa_short_name"]; ?>" name="pwa_short_name" >

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Описание приложения</label>
    <div class="col-lg-5">
          
          <textarea class="form-control" name="pwa_desc" ><?php echo $settings["pwa_desc"]; ?></textarea>

    </div>
  </div>

</div>