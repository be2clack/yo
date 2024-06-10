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
   $get = getAll("SELECT * FROM uni_metro WHERE city_id = $id and parent_id=0 Order by name ASC");
   
    if (count($get) > 0)
    { 
                         
         ?>

        <table class="table mb-0">
        <thead>
             <tr>
              <th>Метро</th>
              <th style="text-align: right;" ></th>
             </tr>
         </thead>
         <tbody>

         <?php       
      
         foreach($get AS $array_data){ 


            ?>

               <tr id="item<?php echo $array_data["id"]; ?>" >
                   
                   <td><span class="metro-color-line" style="background-color:<?php echo $array_data["color"]; ?>;" ></span> <a href="#" ><?php echo $array_data["name"]; ?></a></td>
                   <td style="text-align: right;" class="td-actions" >
                    <?php if($array_data["parent_id"] == 0){ ?>
                    <a data-id="<?php echo $array_data["id"]; ?>" class="btn-modal-icon add_metro_station"><i class="la la-plus edit"></i></a>
                    <?php } ?>
                    <a data-id="<?php echo $array_data["id"]; ?>" class="btn-modal-icon edit_metro_station"><i class="la la-edit edit"></i></a>
                    <a data-id="<?php echo $array_data["id"]; ?>" class="btn-modal-icon delete_metro_city"><i class="la la-close delete"></i></a>
                   </td>
                     
               </tr>

            <?php 

              $getAll = getAll("select * from uni_metro where parent_id='".$array_data["id"]."'");
              if(count($getAll)){
                 foreach ($getAll as $key => $value) {
                    ?>

                           <tr id="item<?php echo $value["id"]; ?>" >
                               
                               <td><span><?php echo $value["name"]; ?></span></td>
                               <td style="text-align: right;" class="td-actions" >
                                <a data-id="<?php echo $value["id"]; ?>" class="btn-modal-icon delete_metro_station"><i class="la la-close delete"></i></a>
                               </td>
                                 
                           </tr>

                    <?php                    
                 }
              }


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


 