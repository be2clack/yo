<?php 
if( !defined('unisitecms') ) exit;

include("fn.php");

$array_data = findOne("uni_blog_articles","blog_articles_id=?", array($id));
if(count($array_data) == 0){
   exit;
}

$cat_ids[] = $array_data["blog_articles_id_cat"];

?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Редактирование публикации</h2>
         <div>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="?route=blog">Блог</a></li>
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
                      <input class="toggle-checkbox-sm" type="checkbox" name="visible" <?php if($array_data["blog_articles_visible"]){echo 'checked=""';} ?> value="1" >
                      <span><span></span></span>
                    </label>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Изображение</label>
                <div class="col-lg-7">

                      <div class="small-image-container" >
                        <span class="small-image-delete" <?php if(!$array_data["blog_articles_image"]){ echo 'style="display: none;"'; } ?> > <i class="la la-trash"></i> </span>

                        <?php echo img( array( "img1" => array( "class" => "change-img", "path" => $config["media"]["big_image_blog"] . "/" . $array_data["blog_articles_image"], "width" => "60px" ), "img2" => array( "class" => "change-img", "path" => $config["media"]["other"] . "/icon_photo_add.png", "width" => "60px" ) ) ); ?>

                        <input type="hidden" name="image_delete" value="0" >
                      </div>
                      
                      <input type="file" name="image" class="input-img" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Заголовок</label>
                <div class="col-lg-7">
                     <input type="text" class="form-control" value="<?php echo $array_data["blog_articles_title"]; ?>" name="title" >
                </div>
              </div>
 
              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Название в URL</label>
                <div class="col-lg-7">
                     <input type="text" class="form-control" value="<?php echo $array_data["blog_articles_alias"]; ?>" name="alias" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Категория</label>
                <div class="col-lg-7">
                    <select name="id_cat" class="selectpicker" data-live-search="true" > 
                      <?php echo outCategoryOptions(); ?>     
                    </select>                      
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Краткое описание (Meta Description)</label>
                <div class="col-lg-7">
                     <textarea class="form-control" name="desc" ><?php echo $array_data["blog_articles_desc"]; ?></textarea>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Полное описание</label>
                <div class="col-lg-7">
                     <textarea name="text" class="ckeditor" ><?php echo urldecode($array_data["blog_articles_text"]); ?></textarea>
                </div>
              </div>              
              
              <input type="hidden" name="id" value="<?php echo $array_data["blog_articles_id"];?>" />

            </form>

         </div>

      </div>

      <p align="right" >
        <button type="button" class="btn btn-success edit-article">Сохранить</button>
      </p>
      
   </div>
</div>

<script type="text/javascript" src="include/modules/blog/script.js"></script>
