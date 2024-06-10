<div class="tab-pane fade <?php if($tab == "notifications"){ echo 'active show'; } ?>" id="tab-notifications" role="tabpanel" aria-labelledby="tab-notifications">

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Уведомлять о новых объявлениях</label>
    <div class="col-lg-9">
         <select class="selectpicker" title="Нет" name="notification_method_new_ads[]" multiple="" >
             <?php
                if($settings["notification_method_new_ads"]){
                    $notification_method = explode(",",$settings["notification_method_new_ads"]);
                }else{
                    $notification_method = array();
                }
             ?>
             <option value="email" <?php if(in_array("email", $notification_method)){ echo ' selected=""'; } ?> >По e-mail</option>
             <option value="telegram" <?php if(in_array("telegram", $notification_method)){ echo ' selected=""'; } ?> >По telergam</option>
         </select>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Уведомлять о новых пользователях</label>
    <div class="col-lg-9">
         <select class="selectpicker" title="Нет" name="notification_method_new_user[]" multiple="" >
             <?php
                if($settings["notification_method_new_user"]){
                    $notification_method = explode(",",$settings["notification_method_new_user"]);
                }else{
                    $notification_method = array();
                }
             ?>
             <option value="email" <?php if(in_array("email", $notification_method)){ echo ' selected=""'; } ?> >По e-mail</option>
             <option value="telegram" <?php if(in_array("telegram", $notification_method)){ echo ' selected=""'; } ?> >По telergam</option>
         </select>
    </div>
 </div>  
 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Уведомлять о продажах</label>
    <div class="col-lg-9">
         <select class="selectpicker" title="Нет" name="notification_method_new_buy[]" multiple="" >
             <?php
                if($settings["notification_method_new_buy"]){
                    $notification_method = explode(",",$settings["notification_method_new_buy"]);
                }else{
                    $notification_method = array();
                }
             ?>
             <option value="email" <?php if(in_array("email", $notification_method)){ echo ' selected=""'; } ?> >По e-mail</option>
             <option value="telegram" <?php if(in_array("telegram", $notification_method)){ echo ' selected=""'; } ?> >По telergam</option>
         </select>
    </div>
 </div>
 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Уведомлять о сообщениях в чате</label>
    <div class="col-lg-9">
         <select class="selectpicker" title="Нет" name="notification_method_new_chat_message[]" multiple="" >
             <?php
                if($settings["notification_method_new_chat_message"]){
                    $notification_method = explode(",",$settings["notification_method_new_chat_message"]);
                }else{
                    $notification_method = array();
                }
             ?>
             <option value="email" <?php if(in_array("email", $notification_method)){ echo ' selected=""'; } ?> >По e-mail</option>
             <option value="telegram" <?php if(in_array("telegram", $notification_method)){ echo ' selected=""'; } ?> >По telergam</option>
         </select>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Уведомлять о сообщениях с формы feedback</label>
    <div class="col-lg-9">
         <select class="selectpicker" title="Нет" name="notification_method_feedback[]" multiple="" >
             <?php
                if($settings["notification_method_feedback"]){
                    $notification_method = explode(",",$settings["notification_method_feedback"]);
                }else{
                    $notification_method = array();
                }
             ?>
             <option value="email" <?php if(in_array("email", $notification_method)){ echo ' selected=""'; } ?> >По e-mail</option>
             <option value="telegram" <?php if(in_array("telegram", $notification_method)){ echo ' selected=""'; } ?> >По telergam</option>
         </select>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Уведомлять о новых жалобах</label>
    <div class="col-lg-9">
         <select class="selectpicker" title="Нет" name="notification_method_complaint[]" multiple="" >
             <?php
                if($settings["notification_method_complaint"]){
                    $notification_method = explode(",",$settings["notification_method_complaint"]);
                }else{
                    $notification_method = array();
                }
             ?>
             <option value="email" <?php if(in_array("email", $notification_method)){ echo ' selected=""'; } ?> >По e-mail</option>
             <option value="telegram" <?php if(in_array("telegram", $notification_method)){ echo ' selected=""'; } ?> >По telergam</option>
         </select>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Уведомлять о новых отзывах</label>
    <div class="col-lg-9">
         <select class="selectpicker" title="Нет" name="notification_method_reviews[]" multiple="" >
             <?php
                if($settings["notification_method_reviews"]){
                    $notification_method = explode(",",$settings["notification_method_reviews"]);
                }else{
                    $notification_method = array();
                }
             ?>
             <option value="email" <?php if(in_array("email", $notification_method)){ echo ' selected=""'; } ?> >По e-mail</option>
             <option value="telegram" <?php if(in_array("telegram", $notification_method)){ echo ' selected=""'; } ?> >По telergam</option>
         </select>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Уведомлять о новых stories</label>
    <div class="col-lg-9">
         <select class="selectpicker" title="Нет" name="notification_method_stories[]" multiple="" >
             <?php
                if($settings["notification_method_stories"]){
                    $notification_method = explode(",",$settings["notification_method_stories"]);
                }else{
                    $notification_method = array();
                }
             ?>
             <option value="email" <?php if(in_array("email", $notification_method)){ echo ' selected=""'; } ?> >По e-mail</option>
             <option value="telegram" <?php if(in_array("telegram", $notification_method)){ echo ' selected=""'; } ?> >По telergam</option>
         </select>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Уведомлять о запросах на верификацию профиля</label>
    <div class="col-lg-9">
         <select class="selectpicker" title="Нет" name="notification_method_verification[]" multiple="" >
             <?php
                if($settings["notification_method_verification"]){
                    $notification_method = explode(",",$settings["notification_method_verification"]);
                }else{
                    $notification_method = array();
                }
             ?>
             <option value="email" <?php if(in_array("email", $notification_method)){ echo ' selected=""'; } ?> >По e-mail</option>
             <option value="telegram" <?php if(in_array("telegram", $notification_method)){ echo ' selected=""'; } ?> >По telergam</option>
         </select>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Уведомлять о новых сделках</label>
    <div class="col-lg-9">
         <select class="selectpicker" title="Нет" name="notification_method_secure[]" multiple="" >
             <?php
                if($settings["notification_method_secure"]){
                    $notification_method = explode(",",$settings["notification_method_secure"]);
                }else{
                    $notification_method = array();
                }
             ?>
             <option value="email" <?php if(in_array("email", $notification_method)){ echo ' selected=""'; } ?> >По e-mail</option>
             <option value="telegram" <?php if(in_array("telegram", $notification_method)){ echo ' selected=""'; } ?> >По telergam</option>
         </select>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Уведомлять о новых сделках аренды и бронирования</label>
    <div class="col-lg-9">
         <select class="selectpicker" title="Нет" name="notification_method_booking[]" multiple="" >
             <?php
                if($settings["notification_method_booking"]){
                    $notification_method = explode(",",$settings["notification_method_booking"]);
                }else{
                    $notification_method = array();
                }
             ?>
             <option value="email" <?php if(in_array("email", $notification_method)){ echo ' selected=""'; } ?> >По e-mail</option>
             <option value="telegram" <?php if(in_array("telegram", $notification_method)){ echo ' selected=""'; } ?> >По telergam</option>
         </select>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Уведомлять о новых магазинах</label>
    <div class="col-lg-9">
         <select class="selectpicker" title="Нет" name="notification_method_shops[]" multiple="" >
             <?php
                if($settings["notification_method_shops"]){
                    $notification_method = explode(",",$settings["notification_method_shops"]);
                }else{
                    $notification_method = array();
                }
             ?>
             <option value="email" <?php if(in_array("email", $notification_method)){ echo ' selected=""'; } ?> >По e-mail</option>
             <option value="telegram" <?php if(in_array("telegram", $notification_method)){ echo ' selected=""'; } ?> >По telergam</option>
         </select>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Уведомлять о подключении пакетов размещений</label>
    <div class="col-lg-9">
         <select class="selectpicker" title="Нет" name="notification_method_ad_package[]" multiple="" >
             <?php
                if($settings["notification_method_ad_package"]){
                    $notification_method = explode(",",$settings["notification_method_ad_package"]);
                }else{
                    $notification_method = array();
                }
             ?>
             <option value="email" <?php if(in_array("email", $notification_method)){ echo ' selected=""'; } ?> >По e-mail</option>
             <option value="telegram" <?php if(in_array("telegram", $notification_method)){ echo ' selected=""'; } ?> >По telergam</option>
         </select>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-9">
       <h3 style="margin-top: 15px" > <strong>Контакты для оповещений</strong> </h3>
    </div>
 </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">E-mail адрес</label>
    <div class="col-lg-5">
         <input type="text" class="form-control"  name="email_alert" value="<?php echo $settings["email_alert"]; ?>" >
         <small>Если email адресов много, то укажите их через запятую.</small>
    </div>
  </div>            

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Номер телефона</label>
    <div class="col-lg-5">
         <input type="text" class="form-control"  name="phone_alert" value="<?php echo $settings["phone_alert"]; ?>" >
         <small>Если номер телефона не один, то укажите их через запятую.</small>
    </div>
  </div>

</div>