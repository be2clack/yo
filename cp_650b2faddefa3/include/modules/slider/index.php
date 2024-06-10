<?php 
if( !defined('unisitecms') ) exit;
?>


<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Медиа слайдер</h2>
      </div>
   </div>
</div>  

<div class="form-group">
<div class="btn-group mb5" >
<a href="#modal-slide-add" data-toggle="modal" class="btn btn-gradient-04 mr-1 mb-2">Добавить слайдер</a>
</div>
</div>

<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
            <div class="table-responsive">

                 <?php

                    $get = getAll("SELECT * FROM uni_sliders order by sliders_sort asc");     

                     if(count($get) > 0){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th></th>
                            <th>Изображение</th>
                            <th>Заголовок</th>
                            <th>Переходов</th>
                            <th>Статус</th>
                            <th style="text-align: right;" ></th>
                           </tr>
                        </thead>
                        <tbody class="sort-container" >                     
                     <?php

                        foreach($get AS $array_data){
 
                        ?>

                         <tr id="item<?php echo $array_data["sliders_id"]; ?>" >
                             <td><span class="icon-move move-sort" ><i class="la la-arrows-v"></i></span></td>
                             <td>
                               <img src="<?php echo Exists($config["media"]["other"],$array_data["sliders_image"],$config["media"]["no_image"]); ?>" width="50px" >
                             </td>
                             <td>
                               <strong style="color: black;" ><?php echo $array_data["sliders_title1"]; ?></strong>
                               <div><?php echo $array_data["sliders_title2"]; ?></div>
                             </td>
                             <td>
                               <?php echo $array_data["sliders_click"]; ?>
                             </td>                             
                             <td>

                               <label style="margin-top: 10px;" >
                                 <input class="toggle-checkbox-sm toolbat-toggle toggle-status" <?php if($array_data["sliders_visible"]){ echo 'checked=""'; } ?> type="checkbox" value="1" data-id="<?php echo $array_data["sliders_id"]; ?>" >
                                 <span> <span></span> </span>
                               </label>

                             </td>                             
                             <td class="td-actions" style="text-align: right;" >
                              <a class="load-edit-slide" data-id="<?php echo $array_data["sliders_id"]; ?>" ><i class="la la-pencil edit"></i></a>
                              <a href="#" class="delete-slide" data-id="<?php echo $array_data["sliders_id"]; ?>" ><i class="la la-close delete"></i></a>
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
                               <p>Слайдов нет</p>
                            </div>
                         <?php

                     }                  
                  ?>

            </div>
         </div>
      </div>
   </div>
</div>

<div id="modal-slide-add" class="modal fade">
   <div class="modal-dialog" style="max-width: 650px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Добавить слайд</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-data-slide-add" >

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Изображение</label>
                  <div class="col-lg-9">
                       <input type="file" name="image">
                  </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Цвет фона</label>
                  <div class="col-lg-9">
                       <input type="text" name="color" class="form-control minicolors" data-swatches="#ef9a9a|#90caf9|#a5d6a7|#fff59d|#ffcc80|#bcaaa4|#eeeeee|#f44336|#2196f3|#4caf50|#ffeb3b|#ff9800|#795548|#9e9e9e" value="#abcdef">
                  </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Цвет текста</label>
                  <div class="col-lg-9">
                       <input type="text" name="color_text" class="form-control minicolors" data-swatches="#ef9a9a|#90caf9|#a5d6a7|#fff59d|#ffcc80|#bcaaa4|#eeeeee|#f44336|#2196f3|#4caf50|#ffeb3b|#ff9800|#795548|#9e9e9e" value="#abcdef">
                  </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Основной заголовок</label>
                  <div class="col-lg-9">
                       <input type="text" class="form-control" value="" name="title1" >
                  </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Подзаголовок</label>
                  <div class="col-lg-9">
                       <input type="text" class="form-control" value="" name="title2" >
                  </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Ссылка</label>
                <div class="col-lg-9">
                <div class="input-group">
                  <input type="text" class="form-control" value="" name="link" >
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Промо страница</button>
                    <div class="dropdown-menu">
                      
                      <?php
                      $getPages = getAll("select * from uni_promo_pages where promo_pages_visible=?",[1]);
                      if( count($getPages) ){
                          foreach ($getPages as $key => $value) {
                             ?>
                             <a class="dropdown-item dropdown-item-page" href="#" data-alias="promo/<?php echo $value["promo_pages_alias"]; ?>" ><?php echo $value["promo_pages_title"]; ?></a>
                             <?php
                          }
                          ?>
                          <div role="separator" class="dropdown-divider"></div>
                          <?php
                      }
                      ?>
                      
                      <a class="dropdown-item" target="_blank" href="?route=promo_pages">Добавить страницу</a>
                    </div>
                  </div>
                </div>
                </div>
                </div>

               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary action-slide-add">Добавить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-slide-settings" class="modal fade">
   <div class="modal-dialog" style="max-width: 650px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Настройки слайдера</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
             <form method="post" class="form-data-slide-settings" >

              <div class="form-group row d-flex mb-5">
                <label class="col-lg-4 form-control-label">Автопрокрутка</label>
                <div class="col-lg-6">
                    <label>
                      <input class="toggle-checkbox-sm" type="checkbox" name="media_slider_autoplay" <?php if( $settings["media_slider_autoplay"] ){ echo 'checked=""'; } ?> value="1" >
                      <span><span></span></span>
                    </label>
                </div>
              </div>

              <div class="form-group row d-flex mb-5">
                <label class="col-lg-4 form-control-label">Стрелки навигации</label>
                <div class="col-lg-6">
                    <label>
                      <input class="toggle-checkbox-sm" type="checkbox" name="media_slider_arrows" <?php if( $settings["media_slider_arrows"] ){ echo 'checked=""'; } ?> value="1" >
                      <span><span></span></span>
                    </label>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-4 form-control-label">Отображать слайдов</label>
                <div class="col-lg-3">
                     <input type="number" class="form-control" value="<?php echo $settings["media_slider_count_show"]; ?>" name="media_slider_count_show" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-4 form-control-label">Высота слайдера, px</label>
                <div class="col-lg-3">
                     <input type="number" class="form-control" value="<?php echo $settings["media_slider_height"]; ?>" name="media_slider_height" >
                </div>
              </div>

             </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary action-slide-settings-edit">Сохранить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-slide-edit" class="modal fade">
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
            
               <form method="post" class="form-data-slide-edit" ></form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary action-slide-edit">Сохранить</button>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript" src="include/modules/slider/script.js"></script>     
