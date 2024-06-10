<?php if( !defined('unisitecms') ) exit;

$variantsPrice = getAll('select * from uni_variants_price');
$measuresPrice = json_decode($settings['measures_price'], true) ?: [];

include("fn.php");
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Новая категория</h2>
         <div>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="?route=category_board">Категории</a></li>
            </ul>
         </div>
      </div>
   </div>
</div>  


<div class="row" >
   <div class="col-lg-12" >

      <div class="widget has-shadow">

         <div class="widget-body">

            <form class="form-data" >

              <div class="form-group row d-flex align-items-center">
                <label class="col-lg-3 form-control-label">Статус видимости</label>
                <div class="col-lg-6">
                    <label class="mb0">
                      <input class="toggle-checkbox-sm" type="checkbox" name="visible" checked="" value="1" >
                      <span><span></span></span>
                    </label>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Изображение</label>
                <div class="col-lg-7">

                      <div class="small-image-container" >
                        <span class="small-image-delete" style="display: none;" > <i class="la la-trash"></i> </span>

                        <img class="change-img" src="<?php echo $config["urlPath"] . "/" . $config["media"]["other"]; ?>/icon_photo_add.png" width="60px" >

                        <input type="hidden" name="image_delete" value="0" >
                      </div>

                      <input type="file" name="image" class="input-img" >
                </div>
              </div>

              <hr>

              <div class="form-group row d-flex align-items-center" style="margin-top: 25px;" >
                <label class="col-lg-3 form-control-label">Отображать на главной</label>
                <div class="col-lg-6">
                    <label class="mb0">
                      <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="show_index" value="1" >
                      <span><span></span></span>
                    </label>
                </div>
              </div>
              
              <div class="form-group row d-flex align-items-center">
                  <label class="col-lg-3 form-control-label">Возможности</label>
                  <div class="col-lg-2">

                        <?php if($settings["main_type_products"] == 'physical'){ ?>
                        <select class="selectpicker" name="rules[]" title="Не выбрано" multiple >
                            <option value="free_price" >Отдать даром</option>
                            <option value="accept_promo" >Применение акции</option>
                            <option value="measure_booking" >Выбор измерения только для бронирования и аренды</option>
                        </select>
                        <?php }else{ ?>
                        <select class="selectpicker" name="rules[]" title="Не выбрано" multiple >
                            <option value="accept_promo" >Применение акции</option>
                        </select>                            
                        <?php } ?>

                  </div>
              </div>

              <div class="form-group row d-flex align-items-center" >
                <label class="col-lg-3 form-control-label">Цена</label>
                <div class="col-lg-6">
                    <label  class="mb0">
                      <input class="toggle-checkbox-sm" type="checkbox" checked="" name="display_price" value="1" >
                      <span><span></span></span>
                    </label>
                </div>
              </div>

              <div class="category-block-variant-price" style="display: block;" >
                
                <div class="form-group row d-flex align-items-center">
                  <label class="col-lg-3 form-control-label">Название поля "Цена"</label>
                  <div class="col-lg-7">
                     <div class="box-label-flex" >
                         <div class="box1-label-flex" >
                             <select name="variant_price_id" class="selectpicker" >
                                <?php
                                    if(count($variantsPrice)){
                                        foreach ($variantsPrice as $value) {
                                            ?>
                                            <option value="<?php echo $value['variants_price_id']; ?>" ><?php echo $value['variants_price_name']; ?></option>
                                            <?php
                                        }
                                    }
                                ?>                        
                             </select>
                         </div>
                         <div class="box2-label-flex box2-label-flex-grow1" >
                            <a href="#" data-toggle="modal" data-target="#modal-list-variants-prefix-price" class="btn btn-gradient-02" ><i class="la la-pencil" ></i></a>
                         </div>
                     </div>
                     <div style="margin-top: 5px;" ><small>Отображается при подаче объявления и в каталоге</small></div>
                  </div>
                </div>
                
                <?php if($settings["main_type_products"] == 'physical'){ ?>

                <div class="form-group row d-flex align-items-center">
                  <label class="col-lg-3 form-control-label">Измерения</label>
                  <div class="col-lg-7">

                         <div class="box-label-flex" >
                             <div class="box1-label-flex" >
                                <select class="selectpicker" name="measures_price[]" title="Не выбрано" multiple >
                                    <?php
                                      if(count($measuresPrice)){
                                         foreach ($measuresPrice as $key => $value) { 
                                             ?>
                                             <option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
                                             <?php
                                         }
                                      }
                                    ?>
                                </select>
                             </div>
                             <div class="box2-label-flex box2-label-flex-grow1" >
                                <a href="#" data-toggle="modal" data-target="#modal-list-variants-measure-price" class="btn btn-gradient-02" ><i class="la la-pencil" ></i></a>
                             </div>
                         </div>

                     <div style="margin-top: 5px;" ><small>Доступны при подаче объявления</small></div>
                  </div>
                </div>

                <?php } ?>

                <?php if($settings["main_type_products"] == 'physical' && $settings["functionality"]["booking"]){ ?>
                    
                  <div class="form-group row d-flex align-items-center">
                    <label class="col-lg-3 form-control-label">Бронирование/Аренда</label>
                    <div class="col-lg-6">
                        <label class="mb0">
                          <input class="toggle-checkbox-sm" type="checkbox" name="booking" value="1" >
                          <span><span></span></span>
                        </label>
                    </div>
                  </div>

                  <div class="category-block-booking-options" >
                    
                      <div class="form-group row d-flex align-items-center">
                        <label class="col-lg-3 form-control-label">Выбор измерения</label>
                        <div class="col-lg-6">
                            <label class="mb0">
                              <input class="toggle-checkbox-sm" type="checkbox" name="measures_price_booking" value="1" >
                              <span><span></span></span>
                            </label>
                            <div style="margin-top: 5px;" ><small>Выбор измерения доступен только при выборе бронирования или аренды</small></div>
                        </div>
                      </div>

                      <div class="form-group row d-flex align-items-center">
                        <label class="col-lg-3 form-control-label">Вариант</label>
                        <div class="col-lg-2">

                            <select class="form-control" name="booking_variant" >
                                <option value="0" >Бронирование</option>
                                <option value="1" >Аренда</option>
                            </select>

                        </div>
                      </div>

                  </div>

                <?php } ?>

                <div class="category-block-conditional-function" >

                <?php if($settings["functionality"]["marketplace"]){ ?>
                  <div class="form-group row d-flex align-items-center" >
                    <label class="col-lg-3 form-control-label">Маркетплейс</label>
                    <div class="col-lg-6">
                        <label class="mb0">
                          <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="marketplace" value="1" >
                          <span><span></span></span>
                        </label>
                    </div>
                  </div>
                <?php } ?>

                <?php if($settings["main_type_products"] == 'physical' && $settings["functionality"]["auction"]){ ?>
                <div class="form-group row d-flex align-items-center">
                  <label class="col-lg-3 form-control-label">Аукционы</label>
                  <div class="col-lg-6">
                      <label class="mb0" >
                        <input class="toggle-checkbox-sm" type="checkbox" name="auction" value="1" >
                        <span><span></span></span>
                      </label>
                  </div>
                </div>
                <?php } ?>
                
                <div class="form-group row d-flex align-items-center">
                  <label class="col-lg-3 form-control-label">Безопасная сделка</label>
                  <div class="col-lg-6">
                      <label class="mb0" >
                        <input class="toggle-checkbox-sm" type="checkbox" name="secure" value="1" >
                        <span><span></span></span>
                      </label>
                  </div>
                </div>

                </div>

              </div>

              <?php if($settings["main_type_products"] == 'physical'){ ?>
              <div class="category-block-conditional-online-view" >

                <div class="form-group row d-flex align-items-center">
                  <label class="col-lg-3 form-control-label">Онлайн-показ</label>
                  <div class="col-lg-6">
                      <label class="mb0" >
                        <input class="toggle-checkbox-sm" type="checkbox" name="online_view" value="1" >
                        <span><span></span></span>
                      </label>
                  </div>
                </div>

              </div>
              <?php } ?>

              <div class="form-group row d-flex align-items-center">
                <label class="col-lg-3 form-control-label">Платная категория</label>
                <div class="col-lg-6">
                    <label class="mb0">
                      <input class="toggle-checkbox-sm" type="checkbox" name="paid" value="1" >
                      <span><span></span></span>
                    </label>
                </div>
              </div>

              <div class="category-block-options" >
                
              <div class="form-group row d-flex align-items-center">
                <label class="col-lg-3 form-control-label">Цена размещения</label>
                <div class="col-lg-2">
                    <div class="input-group mb-2">
                       <input type="number" class="form-control" name="price" >
                       <div class="input-group-prepend">
                          <div class="input-group-text"><?php echo $settings["currency_main"]["sign"]; ?></div>
                       </div>                       
                    </div>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center">
                <label class="col-lg-3 form-control-label">Бесплатных размещений</label>
                <div class="col-lg-2">
                     <input type="text" class="form-control" name="count_free" value="1" >
                </div>
              </div>

              </div>

              <hr>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Название</label>
                <div class="col-lg-7">

                     <div class="row" >
                         <div class="col-lg-6" >
                            <input type="text" class="form-control setTranslate" name="name" >
                         </div>
                         <div class="col-lg-6" >
                            <input type="text" class="form-control outTranslate" name="alias" placeholder="Алиас" > 
                         </div>
                     </div>

                </div>
              </div>
 
              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label"></label>
                <div class="col-lg-7">
                    <div class="alert alert-primary alert-dissmissible fade show" style="margin-top: 10px;" >
                        Макросы: <?php echo outStaticTextMakros(); ?>
                    </div>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Заголовок (Meta title)</label>
                <div class="col-lg-7">
                     <input type="text" class="form-control" name="title" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Заголовок (h1)</label>
                <div class="col-lg-7">
                     <input type="text" class="form-control" name="h1" >                    
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Категория</label>
                <div class="col-lg-7">
                    <select name="id_cat" class="selectpicker" data-live-search="true" > 
                      <option value="0" >Основная категория</option>
                      <?php echo outCategoryOptions(); ?>     
                    </select>                      
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Краткое описание (Meta Description)</label>
                <div class="col-lg-7">
                     <textarea class="form-control" name="desc" ></textarea>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Полное описание</label>
                <div class="col-lg-7">
                     <textarea name="text" class="ckeditor" ></textarea>                     
                </div>
              </div>              
              

              <div class="sticky-action" >

                <hr>

                <div class="sticky-action-box" >
                    <div class="sticky-action-button" >
                        <span type="button" class="btn btn-success add-category">Добавить</span>
                    </div>
                </div>
                  
              </div>

            </form>

         </div>

      </div>
      
   </div>
</div>

<div id="modal-list-variants-prefix-price" class="modal fade">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Список вариантов</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">

                <form class="list-variants-prefix-price-form" >
                <?php
                    if(count($variantsPrice)){
                        foreach ($variantsPrice as $value) {
                            ?>
                            <div class="list-variants-prefix-price-item" id="variant-price<?php echo $value['variants_price_id']; ?>" >

                                 <div class="box-label-flex box-label-flex-align-center" >
                                     <div class="box1-label-flex box2-label-flex-grow1" >
                                        <input type="text" name="list_variant_price[edit][<?php echo $value['variants_price_id']; ?>]" value="<?php echo $value['variants_price_name']; ?>" class="form-control" >
                                     </div>
                                     <div class="box2-label-flex" >
                                        <span data-id="<?php echo $value['variants_price_id']; ?>" class="list-variants-prefix-price-delete" style="cursor: pointer;" ><i class="la la-trash" style="font-size: 16px;" ></i></span>
                                     </div>
                                 </div>

                            </div>
                            <?php
                        }
                    }
                ?>
                </form>

                <div style="margin-top: 15px;" ><button type="button" class="btn btn-sm btn-success list-variants-prefix-price-add" >Добавить</button></div>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary list-variants-prefix-price-save">Сохранить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-list-variants-measure-price" class="modal fade">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Список измерений</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">

                <form class="list-variants-measure-price-form" >
                <?php
                    if(count($measuresPrice)){
                        foreach ($measuresPrice as $key => $value) {
                            if($key === 'hour' || $key === 'day' || $key === 'daynight' || $key === 'week' || $key === 'month'){
                            ?>
                                <div class="list-variants-measure-price-item" >

                                     <div class="box-label-flex box-label-flex-align-center" >
                                         <div class="box1-label-flex box2-label-flex-grow1" >
                                            <?php echo $value; ?>
                                         </div>
                                     </div>

                                </div>
                            <?php
                            }else{
                            ?>
                                <div class="list-variants-measure-price-item" id="measure-price<?php echo $key; ?>" >

                                     <div class="box-label-flex box-label-flex-align-center" >
                                         <div class="box1-label-flex box2-label-flex-grow1" >
                                            <input type="text" name="list_measure_price[]" value="<?php echo $value; ?>" class="form-control" >
                                         </div>
                                         <div class="box2-label-flex" >
                                            <span data-id="<?php echo $key; ?>" class="list-variants-measure-price-delete" style="cursor: pointer;" ><i class="la la-trash" style="font-size: 16px;" ></i></span>
                                         </div>
                                     </div>

                                </div>
                            <?php                                
                            }
                        }
                    }
                ?>
                </form>

                <div style="margin-top: 15px;" ><button type="button" class="btn btn-sm btn-success list-variants-measure-price-add" >Добавить</button></div>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary list-variants-measure-price-save">Сохранить</button>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript" src="include/modules/category_board/script.js"></script>