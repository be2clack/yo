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
include_once("../fn.php");

if(isAjax() == true){

$Filters = new Filters();

$id_filter = (int)$_POST["id"];

$getFilter = findOne("uni_ads_filters","ads_filters_id=?", array($id_filter));

$cat_ids = $Filters->getCategory( ["id_filter"=>$id_filter] );

$getCategory = getAll("select * from uni_category_board where category_board_id IN(".implode(",", $cat_ids).")");

$getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=?", [$id_filter]);


if($getItems){

  ?>
  <div class="alert alert-primary alert-dissmissible fade show" >
    Макросы: <?php echo outStaticTextMakros(); ?>
  </div>  
  <?php
  
  foreach ($getCategory as $key => $value_cat) {

      ?>
      <h3><strong><?php echo $value_cat["category_board_name"]; ?></strong></h3>
      <?php

      foreach ($getItems as $key => $value) {

         $getAlias = getOne("select * from uni_ads_filters_alias where ads_filters_alias_id_filter_item=? and ads_filters_alias_id_cat=?", [$value["ads_filters_items_id"], $value_cat["category_board_id"]]);

         ?>

           <div>

               <div style="margin: 8px 0;" >
                 <strong><?php echo $value["ads_filters_items_value"]; ?></strong>
               </div>
               <div style="margin: 8px 0;">
                 <input type="text" class="form-control" name="alias[<?php echo $value_cat["category_board_id"]; ?>][<?php echo $value["ads_filters_items_id"]; ?>][name]" placeholder="Название в ссылке" value="<?php echo $getAlias["ads_filters_alias_name"]; ?>" >
               </div>
               <div style="margin: 8px 0;">
                 <input type="text" class="form-control" name="alias[<?php echo $value_cat["category_board_id"]; ?>][<?php echo $value["ads_filters_items_id"]; ?>][title]" placeholder="Заголовок (meta title)" value="<?php echo $getAlias["ads_filters_alias_title"]; ?>" >
               </div>
               <div style="margin: 8px 0;">
                 <input type="text" class="form-control" name="alias[<?php echo $value_cat["category_board_id"]; ?>][<?php echo $value["ads_filters_items_id"]; ?>][alias]" placeholder="Алиас (название в url)" value="<?php echo $getAlias["ads_filters_alias_alias"]; ?>" >
               </div>         
               <div style="margin: 8px 0;">
                 <input type="text" class="form-control" name="alias[<?php echo $value_cat["category_board_id"]; ?>][<?php echo $value["ads_filters_items_id"]; ?>][h1]" placeholder="Заголовок h1" value="<?php echo $getAlias["ads_filters_alias_h1"]; ?>" >
               </div>
               <div style="margin: 8px 0;">
                 <textarea class="form-control" name="alias[<?php echo $value_cat["category_board_id"]; ?>][<?php echo $value["ads_filters_items_id"]; ?>][desc]" placeholder="Краткое описание (meta description)" ><?php echo $getAlias["ads_filters_alias_desc"]; ?></textarea>
               </div>
               <div style="margin: 8px 0;">
                 <textarea class="form-control" name="alias[<?php echo $value_cat["category_board_id"]; ?>][<?php echo $value["ads_filters_items_id"]; ?>][text]" placeholder="Дополнительное описание (под объявлениями)" ><?php echo $getAlias["ads_filters_alias_text"]; ?></textarea>
               </div>
           </div>

         <?php
      }

      ?>
      <br>
      <?php

  }
}

} 
?>