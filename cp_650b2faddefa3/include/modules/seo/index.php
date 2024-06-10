<?php
if( !defined('unisitecms') ) exit;

$lang_iso = $_GET["lang"] ? $_GET["lang"] : $settings["lang_site_default"];

$get = getAll("SELECT * FROM uni_seo WHERE lang_iso=?", [$lang_iso]);

  if(count($get)){
     foreach($get AS $data){

        $array_data[$data["page"]]["page"] = $data["page"];
        $array_data[$data["page"]]["meta_title"] = $data["meta_title"];
        $array_data[$data["page"]]["meta_desc"] = $data["meta_desc"];
        $array_data[$data["page"]]["text"] = $data["text"];
        $array_data[$data["page"]]["h1"] = $data["h1"];

     }
  }

  if($settings["main_type_products"] == 'physical'){
     $defaultMakros = array(
         '<span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Название домена ('.$_SERVER["SERVER_NAME"].')" >{domen}</span>',
         '<span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Название проекта ('.$settings["site_name"].')" >{site_name}</span>',
         '<span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Ссылка на сайт ('.$config["urlPath"].')" >{url}</span>',
         '<span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Страна посетителя" >{country}</span>',
         '<span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Город посетителя" >{city}</span>',
         '<span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Регион посетителя" >{region}</span>',
         '<span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Локация посетителя, то что удалось определить (Страна,город или регион)" >{geo}</span>',
         '<span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Локация посетителя со склонением" >{geo_declination}</span>',
         '<span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Описание из города, региона или страны" >{geo_meta_desc}</span>',
     );
  }else{
     $defaultMakros = array(
         '<span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Название домена ('.$_SERVER["SERVER_NAME"].')" >{domen}</span>',
         '<span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Название проекта ('.$settings["site_name"].')" >{site_name}</span>',
         '<span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Ссылка на сайт ('.$config["urlPath"].')" >{url}</span>',
     );      
  }

?>  


<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">SEO оптимизация</h2>
      </div>
   </div>
</div>



<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">
         <div class="widget-body">

          <div class="row" >
           
            <div class="col-lg-12" >
            <?php
                $languages = getAll("SELECT * FROM uni_languages order by id_position asc");     

                 if(count($languages) > 0){   

                    foreach($languages AS $language){ 

                      if($language["iso"] == $lang_iso){
                        $active = 'class="active"';
                      }else{
                        $active = '';
                      }

                        ?>
                        <div class="seo-link-lang" >
                          <a <?php echo $active; ?> href="?route=seo&lang=<?php echo $language["iso"]; ?>">
                          <img src="<?php echo Exists($config["media"]["other"],$language["image"],$config["media"]["no_image"]); ?>" width="32px" ><br>
                          <?php echo $language["name"]; ?></a>
                          <?php
                        ?>
                        </div>

                    <?php }
                    ?>
                    <br><br><br>
                    <?php
                 }
            ?>          
            </div>

            <div class="col-lg-3" >

              <ul class="nav nav-tabs nav-left flex-column" role="tablist" aria-orientation="vertical">
              <li class="nav-item">
              <a class="nav-link active show" id="tab-1" data-toggle="tab" href="#tab-content-1" role="tab" aria-controls="tab-1" aria-selected="false">Главная страница</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2" role="tab" aria-controls="tab-2" aria-selected="false">Каталог объявлений</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" id="tab-3" data-toggle="tab" href="#tab-content-3" role="tab" aria-controls="tab-3" aria-selected="true">Карточка объявления</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" id="tab-4" data-toggle="tab" href="#tab-content-4" role="tab" aria-controls="tab-4" aria-selected="true">Блог</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" id="tab-5" data-toggle="tab" href="#tab-content-5" role="tab" aria-controls="tab-5" aria-selected="true">Карточка статьи</a>
              </li>
              <li class="nav-item">
              <a class="nav-link" id="tab-6" data-toggle="tab" href="#tab-content-6" role="tab" aria-controls="tab-6" aria-selected="true">Каталог магазинов</a>
              </li>                         
              </ul>
             
            </div>

            <div class="col-lg-9" >
            <form class="form-data" >
                <div class="tab-content pt-3">
                
                  <div class="tab-pane fade show active" id="tab-content-1" role="tabpanel" aria-labelledby="tab-1">

                    <div class="seo-block-makros" >
                      <p><strong>Данные для подстановки</strong></p>
                      <?php echo implode("", $defaultMakros); ?>
                    </div>


                    <div class="form-group" >
                     <label class="form-control-label" >Заголовок страницы (Meta Title)</label>
                        <input type="text" class="form-control" id="index-meta_title" value="<?php echo $array_data["index"]["meta_title"]; ?>" name="form[index][meta_title]" />
                    </div>
                    <div class="form-group" >
                     <label class="form-control-label" >Meta Description</label>
                        <textarea class="form-control" id="index-meta_desc" name="form[index][meta_desc]" ><?php echo $array_data["index"]["meta_desc"]; ?></textarea> 
                    </div>
                    <div class="form-group" >
                     <label class="form-control-label" >H1</label><br />
                        <input type="text" class="form-control" id="index-h1" name="form[index][h1]" value="<?php echo $array_data["index"]["h1"]; ?>" />
                    </div>

                    <div class="form-group" >
                     <label class="form-control-label" >Описание</label><br />
                        <textarea name="text1" class="ckeditor" ><?php echo $array_data["index"]["text"]; ?></textarea>
                    </div>

                  </div>

                  <div class="tab-pane fade" id="tab-content-2" role="tabpanel" aria-labelledby="tab-2">

                    <div class="seo-block-makros" >
                      <p><strong>Данные для подстановки</strong></p>
                      <?php echo implode("", $defaultMakros); ?>
                      
                      <span class="tab-content-2-makros-1" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Список только главных категорий доский объявлений" >{board_main_categories}</span>
                      <span class="tab-content-2-makros-2" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Название категории на которой находится посетитель" >{board_category_name}</span>
                      <span class="tab-content-2-makros-3" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Заголовок категории Meta title" >{board_category_title}</span>
                      <span class="tab-content-2-makros-3" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Заголовок категории H1" >{board_category_h1}</span>
                      <span class="tab-content-2-makros-4" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Описание категории Meta description" >{board_category_meta_desc}</span>
                      <span class="tab-content-2-makros-5" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Полное описание категории" >{board_category_text}</span>
                    </div>
                    
                    <div class="form-group" >
                     <label class="form-control-label" >Условие</label>
                        <select class="tab-content-2-change-condition form-control"  >
                           <option value="0" selected="" >Если выбрана категория</option>
                           <option value="1" >Если не выбрана категория</option>
                        </select>
                    </div> 

                    <div class="tab-content-2-condition-1" >                   

                        <div class="form-group" >
                         <label class="form-control-label" >Заголовок страницы (Meta Title)</label>
                            <input type="text" class="form-control" id="board-meta_title" value="<?php echo $array_data["board"]["meta_title"]; ?>" name="form[board][meta_title]" />
                        </div>
                        <div class="form-group" >
                         <label class="form-control-label" >Meta Description</label>
                            <textarea class="form-control" id="board-meta_desc" name="form[board][meta_desc]" ><?php echo $array_data["board"]["meta_desc"]; ?></textarea> 
                        </div>
                        <div class="form-group" >
                         <label class="form-control-label" >H1</label><br />
                            <input type="text" class="form-control" id="board-h1" name="form[board][h1]" value="<?php echo $array_data["board"]["h1"]; ?>" />
                        </div>
                        <div class="form-group" >
                         <label class="form-control-label" >Описание</label><br />
                            <textarea name="text2" class="ckeditor" ><?php echo $array_data["board"]["text"]; ?></textarea>
                        </div>

                    </div>

                    <div class="tab-content-2-condition-2" >                   

                        <div class="form-group" >
                         <label class="form-control-label" >Заголовок страницы (Meta Title)</label>
                            <input type="text" class="form-control" id="board-geo-meta_title" value="<?php echo $array_data["board_geo"]["meta_title"]; ?>" name="form[board_geo][meta_title]" />
                        </div>
                        <div class="form-group" >
                         <label class="form-control-label" >Meta Description</label>
                            <textarea class="form-control" id="board-geo-meta_desc" name="form[board_geo][meta_desc]" ><?php echo $array_data["board_geo"]["meta_desc"]; ?></textarea> 
                        </div>
                        <div class="form-group" >
                         <label class="form-control-label" >H1</label><br />
                            <input type="text" class="form-control" id="board-geo-h1" name="form[board_geo][h1]" value="<?php echo $array_data["board_geo"]["h1"]; ?>" />
                        </div>
                        <div class="form-group" >
                         <label class="form-control-label" >Описание</label><br />
                            <textarea name="text4" class="ckeditor" ><?php echo $array_data["board_geo"]["text"]; ?></textarea>
                        </div>

                    </div>


                  </div>

                  <div class="tab-pane fade" id="tab-content-3" role="tabpanel" aria-labelledby="tab-3">

                    <div class="seo-block-makros" >
                      <p><strong>Данные для подстановки</strong></p>
                      <?php echo implode("", $defaultMakros); ?>

                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Заголовок объявления" >{ad_title}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Описание объявления" >{ad_text}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Город объявления" >{ad_city}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Регион объявления" >{ad_region}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Страна объявления" >{ad_country}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Дата публикации объявления" >{ad_publication}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Цена" >{ad_price}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Название категории" >{board_category_name}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Заголовок категории" >{board_category_title}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="H1 категории" >{board_category_h1}</span>                      
                    </div>


                    <div class="form-group" >
                     <label class="form-control-label" >Заголовок страницы (Meta Title)</label>
                        <input type="text" class="form-control" id="ad-meta_title" value="<?php echo $array_data["ad"]["meta_title"]; ?>" name="form[ad][meta_title]" />
                    </div>
                    <div class="form-group" >
                     <label class="form-control-label" >Meta Description</label>
                        <textarea class="form-control" id="ad-meta_desc" name="form[ad][meta_desc]" ><?php echo $array_data["ad"]["meta_desc"]; ?></textarea> 
                    </div>

                  </div>

                  <div class="tab-pane fade" id="tab-content-4" role="tabpanel" aria-labelledby="tab-4">

                    <div class="seo-block-makros" >
                      <p><strong>Данные для подстановки</strong></p>
                      <?php echo implode("", $defaultMakros); ?>

                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Главные категории" class="tab-content-4-makros-1" >{blog_main_categories}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Название категории" class="tab-content-4-makros-2" >{blog_category_name}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Заголовок категории (Meta title)" class="tab-content-4-makros-3" >{blog_category_title}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Заголовок категории (H1)" class="tab-content-4-makros-4" >{blog_category_h1}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Краткое описание (Meta description)" class="tab-content-4-makros-5" >{blog_category_meta_desc}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Полное описание" class="tab-content-4-makros-6" >{blog_category_text}</span>                      
                    </div>

                    <div class="form-group" >
                        <label class="form-control-label" >Условие</label>
                        <select class="tab-content-4-change-condition form-control"  >
                           <option value="0" selected="" >Если выбрана категория</option>
                           <option value="1" >Если не выбрана категория</option>
                        </select>
                    </div> 

                    <div class="tab-content-4-condition-1" >                   

                       <div class="form-group" >
                        <label class="form-control-label" >Заголовок страницы (Meta Title)</label>
                           <input type="text" class="form-control" id="blog_category-meta_title" value="<?php echo $array_data["blog_category"]["meta_title"]; ?>" name="form[blog_category][meta_title]" />
                       </div>
                       <div class="form-group" >
                        <label class="form-control-label" >Meta Description</label>
                           <textarea class="form-control" id="blog_category-meta_desc" name="form[blog_category][meta_desc]" ><?php echo $array_data["blog_category"]["meta_desc"]; ?></textarea> 
                       </div>
                       <div class="form-group" >
                        <label class="form-control-label" >H1</label><br />
                           <input type="text" class="form-control" id="blog_category-h1" name="form[blog_category][h1]" value="<?php echo $array_data["blog_category"]["h1"]; ?>" />
                       </div>
                       <div class="form-group" >
                        <label class="form-control-label" >Описание</label><br />
                           <textarea name="text3" class="ckeditor" ><?php echo $array_data["blog_category"]["text"]; ?></textarea>
                       </div>

                    </div>

                    <div class="tab-content-4-condition-2" >                   

                       <div class="form-group" >
                        <label class="form-control-label" >Заголовок страницы (Meta Title)</label>
                           <input type="text" class="form-control" id="blog-meta_title" value="<?php echo $array_data["blog"]["meta_title"]; ?>" name="form[blog][meta_title]" />
                       </div>
                       <div class="form-group" >
                        <label class="form-control-label" >Meta Description</label>
                           <textarea class="form-control" id="blog-meta_desc" name="form[blog][meta_desc]" ><?php echo $array_data["blog"]["meta_desc"]; ?></textarea> 
                       </div>
                       <div class="form-group" >
                        <label class="form-control-label" >H1</label><br />
                           <input type="text" class="form-control" id="blog-h1" name="form[blog][h1]" value="<?php echo $array_data["blog"]["h1"]; ?>" />
                       </div>
                       <div class="form-group" >
                        <label class="form-control-label" >Описание</label><br />
                           <textarea name="text5" class="ckeditor" ><?php echo $array_data["blog"]["text"]; ?></textarea>
                       </div>

                    </div>

                  </div>

                  <div class="tab-pane fade" id="tab-content-5" role="tabpanel" aria-labelledby="tab-5">

                    <div class="seo-block-makros" >
                      <p><strong>Данные для подстановки</strong></p>
                      <?php echo implode("", $defaultMakros); ?>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Заголовок статьи блога" >{article_title}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Meta description статьи" >{article_meta_desc}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Название категории статьи" >{blog_category_name}</span>
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Заголовок категории статьи" >{blog_category_title}</span>                      
                    </div>


                    <div class="form-group" >
                     <label class="form-control-label" >Заголовок страницы (Meta Title)</label>
                        <input type="text" class="form-control" id="article-meta_title" value="<?php echo $array_data["article"]["meta_title"]; ?>" name="form[article][meta_title]" />
                    </div>
                    <div class="form-group" >
                     <label class="form-control-label" >Meta Description</label>
                        <textarea class="form-control" id="article-meta_desc" name="form[article][meta_desc]" ><?php echo $array_data["article"]["meta_desc"]; ?></textarea> 
                    </div>

                  </div>                 

                  <div class="tab-pane fade" id="tab-content-6" role="tabpanel" aria-labelledby="tab-6">

                    <div class="seo-block-makros" >
                      <p><strong>Данные для подстановки</strong></p>
                      <?php echo implode("", $defaultMakros); ?>
                      
                      <span data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Название категории на которой находится посетитель" >{shop_category_name}</span>

                    </div>
                    
                    <div class="form-group" >
                     <label class="form-control-label" >Условие</label>
                        <select class="tab-content-6-change-condition form-control"  >
                           <option value="0" selected="" >Если выбрана категория</option>
                           <option value="1" >Если не выбрана категория</option>
                        </select>
                    </div> 

                    <div class="tab-content-6-condition-1" >                   

                        <div class="form-group" >
                         <label class="form-control-label" >Заголовок страницы (Meta Title)</label>
                            <input type="text" class="form-control" id="shops_category-meta_title" value="<?php echo $array_data["shops_category"]["meta_title"]; ?>" name="form[shops_category][meta_title]" />
                        </div>
                        <div class="form-group" >
                         <label class="form-control-label" >Meta Description</label>
                            <textarea class="form-control" id="shops_category-meta_desc" name="form[shops_category][meta_desc]" ><?php echo $array_data["shops_category"]["meta_desc"]; ?></textarea> 
                        </div>
                        <div class="form-group" >
                         <label class="form-control-label" >H1</label><br />
                            <input type="text" class="form-control" id="shops_category-h1" name="form[shops_category][h1]" value="<?php echo $array_data["shops_category"]["h1"]; ?>" />
                        </div>
                        <div class="form-group" >
                         <label class="form-control-label" >Описание</label><br />
                            <textarea name="shops_category_text" class="ckeditor" ><?php echo $array_data["shops_category"]["text"]; ?></textarea>
                        </div>

                    </div>

                    <div class="tab-content-6-condition-2" style="display:none;" >                   

                        <div class="form-group" >
                         <label class="form-control-label" >Заголовок страницы (Meta Title)</label>
                            <input type="text" class="form-control" id="shops-meta_title" value="<?php echo $array_data["shops"]["meta_title"]; ?>" name="form[shops][meta_title]" />
                        </div>
                        <div class="form-group" >
                         <label class="form-control-label" >Meta Description</label>
                            <textarea class="form-control" id="shops-meta_desc" name="form[shops][meta_desc]" ><?php echo $array_data["shops"]["meta_desc"]; ?></textarea> 
                        </div>
                        <div class="form-group" >
                         <label class="form-control-label" >H1</label><br />
                            <input type="text" class="form-control" id="shops-h1" name="form[shops][h1]" value="<?php echo $array_data["shops"]["h1"]; ?>" />
                        </div>
                        <div class="form-group" >
                         <label class="form-control-label" >Описание</label><br />
                            <textarea name="shops_text" class="ckeditor" ><?php echo $array_data["shops"]["text"]; ?></textarea>
                        </div>

                    </div>


                  </div>


                </div>
              
              <input type="hidden" name="lang" value="<?php echo $lang_iso; ?>" >

            </form>
            </div>

          </div>



         </div>
      </div>
   </div>
</div>

<p align="right" >
  <button type="button" class="btn btn-success edit-seo">Сохранить</button>
</p>

<script type="text/javascript" src="include/modules/seo/script.js"></script>
     

