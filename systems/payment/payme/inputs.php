
  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Обработчик оплаты</label>
    <div class="col-lg-5">
         <span><?php echo $config["urlPath"]; ?>/systems/payment/<?php echo $sql["code"]; ?>/callback.php</span>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Тестовый режим</label>
    <div class="col-lg-5">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" <?php if($param["test"] == 1){ echo ' checked=""'; } ?> name="payment_param[test]" value="1" >
          <span><span></span></span>
        </label>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Merchant ID</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["merchant_id"]; ?>"  name="payment_param[merchant_id]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Ключ</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["secret_key"]; ?>"  name="payment_param[secret_key]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Перенаправлять клиента при удачной оплате</label>
    <div class="col-lg-5">
         <?php echo $config["urlPath"] . "/pay/status/success"; ?>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">
         <a class="test-payment btn btn-primary" data-name="<?php echo $sql["code"]; ?>" >Проверить платежную систему</a>
    </div>
  </div>