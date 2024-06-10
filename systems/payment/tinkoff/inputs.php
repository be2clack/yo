
  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Обработчик оплаты</label>
    <div class="col-lg-5">
         <span><?php echo $config["urlPath"]; ?>/systems/payment/<?php echo $sql["code"]; ?>/callback_payment.php</span>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Обработчик добавления карты</label>
    <div class="col-lg-5">
         <span><?php echo $config["urlPath"]; ?>/systems/payment/<?php echo $sql["code"]; ?>/callback_add_card.php</span>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">54-ФЗ</label>
    <div class="col-lg-5">
        <label>
          <input class="toggle-checkbox-sm checkbox-receipt" type="checkbox" <?php if($param["receipt"] == 1){ echo 'checked=""'; } ?> name="payment_param[receipt]" value="1" >
          <span><span></span></span>
        </label>
    </div>
  </div>

  <div class="payment-receipt" <?php if(!$param["receipt"]){ echo 'style="display: none;"'; } ?> >

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Ваша система налогообложения</label>
    <div class="col-lg-5">
         <select name="payment_param[tax_system_code]" class="selectpicker" >
            <option value="0" >Не выбрано</option>
            <option value="osn" <?php if($param["tax_system_code"] == "osn"){ echo 'selected=""'; } ?> >Общая система налогообложения</option>
            <option value="usn_income" <?php if($param["tax_system_code"] == "usn_income"){ echo 'selected=""'; } ?> >Упрощенная (УСН, доходы)</option>
            <option value="usn_income_outcome" <?php if($param["tax_system_code"] == "usn_income_outcome"){ echo 'selected=""'; } ?> >Упрощенная (УСН, доходы минус расходы)</option>
            <option value="envd" <?php if($param["tax_system_code"] == "envd"){ echo 'selected=""'; } ?> >Единый налог на вмененный доход (ЕНВД)</option>
            <option value="esn" <?php if($param["tax_system_code"] == "esn"){ echo 'selected=""'; } ?> >Единый сельскохозяйственный налог (ЕСН)</option>
            <option value="patent" <?php if($param["tax_system_code"] == "patent"){ echo 'selected=""'; } ?> >Патентная система налогообложения</option>
         </select>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Ставка НДС</label>
    <div class="col-lg-5">
         <select name="payment_param[vat_code]" class="selectpicker" >
            <option value="0" >Не выбрано</option>
            <option value="none" <?php if($param["vat_code"] == "none"){ echo 'selected=""'; } ?> >без НДС</option>
            <option value="vat0" <?php if($param["vat_code"] == "vat0"){ echo 'selected=""'; } ?> >НДС по ставке 0%</option>
            <option value="vat10" <?php if($param["vat_code"] == "vat10"){ echo 'selected=""'; } ?> >НДС по ставке 10%</option>
            <option value="vat20" <?php if($param["vat_code"] == "vat20"){ echo 'selected=""'; } ?> >НДС по ставке 20%</option>
         </select>
    </div>
  </div>

  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Terminal Key</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["terminal_key"]; ?>"  name="payment_param[terminal_key]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Secret Key</label>
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
    <label class="col-lg-3 form-control-label">Перенаправлять клиента при отмене оплаты</label>
    <div class="col-lg-5">
         <?php echo $config["urlPath"] . "/pay/status/fail"; ?>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">
         <h3 style="margin-top: 10px;" > <strong>Сертификат</strong> </h3>
         <small>Сертификат требуется для выплат по безопасным сделкам.</small>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Ключ сертификата</label>
    <div class="col-lg-5">
         <textarea class="form-control" name="payment_param[certificate_key]" ><?php echo $param["certificate_key"]; ?></textarea>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Серийный номер сертификата</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["serial_number"]; ?>"  name="payment_param[serial_number]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">
         <a class="test-payment btn btn-primary" data-name="<?php echo $sql["code"]; ?>" >Проверить платежную систему</a>
    </div>
  </div>