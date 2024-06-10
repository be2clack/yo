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

$region = intval($_POST["region"]);

$city = getAll("SELECT * FROM uni_city WHERE region_id = '".$region."' order by city_name asc");
if(count($city) > 0){
?>

   <div class="form-group row d-flex align-items-center mb-5">
      <label class="col-lg-3 form-control-label">Город</label>
      <div class="col-lg-9">
         <select name="city_id" class="selectpicker" >
            <option value="0" >Все города</option>
            <?php 
                
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
                
             ?>
         </select>
      </div>
   </div>

<?php
}

}  
?>