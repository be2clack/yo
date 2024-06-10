<div class="tab-pane fade <?php if($tab == "payments"){ echo 'active show'; } ?>" id="tab-payments" role="tabpanel" aria-labelledby="tab-payments">

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label">Платежные системы</label>
<div class="col-lg-9">
     <select class="selectpicker" name="payment_variant[]" multiple="" title="Не выбрано" >

        <?php
           $get = getAll("select * from uni_payments");
           if(count($get) > 0){
             foreach ($get as $key => $value) {
              
              if($settings["payment_variant"]){
                if(in_array($value["id"], explode(",",$settings["payment_variant"]))){
                   $selected = 'selected=""';
                }else{
                   $selected = '';
                }
              }else{ $selected = ''; }

              ?>
              <option value="<?php echo $value["id"]; ?>" <?php echo $selected; ?> ><?php echo $value["name"]; ?></option>                          
              <?php
             }
           }
        ?>
        
     </select>
     <div class="mt10" ></div>
     <small>Выберите платежные системы которые будут использоваться на сайте при оплате.</small>
</div>
</div>

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label"></label>
<div class="col-lg-9">

    <?php
       $get = getAll("select * from uni_payments");
       if(count($get) > 0){
         foreach ($get as $key => $value) {
            ?>
            <div class="styled-radio" style="display: inline-block; margin-right: 25px;" >

                <input type="radio" name="payment" class="change-payment" value="<?php echo $value["code"]; ?>" id="payment-radio-<?php echo $value["id"]; ?>" >
                <label for="payment-radio-<?php echo $value["id"]; ?>"><img src="<?php echo Exists($config["media"]["other"],$value["logo"],$config["media"]["no_image"]); ?>" style="max-height: 40px; max-width: 70px;" ></label>

            </div>                              
            <?php
         }
       }
    ?>  

</div>
</div>         

<div class="param-payment" ></div>

</div>