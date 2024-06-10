<?php 
if( !defined('unisitecms') ) exit;

$data = findOne("uni_seo_filters", "seo_filters_id=?", [$id]);
if(!$data){ exit; }
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Редактирование фильтра</h2>
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
                <label class="col-lg-3 form-control-label">Название</label>
                <div class="col-lg-7">
                     <input type="text" class="form-control setTranslate" name="name" value="<?php echo $data["seo_filters_name"]; ?>" >
                </div>
              </div>
 
              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Алиас</label>
                <div class="col-lg-7">
                     <input type="text" class="form-control outTranslate" name="alias" value="<?php echo $data["seo_filters_alias"]; ?>" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Заголовок (Meta title)</label>
                <div class="col-lg-7">
                     <input type="text" class="form-control" name="title" value="<?php echo $data["seo_filters_title"]; ?>" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Заголовок (h1)</label>
                <div class="col-lg-7">
                   <input type="text" class="form-control" name="h1" value="<?php echo $data["seo_filters_h1"]; ?>" >

                   <div class="alert alert-primary alert-dissmissible fade show" style="margin-top: 10px;" >
                      Макросы: <?php echo outStaticTextMakros(); ?>
                   </div>

                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">URL параметры</label>
                <div class="col-lg-7">
                     <input type="text" class="form-control setUrlParameters" name="url" value="<?php echo $data["seo_filters_params"]; ?>" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Алиас геолокации</label>
                <div class="col-lg-4">
                     <input type="text" class="form-control" name="alias_geo" <?php if($data["seo_filters_geo_auto"]){ echo 'disabled=""'; } ?> value="<?php echo $data["seo_filters_alias_geo"]; ?>" >
                     <div>
                       <small>Если не нужна привязка к геолокации, то оставьте это поле пустым.</small>
                     </div>                     
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Алиас категории</label>
                <div class="col-lg-4">
                     <input type="text" class="form-control" name="alias_category" value="<?php echo $data["seo_filters_alias_category"]; ?>" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Краткое описание</label>
                <div class="col-lg-7">
                     <textarea class="form-control" name="desc" ><?php echo $data["seo_filters_desc"]; ?></textarea>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Полное описание</label>
                <div class="col-lg-7">
                     <textarea name="text" class="ckeditor" ><?php echo $data["seo_filters_text"]; ?></textarea>
                </div>
              </div>              
              
              <input type="hidden" name="id" value="<?php echo $data["seo_filters_id"]; ?>" >

            </form>

         </div>

      </div>

      <p align="right" >
        <button type="button" class="btn btn-success edit-seo-filter">Сохранить</button>
      </p>
      
   </div>
</div>

    

<script type="text/javascript" src="include/modules/seo/script.js"></script>