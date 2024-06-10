<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_settings']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

if(isAjax() == true){

$country = intval($_POST["country"]);

$region = getAll("SELECT * FROM uni_region WHERE country_id = '".$country."' order by region_name asc");

if(count($region) > 0){
?>

   <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Регион</label>
      <div class="col-lg-9">
         <select name="region_id" class="selectpicker" >
            <option value="0" >Все регионы</option>
            <?php 
                
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
                
             ?>
         </select>
      </div>
   </div>

   <div class="settings-city-box" ></div>

<?php
}

}  
?>