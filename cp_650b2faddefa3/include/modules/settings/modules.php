<?php 
if( !defined('unisitecms') ) exit;

if( $settings["demo_view"] ) exit;

$LINK = '?route=modules';
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Модули</h2>
      </div>
   </div>
</div>  

<div class="row flex-row">
 
   <?php   

   $getModules = file_get_contents_curl("http://api.unisitecloud.ru/get_modules.php?uniq_id_order=".$settings["uniq_id_order"]."&domen=".$settings["domen"]);

   if( $getModules ){
       echo $getModules;
   }

   ?>

</div>

        
<script type="text/javascript" src="include/modules/settings/script.js"></script>

