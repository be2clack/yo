<?php 
if( !defined('unisitecms') ) exit;
?>


<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Промо страницы</h2>
      </div>
   </div>
</div>  

<div class="form-group">
<a  href="#modal-page-add" data-toggle="modal" class="btn btn-gradient-04 mr-1 mb-2">Добавить</a>
</div>

<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
            <div class="table-responsive">

                 <?php

                    $getPages = getAll("SELECT * FROM uni_promo_pages");     

                     if(count($getPages) > 0){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Заголовок</th>
                            <th>Просмотров</th>
                            <th>Статус</th>
                            <th style="text-align: right;" ></th>
                           </tr>
                        </thead>
                        <tbody>                     
                     <?php

                        foreach($getPages AS $array_data){
 
                        ?>

                         <tr>
                             <td><a href="<?php echo $config["urlPath"] . "/promo/" . $array_data["promo_pages_alias"]; ?>" target="_blank" ><?php echo $array_data["promo_pages_title"]; ?></a></td>
                             <td><?php echo $array_data["promo_pages_count_view"]; ?></td>
                             <td>

                               <label style="margin-top: 10px;" >
                                 <input class="toggle-checkbox-sm toolbat-toggle toggle-status" <?php if($array_data["promo_pages_visible"]){ echo 'checked=""'; } ?> type="checkbox" value="1" data-id="<?php echo $array_data["promo_pages_id"]; ?>" >
                                 <span> <span></span> </span>
                               </label>

                             </td> 
                             <td class="td-actions" style="text-align: right;" >
                              <a href="<?php echo $config["urlPath"] . "/promo/" . $array_data["promo_pages_alias"]; ?>" target="_blank" ><i class="la la-edit edit"></i></a>
                              <a class="load-edit-page" data-id="<?php echo $array_data["promo_pages_id"]; ?>" ><i class="la la-cog edit"></i></a>
                              <a href="#" class="delete-page" data-id="<?php echo $array_data["promo_pages_id"]; ?>" ><i class="la la-close delete"></i></a>
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
                               <p>Промо страниц нет</p>
                            </div>
                         <?php

                     }                  
                  ?>

            </div>
         </div>
      </div>
   </div>
</div>

<div id="modal-page-add" class="modal fade">
   <div class="modal-dialog" style="max-width: 650px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Добавить промо страницу</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-data-page-add" >

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Заголовок страницы</label>
                  <div class="col-lg-9">
                       <input type="text" class="form-control" name="title" >
                  </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Алиас</label>
                  <div class="col-lg-9">
                       <input type="text" class="form-control" name="alias" >
                  </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Meta Description</label>
                  <div class="col-lg-9">
                       <textarea class="form-control" name="desc" ></textarea>
                  </div>
                </div>

               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary action-page-add">Добавить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-page-edit" class="modal fade">
   <div class="modal-dialog" style="max-width: 650px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Редактирование</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-data-page-edit" ></form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary action-page-edit">Сохранить</button>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript" src="include/modules/promo_pages/script.js"></script>     
