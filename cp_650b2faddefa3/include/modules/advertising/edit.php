<?php if( !defined('unisitecms') ) exit;

include("fn.php");
$Banners = new Banners();

$pages = $settings["advertising_pages"] ? explode(",", $settings["advertising_pages"] ) : [];
$pos_array = $Banners->positions();

$array_data = findOne("uni_advertising","advertising_id=?", array($id));
 if(count($array_data) == 0){
     exit;
 }

 if($array_data->advertising_ids_cat) $array_data->advertising_ids_cat = explode(",", $array_data->advertising_ids_cat); else $array_data->advertising_ids_cat = [];
 if($array_data->advertising_ids_city) $array_data->advertising_ids_city = explode(",", $array_data->advertising_ids_city); else $array_data->advertising_ids_city = [];

 $pos_category_display_all = ["result", "catalog_sidebar", "catalog_top", "catalog_bottom", "ad_view_top", "ad_view_sidebar", "ad_view_bottom", "blog_sidebar", "blog_top", "blog_bottom", "article_view_sidebar", "article_view_top", "article_view_bottom"];
 $pos_category_display_board = ["result", "catalog_sidebar", "catalog_top", "catalog_bottom", "ad_view_top", "ad_view_sidebar", "ad_view_bottom"];
 $pos_category_display_blog = ["blog_sidebar", "blog_top", "blog_bottom", "article_view_sidebar", "article_view_top", "article_view_bottom"];
 $pages_name = ["ad_create"=>"Добавление объявления", "ad_view"=>"Карточка объявления", "blog"=>"Блог", "blog_view"=>"Карточка статьи", "catalog"=>"Каталог объявлений", "cities"=>"Список городов", "index"=>"Главная страница", "page"=>"Сервисные страницы"];

?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title"><?php echo $array_data->advertising_title; ?></h2>
         <div>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="?route=advertising">Баннеры/Реклама</a></li>
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

              <br>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Статус видимости</label>
                <div class="col-lg-6">
                    <label>
                      <input class="toggle-checkbox-sm" type="checkbox" name="visible" <?php if($array_data->advertising_visible){echo 'checked=""';}?> value="1" >
                      <span><span></span></span>
                    </label>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Показывать баннер</label>
                <div class="col-lg-6">
                    <select class="selectpicker" name="var_out" >
                        <option value="0" <?php if($array_data->advertising_var_out == 0){ echo 'selected=""'; } ?> >Всем пользователям</option>
                        <option value="1" <?php if($array_data->advertising_var_out == 1){ echo 'selected=""'; } ?> >Только зарегистрированным</option>
                        <option value="2" <?php if($array_data->advertising_var_out == 2){ echo 'selected=""'; } ?> >Только не зарегистрированным</option>
                    </select>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Название</label>
                <div class="col-lg-5">
                     <input type="text" class="form-control" value="<?php echo $array_data->advertising_title; ?>" name="title" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Позиция баннера</label>
                <div class="col-lg-7">
                    <select class="selectpicker select_advertising_position" name="banner_position" >
                       <option value="" >Позиция не выбрана</option>                
                          <?php                    
                              echo $Banners->bannersPositions($array_data->advertising_banner_position);                    
                          ?>                
                    </select>
                </div>
              </div>
              
              <div class="box-pages" <?php if($array_data->advertising_banner_position == "stretching"){ echo 'style="display: block;"'; } ?> >
              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Страницы показа</label>
                <div class="col-lg-7">
                    <select class="selectpicker" name="pages[]" multiple="" title="Не выбрано" >
                       <?php

                          foreach ($pages as $key => $value) {
                             ?>
                             <option <?php if($array_data->advertising_pages){ if( in_array($value, explode(",", $array_data->advertising_pages) ) ){ echo 'selected=""'; } } ?> value="<?php echo $value; ?>" ><?php echo $pages_name[$value]; ?></option>
                             <?php
                          }

                       ?>               
                    </select>
                </div>
              </div>
              </div>

              <div class="index_out_advertising_position" <?php if($array_data->advertising_banner_position == "result"){ echo 'style="display: block;"'; } ?> >
              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Укажите позицию вывода</label>
                <div class="col-lg-3">

                    <input type="text" class="form-control" value="<?php echo $array_data->advertising_index_out; ?>" name="index_out" >
                    <small>Укажите позицию вывода баннера в результате выдачи объявлений. К примеру если вы укажите 6, то баннер появится после 6-го объявления в поиске.</small>

                </div>
              </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Дата начала показа</label>
                <div class="col-lg-3">
                   
                    <div class="input-group">
                    <input type="text"class="form-control datetime" value="<?php echo date("Y-m-d H:i",strtotime($array_data->advertising_date_start)); ?>" name="date_start" >
                    <span class="input-group-addon">
                    <i class="la la-calendar"></i>
                    </span>                    
                    </div>                  
                     
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Дата окончания показа</label>
                <div class="col-lg-3">
                   
                    <div class="input-group">
                    <input type="text"class="form-control datetime" name="date_end" value="<?php if($array_data->advertising_date_end != "0000-00-00 00:00:00"){ echo date("Y-m-d H:i",strtotime($array_data->advertising_date_end)); } ?>" >
                    <span class="input-group-addon">
                    <i class="la la-calendar"></i>
                    </span>                    
                    </div>  

                    <small>Если баннер без ограничений, то оставьте это поле пустым</small>               
                     
                </div>
              </div>             
              
              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Выводить</label>
                <div class="col-lg-3">
                     

                    <div class="styled-radio">
                    <input type="radio" name="type_banner" value="1" <?php if($array_data->advertising_type_banner == 1){ echo 'checked=""'; } ?> id="rad-1">
                    <label for="rad-1">Баннер</label>
                    </div>
                    <div class="styled-radio">
                    <input type="radio" name="type_banner" value="2" <?php if($array_data->advertising_type_banner == 2){ echo 'checked=""'; } ?> id="rad-2">
                    <label for="rad-2">Код рекламы</label>
                    </div>

                </div>
              </div>
              
              <div class="box-rad-1" <?php if($array_data->advertising_type_banner == 1){ echo 'style="display: block;"'; } ?> >

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Ссылка перенаправления</label>
                <div class="col-lg-5">
                     
                      <input type="text"class="form-control" value="<?php echo urldecode($array_data->advertising_link_site); ?>" name="link" >

                </div>
              </div>
              
              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Изображение</label>
                <div class="col-lg-3">
                    
                    <?php echo img( array( "img1" => array( "class" => "change-img-bnr", "path" => $config["media"]["banners"] . "/" . $array_data->advertising_image, "width" => "150px" ), "img2" => array( "class" => "change-img-bnr", "path" => $config["media"]["other"] . "/icon_photo_add.png", "width" => "60px" ) ) ); ?>

                    <input type="file" name="image" class="input-img-bnr" >

                </div>
              </div>

              </div>

              <div class="box-rad-2" <?php if($array_data->advertising_type_banner == 2){ echo 'style="display: block;"'; } ?> >

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Код скрипта/баннера</label>
                <div class="col-lg-7">
                     
                      <textarea class="form-control" name="code"  ><?php echo urldecode($array_data->advertising_code_script); ?></textarea>
                      <small>Внимание! При использовании скрипта не фиксируются переходы по баннеру.</small>

                </div>
              </div>
              
              </div>
              
              <div class="box-categories" <?php if( in_array($array_data->advertising_banner_position,  $pos_category_display_all) ){ echo 'style="display: block;"'; } ?> >

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Категории показа</label>
                <div class="col-lg-3">

                    <div class="styled-radio">
                    <input type="radio" name="type_cat" value="1" <?php if(!$array_data->advertising_ids_cat){ echo 'checked=""'; } ?>  id="cat-rad-1">
                    <label for="cat-rad-1">Во всех категориях</label>
                    </div>
                    <div class="styled-radio">
                    <input type="radio" name="type_cat" value="2" <?php if($array_data->advertising_ids_cat){ echo 'checked=""'; } ?> id="cat-rad-2">
                    <label for="cat-rad-2">В определенных</label>
                    </div>

                </div>
              </div>

              
              <div class="box-cat-rad-1" <?php if($array_data->advertising_ids_cat){ echo 'style="display: block;"'; } ?>  >
              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label"></label>
                <div class="col-lg-3">

                    <div class="styled-checkbox">
                    <input type="checkbox" name="out_podcat" id="check-1" value="1" <?php if($array_data->advertising_out_podcat){ echo 'checked=""'; } ?> >
                    <label for="check-1">Учитывать подкатегории</label>
                    </div>
                    
                    <div class="bnr_ids_cat_board" <?php if( in_array($array_data->advertising_banner_position,  $pos_category_display_board) ){ echo 'style="display: block;"'; } ?> >
                    <select class="selectpicker" name="ids_cat_board[]" data-live-search="true" multiple="" title="Не выбрано" >
                      <?php 
                      echo outCategoryOptionsBoard(); 
                      ?>
                    </select>
                    </div>

                    <div class="bnr_ids_cat_blog" <?php if( in_array($array_data->advertising_banner_position,  $pos_category_display_blog) ){ echo 'style="display: block;"'; } ?> >
                    <select class="selectpicker" name="ids_cat_blog[]" data-live-search="true" multiple="" title="Не выбрано" >
                      <?php 
                      echo outCategoryOptionsBlog(); 
                      ?>
                    </select>
                    </div>

                </div>
              </div>
              </div>

              </div>

              <div class="box-cities" >

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Города показа</label>
                <div class="col-lg-3">

                    <div class="styled-radio">
                    <input type="radio" name="type_city" value="1"  <?php if(!$array_data->advertising_geo){ echo 'checked=""'; } ?> id="city-rad-1">
                    <label for="city-rad-1">Во всех городах</label>
                    </div>
                    <div class="styled-radio">
                    <input type="radio" name="type_city" value="2"  <?php if($array_data->advertising_geo){ echo 'checked=""'; } ?> id="city-rad-2">
                    <label for="city-rad-2">В определенных</label>
                    </div>

                </div>
              </div>
              
              <div class="box-city-rad-1" <?php if($array_data->advertising_geo){ echo 'style="display: block;"'; } ?> >
              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label"></label>
                <div class="col-lg-5">

                    <div class="container-custom-search" >
                      <input type="text" autocomplete="nope" class="form-control action-input-search-city" placeholder="Укажите город, регион или страну" >
                      <div class="custom-results SearchCityResults custom-results-position-top" ></div>
                    </div>   

                    <div class="container-geo" <?php if($array_data->advertising_geo){ echo 'style="display: block;"'; } ?> >
                      <?php
                      if($array_data->advertising_geo){
                         $geo = json_decode($array_data->advertising_geo, true);
                         foreach ($geo as $geo_name => $nested) {
                           foreach ($nested as $key => $value) {
                              if($geo_name == "city"){
                              $get = findOne("uni_city", "city_id=?", [ intval($value) ]);
                              ?>
                              <div> <input type="hidden" name="geo[city][]" value="<?php echo $get["city_id"]; ?>" > <?php echo $get["city_name"]; ?> <i class="la la-times"></i> </div>
                              <?php
                              }elseif($geo_name == "region"){
                              $get = findOne("uni_region", "region_id=?", [ intval($value) ]);
                              ?>
                              <div> <input type="hidden" name="geo[region][]" value="<?php echo $get["region_id"]; ?>" > <?php echo $get["region_name"]; ?> <i class="la la-times"></i> </div>
                              <?php                              
                              }elseif($geo_name == "country"){
                              $get = findOne("uni_country", "country_id=?", [ intval($value) ]);
                              ?>
                              <div> <input type="hidden" name="geo[country][]" value="<?php echo $get["country_id"]; ?>" > <?php echo $get["country_name"]; ?> <i class="la la-times"></i> </div>
                              <?php                              
                              }
                           }
                         }
                      }
                      ?>
                    </div>                 

                </div>
              </div>
              </div>

              </div>

              <input type="hidden" name="id" value="<?php echo $id; ?>" >

            </form>

         </div>

      </div>

      <p align="right" >
        <button class="btn btn-gradient-01 delete-advertising mr5" data-id="<?php echo $array_data["advertising_id"]; ?>">Удалить баннер</button>
        <button class="btn btn-success edit-advertising">Сохранить</button>
      </p>
      
   </div>
</div>

<script type="text/javascript" src="include/modules/advertising/script.js"></script>
