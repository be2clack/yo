
  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Обработчик оплаты</label>
    <div class="col-lg-5">
         <span><?php echo $config["urlPath"]; ?>/systems/payment/<?php echo $sql["code"]; ?>/callback.php</span>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Номер кошелька</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["wallet_number"]; ?>"  name="payment_param[wallet_number]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Секретный ключ (для проверки подлинности)</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["private_key"]; ?>"  name="payment_param[private_key]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Перенаправлять клиента при удачной оплате</label>
    <div class="col-lg-5">
         <input type="text" class="form-control"  value="<?php echo $param["link_success"] ? $param["link_success"] : $config["urlPath"] . "/pay/status/success"; ?>" name="payment_param[link_success]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">
         <a class="test-payment btn btn-primary" data-name="<?php echo $sql["code"]; ?>" >Проверить платежную систему</a>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">
         <h3 style="margin-top: 10px;" > <strong>Приложение</strong> </h3>
         <small>Регистрация приложения требуется для выплат денег по безопасным сделкам.</small>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Идентификатор приложения (client_id)</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["client_id"]; ?>"  name="payment_param[client_id]" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Секретный ключ (client_secret)</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $param["client_secret"]; ?>"  name="payment_param[client_secret]" >
    </div>
  </div>

  <?php
  if($param["client_id"]){
      $url_build = array(
            'client_id'=>$param["client_id"],
            'response_type'=>'code',
            'redirect_uri'=>$config["urlPath"].'/systems/payment/'.$sql["code"].'/OAuth.php',
            'scope'=>'account-info operation-history payment-p2p',
      );
      ?>
      <div class="form-group row d-flex align-items-center mb-5">
        <label class="col-lg-3 form-control-label"></label>
        <div class="col-lg-5">
          <?php if(!$param["access_token"]){ ?>
             <a class="btn btn-danger" target="_blank" href="https://yoomoney.ru/oauth/authorize?<?php echo urldecode(http_build_query($url_build)); ?>" >Получить токен</a>
           <?php }else{ ?>
             <a class="btn btn-success" target="_blank" href="https://yoomoney.ru/oauth/authorize?<?php echo urldecode(http_build_query($url_build)); ?>" >Получить новый токен</a>
             <input type="hidden" name="payment_param[access_token]" value="<?php echo $param["access_token"]; ?>" >
           <?php } ?>
        </div>
      </div>
      <?php 
  }
  ?>