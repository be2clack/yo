<?php if( !defined('unisitecms') ) exit;

include("fn.php");

$array_data = findOne("uni_category_board","category_board_id=?", array($id));
if(count($array_data) == 0){
   exit;
}

$cat_ids[] = $array_data["category_board_id"];

$variantsPrice = getAll('select * from uni_variants_price');
$measuresPrice = json_decode($settings['measures_price'], true) ?: [];
$rules = json_decode($array_data['category_board_rules'], true) ?: [];
$array_data->category_board_measures_price = json_decode($array_data->category_board_measures_price, true) ?: [];

$Filters = new Filters();
?>


<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title"><?php echo $array_data->category_board_name;?></h2>
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
                      <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="visible" <?php if($array_data->category_board_visible){ echo 'checked=""'; } ?> value="1" >
                      <span><span></span></span>
                    </label>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Изображение</label>
                <div class="col-lg-7">

                      <div class="small-image-container" >
                        <span class="small-image-delete" <?php if(!$array_data->category_board_image){ echo 'style="display: none;"'; } ?> > <i class="la la-trash"></i> </span>

                        <?php echo img( array( "img1" => array( "class" => "change-img", "path" => $config["media"]["other"] . "/" . $array_data->category_board_image, "width" => "60px" ), "img2" => array( "class" => "change-img", "path" => $config["media"]["other"] . "/icon_photo_add.png", "width" => "60px" ) ) ); ?>

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
                      <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="show_index" <?php if($array_data->category_board_show_index){ echo 'checked=""'; } ?> value="1" >
                      <span><span></span></span>
                    </label>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center">
                  <label class="col-lg-3 form-control-label">Возможности</label>
                  <div class="col-lg-2">

                    <?php if($settings["main_type_products"] == 'physical'){ ?>
                        <select class="selectpicker" name="rules[]" title="Не выбрано" multiple >
                            <option value="free_price" <?php if(in_array('free_price', $rules)){ echo 'selected=""'; } ?> >Отдать даром</option>
                            <option value="accept_promo" <?php if(in_array('accept_promo', $rules)){ echo 'selected=""'; } ?> >Применение акции</option>
                            <option value="measure_booking" <?php if(in_array('measure_booking', $rules)){ echo 'selected=""'; } ?> >Выбор измерения только для бронирования и аренды</option>
                        </select>
                    <?php }else{ ?>
                        <select class="selectpicker" name="rules[]" title="Не выбрано" multiple >
                            <option value="accept_promo" <?php if(in_array('accept_promo', $rules)){ echo 'selected=""'; } ?> >Применение акции</option>
                        </select>                        
                    <?php } ?>

                  </div>
              </div>

              <div class="form-group row d-flex align-items-center" >
                <label class="col-lg-3 form-control-label">Цена</label>
                <div class="col-lg-6">
                    <label class="mb0">
                      <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="display_price" <?php if($array_data->category_board_display_price){ echo 'checked=""'; } ?> value="1" >
                      <span><span></span></span>
                    </label>
                </div>
              </div>

              <div class="category-block-variant-price" <?php if($array_data->category_board_display_price){ echo 'style="display: block;"'; } ?> >
                
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
                                            <option value="<?php echo $value['variants_price_id']; ?>" <?php if($array_data->category_board_variant_price_id == $value['variants_price_id']) echo 'selected=""'; ?> ><?php echo $value['variants_price_name']; ?></option>
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
                                         <option value="<?php echo $key; ?>" <?php if(in_array($key, $array_data->category_board_measures_price)){ echo 'selected=""'; } ?> ><?php echo $value; ?></option>
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
                          <input class="toggle-checkbox-sm" type="checkbox" name="booking" <?php if($array_data->category_board_booking){ echo 'checked=""'; } ?> value="1" >
                          <span><span></span></span>
                        </label>
                    </div>
                  </div>

                  <div class="category-block-booking-options" <?php if($array_data->category_board_booking){ echo 'style="display: block"'; } ?> >
                    
                      <div class="form-group row d-flex align-items-center">
                        <label class="col-lg-3 form-control-label">Вариант</label>
                        <div class="col-lg-2">

                            <select class="selectpicker" name="booking_variant" >
                                <option value="0" <?php if($array_data->category_board_booking_variant == 0){ echo 'selected=""'; } ?> >Бронирование</option>
                                <option value="1" <?php if($array_data->category_board_booking_variant == 1){ echo 'selected=""'; } ?> >Аренда</option>
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
                          <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="marketplace" <?php if($array_data->category_board_marketplace){ echo 'checked=""'; } ?> value="1" >
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
                        <input class="toggle-checkbox-sm" type="checkbox"  <?php if($array_data->category_board_auction){ echo 'checked=""'; } ?> name="auction" value="1" >
                        <span><span></span></span>
                      </label>
                  </div>
                </div>
                <?php } ?>
                
                <div class="form-group row d-flex align-items-center">
                  <label class="col-lg-3 form-control-label">Безопасная сделка</label>
                  <div class="col-lg-6">
                      <label class="mb0" >
                        <input class="toggle-checkbox-sm" type="checkbox" name="secure" <?php if($array_data->category_board_secure){ echo 'checked=""'; } ?> value="1" >
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
                        <input class="toggle-checkbox-sm" type="checkbox" name="online_view" <?php if($array_data->category_board_online_view){ echo 'checked=""'; } ?> value="1" >
                        <span><span></span></span>
                      </label>
                  </div>
                </div>

              </div>
              <?php } ?>

              <div class="form-group row d-flex align-items-center">
                <label class="col-lg-3 form-control-label">Платная категория</label>
                <div class="col-lg-6">
                    <label class="mb0" >
                      <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="paid" <?php if($array_data->category_board_status_paid){ echo 'checked=""'; } ?> value="1" >
                      <span><span></span></span>
                    </label>
                </div>
              </div>

              <div class="category-block-options" <?php if($array_data->category_board_status_paid){ echo 'style="display: block"'; } ?> >
                
              <div class="form-group row d-flex align-items-center">
                <label class="col-lg-3 form-control-label">Цена размещения</label>
                <div class="col-lg-2">
                    <div class="input-group mb-2">
                       <input type="number" class="form-control" name="price" value="<?php echo $array_data->category_board_price; ?>" >
                       <div class="input-group-prepend">
                          <div class="input-group-text"><?php echo $settings["currency_main"]["sign"]; ?></div>
                       </div>                       
                    </div>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center">
                <label class="col-lg-3 form-control-label">Бесплатных размещений</label>
                <div class="col-lg-2">
                     <input type="text" class="form-control" name="count_free" value="<?php echo $array_data->category_board_count_free; ?>" >
                </div>
              </div>

              </div>
              
              <?php if($settings["main_type_products"] == 'physical' &&  !findOne("uni_category_board", "category_board_id_parent=?", [$id])){ ?>
              <div class="form-group row d-flex align-items-center">
                <label class="col-lg-3 form-control-label">Автогенерация заголовка объявления</label>
                <div class="col-lg-6">
                    <label class="mb0">
                      <input class="toggle-checkbox-sm" type="checkbox" name="auto_title" <?php if($array_data->category_board_auto_title){ echo 'checked=""'; } ?> value="1" >
                      <span><span></span></span>
                    </label>
                </div>
              </div>

              <div class="category-block-options-auto-title" <?php if($array_data->category_board_auto_title){ echo 'style="display: block"'; } ?> >
                
                  <div class="form-group row d-flex align-items-center">
                    <label class="col-lg-3 form-control-label">Шаблон заголовка</label>
                    <div class="col-lg-7">

                         <div class="box-label-flex" >
                             <div class="box1-label-flex" >
                                <input type="text" class="form-control" name="auto_title_template" value="<?php echo $array_data->category_board_auto_title_template; ?>" >
                             </div>
                             <div class="box2-label-flex box2-label-flex-grow1" >
                                <a href="#" data-toggle="modal" data-target="#modal-list-filters" class="btn btn-gradient-04" ><i class="la la-filter filter" ></i></a>
                             </div>
                         </div>

                    </div>
                  </div>

              </div>

              <?php } ?>

              <hr>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Название</label>
                <div class="col-lg-7">

                     <div class="row" >
                         <div class="col-lg-6" >
                            <input type="text" class="form-control" name="name" value="<?php echo $array_data->category_board_name; ?>" >
                         </div>
                         <div class="col-lg-6" >
                            <input type="text" class="form-control" name="alias" value="<?php echo $array_data->category_board_alias; ?>" > 
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
                     <input type="text" class="form-control" name="title" value="<?php echo $array_data->category_board_title; ?>" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Заголовок (h1)</label>
                <div class="col-lg-7">
                     <input type="text" class="form-control" name="h1" value="<?php echo $array_data->category_board_h1; ?>" >
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
                     <textarea class="form-control" name="desc" ><?php echo $array_data->category_board_description; ?></textarea>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Полное описание</label>
                <div class="col-lg-7">
                     <textarea name="text" class="ckeditor" ><?php echo urldecode($array_data->category_board_text); ?></textarea>
                </div>
              </div>              
              
              <input type="hidden" name="id" value="<?php echo $id;?>" />

              <div class="sticky-action" >

                    <hr>

                    <div class="sticky-action-box" >
                        <?php if( findOne("uni_category_board", "category_board_id_parent=?", [$id]) ){ ?>

                        <div class="sticky-action-button text-right" >
                            <label>
                              <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="subcategories" value="1" >
                              <span><span></span></span>
                            </label>
                            <div><small>Применить настройки к подкатегориям</small></div>
                        </div>

                        <?php } ?>

                        <div class="sticky-action-button" >
                            <span class="btn btn-success edit-category">Сохранить</span>
                        </div>
                    </div>
                      
               </div>

            </form>

         </div>

      </div>

   </div>
</div>

<div id="modal-list-filters" class="modal fade">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Фильтры</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
             <?php

                $getCategoryFilters = $Filters->getCategory( [ "id_cat" => $array_data["category_board_id"] ] );
                
                if($getCategoryFilters){

                    $getFilters = getAll( "select * from uni_ads_filters where ads_filters_id IN(".implode(",", $getCategoryFilters).")" );

                    if( count($getFilters) ){
                        foreach ($getFilters as $key => $value) {

                            ?>
                            <div>
                              <span style="color: #98a8b4;" ><?php echo $value["ads_filters_name"]; ?></span> <span class="filter-copy" style="cursor: pointer;" >{<?php echo $value["ads_filters_alias"]; ?>}</span>
                            </div>
                            <?php

                        }
                    }

                }else{
                  ?>
                  <div> Фильтров нет </div>
                  <?php
                }
                
             ?>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
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
                    if($variantsPrice){
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
                    if($measuresPrice){
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