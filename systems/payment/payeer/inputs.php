
  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Обработчик оплаты</label>
    <div class="col-lg-5">
         <span><?php echo $config["urlPath"]; ?>/systems/payment/<?php echo $sql["code"]; ?>/callback.php</span>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Номер счета</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["account_number"]; ?>"  name="payment_param[account_number]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">ID merchant</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["id_merchant"]; ?>"  name="payment_param[id_merchant]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Секретный ключ</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["secret_key"]; ?>"  name="payment_param[secret_key]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Секретный ключ для дополнительных параметров</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["secret_key_parameters"]; ?>"  name="payment_param[secret_key_parameters]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Валюта</label>
    <div class="col-lg-5">

       <select name="payment_param[curr]" class="selectpicker" >
         <option <?php if($param["curr"] == "RUB"){ echo ' selected=""'; } ?> value="RUB" >RUB</option>
         <option <?php if($param["curr"] == "USD"){ echo ' selected=""'; } ?> value="USD" >USD</option>
         <option <?php if($param["curr"] == "EUR"){ echo ' selected=""'; } ?> value="EUR" >EUR</option>
         <option <?php if($param["curr"] == "BTC"){ echo ' selected=""'; } ?> value="BTC" >BTC</option>
         <option <?php if($param["curr"] == "ETH"){ echo ' selected=""'; } ?> value="ETH" >ETH</option>
         <option <?php if($param["curr"] == "BCH"){ echo ' selected=""'; } ?> value="BCH" >BCH</option>
         <option <?php if($param["curr"] == "LTC"){ echo ' selected=""'; } ?> value="LTC" >LTC</option>
         <option <?php if($param["curr"] == "DASH"){ echo ' selected=""'; } ?> value="DASH" >DASH</option>
         <option <?php if($param["curr"] == "USDT"){ echo ' selected=""'; } ?> value="USDT" >USDT</option>
         <option <?php if($param["curr"] == "XRP"){ echo ' selected=""'; } ?> value="XRP" >XRP</option>
       </select>

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Перенаправлять клиента при удачной оплате</label>
    <div class="col-lg-5">
         <input type="text" class="form-control"  value="<?php echo $param["link_success"] ? $param["link_success"] : $config["urlPath"] . "/pay/status/success"; ?>" name="payment_param[link_success]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Перенаправлять клиента при отмене оплаты</label>
    <div class="col-lg-5">
         <input type="text" class="form-control"  value="<?php echo $param["link_cancel"] ? $param["link_cancel"] : $config["urlPath"] . "/pay/status/fail"; ?>" name="payment_param[link_cancel]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">
         <a class="test-payment btn btn-primary" data-name="<?php echo $sql["code"]; ?>" >Проверить платежную систему</a>
    </div>
  </div>