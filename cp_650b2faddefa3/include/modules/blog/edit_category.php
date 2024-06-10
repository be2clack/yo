<?php if( !defined('unisitecms') ) exit;

include("fn.php");

$array_data = findOne("uni_blog_category","blog_category_id=?", array($id));
if(count($array_data) == 0){
   exit;
}

$cat_ids[] = $array_data["blog_category_id"];
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title"><?php echo $array_data["blog_category_name"]; ?></h2>
         <div>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="?route=blog&tab=category">Категории блога</a></li>
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

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Статус видимости</label>
                <div class="col-lg-6">
                    <label>
                      <input class="toggle-checkbox-sm" type="checkbox" name="visible" <?php if($array_data["blog_category_visible"]){ echo 'checked=""'; } ?> value="1" >
                      <span><span></span></span>
                    </label>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Изображение</label>
                <div class="col-lg-7">

                      <div class="small-image-container" >
                        <span class="small-image-delete" <?php if(!$array_data["blog_category_image"]){ echo 'style="display: none;"'; } ?> > <i class="la la-trash"></i> </span>

                        <?php echo img( array( "img1" => array( "class" => "change-img", "path" => $config["media"]["big_image_blog"] . "/" . $array_data["blog_category_image"], "width" => "60px" ), "img2" => array( "class" => "change-img", "path" => $config["media"]["other"] . "/icon_photo_add.png", "width" => "60px" ) ) ); ?>

                        <input type="hidden" name="image_delete" value="0" >
                      </div>

                      <input type="file" name="image" class="input-img" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Название</label>
                <div class="col-lg-7">
                     
                    <div class="row" >
                        <div class="col-lg-6" >
                          
                             <input type="text" class="form-control setTranslate" name="name" value="<?php echo $array_data["blog_category_name"]; ?>" >

                        </div>
                        <div class="col-lg-6" >
                             
                             <input type="text" class="form-control outTranslate" name="alias" placeholder="Алиас" value="<?php echo $array_data["blog_category_alias"]; ?>" >

                        </div>
                    </div>

                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Заголовок (Meta title)</label>
                <div class="col-lg-7">
                     <input type="text" class="form-control" name="title" value="<?php echo $array_data["blog_category_title"]; ?>" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Заголовок (H1)</label>
                <div class="col-lg-7">
                     <input type="text" class="form-control" name="h1" value="<?php echo $array_data["blog_category_h1"]; ?>" >
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
                     <textarea class="form-control" name="desc" ><?php echo $array_data["blog_category_desc"]; ?></textarea>
                </div>
              </div>

              <div class="form-group row mb-5">
                <label class="col-lg-3 form-control-label">Описание категории</label>
                <div class="col-lg-7">
                     <textarea name="text" class="ckeditor" ><?php echo urldecode($array_data["blog_category_text"]); ?></textarea>
                </div>
              </div>

              <input type="hidden" name="id" value="<?php echo $array_data["blog_category_id"]; ?>" >              
              

            </form>

         </div>

      </div>

      <p align="right" >
        <button type="button" class="btn btn-success edit-category">Сохранить</button>
      </p>
      
   </div>
</div>

<script type="text/javascript" src="include/modules/blog/script.js"></script>