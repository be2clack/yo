<div class="tab-pane fade <?php if($tab == "mailing"){ echo 'active show'; } ?>" id="tab-mailing" role="tabpanel" aria-labelledby="tab-mailing">

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label">Имя отправителя</label>
<div class="col-lg-6">
    <input type="text" class="form-control" name="name_responder" value="<?php echo $settings["name_responder"]; ?>" >
</div>
</div>           

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label">Ответный E-Mail</label>
<div class="col-lg-6">
    <input type="text" class="form-control" value="<?php echo $settings["email_noreply"]; ?>" name="email_noreply">
</div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label"></label>
<div class="col-lg-6">
    <h3 style="margin-top: 15px">Метод рассылки</h3>
    <hr>
</div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label"></label>
<div class="col-lg-6">

    <div class="styled-radio">
        <input type="radio" name="variant_send_mail" value="1" id="responder-rad-1" <?php if($settings["variant_send_mail"] == 1){echo 'checked=""';}?> >
        <label for="responder-rad-1">Стандартная отправка Email</label>
    </div>
    <div class="styled-radio">
        <input type="radio" name="variant_send_mail" value="2" id="responder-rad-2" <?php if($settings["variant_send_mail"] == 2){echo 'checked=""';}?> >
        <label for="responder-rad-2">Отправка через SMTP</label>
    </div>

</div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label">Хост</label>
<div class="col-lg-6">
    <input type="text" class="form-control" value="<?php echo $settings["smtp_host"]?>" name="smtp_host" <?php if($settings["variant_send_mail"] == 1){echo 'disabled=""';}?> >
</div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label">Порт</label>
<div class="col-lg-6">
    <input type="text" class="form-control" value="<?php echo $settings["smtp_port"]?>" name="smtp_port" <?php if($settings["variant_send_mail"] == 1){echo 'disabled=""';}?> >
</div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label">Email</label>
<div class="col-lg-6">
    <input type="text" class="form-control" value="<?php echo $settings["smtp_username"]?>" name="smtp_username" <?php if($settings["variant_send_mail"] == 1){echo 'disabled=""';}?> >
</div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label">Пароль от почты</label>
<div class="col-lg-6">
    <input type="text" class="form-control" autocomplete="new-password" placeholder="<?php if($settings["smtp_password"]){ echo 'Пароль задан'; } ?>" name="smtp_password" <?php if($settings["variant_send_mail"] == 1){echo 'disabled=""';}?> >

</div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label">Шифрование</label>
<div class="col-lg-6">
    
    <select class="selectpicker" name="smtp_secure"  >
       <option value="ssl" <?php if($settings["smtp_secure"] == "ssl"){echo 'selected=""';}?> >SSL</option>
       <option value="tsl" <?php if($settings["smtp_secure"] == "tsl"){echo 'selected=""';}?> >TSL</option>
    </select>

</div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label"></label>
<div class="col-lg-6">

    <a href="https://unisite.org/doc/otpravka-email-cherez-smtp"> <strong><i class="la la-question-circle question-circle"></i> Как настроить SMTP?</strong> </a>

    <div class="text-right" >
        <a data-toggle="modal" data-target="#modal-log" class="test-send-smtp btn btn-primary" >Тестовая отправка письма</a>
    </div>

</div>
</div>

</div>