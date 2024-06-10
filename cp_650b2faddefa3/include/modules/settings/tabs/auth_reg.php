<div class="tab-pane fade <?php if($tab == "auth_reg"){ echo 'active show'; } ?>" id="tab-auth_reg" role="tabpanel" aria-labelledby="tab-auth_reg">

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Подтверждение номера телефона</label>
  <div class="col-lg-9">
      <label>
        <input class="toggle-checkbox-sm" type="checkbox" name="confirmation_phone" value="1" <?php if($settings["confirmation_phone"]){ echo ' checked=""'; } ?> >
        <span><span></span></span>
      </label>
  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label">Метод авторизации</label>
<div class="col-lg-9">
   <select name="authorization_method" class="selectpicker">
      
      <option value="2"  <?php if($settings["authorization_method"] == 2){ echo ' selected=""'; } ?> >По e-mail/номеру телефона и паролю</option>
      <option value="1"  <?php if($settings["authorization_method"] == 1){ echo ' selected=""'; } ?> >По номеру телефона и паролю</option>
      <option value="3"  <?php if($settings["authorization_method"] == 3){ echo ' selected=""'; } ?> >По e-mail и паролю</option>

   </select>

</div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label">Метод регистрации</label>
<div class="col-lg-9">
   <select name="registration_method" class="selectpicker">
      
      <option value="2"  <?php if($settings["registration_method"] == 2){ echo ' selected=""'; } ?> >По e-mail/номеру телефона</option>
      <option value="1"  <?php if($settings["registration_method"] == 1){ echo ' selected=""'; } ?> >По номеру телефона</option>
      <option value="3"  <?php if($settings["registration_method"] == 3){ echo ' selected=""'; } ?> >По e-mail</option>

   </select>

</div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label">Вход через сервисы</label>
<div class="col-lg-9">
   <select name="authorization_social[]" class="selectpicker" multiple="" title="Отключены" >

      <?php
      if($settings["authorization_social"])
        $authorization_social_list = explode(",", $settings["authorization_social"]);
      else $authorization_social_list = [];
      ?>
      
      <option value="yandex"  <?php if( in_array( "yandex" , $authorization_social_list ) ){ echo ' selected=""'; } ?> >Yandex</option>
      <option value="vk"  <?php if( in_array( "vk" , $authorization_social_list ) ){ echo ' selected=""'; } ?> >VKontakte</option>
      <option value="google"  <?php if( in_array( "google" , $authorization_social_list ) ){ echo ' selected=""'; } ?> >Google</option>
      <option value="fb"  <?php if( in_array( "fb" , $authorization_social_list ) ){ echo ' selected=""'; } ?> >FaceBook</option>

   </select>

</div>
</div>

</div>