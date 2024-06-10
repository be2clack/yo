<?php 
if( !defined('unisitecms') ) exit;
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Обновления</h2>
      </div>
   </div>
</div>  

<div class="row" >

   <div class="col-lg-6" >
      <div class="widget has-shadow">

         <div class="widget-body widget-body-bg-update min-height-190">
            
            <div class="updates-box-widget" ></div>

         </div>
         
      </div>
   </div>

   <div class="col-lg-6" >
      <div class="widget has-shadow">

         <div class="widget-body widget-body-bg-patch-fix min-height-190">
            
            <div class="updates-box-widget-patch-fix" ></div>

         </div>
         
      </div>
   </div>

   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
            <div class="table-responsive">

                 <?php

                    $get = getAll("SELECT * FROM uni_updates order by id desc");     

                     if(count($get)){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Версия</th>
                            <th>Лог</th>
                            <th>Статус</th>
                           </tr>
                        </thead>
                        <tbody>                     
                     <?php

                        foreach($get AS $array_data){
 
                        ?>

                         <tr>
                             <td>
                                <?php
                                  if($array_data["patch"]){
                                    ?>
                                    <strong>Патч <?php echo $array_data["version"]; ?></strong>
                                    <?php
                                  }else{
                                    ?>
                                    <strong>Обновление <?php echo $array_data["version"]; ?></strong>
                                    <?php                                    
                                  }
                                ?>
                             </td>
                             <td> <span class="updates-action-open-log" data-id="<?php echo $array_data["id"]; ?>" data-toggle="modal" data-target="#modal-updates-log" >Открыть</span> </td>
                             <td>

                               <?php
                                 if($array_data["status"] == 0){
                                    ?>
                                    <span class="badge badge-warning">Ждет установки</span>
                                    <?php
                                 }elseif($array_data["status"] == 1){
                                    ?>
                                    <span class="badge badge-success">Установлено</span>
                                    <?php                                    
                                 }elseif($array_data["status"] == 2){
                                    ?>
                                    <span class="badge badge-danger">Ошибка</span>
                                    <?php                                    
                                 }
                               ?>

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
                               <p>Обновлений нет</p>
                            </div>
                         <?php

                     }                  
                  ?>

            </div>
         </div>

      </div>
   </div>
</div>

<div id="modal-updates-log" class="modal fade">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Лог</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
            <div class="modal-updates-log-container" ></div>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-updates-variant-install" class="modal fade">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Вариант установки</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body text-center">
            
            <div class="alert-light-custom-light alert-dissmissible fade show" style="margin-bottom: 10px;">Обновления установятся автоматически, данный вариант подходит если вы не делали никаких изменений в коде системы.</div>            
            <div class="mb10" ><div class="btn btn-gradient-01 width100 updates-init-free-install modal-updates-variant-install-actions" data-version="" >Установить автоматически</div></div>

            <div class="alert-light-custom-light alert-dissmissible fade show" style="margin-bottom: 10px;">Обновление и добавление функционала производится в ручном режиме, данный вариант подходит если вы делали изменения или доработки в коде системы.</div>
            <div><div class="btn btn-gradient-02 width100 updates-load-manually-install modal-updates-variant-install-actions" data-toggle="modal" data-target="#modal-updates-manually" data-version="" >Установить вручную</div></div>

         </div>
      </div>
   </div>
</div>

<div id="modal-updates-manually" class="modal fade">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Мануал</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
            <div class="modal-updates-manually-container" ></div>

         </div>
      </div>
   </div>
</div>

<script type="text/javascript" src="include/modules/settings/script.js"></script>     
