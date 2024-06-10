<?php 
if( !defined('unisitecms') ) exit;

$getPackagesCategories = $Profile->getPackagesCategories();

include_once("fn.php");

$CategoryBoard = new CategoryBoard();
$Main = new Main();

$url[] = "route=ad_packages";

$LINK = "?".implode("&",$url);

if($_GET["cat_id"]){
   
   $ids = idsBuildJoin($CategoryBoard->idsBuild($_GET["cat_id"], $getPackagesCategories),$_GET["cat_id"]);
   $query = "where cat_id IN(".$ids.")";

}

?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Пакеты размещений</h2>
      </div>
   </div>
</div>

<div class="form-group" style="margin-bottom: 25px;" >
 
 <div class="btn-group" >

     <button class="btn btn-gradient-04" data-toggle="modal" data-target="#modal-add-package" >Добавить</button>

 </div>

 <div class="btn-group" >

     <div class="dropdown">
      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Категории <?php if(!empty($_GET["cat_name"])){ echo "(".$_GET["cat_name"].")"; } ?>
      </button>
      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="max-height: 350px; overflow: auto;" >
         <a class="dropdown-item" href="?route=ad_packages">Все категории</a>
         <?php echo outCategoryDropdownPackages(); ?>
      </div>
     </div>

 </div>

</div>

<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
            <div class="table-responsive">

                <?php

                     $get = getAll("SELECT * FROM uni_ads_packages_categories $query ORDER By id DESC");     

                     if($get){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Категория</th>
                            <th>Пакет</th>
                            <th>Стоимость</th>
                            <th style="text-align: right;" ></th>
                           </tr>
                        </thead>
                        <tbody>                     
                             <?php

                                foreach($get AS $value){

                                    $getPackages = getAll("select * from uni_ads_packages where id=?", [$value["package_id"]]);

                                    foreach ($getPackages as $package) {
                                        
                                        ?>

                                             <tr>  
                                                 <td>
                                                   <?php echo $getPackagesCategories["category_board_id"][$value["cat_id"]]["category_board_name"]; ?>
                                                 </td>                                                                                          
                                                 <td>
                                                   <?php echo $package["count_ad"]; ?> <?php echo ending($package["count_ad"], 'размещение', 'размещения', 'размещений') ?> на <?php echo $package["period"]; ?> <?php echo ending($package["period"], 'день', 'дня', 'дней') ?>
                                                 </td>                                                                  
                                                 <td>
                                                    <?php echo $Main->price($package["price_ad"]); ?>                              
                                                 </td>
                                                 <td class="td-actions" style="text-align: right;" >
                                                  <a href="#" class="load-edit-package"  data-toggle="modal" data-target="#modal-edit-package" data-id="<?php echo $package["id"]; ?>" ><i class="la la-pencil edit"></i></a>
                                                  <a href="#" class="delete-package" data-id="<?php echo $package["id"]; ?>" ><i class="la la-close delete"></i></a>
                                                 </td>
                                             </tr> 
                                 
                                       
                                         <?php 

                                    }
                                        
                                } 

                                ?>

                           </tbody>
                        </table>

                        <?php               
                     }else{
                         
                         ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Пакетов нет</p>
                            </div>
                         <?php

                     }

                ?>

            </div>
         </div>
      </div>
   </div>
</div>

<div id="modal-add-package" class="modal fade">
   <div class="modal-dialog" style="max-width: 500px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Добавить пакет</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-add-package" >

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Категория</label>
                    <div class="col-lg-6">
                        <select name="cat_id[]" class="selectpicker" multiple="" title="Не выбрано"  data-width="100%" >
                           <?php echo outCategoryOptionsPackages(0,0,(new CategoryBoard())->getCategories("where category_board_visible=1 and category_board_status_paid=1")); ?>
                        </select>                         
                    </div>
                  </div>
  
                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Срок пакета</label>
                    <div class="col-lg-6">
                        <div class="input-group mb-2">
                           <input type="number" class="form-control" value="30" name="period" >
                           <div class="input-group-prepend">
                              <div class="input-group-text">дней</div>
                           </div>                       
                        </div>
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Объявлений</label>
                    <div class="col-lg-6">
                         <input type="number" class="form-control" value="" name="count_ad" >
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Стоимость объявления</label>
                    <div class="col-lg-6">
                        <div class="input-group mb-2">
                           <input type="number" class="form-control" name="price_ad" >
                           <div class="input-group-prepend">
                              <div class="input-group-text"><?php echo $settings["currency_main"]["sign"]; ?></div>
                           </div>                       
                        </div>
                    </div>
                  </div>

               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary button-add-package">Добавить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-edit-package" class="modal fade">
   <div class="modal-dialog" style="max-width: 500px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Редактирование пакета</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-edit-package" ></form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary button-edit-package">Сохранить</button>
         </div>
      </div>
   </div>
</div>


<script type="text/javascript" src="include/modules/board/script.js"></script>
     
