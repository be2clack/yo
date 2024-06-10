<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_city']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

$id = intval($_POST["id"]);
if(!empty($_POST["q"])) $q = clearSearch($_POST["q"]); else $q = "";

if(!empty($id)){
?>

  <div class="table-responsive">  
   
  <?php            
   $get = getAll("SELECT * FROM uni_city WHERE region_id = $id AND city_name LIKE '%$q%' Order by city_name ASC");
   
    if (count($get) > 0)
    { 
                         
         ?>

          <table class="table mb-0">
          <thead>
               <tr>
                <th>Название города</th>
                <th>По умолчанию</th>
                <th style="text-align: right;" ></th>
               </tr>
           </thead>
           <tbody>

         <?php        
      
         foreach($get AS $array_data){ 

            ?>

             <tr>
             
                 <td><span><?php echo $array_data["city_name"]; ?></span></td>
                 <td>
                     <label>
                       <input class="toggle-checkbox-sm toolbat-toggle toggle-default-city" <?php if($array_data["city_default"]){ echo 'checked=""'; } ?> type="checkbox" value="1" data-id="<?php echo $array_data["city_id"]; ?>" >
                       <span> <span></span> </span>
                     </label>                   
                 </td>
                 <td style="text-align: right;" class="td-actions" >
                  <a data-id="<?php echo $array_data["city_id"]; ?>" class="btn-modal-icon edit_item_city"><i class="la la-edit edit"></i></a>
                  <a data-id="<?php echo $array_data["city_id"]; ?>" class="btn-modal-icon delete_item_city"><i class="la la-close delete"></i></a>
                 </td>
                   
             </tr>

            <?php

        }

        ?>
        </tbody></table>
        <?php
         
    } else
    {
           ?>
              <div class="plug" >
                 <i class="la la-exclamation-triangle"></i>
                 <p>Ничего не найдено</p>
              </div>
           <?php
    }

      
        ?>
        
 </div>

 <?php }else{ 
    
    ?>
        <div class="plug" >
           <i class="la la-exclamation-triangle"></i>
           <p>Выберите регион</p>
        </div>
    <?php

  } ?>
 

