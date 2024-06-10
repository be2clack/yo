<div class="tab-pane fade <?php if($tab == "access"){ echo 'active show'; } ?>" id="tab-access" role="tabpanel" aria-labelledby="tab-access">

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label">Сайт доступен</label>
<div class="col-lg-9">
    <label>
      <input class="toggle-checkbox-sm settings-access-site" value="1" type="checkbox" name="access_site" <?php if($settings["access_site"] == "1"){ echo 'checked=""'; } ?> >
      <span><span></span></span>
    </label>
</div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label"></label>
<div class="col-lg-6">
    <div class="styled-radio">
      <input type="radio" name="access_action" value="text" id="rad-2" class="settings-access-out-text" <?php if($settings["access_site"] == "0"){ if($settings["access_action"] == "text"){ echo 'checked=""'; } if($settings["access_site"] == "1"){ echo ' disabled=""'; } }else{ echo ' disabled=""'; } ?> >
      <label for="rad-2">Выводить текст</label>
    </div>
    <textarea class="form-control settings-access-text" name="access_text" <?php if($settings["access_site"] == "0"){ if($settings["access_action"] != "text"){ echo 'disabled=""'; } }else{ echo ' disabled=""'; } ?> ><?php echo $settings["access_text"]; ?></textarea>
</div>
</div>             

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label"></label>
<div class="col-lg-6">
    <div class="styled-radio">
      <input type="radio" name="access_action" value="redirect" id="rad-3" class="settings-access-redirect" <?php if($settings["access_site"] == "0"){ if($settings["access_action"] == "redirect"){ echo 'checked=""'; } if($settings["access_site"] == "1"){ echo ' disabled=""'; } }else{ echo ' disabled=""'; } ?> />
      <label for="rad-3">Перенаправлять на другой сайт</label>
    </div>                  
    <input type="text" class="form-control settings-access-redirect-link" value="<?php echo $settings["access_redirect_link"]; ?>" name="access_redirect_link" <?php if($settings["access_site"] == "0"){ if($settings["access_action"] != "redirect"){ echo 'disabled=""'; } }else{ echo ' disabled=""'; } ?>  ></input>
</div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label">Разрешенные IP для входа</label>
<div class="col-lg-6">
    <textarea class="form-control settings-access-ip" name="access_allowed_ip" <?php if($settings["access_site"] == "1"){ echo ' disabled=""'; } ?> ><?php if(!empty($settings["access_allowed_ip"])){echo $settings["access_allowed_ip"];}else{echo $_SERVER["REMOTE_ADDR"];}  ?></textarea>
    <small>Укажите разрешенные IP через запятую. Ваш IP подставляется автоматически!</small>
</div>
</div>

</div>