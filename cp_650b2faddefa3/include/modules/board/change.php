<?php 
if( !defined('unisitecms') ) exit;

$Ads = new Ads();
$Main = new Main();

$getAd = findOne("uni_ads", "ads_id=?", [ $id ]);

if(!$getAd){ exit; }
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Лог изменений «<?php echo $getAd["ads_title"]; ?>»</h2>
         <div>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="?route=board">Объявления</a></li>
            </ul>
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

                    $get = getAll("SELECT * FROM uni_ads_change WHERE ads_change_id_ad=? order by ads_change_date desc", [$id]);     

                     if(count($get) > 0){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Действие</th>
                            <th>Дата</th>
                           </tr>
                        </thead>
                        <tbody class="sort-container" >                     
                     <?php

                        foreach($get AS $value){

                        ?>

                         <tr>
                             <td>
                               <?php
                                  if( $value["ads_change_action"] ){

                                     if( $value["ads_change_action"] == "update" ){
                                        ?>
                                        <h5>Объявление обновлено</h5>
                                        <?php
                                     }

                                  }else{
                                     ?>
                                     <h5>Статус изменен на «<?php echo $Ads->status( $value["ads_change_status"] ); ?>»</h5>
                                     <?php
                                     if($value["ads_change_note"]){
                                       echo "Причина: " . $value["ads_change_note"];
                                     }
                                  }
                               ?>
                             </td> 
                             <td><?php echo datetime_format_cp($value["ads_change_date"]); ?></td>                          
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
                               <p>Лог изменений пуст</p>
                            </div>
                         <?php

                     }                  
                  ?>

            </div>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript" src="include/modules/board/script.js"></script>

