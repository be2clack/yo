<?php 
if( !defined('unisitecms') ) exit;

$Banners = new Banners();
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Баннеры/Реклама</h2>
      </div>
   </div>
</div>  

<div class="form-group" style="margin-bottom: 25px;" >
 
 <div class="btn-group" >  
 <a  href="?route=add_advertising" class="btn btn-gradient-04">Добавить</a>
 </div>

</div>

<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
            <div class="table-responsive">

                 <?php

                    $get = getAll("SELECT * FROM uni_advertising order by advertising_id desc");     

                     if(count($get)){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Название</th>
                            <th>Начало показа</th>
                            <th>Конец показа</th>
                            <th>Кликов</th>
                            <th style="text-align: right;" ></th>
                           </tr>
                        </thead>
                        <tbody class="sort-container" >                     
                     <?php

                        foreach($get AS $value){

                          if($value["advertising_date_end"] == "0000-00-00 00:00:00"){
                              $date_end = "∞";
                          }else{ $date_end = $value["advertising_date_end"]; }

                            ?>

                             <tr>
                                 <td><a href="?route=edit_advertising&id=<?php echo $value["advertising_id"]; ?>" ><?php echo $value["advertising_title"]; ?></a><br/><span>Расположение - </span> <?php echo $Banners->positions()[$value["advertising_banner_position"]]["name"]; ?></td> 
                                 <td><?php echo datetime_format_cp($value["advertising_date_start"]); ?></td>
                                 <td><?php echo $date_end; ?></td>
                                 <td><?php echo $value["advertising_click"]; ?></td> 
                                 <td class="td-actions" style="text-align: right;" >
                                  <a href="?route=edit_advertising&id=<?php echo $value["advertising_id"]; ?>"><i class="la la-edit edit"></i></a>
                                  <a href="#" class="delete-advertising" data-id="<?php echo $value["advertising_id"]; ?>" ><i class="la la-close delete"></i></a>
                                 </td>                          
                             </tr> 
                     
                           
                             <?php                                         
                            } 

                            ?>

                           </tbody>
                        </table>

                        <?php               
                     }else{
                         
                         ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Рекламных материалов нет</p>
                            </div>
                         <?php

                     }                  
                  ?>

            </div>
         </div>
      </div>
   </div>
</div>


<script type="text/javascript" src="include/modules/advertising/script.js"></script>
 