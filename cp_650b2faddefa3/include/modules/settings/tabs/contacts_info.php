<div class="tab-pane fade <?php if($tab == "contacts_info"){ echo 'active show'; } ?>" id="tab-contacts_info" role="tabpanel" aria-labelledby="tab-contacts_info">

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Заголовок сайта</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $settings["title"]; ?>" name="title" >
         <small>Указанная тут информация будет отображаться в нижней части сайта и в e-mail письмах отправленные пользователям.</small>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Название сайта/проекта</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $settings["site_name"]; ?>"  name="site_name" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Номер телефона</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $settings["contact_phone"]; ?>" name="contact_phone" >
         <small>Если номер телефона не один, то укажите их через запятую.</small>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">E-mail</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $settings["contact_email"]; ?>"  name="contact_email" >
         <small>Если email адресов много, то укажите их через запятую.</small>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Адрес</label>
    <div class="col-lg-5">
         <input type="text" class="form-control"  value="<?php echo $settings["contact_address"]; ?>" name="contact_address" >
    </div>
  </div>

</div>