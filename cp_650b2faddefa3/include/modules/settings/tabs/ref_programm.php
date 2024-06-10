<div class="tab-pane fade <?php if($tab == "ref_programm"){ echo 'active show'; } ?>" id="tab-ref_programm" role="tabpanel" aria-labelledby="tab-ref_programm">

  <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Статус</label>
    <div class="col-lg-2">
        
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="referral_program_status" value="1" <?php if($settings["referral_program_status"]){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>

    </div>
  </div>

  <div class="form-group row d-flex align-items-center mb-5" >
     <label class="col-lg-3 form-control-label">Вознаграждение от суммы пополнения баланса</label>
     <div class="col-lg-2">

         <div class="input-group mb-2">
            <input type="text" class="form-control" name="referral_program_award_percent" value="<?php echo $settings["referral_program_award_percent"]; ?>" >
            <div class="input-group-prepend">
               <div class="input-group-text">%</div>
            </div>                       
         </div>

     </div>
  </div>

</div>