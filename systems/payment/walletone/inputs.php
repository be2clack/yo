
  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Обработчик оплаты</label>
    <div class="col-lg-5">
         <span><?php echo $config["urlPath"]; ?>/systems/payment/<?php echo $sql["code"]; ?>/callback.php</span>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">ID merchant </label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["id_merchant"]; ?>"  name="payment_param[id_merchant]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Секретный ключ</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["key"]; ?>"  name="payment_param[key]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Валюта</label>
    <div class="col-lg-5">

       <select name="payment_param[curr]" class="selectpicker" >
         <option <?php if($param["curr"] == "643"){ echo ' selected=""'; } ?> value="643" >Российские рубли</option>
         <option <?php if($param["curr"] == "710"){ echo ' selected=""'; } ?> value="710" >Южно-Африканские ранды</option>
         <option <?php if($param["curr"] == "840"){ echo ' selected=""'; } ?> value="840" >Американские доллары</option>
         <option <?php if($param["curr"] == "978"){ echo ' selected=""'; } ?> value="978" >Евро</option>
         <option <?php if($param["curr"] == "980"){ echo ' selected=""'; } ?> value="980" >Украинские гривны</option>
         <option <?php if($param["curr"] == "398"){ echo ' selected=""'; } ?> value="398" >Казахстанские тенге</option>
         <option <?php if($param["curr"] == "974"){ echo ' selected=""'; } ?> value="974" >Белорусские рубли</option>
         <option <?php if($param["curr"] == "972"){ echo ' selected=""'; } ?> value="972" >Таджикские сомони</option>
         <option <?php if($param["curr"] == "985"){ echo ' selected=""'; } ?> value="985" >Польский злотый</option>
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