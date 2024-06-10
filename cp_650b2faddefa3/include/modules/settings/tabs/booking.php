<div class="tab-pane fade <?php if($tab == "booking"){ echo 'active show'; } ?>" id="tab-booking" role="tabpanel" aria-labelledby="tab-booking">

   <div class="form-group row d-flex align-items-center mb-5" >
      <label class="col-lg-3 form-control-label">Платежная система</label>
      <div class="col-lg-2">

          <select class="selectpicker" name="booking_payment_service_name" title="Не выбрано" >
              <?php
                $getPayments = getAll("select * from uni_payments", []);
                if (count($getPayments)) {
                    foreach ($getPayments as $key => $value) {
                        ?>
                        <option <?php if( $settings["booking_payment_service_name"] == $value["code"] ){ echo 'selected=""'; } ?> value="<?php echo $value["code"]; ?>" ><?php echo $value["name"]; ?></option>
                        <?php
                    }
                }
              ?>
          </select>

      </div>
   </div>

   <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label"></label>
      <div class="col-lg-9">

          <small>Выберите платежную систему с помощью которой будут оплачивать бронирование/аренду.</small>

      </div>
   </div>

   <div class="form-group row d-flex align-items-center" style="margin-bottom: 0px;" >
      <label class="col-lg-3 form-control-label">Процент комиссии</label>
      <div class="col-lg-2">

          <div class="input-group mb-2">
             <input type="text" class="form-control" name="booking_prepayment_percent_service" value="<?php echo $settings["booking_prepayment_percent_service"]; ?>" >
             <div class="input-group-prepend">
                <div class="input-group-text">%</div>
             </div>                       
          </div>

      </div>
   </div>

   <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label"></label>
      <div class="col-lg-9">

          <small>Укажите процент который вы будете получать от оплаты бронирования/аренды</small>

      </div>
   </div>

</div>