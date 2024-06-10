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

?>

  <div class="table-responsive">  
   
  <?php            
   $get = getAll("SELECT * FROM uni_city_area WHERE city_area_id_city = $id Order by city_area_name ASC");
   
    if (count($get) > 0)
    { 
                         
         ?>

        <table class="table mb-0">
        <thead>
             <tr>
              <th>Название</th>
              <th style="text-align: right;" ></th>
             </tr>
         </thead>
         <tbody>

         <?php       
      
         foreach($get AS $array_data){ 


            ?>

               <tr>
                   
                   <td><?php echo $array_data["city_area_name"]; ?></td>
                   <td style="text-align: right;" class="td-actions" >
                    <a data-id="<?php echo $array_data["city_area_id"]; ?>" class="btn-modal-icon delete_area_city"><i class="la la-close delete"></i></a>
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
                 <p>Список пуст</p>
              </div>
           <?php
    }

      
    ?>
        
 </div>


 
 