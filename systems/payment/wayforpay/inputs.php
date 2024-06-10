
  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Обработчик оплаты</label>
    <div class="col-lg-5">
         <span><?php echo $config["urlPath"]; ?>/systems/payment/<?php echo $sql["code"]; ?>/callback.php</span>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Идентификатор продавца</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["id_shop"]; ?>"  name="payment_param[id_shop]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Секретный ключ</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["private_key"]; ?>"  name="payment_param[private_key]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Валюта</label>
    <div class="col-lg-5">

       <select name="payment_param[curr]" class="selectpicker" >
         <option <?php if($param["curr"] == "RUB"){ echo ' selected=""'; } ?> value="RUB" >RUB</option>
         <option <?php if($param["curr"] == "USD"){ echo ' selected=""'; } ?> value="USD" >USD</option>
         <option <?php if($param["curr"] == "EUR"){ echo ' selected=""'; } ?> value="EUR" >EUR</option>
         <option <?php if($param["curr"] == "UAH"){ echo ' selected=""'; } ?> value="UAH" >UAH</option>
       </select>

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">
         <a class="test-payment btn btn-primary" data-name="<?php echo $sql["code"]; ?>" >Проверить платежную систему</a>
    </div>
  </div>