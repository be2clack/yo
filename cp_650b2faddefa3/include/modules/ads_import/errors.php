<?php 
if( !defined('unisitecms') ) exit;
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Ошибки импорта №<?php echo $_GET["uniq"]; ?></h2>
      </div>
   </div>
</div>  

<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">

          <textarea class="form-control" style="min-height: 500px;" ><?php
            $path = $config["basePath"] . "/" . $config["folder_admin"] . "/include/modules/ads_import/errors/".$_GET["uniq"].".txt";
            if(file_exists($path)){
               $data = unserialize(file_get_contents($path));
               if(count($data)){
                  foreach ($data as $name => $value) {
                     echo $name . ', ' . implode(", ", $value) . "\r";
                  }
               }else{
                  echo 'Ошибок нет';
               }
            }
            ?>
          </textarea>
            
         </div>

      </div>
   </div>
</div>

<script type="text/javascript" src="include/modules/ads_import/script.js"></script>     

