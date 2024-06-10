
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
    <label class="col-lg-3 form-control-label">Касса</label>
    <div class="col-lg-5">
        <label>
          <input class="toggle-checkbox-sm checkbox-receipt" type="checkbox" <?php if($param["receipt"] == 1){ echo 'checked=""'; } ?> name="payment_param[receipt]" value="1" >
          <span><span></span></span>
        </label>
    </div>
  </div>

  <div class="payment-receipt" <?php if(!$param["receipt"]){ echo 'style="display: none;"'; } ?> >

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Тип налога</label>
    <div class="col-lg-5">
         <select name="payment_param[tax_type]" class="selectpicker" >
            <option value="0" >Без налога</option>
            <option value="1" <?php if($param["tax_type"] == "1"){ echo 'selected=""'; } ?> >НДС 0%</option>
            <option value="2" <?php if($param["tax_type"] == "2"){ echo 'selected=""'; } ?> >НДС 12%</option>
            <option value="3" <?php if($param["tax_type"] == "3"){ echo 'selected=""'; } ?> >НДС 12/112</option>
            <option value="4" <?php if($param["tax_type"] == "4"){ echo 'selected=""'; } ?> >НДС 18%</option>
            <option value="5" <?php if($param["tax_type"] == "5"){ echo 'selected=""'; } ?> >НДС 18/118</option>
            <option value="6" <?php if($param["tax_type"] == "6"){ echo 'selected=""'; } ?> >НДС 10%</option>
            <option value="7" <?php if($param["tax_type"] == "7"){ echo 'selected=""'; } ?> >НДС 10/110</option>
            <option value="8" <?php if($param["tax_type"] == "8"){ echo 'selected=""'; } ?> >НДС 20%</option>
            <option value="9" <?php if($param["tax_type"] == "9"){ echo 'selected=""'; } ?> >НДС 20/120</option>
         </select>
    </div>
  </div>

  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Идентификатор магазина</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["id_merchant"]; ?>"  name="payment_param[id_merchant]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Секретный ключ для приема платежей</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["secret_key_payment"]; ?>"  name="payment_param[secret_key_payment]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Валюта</label>
    <div class="col-lg-5">

       <select name="payment_param[curr]" class="selectpicker" >
         <option <?php if($param["curr"] == "EUR"){ echo ' selected=""'; } ?> value="EUR" >EUR</option>
         <option <?php if($param["curr"] == "KZT"){ echo ' selected=""'; } ?> value="KZT" >KZT</option>
         <option <?php if($param["curr"] == "KGS"){ echo ' selected=""'; } ?> value="KGS" >KGS</option>
         <option <?php if($param["curr"] == "USD"){ echo ' selected=""'; } ?> value="USD" >USD</option>
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
         <h3 style="margin-top: 10px;" > <strong>Безопасная сделка</strong> </h3>
         <small>Ключ требуется для выплат по безопасным сделкам.</small>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Секретный ключ для выплат клиентам</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["secret_key_payout"]; ?>"  name="payment_param[secret_key_payout]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">
         <a class="test-payment btn btn-primary" data-name="<?php echo $sql["code"]; ?>" >Проверить платежную систему</a>
    </div>
  </div>

  

