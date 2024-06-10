
<?php

$social_share_links = [];

if($settings["social_share_links"]){
    $social_share_links = json_decode($settings["social_share_links"], true);
}

?>

<div class="tab-pane fade <?php if($tab == "integrations"){ echo 'active show'; } ?>" id="tab-integrations" role="tabpanel" aria-labelledby="tab-integrations">

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-6">
        <h3> <strong>Поделиться в соц сетях</strong> </h3>
    </div>
 </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Выберите сервис</label>
    <div class="col-lg-5">
         <select name="social_share_links[]" class="selectpicker" multiple title="Не выбрано" >
           <option value="vk" <?php if(in_array('vk', $social_share_links)){ echo 'selected'; } ?> >ВКонтакте</option>
           <option value="ok" <?php if(in_array('ok', $social_share_links)){ echo 'selected'; } ?> >Одноклассники</option>
           <option value="fb" <?php if(in_array('fb', $social_share_links)){ echo 'selected'; } ?> >FaceBook</option>
           <option value="tw" <?php if(in_array('tw', $social_share_links)){ echo 'selected'; } ?> >Twitter</option>
         </select>
    </div>
  </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-6">
        <h3> <strong>СМС рассылка</strong> </h3>
    </div>
 </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Выберите сервис</label>
    <div class="col-lg-5">
         <select name="sms_service" class="selectpicker" >
           <option value="" >Не выбрано</option>
           <?php
             if($config["sms_services"]){
                 foreach ($config["sms_services"] as $name => $value) {
                     ?>
                     <option <?php if($name == $settings["sms_service"]){ echo 'selected=""'; } ?> value="<?php echo $name; ?>" data-param="<?php echo $value["param"]; ?>" data-label="<?php echo $value["label"]; ?>" data-call="<?php echo $value["call"]; ?>" ><?php echo $name; ?></option>
                     <?php
                 }
             }
           ?>
         </select>
    </div>
  </div>
  
  <div class="sms_service_method_send" <?php if( $config["sms_services"][$settings["sms_service"]]["call"] ){ echo 'style="display: block;"'; } ?> >
    
     <div class="form-group row d-flex align-items-center mb-5">
       <label class="col-lg-3 form-control-label">Метод подтверждения</label>
       <div class="col-lg-5">
            <select name="sms_service_method_send" class="selectpicker" >
              <option value="sms" <?php if( $settings["sms_service_method_send"] == 'sms' ){ echo 'selected=""'; } ?> >По смс</option>
              <option value="call" <?php if( $settings["sms_service_method_send"] == 'call' ){ echo 'selected=""'; } ?> >По звонку</option>
            </select>
       </div>
     </div>

  </div>

  <div class="sms_service_label" <?php if( $config["sms_services"][$settings["sms_service"]]["label"] ){ echo 'style="display: block;"'; } ?> >
    
      <div class="form-group row d-flex align-items-center mb-5">
        <label class="col-lg-3 form-control-label">Имя отправителя</label>
        <div class="col-lg-5">
             <input type="text" class="form-control"  name="sms_service_label" value="<?php echo $settings["sms_service_label"]; ?>" >
        </div>
      </div>

  </div>

  <div class="sms_service_method_send_sms" <?php if( $settings["sms_service_method_send"] == 'sms' ){ echo 'style="display: block;"'; } ?> >

     <div class="form-group row d-flex align-items-center mb-5">
        <label class="col-lg-3 form-control-label">Сообщение</label>
        <div class="col-lg-5">
             <input type="text" class="form-control"  name="sms_prefix_confirmation_code" value="<?php echo $settings["sms_prefix_confirmation_code"]; ?>" >
        </div>
     </div>

  </div>

  <div class="sms_service_id" <?php if( $config["sms_services"][$settings["sms_service"]]["param"] == "id" ){ echo 'style="display: block;"'; } ?> >

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Api id</label>
      <div class="col-lg-5">
           <input type="text" class="form-control"  name="sms_service_id" value="<?php echo decrypt($settings["sms_service_id"]); ?>" >
      </div>
    </div>

    <?php if($settings["sms_service_id"]){ ?>

      <div class="form-group row d-flex align-items-center mb-5">
        <label class="col-lg-3 form-control-label"></label>
        <div class="col-lg-5">
             <a data-toggle="modal" data-target="#modal-log" class="test-send-sms btn btn-primary" >Тестовая отправка</a>
        </div>
      </div>

    <?php } ?>

  </div>
  
  <div class="sms_service_login_pass" <?php if( $config["sms_services"][$settings["sms_service"]]["param"] == "login:pass" ){ echo 'style="display: block;"'; } ?>  >
    
    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Логин</label>
      <div class="col-lg-5">
           <input type="text" class="form-control"  name="sms_service_login" value="<?php echo $settings["sms_service_login"]; ?>" >
      </div>
    </div>              

    <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Пароль</label>
      <div class="col-lg-5">
           <input type="text" class="form-control"  name="sms_service_pass" value="<?php echo decrypt($settings["sms_service_pass"]); ?>" >
      </div>
    </div>

    <?php if($settings["sms_service_login"] && $settings["sms_service_pass"]){ ?>
      <div class="form-group row d-flex align-items-center mb-5">
        <label class="col-lg-3 form-control-label"></label>
        <div class="col-lg-5">
             <a data-toggle="modal" data-target="#modal-log" class="test-send-sms btn btn-primary" >Тестовая отправка</a>
        </div>
      </div>
    <?php } ?>

  </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-6">
        <h3> <strong>Telegram bot</strong> </h3>
        <a href="https://unisite.org/doc/nastroyka-telegram-opoveshcheniy"> <strong><i class="la la-question-circle question-circle"></i> Как настроить telegram bot?</strong> </a>
    </div>
 </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Ключ токена</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo decrypt($settings["api_id_telegram"]); ?>"  name="api_id_telegram" >
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">ID чата</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $settings["chat_id_telegram"]; ?>"  name="chat_id_telegram" >
    </div>
  </div>
  
  <?php if($settings["api_id_telegram"] && $settings["chat_id_telegram"]){ ?>
  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">
         <a data-toggle="modal" data-target="#modal-log" class="test-send-telegram btn btn-primary" >Проверить подключение</a>
    </div>
  </div>
  <?php } ?>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-6">
        <h3> <strong>Карты</strong> </h3>
        <a href="https://unisite.org/doc/nastroyka-yandeks-i-google-kart"> <strong><i class="la la-question-circle question-circle"></i> Как настроить карту?</strong> </a>
    </div>
 </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Выберите поставщика карт</label>
    <div class="col-lg-5">
         <select name="map_vendor" class="selectpicker" >
            <option value="yandex" <?php if($settings["map_vendor"] == "yandex"){ echo 'selected=""'; } ?> >Yandex</option>
            <option value="google" <?php if($settings["map_vendor"] == "google"){ echo 'selected=""'; } ?> >Google</option>
            <option value="openstreetmap" <?php if($settings["map_vendor"] == "openstreetmap"){ echo 'selected=""'; } ?> >OpenStreetMap</option>
         </select>
    </div>
  </div>
  
  <div class="map-google-key" <?php if($settings["map_vendor"] == "google"){ echo 'style="display: block;"'; } ?> >
  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Ключ</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $settings["map_google_key"]; ?>"  name="map_google_key" >
    </div>
  </div>
  </div>

  <div class="map-yandex-key" <?php if($settings["map_vendor"] == "yandex"){ echo 'style="display: block;"'; } ?> >
  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Ключ</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $settings["map_yandex_key"]; ?>"  name="map_yandex_key" >
    </div>
  </div>
  </div>

  <div class="map-openstreetmap-key" <?php if($settings["map_vendor"] == "openstreetmap"){ echo 'style="display: block;"'; } ?> >
  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Ключ</label>
    <div class="col-lg-5">
         <input type="text" class="form-control" value="<?php echo $settings["map_openstreetmap_key"]; ?>"  name="map_openstreetmap_key" >
    </div>
  </div>
  </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-6">
        <h3> <strong>Служба доставки</strong> </h3>
    </div>
 </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Служба доставки</label>
    <div class="col-lg-5">
         <select name="delivery_service" class="selectpicker" >
            <option value="" <?php if(!$settings["delivery_service"]){ echo 'selected=""'; } ?> >Не выбрано</option>
            <option value="boxberry" <?php if($settings["delivery_service"] == 'boxberry'){ echo 'selected=""'; } ?> >Boxberry</option>
         </select>
         <div class="mt5" >
            <small>Выберите службу доставки которая будет использоваться на сайте</small>
         </div>
    </div>
  </div>
  
  <div class="settings-box-options-delivery" <?php if($settings["delivery_service"]){ echo 'style="display: block;"'; } ?> >

     <div class="form-group row d-flex align-items-center mb-5">
       <label class="col-lg-3 form-control-label">Ключ API</label>
       <div class="col-lg-5">
            <input type="text" class="form-control" value="<?php echo decrypt($settings["delivery_api_key"]); ?>"  name="delivery_api_key" >
       </div>
     </div>

     <div class="form-group row d-flex align-items-center mb-5">
       <label class="col-lg-3 form-control-label">Категории товаров</label>
       <div class="col-lg-5">
            <select name="delivery_available_categories[]" class="selectpicker" multiple="" title="Не выбрано" >
               <?php echo outCategoryOptionsDelivery(0,0,(new CategoryBoard())->getCategories()); ?>
            </select>
            <div class="mt5" >
               <small>Выберите категории товаров которые подлежат доставки</small>
            </div>
       </div>
     </div>

     <div class="form-group row d-flex align-items-center mb-5">
       <label class="col-lg-3 form-control-label">Цена от</label>
       <div class="col-lg-2">
            <div class="input-group mb-2">
               <input type="text" class="form-control" name="delivery_from_price" value="<?php echo $settings["delivery_from_price"]; ?>">
               <div class="input-group-prepend">
                  <div class="input-group-text"><?php echo $settings["currency_main"]["sign"]; ?></div>
               </div>                       
            </div>
            <small>Укажите от какой цены действует доставка</small>
       </div>
     </div>

     <div class="form-group row d-flex align-items-center mb-5">
       <label class="col-lg-3 form-control-label">Цена до</label>
       <div class="col-lg-2">
            <div class="input-group mb-2">
               <input type="text" class="form-control" name="delivery_before_price" value="<?php echo $settings["delivery_before_price"]; ?>">
               <div class="input-group-prepend">
                  <div class="input-group-text"><?php echo $settings["currency_main"]["sign"]; ?></div>
               </div>                       
            </div>                  
            <small>Укажите до какой цены действует доставка</small>
       </div>
     </div>

     <div class="form-group row d-flex align-items-center mb-5">
       <label class="col-lg-3 form-control-label">Минимальный вес товара</label>
       <div class="col-lg-2">
            <div class="input-group mb-2">
               <input type="text" class="form-control" name="delivery_weight_min" value="<?php echo $settings["delivery_weight_min"]; ?>">
               <div class="input-group-prepend">
                  <div class="input-group-text">гр</div>
               </div>                       
            </div>                  
       </div>
     </div>

     <div class="form-group row d-flex align-items-center mb-5">
       <label class="col-lg-3 form-control-label">Максимальный вес товара</label>
       <div class="col-lg-2">
            <div class="input-group mb-2">
               <input type="text" class="form-control" name="delivery_weight_max" value="<?php echo $settings["delivery_weight_max"]; ?>">
               <div class="input-group-prepend">
                  <div class="input-group-text">гр</div>
               </div>                       
            </div>                  
       </div>
     </div>

  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-6">
        <h3> <strong>Подключение сервисов для авторизации</strong> </h3>
    </div>
  </div>

  <div class="form-group row d-flex align-items-center">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">

         <h4> <strong>Yandex</strong> </h4>

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">ID приложения</label>
    <div class="col-lg-5">

         <input type="text" class="form-control mt5" value="<?php echo $social_auth_params["yandex"]["id_app"]; ?>"  name="social_auth_params[yandex][id_app]" >  

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Секретный ключ</label>
    <div class="col-lg-5">

         <input type="text" class="form-control mt5" value="<?php echo $social_auth_params["yandex"]["key"]; ?>"  name="social_auth_params[yandex][key]" >                
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">URL перенаправления</label>
    <div class="col-lg-5">

         <?php echo $config["urlPath"] . "/systems/ajax/oauth.php?network=yandex"; ?>  

    </div>
  </div>

  <div class="form-group row d-flex align-items-center">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">

         <h4> <strong>ВКонтакте</strong> </h4>

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">ID приложения</label>
    <div class="col-lg-5">

         <input type="text" class="form-control mt5" value="<?php echo $social_auth_params["vk"]["id_client"] ?>"  name="social_auth_params[vk][id_client]" >  

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Секретный ключ</label>
    <div class="col-lg-5">

         <input type="text" class="form-control mt5" value="<?php echo $social_auth_params["vk"]["key"] ?>"  name="social_auth_params[vk][key]" >                
    </div>
  </div>

  <div class="form-group row d-flex align-items-center">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">

         <h4> <strong>Google</strong> </h4>

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">ID клиента</label>
    <div class="col-lg-5">

         <input type="text" class="form-control mt5" value="<?php echo $social_auth_params["google"]["id_client"] ?>"  name="social_auth_params[google][id_client]" >  

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Секретный ключ</label>
    <div class="col-lg-5">

         <input type="text" class="form-control mt5" value="<?php echo $social_auth_params["google"]["key"] ?>"  name="social_auth_params[google][key]" >                
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">URL перенаправления</label>
    <div class="col-lg-5">

         <?php echo $config["urlPath"] . "/systems/ajax/oauth.php?network=google"; ?>

    </div>
  </div>

  <div class="form-group row d-flex align-items-center">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">

         <h4> <strong>FaceBook</strong> </h4>

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">ID приложения</label>
    <div class="col-lg-5">

         <input type="text" class="form-control mt5" value="<?php echo $social_auth_params["fb"]["id_app"]; ?>"  name="social_auth_params[fb][id_app]" >  

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Секретный ключ</label>
    <div class="col-lg-5">

         <input type="text" class="form-control mt5" value="<?php echo $social_auth_params["fb"]["key"]; ?>"  name="social_auth_params[fb][key]" >                
    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">URL перенаправления</label>
    <div class="col-lg-5">

         <?php echo $config["urlPath"] . "/systems/ajax/oauth.php?network=fb"; ?>  

    </div>
  </div>

</div>