<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_board']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$id_filter = (int)$_POST["id_filter"];
$id_item = (int)$_POST["id_item"];
      
if( !$id_item || !$id_filter ) exit;

  $items = getAll("SELECT * FROM uni_ads_filters_items WHERE ads_filters_items_id_filter=? and ads_filters_items_id_item_parent=? order by  ads_filters_items_sort asc", array($id_filter,$id_item));
  
  ?>

  <button class="btn btn-success btn-sm action-add-item-filter" ><i class="la la-plus" ></i> Добавить значение</button>

  <div class="alert alert-primary filter-slider-hint" style="margin-top: 15px; font-size: 12px;" role="alert">
    Добавьте 2 поля. В первом укажите значение от, а во втором поле значение до
  </div>

  <?php

  if(count($items)>0){
  
  ?>

     <div class="list-podfilter" >
       <?php
           foreach($items AS $item){ 

                 ?>
                 <div class="podfilter-item" ><input type="text" class="form-control" value="<?php echo $item["ads_filters_items_value"]; ?>" name="value_filter[edit][<?php echo $item["ads_filters_items_id"]; ?>]" /><i class="la la-arrows-v sort-move-podfilter" ></i><i class="la la-times delete-podfilter" ></i></div>
                 <?php
             
           }
       ?>
     </div>

  <?php
   
  }else{

    ?>
     <div class="list-podfilter" ></div>
    <?php

  }      
      
}
?>