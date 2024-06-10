
  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Обработчик оплаты</label>
    <div class="col-lg-5">
         <span><?php echo $config["urlPath"]; ?>/systems/payment/<?php echo $sql["code"]; ?>/callback.php</span>
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
            <option value="1" <?php if($param["tax_system_code"] == "1"){ echo 'selected=""'; } ?> >Общая система налогообложения</option>
            <option value="2" <?php if($param["tax_system_code"] == "2"){ echo 'selected=""'; } ?> >Упрощенная (УСН, доходы)</option>
            <option value="3" <?php if($param["tax_system_code"] == "3"){ echo 'selected=""'; } ?> >Упрощенная (УСН, доходы минус расходы)</option>
            <option value="4" <?php if($param["tax_system_code"] == "4"){ echo 'selected=""'; } ?> >Единый налог на вмененный доход (ЕНВД)</option>
            <option value="5" <?php if($param["tax_system_code"] == "5"){ echo 'selected=""'; } ?> >Единый сельскохозяйственный налог (ЕСН)</option>
            <option value="6" <?php if($param["tax_system_code"] == "6"){ echo 'selected=""'; } ?> >Патентная система налогообложения</option>
         </select>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Ставка НДС</label>
    <div class="col-lg-5">
         <select name="payment_param[vat_code]" class="selectpicker" >
            <option value="0" >Не выбрано</option>
            <option value="1" <?php if($param["vat_code"] == "1"){ echo 'selected=""'; } ?> >без НДС</option>
            <option value="2" <?php if($param["vat_code"] == "2"){ echo 'selected=""'; } ?> >НДС по ставке 0%</option>
            <option value="3" <?php if($param["vat_code"] == "3"){ echo 'selected=""'; } ?> >НДС чека по ставке 10%</option>
            <option value="4" <?php if($param["vat_code"] == "4"){ echo 'selected=""'; } ?> >НДС чека по ставке 20%</option>
            <option value="5" <?php if($param["vat_code"] == "5"){ echo 'selected=""'; } ?> >НДС чека по расчетной ставке 10/110</option>
            <option value="6" <?php if($param["vat_code"] == "6"){ echo 'selected=""'; } ?> >НДС чека по расчетной ставке 20/120</option>
         </select>
    </div>
  </div>

  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Идентификатор магазина</label>
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
         <option <?php if($param["curr"] == "KZT"){ echo ' selected=""'; } ?> value="KZT" >KZT</option>
         <option <?php if($param["curr"] == "CNY"){ echo ' selected=""'; } ?> value="CNY" >CNY</option>
       </select>

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">
         <a class="test-payment btn btn-primary" data-name="<?php echo $sql["code"]; ?>" >Проверить платежную систему</a>
    </div>
  </div>