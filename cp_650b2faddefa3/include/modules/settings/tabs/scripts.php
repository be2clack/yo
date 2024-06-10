<div class="tab-pane fade <?php if($tab == "scripts"){ echo 'active show'; } ?>" id="tab-scripts" role="tabpanel" aria-labelledby="tab-scripts">

<div class="accordion" id="accordion_code_script">

<div class="card">
   <div class="card-header" id="headingOne">
      <h5 class="mb-0">
      <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
         Meta теги
      </button>
      </h5>
   </div>

   <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion_code_script">
      <div class="card-body">
      
         <textarea class="form-control" name="header_meta" style="min-height: 300px;" ><?php echo trim($settings["header_meta"]);?></textarea>
         
      </div>
   </div>
</div>
<div class="card">
   <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
      <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            Скрипты/виджеты
      </button>
      </h5>
   </div>
   <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion_code_script">
      <div class="card-body">
          
          <textarea class="form-control" name="code_script" style="min-height: 300px;" ><?php echo trim($settings["code_script"]);?></textarea>

      </div>
   </div>
</div>
</div>

</div>