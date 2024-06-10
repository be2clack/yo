<?php 
if( !defined('unisitecms') ) exit;

?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Новая страница</h2>
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
                      <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="visible" checked="" value="1" >
                      <span><span></span></span>
                    </label>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Название</label>
                <div class="col-lg-7">
                     <input type="text" class="form-control setTranslate" name="name" >
                </div>
              </div>
 
              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Алиас</label>
                <div class="col-lg-7">
                     <input type="text" class="form-control outTranslate" name="alias" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Заголовок</label>
                <div class="col-lg-7">
                     <input type="text" class="form-control" name="title" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Meta Description</label>
                <div class="col-lg-7">
                     <textarea class="form-control" name="desc" ></textarea>
                </div>
              </div>

              <div class="form-group row mb-5">
                <label class="col-lg-3 form-control-label">Содержание страницы</label>
                <div class="col-lg-7">
                     <textarea name="text" class="ckeditor" ></textarea>
                </div>
              </div>              
              


            </form>

         </div>

      </div>

      <p align="right" >
        <button type="button" class="btn btn-success add-page">Добавить</button>
      </p>
      
   </div>
</div>

    

<script type="text/javascript" src="include/modules/pages/script.js"></script>