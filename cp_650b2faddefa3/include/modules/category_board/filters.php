<?php if( !defined('unisitecms') ) exit;

include("fn.php");

$Filters = new Filters();

if(!empty($_GET["search"])){

    $_GET["search"] = clearSearch($_GET["search"]);

    $query = " where ads_filters_name LIKE '%".$_GET["search"]."%'"; 
   
}else{

    if($_GET["cat_id"]){

       $filters_ids = $Filters->getCategory( ["id_cat"=> intval($_GET["cat_id"]) ] );
       if($filters_ids) $query = "where ads_filters_id IN(".implode(",", $filters_ids).")"; else $query = "where ads_filters_id IN(0)";

    }

}

?>   

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Фильтры</h2>
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
    
      <form method="get" action="<?php echo $config["urlPrefix"].$config["folder_admin"]; ?>" >
        <input type="text" class="form-control" placeholder="Укажите название фильтра" style="height: 44px;" value="<?php echo $_GET["search"]; ?>" name="search">
        <input type="hidden" name="route" value="filters" >
      </form>

  </div>
</div>

<div class="form-group" style="margin-top: 20px;" >
 
 <div class="row" >
    <div class="col-lg-6 col-12" >
       
       <a href="#" data-toggle="modal" data-target="#modal-add-filters" class="btn btn-gradient-04 mr-1" >Добавить фильтр</a>

       <div class="btn-group" >
         <div class="dropdown">
          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Категории <?php if(!empty($_GET["cat_name"])){ echo "(".$_GET["cat_name"].")"; } ?>
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="max-height: 350px; overflow: auto;" >
             <a class="dropdown-item" href="?route=filters">Все категории</a>
             <?php echo outCategoryDropdownFilters(); ?>
          </div>
         </div>
       </div>

    </div>
    <div class="col-lg-6 col-12 text-right" >
       
       <div class="btn-group" >
         <div class="btn btn-gradient-01 mr-1 mb-2 filters-clear" >Удалить фильтры</div>
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
              $getFilters = $Filters->getFilters( $query );

              $filters = outFilters($getFilters);

              if($filters){
              ?>

                 <table class="table mb-0">
                    <thead>
                       <tr>
                        <th>Название</th>
                        <th>Категории</th>
                        <th>Статус</th>
                        <th></th>
                       </tr>
                    </thead>
                    <tbody class="sort-container-filter" >

                      <?php

                        echo $filters;

                      ?>

                    </tbody>
                </table>

              <?php }else{ ?>

                 <div class="plug" >
                   <i class="la la-exclamation-triangle"></i>
                   <p>Фильтров нет</p>
                </div>               

              <?php } ?>

            </div>
         </div>
      </div>
   </div>
</div>

<div id="modal-add-filters" class="modal fade">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Добавление фильтра</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
            <form class="form-add-filter" >
              
              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Название фильтра</label>
                <div class="col-lg-9">
                    <input type="text" name="name" class="form-control setTranslate" value="" /> 
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Алиас</label>
                <div class="col-lg-9">
                    <input type="text" name="alias" class="form-control outTranslate" value="" /> 
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Категория</label>
                <div class="col-lg-9">
                    <select class="selectpicker" name="id_cat[]" multiple="" title="Не выбрано" data-live-search="true" >
                       <?php echo outCategoryOptions(); ?>
                    </select> 
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Вид при подаче объявления</label>
                <div class="col-lg-9">
                    <select class="filter-list-cat-select selectpicker" name="type_filter" >
                      <option value="select" >Выпадающий список с одиночным выбором</option>
                      <option value="select_multi" >Выпадающий список с множественным выбором</option>
                      <option value="input" >Поле ввода цифр</option>
                      <option value="input_text" >Поле ввода текста</option>
                    </select>
                </div>
              </div>

              <div class="form-group row d-flex mb-5">
                <label class="col-lg-3 form-control-label">Фильтр активен</label>
                <div class="col-lg-9">
                    <label>
                      <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="visible" value="1" checked="" >
                      <span><span></span></span>
                    </label>
                </div>
              </div>
              
              <div class="form-group row d-flex mb-5">
                <label class="col-lg-3 form-control-label">Обязательный фильтр</label>
                <div class="col-lg-9">
                    <label>
                      <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="required" value="1"  >
                      <span><span></span></span>
                    </label>
                </div>
              </div>

              <div class="box-value-filters" >
                 <div class="form-group row d-flex mb-5">
                   <label class="col-lg-3 form-control-label">Значения фильтра</label>
                   <div class="col-lg-9">

                    <button class="btn btn-success btn-sm action-add-item-filter" ><i class="la la-plus" ></i> Добавить значение</button>

                     <div class="alert alert-primary filter-slider-hint" style="margin-top: 15px; font-size: 12px;" role="alert">
                       Добавьте 2 поля. В первом укажите значение от, а во втором поле значение до
                     </div>

                    <div class="list-podfilter" ></div>

                   </div>
                 </div>
              </div>
              
            </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary action-add-filter">Добавить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-edit-filter" class="modal fade">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Редактирование фильтра</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
            <form class="form-edit-filter" ></form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary action-edit-filter">Сохранить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-alias-filters" class="modal fade">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Алиасы фильтра</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
            <form class="form-alias-filter" ></form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary action-edit-alias-filter">Сохранить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-add-podfilter" class="modal fade">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Добавление подфильтра</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
            <form class="form-add-podfilter" ></form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary action-add-podfilter">Добавить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-edit-podfilter" class="modal fade">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Редактирование фильтра</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
            <form class="form-edit-podfilter" ></form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary action-edit-podfilter">Сохранить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-load-copy-filter" class="modal fade">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Копирование фильтра</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
            <form class="form-copy-filter" ></form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary action-add-copy-filter">Добавить</button>
         </div>
      </div>
   </div>
</div>

 <script type="text/javascript" src="include/modules/category_board/script.js"></script>
 <script type="text/javascript" src="include/modules/category_board/filters.js"></script>