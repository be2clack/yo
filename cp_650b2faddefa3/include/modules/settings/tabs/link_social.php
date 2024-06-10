<div class="tab-pane fade <?php if($tab == "link_social"){ echo 'active show'; } ?>" id="tab-link_social" role="tabpanel" aria-labelledby="tab-link_social">

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">ВКонтакте</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $settings["social_link_vk"]; ?>" name="social_link_vk" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Одноклассники</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $settings["social_link_ok"]; ?>" name="social_link_ok" >
    </div>
  </div>
  
  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">YouTube</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $settings["social_link_you"]; ?>" name="social_link_you" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Telegram</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $settings["social_link_telegram"]; ?>" name="social_link_telegram" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">FaceBook</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $settings["social_link_facebook"]; ?>" name="social_link_facebook" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Instagram</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $settings["social_link_instagram"]; ?>" name="social_link_instagram" >
    </div>
  </div>

</div>