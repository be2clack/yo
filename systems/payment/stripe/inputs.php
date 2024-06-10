
  <?php require 'currency.php'; ?>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Обработчик оплаты</label>
    <div class="col-lg-5">
         <span><?php echo $config["urlPath"]; ?>/systems/payment/<?php echo $sql["code"]; ?>/callback.php</span>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Secret key</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["private_key"]; ?>"  name="payment_param[private_key]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Webhook secret</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["secret_webhook"]; ?>"  name="payment_param[secret_webhook]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Валюта</label>
    <div class="col-lg-5">

       <select name="payment_param[curr]" class="selectpicker" >
         <?php
          foreach ($currency as $iso => $value) {
             ?>
             <option <?php if($param["curr"] == $iso){ echo ' selected=""'; } ?> value="<?php echo $iso; ?>" ><?php echo $iso; ?></option>
             <?php
          }
         ?>
       </select>

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">
         <a class="test-payment btn btn-primary" data-name="<?php echo $sql["code"]; ?>" >Проверить платежную систему</a>
    </div>
  </div>