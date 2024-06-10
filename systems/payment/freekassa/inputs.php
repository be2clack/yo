
  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Обработчик оплаты</label>
    <div class="col-lg-5">
         <span><?php echo $config["urlPath"]; ?>/systems/payment/<?php echo $sql["code"]; ?>/callback.php</span>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Идентификатор магазина</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["id_shop"]; ?>"  name="payment_param[id_shop]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Секретное слово 1</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["secret_word1"]; ?>"  name="payment_param[secret_word1]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Секретное слово 2</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["secret_word2"]; ?>"  name="payment_param[secret_word2]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Валюта</label>
    <div class="col-lg-5">
         <select class="form-control" name="payment_param[currency]"  >
            <option value="RUB" <?php if( $param["currency"] == "RUB" ){ echo 'selected=""'; } ?> >RUB</option>
            <option value="USD" <?php if( $param["currency"] == "USD" ){ echo 'selected=""'; } ?> >USD</option>
            <option value="EUR" <?php if( $param["currency"] == "EUR" ){ echo 'selected=""'; } ?> >EUR</option>
            <option value="UAH" <?php if( $param["currency"] == "UAH" ){ echo 'selected=""'; } ?> >UAH</option>
            <option value="KZT" <?php if( $param["currency"] == "KZT" ){ echo 'selected=""'; } ?> >KZT</option>
         </select>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Перенаправлять клиента при удачной оплате</label>
    <div class="col-lg-5">
         <?php echo $config["urlPath"] . "/pay/status/success"; ?>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Перенаправлять клиента при отмене оплаты</label>
    <div class="col-lg-5">
         <?php echo $config["urlPath"] . "/pay/status/fail"; ?>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">
         <a class="test-payment btn btn-primary" data-name="<?php echo $sql["code"]; ?>" >Проверить платежную систему</a>
    </div>
  </div>