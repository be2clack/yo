<div class="tab-pane fade <?php if($tab == "secure"){ echo 'active show'; } ?>" id="tab-secure" role="tabpanel" aria-labelledby="tab-secure">

<div class="form-group row d-flex align-items-center mb-5" >
  <label class="col-lg-3 form-control-label">Платежная система</label>
  <div class="col-lg-5">

      <select class="selectpicker" name="secure_payment_service_name" title="Не выбрано" >
          <option value="" >Не выбрано</option>
          <?php
            $getPayments = getAll("select * from uni_payments where secure=?", [1]);
            if (count($getPayments)) {
                foreach ($getPayments as $key => $value) {
                    ?>
                    <option <?php if( $settings["secure_payment_service_name"] == $value["code"] ){ echo 'selected=""'; } ?> value="<?php echo $value["code"]; ?>" ><?php echo $value["name"]; ?></option>
                    <?php
                }
            }
          ?>
      </select>

      <div><small>Выберите платежную систему для автоматических выплат средств, иначе выплаты будут в ручном режиме.</small></div>

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Оплата с баланса</label>
  <div class="col-lg-9">
      <label>
        <input class="toggle-checkbox-sm" type="checkbox" name="secure_payment_balance" value="1" <?php if($settings["secure_payment_balance"] == 1){ echo ' checked=""'; } ?> >
        <span><span></span></span>
      </label>
  </div>
</div>

<div class="form-group row d-flex align-items-center" style="margin-bottom: 0px;" >
  <label class="col-lg-3 form-control-label">Доход от безопасной сделки</label>
  <div class="col-lg-2">

      <div class="input-group mb-2">
         <input type="text" class="form-control" name="secure_percent_service" value="<?php echo $settings["secure_percent_service"]; ?>" >
         <div class="input-group-prepend">
            <div class="input-group-text">%</div>
         </div>                       
      </div>

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label"></label>
  <div class="col-lg-9">

      <small>Укажите процент который вы будете получать от безопасной сделки</small>

  </div>
</div>

<div class="form-group row d-flex align-items-center"  style="margin-bottom: 0px;" >
  <label class="col-lg-3 form-control-label">Процент платежной системы</label>
  <div class="col-lg-2">

      <div class="input-group mb-2">
         <input type="text" class="form-control" name="secure_percent_payment" value="<?php echo $settings["secure_percent_payment"]; ?>" >
         <div class="input-group-prepend">
            <div class="input-group-text">%</div>
         </div>                       
      </div>

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label"></label>
  <div class="col-lg-9">

      <small>Укажите процент который списывает платежная система при переводе денег по безопасной сделке</small>

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Дополнительные вычеты платежной системы</label>
  <div class="col-lg-2">

      <div class="input-group mb-2">
         <input type="text" class="form-control" name="secure_other_payment" value="<?php echo $settings["secure_other_payment"]; ?>" >
         <div class="input-group-prepend">
            <div class="input-group-text"><?php echo $settings["currency_main"]["sign"]; ?></div>
         </div>                       
      </div>

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label"></label>
  <div class="col-lg-9">
     <h3 style="margin-top: 15px" > <strong>Лимиты</strong> </h3>
     <small>Лимиты на массовую выплату средств устанавливает платежная система. Безопасная сделка не будет действовать если сумма товара будет меньше <?php echo $settings["secure_min_amount_payment"]; ?> или больше <?php echo $settings["secure_max_amount_payment"]; ?></small>
  </div>
</div>           

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Минимальная сумма безопасной сделки</label>
  <div class="col-lg-2">

      <div class="input-group mb-2">
         <input type="text" class="form-control" name="secure_min_amount_payment" value="<?php echo $settings["secure_min_amount_payment"]; ?>" >
         <div class="input-group-prepend">
            <div class="input-group-text"><?php echo $settings["currency_main"]["sign"]; ?></div>
         </div>                       
      </div>

  </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
  <label class="col-lg-3 form-control-label">Максимальная сумма безопасной сделки</label>
  <div class="col-lg-2">

      <div class="input-group mb-2">
         <input type="text" class="form-control" name="secure_max_amount_payment" value="<?php echo $settings["secure_max_amount_payment"]; ?>" >
         <div class="input-group-prepend">
            <div class="input-group-text"><?php echo $settings["currency_main"]["sign"]; ?></div>
         </div>                       
      </div>

  </div>
</div>

</div>