<?php 
if( !defined('unisitecms') ) exit;

include "fn.php";

$Ads = new Ads();
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Конструктор RSS</h2>
      </div>
   </div>
</div>  

<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
 
              <p class="display-info-title" style="margin-bottom: 25px;" >
                RSS позволяет вывести контент блога или объявлений в формате xml, сгенерированную ссылку вы можете использовать в различных сервисах и турбо страницах яндекса.
              </p> 

             <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Контент</label>
                <div class="col-lg-9">
                    <select name="content" class="selectpicker" >
                        <option value="0" >Не выбрано</option>
                        <option value="1" >Блог</option>
                        <option value="2" >Объявления</option>
                    </select>
                </div>
             </div>

             <form class="form-ajax" >
 
             <div class="rss-container-blog" >

                 <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label">Название канала</label>
                    <div class="col-lg-5">
                        <input class="form-control" type="text" name="blog_title" value="<?php echo $settings["site_name"]; ?>" >
                    </div>
                 </div>

                 <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label">Краткое описание канала</label>
                    <div class="col-lg-5">
                        <textarea class="form-control" name="blog_desc" rows="3" ></textarea>
                    </div>
                 </div>

                 <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label">Яндекс Турбо</label>
                    <div class="col-lg-9">
                        <label>
                          <input class="toggle-checkbox-sm" type="checkbox" name="blog_turbo" value="1" >
                          <span><span></span></span>
                        </label>
                    </div>
                 </div>
               
                 <div class="rss-container-blog-turbo-metrics" >
                   
                   <div class="form-group row d-flex align-items-center mb-5">
                      <label class="col-lg-3 form-control-label">id метрики</label>
                      <div class="col-lg-5">
                          <input type="text" name="blog_id_metrics" class="form-control" value="" >
                      </div>
                   </div>

                 </div>

                 <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label">Категория</label>
                    <div class="col-lg-9">
                        <select name="blog_category" class="selectpicker" >
                            <option value="0" >Все категории</option>
                            <?php echo outCategoryOptionsBlog(); ?>
                        </select>
                    </div>
                 </div>             

                 <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label">Сортировать</label>
                    <div class="col-lg-9">
                        <select name="blog_sort" class="selectpicker" >
                            <option value="0" >По умолчанию</option>
                            <option value="news" >Самые новые</option>
                            <option value="rand" >Рандомные</option>
                        </select>
                    </div>
                 </div>

                 <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label">Выводить статей</label>
                    <div class="col-lg-9">
                        <div class="row" >
                            <div class="col-lg-2" >
                               <input type="number" name="blog_count" class="form-control" value="20" >
                            </div>
                        </div>
                    </div>
                 </div>

                 <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label">RSS ссылка</label>
                    <div class="col-lg-9">
                        <strong class="blog_link" style="word-break: break-word;" ><?php echo $config["urlPath"] . "/rss.php?content=blog&title=".$settings["site_name"]."&count=20" ?></strong>
                    </div>
                 </div> 

             </div>

             <div class="rss-container-ads" >
               
                 <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label">Название канала</label>
                    <div class="col-lg-5">
                        <input class="form-control" type="text" name="ads_title" value="<?php echo $settings["site_name"]; ?>" >
                    </div>
                 </div>

                 <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label">Краткое описание канала</label>
                    <div class="col-lg-5">
                        <textarea class="form-control" name="ads_desc" rows="3" ></textarea>
                    </div>
                 </div>

                 <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label">Яндекс Турбо</label>
                    <div class="col-lg-9">
                        <label>
                          <input class="toggle-checkbox-sm" type="checkbox" name="ads_turbo" value="1" >
                          <span><span></span></span>
                        </label>
                    </div>
                 </div>

                 <div class="rss-container-ads-turbo-metrics" >
                   
                   <div class="form-group row d-flex align-items-center mb-5">
                      <label class="col-lg-3 form-control-label">id метрики</label>
                      <div class="col-lg-5">
                          <input type="text" name="ads_id_metrics" class="form-control" value="" >
                      </div>
                   </div>

                 </div>

                 <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label">Категория</label>
                    <div class="col-lg-9">
                        <select name="ads_category" class="selectpicker" >
                            <option value="0" >Все категории</option>
                            <?php echo outCategoryOptionsBoard(); ?>
                        </select>
                    </div>
                 </div>             

                 <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label">Сортировать</label>
                    <div class="col-lg-9">
                        <select name="ads_sort" class="selectpicker" >
                            <option value="" >По умолчанию</option>
                            <option value="news" >Самые новые</option>
                            <option value="rand" >Рандомные</option>
                        </select>
                    </div>
                 </div>

                 <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label">Выводить объявлений</label>
                    <div class="col-lg-9">
                        <div class="row" >
                            <div class="col-lg-2" >
                                <input type="number" name="ads_count" class="form-control" value="20" >
                            </div>
                        </div>
                    </div>
                 </div>

                 <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label">RSS ссылка</label>
                    <div class="col-lg-9">
                        <strong class="ads_link" style="word-break: break-word;" ><?php echo $config["urlPath"] . "/rss.php?content=ads&title=".$settings["site_name"]."&count=20" ?></strong>
                    </div>
                 </div> 

             </div>

             </form>


         </div>

      </div>
   </div>
</div>
 
<script type="text/javascript" src="include/modules/rss/script.js"></script>