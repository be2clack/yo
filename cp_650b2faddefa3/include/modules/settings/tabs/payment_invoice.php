
<?php
$requisites = $settings["requisites"] ? json_decode(decrypt($settings["requisites"]), true) : [];
?>
<div class="tab-pane fade <?php if($tab == "payment_invoice"){ echo 'active show'; } ?>" id="tab-payment_invoice" role="tabpanel" aria-labelledby="tab-payment_invoice">

<div class="form-group row d-flex align-items-center mb-5">
     <label class="col-lg-3 form-control-label">Пополнение баланса через реквизиты</label>
     <div class="col-lg-9">
         <label>
           <input class="toggle-checkbox-sm" type="checkbox" name="balance_payment_requisites" value="1" <?php if($settings["balance_payment_requisites"] == 1){ echo ' checked=""'; } ?> >
           <span><span></span></span>
         </label>
     </div>
 </div>

<div class="form-group row d-flex align-items-center mb-5">
   <label class="col-lg-3 form-control-label">Подпись</label>
   <div class="col-lg-9">
     <div class="settings-change-img settings-logo" >

       <?php echo img( array( "img1" => array( "class" => "load-requisites-image-signature", "path" => $config["media"]["other"] . "/" . $settings['requisites_image_signature'], "width" => "32px" ), "img2" => array( "class" => "load-requisites-image-signature", "path" => $config["media"]["other"] . "/icon_photo_add.png", "width" => "32px" ) ) ); ?>

       <input type="file" class="input-requisites-image-signature" name="requisites_image_signature" >
       <input type="hidden" name="requisites_image_signature_delete" value="0" >

     </div>
     <div><span class="settings-requisites-image-signature-delete" <?php if(!$settings['requisites_image_signature']){ echo 'style="display: none;"'; } ?> >Удалить</span></div>
   </div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
   <label class="col-lg-3 form-control-label">Печать</label>
   <div class="col-lg-9">
     <div class="settings-change-img settings-logo" >

       <?php echo img( array( "img1" => array( "class" => "load-requisites-image-print", "path" => $config["media"]["other"] . "/" . $settings['requisites_image_print'], "width" => "32px" ), "img2" => array( "class" => "load-requisites-image-print", "path" => $config["media"]["other"] . "/icon_photo_add.png", "width" => "32px" ) ) ); ?>

       <input type="file" class="input-requisites-image-print" name="requisites_image_print" >
       <input type="hidden" name="requisites_image_print_delete" value="0" >
       
     </div>
     <div><span class="settings-requisites-image-print-delete" <?php if(!$settings['requisites_image_print']){ echo 'style="display: none;"'; } ?> >Удалить</span></div>
   </div>
</div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">ИНН</label>
    <div class="col-lg-5">
       
       <input type="text" name="requisites[inn]" class="form-control" value="<?php if(isset($requisites['inn'])) echo $requisites['inn']; ?>" >

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Правовая форма</label>
    <div class="col-lg-5">
       
       <select name="requisites[legal_form]" class="selectpicker settings-requisites-change-legal-form" >
          <option value="0" >Не выбрано</option>
          <option value="1" <?php if($requisites['legal_form'] == 1) echo 'selected=""'; ?> >Юридическое лицо</option>
          <option value="2" <?php if($requisites['legal_form'] == 2) echo 'selected=""'; ?> >ИП</option>
       </select>                     

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">НДС</label>
    <div class="col-lg-5">
       
      <div class="input-group mb-2">
         <input type="text" name="requisites[nds]" class="form-control" value="<?php if(isset($requisites['nds'])) echo $requisites['nds']; ?>" >
         <div class="input-group-prepend">
            <div class="input-group-text">%</div>
         </div>                       
      </div>
       
      <small>Оставьте поле пустым если ндс не применен</small>

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Название организации</label>
    <div class="col-lg-5">
       
       <input type="text" name="requisites[name_company]" class="form-control" value="<?php if(isset($requisites['name_company'])) echo $requisites['name_company']; ?>" >

    </div>
 </div>

 <div class="settings-requisites-legal-form-1" <?php if($requisites['legal_form'] == 1) echo 'style="display: block;"'; ?> >
    
    <div class="form-group row d-flex align-items-center mb-5">
       <label class="col-lg-3 form-control-label">КПП</label>
       <div class="col-lg-5">
          
          <input type="text" name="requisites[kpp]" class="form-control" value="<?php if(isset($requisites['kpp'])) echo $requisites['kpp']; ?>" >

       </div>
    </div>

    <div class="form-group row d-flex align-items-center mb-5">
       <label class="col-lg-3 form-control-label">ОГРН</label>
       <div class="col-lg-5">
          
          <input type="text" name="requisites[ogrn]" class="form-control" value="<?php if(isset($requisites['ogrn'])) echo $requisites['ogrn']; ?>" >

       </div>
    </div>

 </div>

 <div class="settings-requisites-legal-form-2" <?php if($requisites['legal_form'] == 1) echo 'style="display: block;"'; ?> >
    
    <div class="form-group row d-flex align-items-center mb-5">
       <label class="col-lg-3 form-control-label">ОГРНИП</label>
       <div class="col-lg-5">
          
          <input type="text" name="requisites[ogrnip]" class="form-control" value="<?php if(isset($requisites['ogrnip'])) echo $requisites['ogrnip']; ?>" >

       </div>
    </div>

 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">
       
       <h3>Информация о банке</h3>

    </div>
 </div>               

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Название банка</label>
    <div class="col-lg-5">
       
       <input type="text" name="requisites[name_bank]" class="form-control" value="<?php if(isset($requisites['name_bank'])) echo $requisites['name_bank']; ?>" >

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Расчетный счет в банке</label>
    <div class="col-lg-5">
       
       <input type="text" name="requisites[payment_account_bank]" class="form-control" value="<?php if(isset($requisites['payment_account_bank'])) echo $requisites['payment_account_bank']; ?>" >

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Корреспондентский счёт</label>
    <div class="col-lg-5">
       
       <input type="text" name="requisites[correspondent_account_bank]" class="form-control" value="<?php if(isset($requisites['correspondent_account_bank'])) echo $requisites['correspondent_account_bank']; ?>" >

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">БИК</label>
    <div class="col-lg-5">
       
       <input type="text" name="requisites[bik_bank]" class="form-control" value="<?php if(isset($requisites['bik_bank'])) echo $requisites['bik_bank']; ?>" >

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">
       
       <h3>Юридический адрес</h3>

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Почтовый индекс</label>
    <div class="col-lg-5">
       
       <input type="text" name="requisites[address_index]" class="form-control" value="<?php if(isset($requisites['address_index'])) echo $requisites['address_index']; ?>" >

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Регион</label>
    <div class="col-lg-5">
       
       <input type="text" name="requisites[address_region]" class="form-control" value="<?php if(isset($requisites['address_region'])) echo $requisites['address_region']; ?>" >

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Город</label>
    <div class="col-lg-5">
       
       <input type="text" name="requisites[address_city]" class="form-control" value="<?php if(isset($requisites['address_city'])) echo $requisites['address_city']; ?>" >

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Улица</label>
    <div class="col-lg-5">
       
       <input type="text" name="requisites[address_street]" class="form-control" value="<?php if(isset($requisites['address_street'])) echo $requisites['address_street']; ?>" >

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Дом</label>
    <div class="col-lg-5">
       
       <input type="text" name="requisites[address_house]" class="form-control" value="<?php if(isset($requisites['address_house'])) echo $requisites['address_house']; ?>" >

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Офис</label>
    <div class="col-lg-5">
       
       <input type="text" name="requisites[address_office]" class="form-control" value="<?php if(isset($requisites['address_office'])) echo $requisites['address_office']; ?>" >

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label"></label>
    <div class="col-lg-5">
       
       <h3>Генеральный директор</h3>

    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">ФИО</label>
    <div class="col-lg-5">
       
       <input type="text" name="requisites[fio]" class="form-control" value="<?php if(isset($requisites['fio'])) echo $requisites['fio']; ?>" >

    </div>
 </div>

</div>