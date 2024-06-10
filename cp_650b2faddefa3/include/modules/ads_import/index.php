<?php 
if( !defined('unisitecms') ) exit;
?>


<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Импорт объявлений</h2>
      </div>
   </div>
</div>  

<div class="form-group">
<a  href="#" data-toggle="modal" data-target="#modal-import" class="btn btn-gradient-04 mr-1 mb-2">Создать импорт</a>
</div>

<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
            <div class="table-responsive">

                 <?php

                    $get = getAll("SELECT * FROM uni_ads_import order by ads_import_id desc");     

                     if(count($get) > 0){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Файл</th>
                            <th>Загружено</th>
                            <th>Ошибок</th>
                            <th>Статус</th>
                            <th>Дата импорта</th>
                            <th style="text-align: right;" ></th>
                           </tr>
                        </thead>
                        <tbody class="sort-container" >                     
                         <?php

                            foreach($get AS $array_data){
     
                            ?>

                             <tr>
                                 <td><?php echo $array_data["ads_import_file"]; ?></td>
                                 <td><?php echo $array_data["ads_import_count_loaded"]; ?> из <?php echo $array_data["ads_import_count"]; ?></td> 
                                 <td><a href="?route=ads_import_errors&uniq=<?php echo $array_data["ads_import_uniq"]; ?>" style="color: red;" ><i class="la la-exclamation-triangle"></i> <?php echo $array_data["ads_import_errors"]; ?></a></td> 
                                 <td>
                                   <?php
                                     if($array_data["ads_import_status"] == 0){
                                        ?>
                                        <h4><span class="badge badge-pill badge-secondary">Ожидает</span></h4>
                                        <?php
                                     }elseif($array_data["ads_import_status"] == 1){
                                        ?>
                                        <h4><span class="badge badge-pill badge-warning">В процессе</span></h4>
                                        <?php
                                     }elseif($array_data["ads_import_status"] == 2){
                                        if($array_data["ads_import_status_images"] == 1){
                                        ?>
                                          <h4><span class="badge badge-pill badge-success">Завершен</span></h4>
                                        <?php
                                        }else{
                                        ?>
                                          <h4><span class="badge badge-pill badge-warning">Скачивание изображений</span></h4>
                                        <?php
                                        }                                      
                                     }elseif($array_data["ads_import_status"] == 3){
                                        ?>
                                        <h4><span class="badge badge-pill badge-danger">В процессе удаления</span></h4>
                                        <?php                                      
                                     }
                                   ?>
                                 </td> 
                                 <td><?php echo datetime_format_cp($array_data["ads_import_date"]); ?></td>
                                 <td class="td-actions" style="text-align: right;" >
                                  <?php if($array_data["ads_import_status"] == 0){ ?>
                                  <a href="?route=ads_import_view&id=<?php echo $array_data["ads_import_id"]; ?>" ><i class="la la-eye edit"></i></a>
                                  <a href="#" class="delete-import" data-id="<?php echo $array_data["ads_import_id"]; ?>" ><i class="la la-close delete"></i></a>
                                  <?php } ?>
                                  <?php if($array_data["ads_import_status"] == 1 || $array_data["ads_import_status"] == 2){ ?>
                                  <a href="#" class="delete-import-ads" data-id="<?php echo $array_data["ads_import_id"]; ?>" ><i class="la la-close delete"></i></a>
                                  <?php } ?>
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
                               <p>Импортов нет</p>
                            </div>
                         <?php

                     }                  
                  ?>

            </div>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript" src="include/modules/ads_import/script.js"></script>     

<div id="modal-import" class="modal fade">
   <div class="modal-dialog" style="max-width: 600px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Создание импорта</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">

               <p><strong>Выберите файл в формате .csv</strong></p>              
            
               <form class="form-import" >

                  <input type="file" name="file" >
  
               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-danger form-import-continue">Продолжить</button>
         </div>
      </div>
   </div>
</div>



