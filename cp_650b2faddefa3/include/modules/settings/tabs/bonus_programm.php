<div class="tab-pane fade <?php if($tab == "bonus_programm"){ echo 'active show'; } ?>" id="tab-bonus_programm" role="tabpanel" aria-labelledby="tab-bonus_programm">

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Бонус за регистрацию</label>
    <div class="col-lg-2">
        
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="bonus[register][status]" value="1" <?php if($settings["bonus_program"]["register"]["status"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Название</label>
    <div class="col-lg-5">
        <input type="text" class="form-control" name="bonus[register][name]" value="<?php echo $settings["bonus_program"]["register"]["name"]; ?>" >
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Бонус</label>
    <div class="col-lg-2">
        <div class="input-group mb-2">
           <input type="number" step="any" class="form-control" name="bonus[register][price]" value="<?php echo $settings["bonus_program"]["register"]["price"]; ?>" >
           <div class="input-group-prepend">
              <div class="input-group-text"><?php echo $settings["currency_main"]["sign"]; ?></div>
           </div>                       
        </div>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Бонус за пополнение баланса</label>
    <div class="col-lg-2">
        
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="bonus[balance][status]" value="1" <?php if($settings["bonus_program"]["balance"]["status"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Название</label>
    <div class="col-lg-5">
        <input type="text" class="form-control" name="bonus[balance][name]" value="<?php echo $settings["bonus_program"]["balance"]["name"]; ?>" >
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Процент от суммы пополнения</label>
    <div class="col-lg-2">
        <div class="input-group mb-2">
           <input type="number" step="any" class="form-control" name="bonus[balance][price]" value="<?php echo $settings["bonus_program"]["balance"]["price"]; ?>" >
           <div class="input-group-prepend">
              <div class="input-group-text">%</div>
           </div>                       
        </div>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Бонус за добавление e-mail адреса</label>
    <div class="col-lg-2">
        
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="bonus[email][status]" value="1" <?php if($settings["bonus_program"]["email"]["status"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Название</label>
    <div class="col-lg-5">
        <input type="text" class="form-control" name="bonus[email][name]" value="<?php echo $settings["bonus_program"]["email"]["name"]; ?>" >
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Бонус</label>
    <div class="col-lg-2">
        <div class="input-group mb-2">
           <input type="number" step="any" class="form-control" name="bonus[email][price]" value="<?php echo $settings["bonus_program"]["email"]["price"]; ?>" >
           <div class="input-group-prepend">
              <div class="input-group-text"><?php echo $settings["currency_main"]["sign"]; ?></div>
           </div>                       
        </div>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Бонус за публикацию первого объявления</label>
    <div class="col-lg-2">
        
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="bonus[ad_publication][status]" value="1" <?php if($settings["bonus_program"]["ad_publication"]["status"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Название</label>
    <div class="col-lg-5">
        <input type="text" class="form-control" name="bonus[ad_publication][name]" value="<?php echo $settings["bonus_program"]["ad_publication"]["name"]; ?>" >
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Бонус</label>
    <div class="col-lg-2">
        <div class="input-group mb-2">
           <input type="number" step="any" class="form-control" name="bonus[ad_publication][price]" value="<?php echo $settings["bonus_program"]["ad_publication"]["price"]; ?>" >
           <div class="input-group-prepend">
              <div class="input-group-text"><?php echo $settings["currency_main"]["sign"]; ?></div>
           </div>                       
        </div>
    </div>
 </div>

</div>