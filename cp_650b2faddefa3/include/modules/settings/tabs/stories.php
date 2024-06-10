<div class="tab-pane fade <?php if($tab == "stories"){ echo 'active show'; } ?>" id="tab-stories" role="tabpanel" aria-labelledby="tab-stories">

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Статус</label>
    <div class="col-lg-2">
        
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="user_stories_status" value="1" <?php if($settings["user_stories_status"]){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5" >
     <label class="col-lg-3 form-control-label">Модерация</label>
     <div class="col-lg-2">

        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="user_stories_moderation" value="1" <?php if($settings["user_stories_moderation"]){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>

     </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5" >
     <label class="col-lg-3 form-control-label">Платное размещение</label>
     <div class="col-lg-2">

        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="user_stories_paid_add" value="1" <?php if($settings["user_stories_paid_add"]){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>

     </div>
  </div>

  <div class="stories-paid-option" <?php if($settings["user_stories_paid_add"]){ echo 'style="display: block;"'; }else{ echo 'style="display: none;"'; } ?> >

     <div class="form-group row d-flex align-items-center mb-5" >
        <label class="col-lg-3 form-control-label">Стоимость размещения</label>
        <div class="col-lg-2">

            <div class="input-group mb-2">
               <input type="number" class="form-control" name="user_stories_price_add" value="<?php echo $settings["user_stories_price_add"]; ?>" >
               <div class="input-group-prepend">
                  <div class="input-group-text"><?php echo $settings["currency_main"]["sign"]; ?></div>
               </div>                       
            </div>

        </div>
     </div>

     <div class="form-group row d-flex align-items-center mb-5" >
        <label class="col-lg-3 form-control-label">Бесплатных размещений</label>
        <div class="col-lg-2">

            <input type="number" class="form-control" name="user_stories_free_add" value="<?php echo $settings["user_stories_free_add"]; ?>" >

        </div>
     </div>

  </div>

  <div class="form-group row d-flex align-items-center mb-5" >
     <label class="col-lg-3 form-control-label">Срок размещения</label>
     <div class="col-lg-2">

         <div class="input-group mb-2">
            <input type="number" class="form-control" name="user_stories_period_add" value="<?php echo $settings["user_stories_period_add"]; ?>" >
            <div class="input-group-prepend">
               <div class="input-group-text">часов</div>
            </div>                       
         </div>
         
     </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5" >
     <label class="col-lg-3 form-control-label">Длительность видео</label>
     <div class="col-lg-2">

         <div class="input-group mb-2">
            <input type="number" class="form-control" name="user_stories_video_length" value="<?php echo $settings["user_stories_video_length"]; ?>" >
            <div class="input-group-prepend">
               <div class="input-group-text">сек</div>
            </div>                       
         </div>
         
     </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5" >
     <label class="col-lg-3 form-control-label">Длительность фото</label>
     <div class="col-lg-2">

         <div class="input-group mb-2">
            <input type="number" class="form-control" name="user_stories_image_length" value="<?php echo $settings["user_stories_image_length"]; ?>" >
            <div class="input-group-prepend">
               <div class="input-group-text">сек</div>
            </div>                       
         </div>
         
     </div>
  </div>

</div>