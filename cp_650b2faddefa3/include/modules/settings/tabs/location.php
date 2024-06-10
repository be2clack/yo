<div class="tab-pane fade <?php if($tab == "location"){ echo 'active show'; } ?>" id="tab-location" role="tabpanel" aria-labelledby="tab-location">

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Автоматическое определение города посетителя</label>
    <div class="col-lg-9">
        <label>
          <input class="toggle-checkbox-sm" type="checkbox" name="city_auto_detect" value="1" <?php if($settings["city_auto_detect"] == 1){ echo ' checked=""'; } ?> >
          <span><span></span></span>
        </label>
    </div>
 </div>

 <div class="form-group row d-flex align-items-center mb-5">
    <label class="col-lg-3 form-control-label">Основная страна сайта</label>
    <div class="col-lg-9">
       <select name="country_default" class="selectpicker" >
            <?php 
                $country = getAll("SELECT * FROM uni_country WHERE country_status = 1 order by country_name asc");
                if(count($country) > 0){
                  foreach ($country as $key => $value) {
                    if($settings["country_default"] == $value["country_alias"]){
                      $selected = 'selected=""';
                    }else{
                      $selected = '';
                    }
                    ?>
                      <option data-id="<?php echo $value["country_id"]; ?>" value="<?php echo $value["country_alias"]; ?>" <?php echo $selected; ?> ><?php echo $value["country_name"]; ?></option>
                    <?php
                  }
                }
             ?>
       </select>
    </div>
 </div>  

 <div class="settings-region-box" >
  
   <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Регион</label>
      <div class="col-lg-9">
         <select name="region_id" class="selectpicker" >
            <option value="0" >Все регионы</option>
            <?php 
                $region = getAll("SELECT * FROM uni_region WHERE country_id = '".intval($settings["country_id"])."' order by region_name asc");
                if(count($region) > 0){
                  foreach ($region as $key => $value) {
                    if($settings["region_id"] == $value["region_id"]){
                      $selected = 'selected=""';
                    }else{
                      $selected = '';
                    }
                    ?>
                      <option value="<?php echo $value["region_id"]; ?>" <?php echo $selected; ?> ><?php echo $value["region_name"]; ?></option>
                    <?php
                  }
                }
             ?>
         </select>
      </div>
   </div>

   <div class="settings-city-box" >
    <?php if($settings["region_id"]){ ?>
     <div class="form-group row d-flex align-items-center mb-5">
        <label class="col-lg-3 form-control-label">Город</label>
        <div class="col-lg-9">
           <select name="city_id" class="selectpicker" >
              <option value="0" >Все города</option>
              <?php 
                  $city = getAll("SELECT * FROM uni_city WHERE region_id = '".intval($settings["region_id"])."' order by city_name asc");
                  if(count($city) > 0){
                    foreach ($city as $key => $value) {
                      if($settings["city_id"] == $value["city_id"]){
                        $selected = 'selected=""';
                      }else{
                        $selected = '';
                      }
                      ?>
                        <option value="<?php echo $value["city_id"]; ?>" <?php echo $selected; ?> ><?php echo $value["city_name"]; ?></option>
                      <?php
                    }
                  }
               ?>
           </select>
        </div>
     </div>
    <?php } ?> 
   </div>
   
 </div>

</div>