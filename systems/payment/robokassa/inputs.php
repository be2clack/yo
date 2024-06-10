
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
         <select name="payment_param[sno]" class="selectpicker" >
            <option value="osn" <?php if($param["sno"] == "osn"){ echo 'selected=""'; } ?> >Общая система налогообложения</option>
            <option value="usn_income" <?php if($param["sno"] == "usn_income"){ echo 'selected=""'; } ?> >Упрощенная (УСН, доходы)</option>
            <option value="usn_income_outcome" <?php if($param["sno"] == "usn_income_outcome"){ echo 'selected=""'; } ?> >Упрощенная (УСН, доходы минус расходы)</option>
            <option value="esn" <?php if($param["sno"] == "esn"){ echo 'selected=""'; } ?> >Единый сельскохозяйственный налог (ЕСН)</option>
            <option value="patent" <?php if($param["sno"] == "patent"){ echo 'selected=""'; } ?> >Патентная система налогообложения</option>
         </select>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Ставка НДС</label>
    <div class="col-lg-5">
         <select name="payment_param[tax]" class="selectpicker" >
            <option value="none" <?php if($param["tax"] == "none"){ echo 'selected=""'; } ?> >без НДС</option>
            <option value="vat0" <?php if($param["tax"] == "vat0"){ echo 'selected=""'; } ?> >НДС по ставке 0%</option>
            <option value="vat10" <?php if($param["tax"] == "vat10"){ echo 'selected=""'; } ?> >НДС чека по ставке 10%</option>
            <option value="vat20" <?php if($param["tax"] == "vat20"){ echo 'selected=""'; } ?> >НДС чека по ставке 20%</option>
            <option value="vat110" <?php if($param["tax"] == "vat110"){ echo 'selected=""'; } ?> >НДС чека по расчетной ставке 10/110</option>
            <option value="vat120" <?php if($param["tax"] == "vat120"){ echo 'selected=""'; } ?> >НДС чека по расчетной ставке 20/120</option>
         </select>
    </div>
  </div>

  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Testing password 1</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["test_pass1"]; ?>"  name="payment_param[test_pass1]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Testing password 2</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["test_pass2"]; ?>"  name="payment_param[test_pass2]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">ID Shop</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["id_shop"]; ?>"  name="payment_param[id_shop]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Password 1</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["pass1"]; ?>"  name="payment_param[pass1]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Password 2</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["pass2"]; ?>"  name="payment_param[pass2]" >
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