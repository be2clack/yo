<div class="tab-pane fade <?php if($tab == "currency"){ echo 'active show'; } ?>" id="tab-currency" role="tabpanel" aria-labelledby="tab-currency">

<div class="form-group row d-flex align-items-center mb-5">
<label class="col-lg-3 form-control-label">Основная валюта сайта</label>
<div class="col-lg-9">

<select name="main_currency" id="select_main_currency" class="selectpicker" >

  <?php
  $get = getAll("SELECT * FROM uni_currency");
      if (count($get) > 0) {
          foreach($get as $result){
            
                if(!empty($result["main"])){  
                  $selected = 'selected="selected"';
                  $main_currency = $result["code"];
                  $value = "";
                }else{
                  $selected = "";
                  $value = $result["code"];
                }  
            
             ?>
             <option <?php echo $selected; ?> data-value="<?php echo $result["code"]; ?>" data-price="<?php echo $result["price"]; ?>" value="<?php echo $value; ?>" ><?php echo $result["name"]; ?>(<?php echo $result["code"]; ?>)</option>
             <?php
         
         };
  
      }           

   ?>
   
</select>

</div>
</div>

<div class="form-group row d-flex mb-5">
<label class="col-lg-3 form-control-label">Валюты</label>
<div class="col-lg-9">

<div id="main-box-currency" >

    <span class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add-currency" ><i class="la la-plus" ></i> Добавить</span>

    <div id="container-currency" >
    
     <?php
     
      $get = getAll("SELECT * FROM uni_currency");
          if (count($get) > 0) {
              foreach($get as $result){

                 ?>

                    <div class="row item<?php echo $result["id"]; ?>" id="<?php echo $result["code"]; ?>" style="margin-bottom: 10px;" >

                       <div class="col-lg-4 col-sm-4 col-md-4 col-4"><input class="form-control" type="text" placeholder="Название"  name="currency[][<?php echo $result["id"]; ?>][name]" value="<?php echo $result["name"]; ?>" /></div>

                       <div class="col-lg-2 col-sm-2 col-md-2 col-2"><input class="form-control" type="text" placeholder="Знак"  name="currency[][<?php echo $result["id"]; ?>][sign]" value="<?php echo $result["sign"]; ?>" /></div>

                       <div class="col-lg-3 col-sm-3 col-md-3 col-3"><input class="form-control" type="text" placeholder="Код"  name="currency[][<?php echo $result["id"]; ?>][code]" value="<?php echo $result["code"]; ?>" /></div>

                       <div class="col-lg-3 col-sm-3 col-md-3 col-3" ><span class="btn btn-danger btn-sm delete-currency" uid="<?php echo $result["id"]; ?>" ><i class="la la-trash-o" ></i></span></div> 

                    </div>                       
                      
                 <?php
      
              }
            
          }           
     
     
       ?>

       </div>


  </div>

</div>
</div>

</div>
