<?php
if( !defined('unisitecms') ) exit;
?>  

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Мультиязычность</h2>
      </div>
   </div>
</div>


<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">
         <div class="widget-body">
          
          <ul class="nav nav-tabs nav-fill" role="tablist">
             <li class="nav-item">
                <a class="nav-link <?php if(!$_GET["tab"] || $_GET["tab"] == "content"){ echo 'active'; } ?>" id="j-drop-tab-1"  href="?route=multilanguage&tab=content" >Контент</a>
             </li>           
             <li class="nav-item">
                <a class="nav-link <?php if($_GET["tab"] == "category_board"){ echo 'active'; } ?>" id="j-drop-tab-2"  href="?route=multilanguage&tab=category_board" >Категории объявлений</a>
             </li>
             <li class="nav-item <?php if($_GET["tab"] == "category_board_filters"){ echo 'active'; } ?>">
                <a class="nav-link" id="j-drop-tab-3" href="?route=multilanguage&tab=category_board_filters" >Фильтры</a>
             </li> 
             <li class="nav-item <?php if($_GET["tab"] == "category_board_filters_alias"){ echo 'active'; } ?>">
                <a class="nav-link" id="j-drop-tab-10" href="?route=multilanguage&tab=category_board_filters_alias" >Алиасы фильтров</a>
             </li>
             <li class="nav-item <?php if($_GET["tab"] == "seo_filters"){ echo 'active'; } ?>">
                <a class="nav-link" id="j-drop-tab-11" href="?route=multilanguage&tab=seo_filters" >SEO фильтры</a>
             </li>                                      
             <li class="nav-item <?php if($_GET["tab"] == "category_blog"){ echo 'active'; } ?>">
                <a class="nav-link" id="j-drop-tab-4" href="?route=multilanguage&tab=category_blog" >Категории блога</a>
             </li>                          
             <li class="nav-item <?php if($_GET["tab"] == "articles"){ echo 'active'; } ?>">
                <a class="nav-link" id="j-drop-tab-6" href="?route=multilanguage&tab=articles" >Статьи</a>
             </li>
             <li class="nav-item <?php if($_GET["tab"] == "cities"){ echo 'active'; } ?>">
                <a class="nav-link" id="j-drop-tab-7" href="?route=multilanguage&tab=cities" >Города</a>
             </li>   
             <li class="nav-item <?php if($_GET["tab"] == "pages"){ echo 'active'; } ?>">
                <a class="nav-link" id="j-drop-tab-8" href="?route=multilanguage&tab=pages" >Сервисные страницы</a>
             </li>
             <li class="nav-item <?php if($_GET["tab"] == "services_ad"){ echo 'active'; } ?>">
                <a class="nav-link" id="j-drop-tab-9" href="?route=multilanguage&tab=services_ad" >Платные услуги</a>
             </li>
             <li class="nav-item <?php if($_GET["tab"] == "user_tariffs"){ echo 'active'; } ?>">
                <a class="nav-link" id="j-drop-tab-14" href="?route=multilanguage&tab=user_tariffs" >Тарифы</a>
             </li>             
             <li class="nav-item <?php if($_GET["tab"] == "promo_slider"){ echo 'active'; } ?>">
                <a class="nav-link" id="j-drop-tab-12" href="?route=multilanguage&tab=promo_slider" >Промо слайдер</a>
             </li>
             <li class="nav-item <?php if($_GET["tab"] == "area"){ echo 'active'; } ?>">
                <a class="nav-link" id="j-drop-tab-13" href="?route=multilanguage&tab=area" >Районы</a>
             </li>                                                                                                                 
          </ul>

          <div class="row" >
            
            <div class="col-lg-12" >
            
                <div class="tab-content pt-3">

                  <div class="tab-pane fade <?php if(!$_GET["tab"] || $_GET["tab"] == "content"){ echo 'show active'; } ?>" id="j-d-tab-1" role="tabpanel" aria-labelledby="vert-tab-1">

                     <div class="header-multilang" >
                      <?php
                      $get = getAll("SELECT * FROM uni_languages order by id_position asc");     

                       if(count($get) > 0){
                          foreach($get AS $array_data){

                            if($_GET["iso"] == $array_data["iso"]){
                               $active = 'class="active"';
                            }else{
                               $active = '';
                            }

                             ?>
                             <div>
                             <a href="?route=multilanguage&tab=content&iso=<?php echo $array_data["iso"]; ?>"></a>
                             <a <?php echo $active; ?> href="?route=multilanguage&tab=content&iso=<?php echo $array_data["iso"]; ?>">
                              <img src="<?php echo Exists($config["media"]["other"],$array_data["image"],$config["media"]["no_image"]); ?>" width="32px" >
                              <br>
                              <?php echo $array_data["name"]; ?></a>
                             </div>
                             <?php
                          } 
                       } 

                       ?>
                      </div>

                      <?php if($_GET["iso"]){ ?>

                      <?php if($settings["app_available_status"]){ ?>

                      <ul class="nav nav-tabs" role="tablist">
                         <li class="nav-item">
                            <a class="nav-link <?php if(!$_GET["type"] || $_GET["type"] == "site"){ echo 'active'; } ?>" id="j-drop-tab-2-1"  href="?route=multilanguage&tab=content&type=site&iso=<?php echo $_GET["iso"]; ?>" >Сайт</a>
                         </li>           
                         <li class="nav-item">
                            <a class="nav-link <?php if($_GET["type"] == "app"){ echo 'active'; } ?>" id="j-drop-tab-2-2"  href="?route=multilanguage&tab=content&type=app&iso=<?php echo $_GET["iso"]; ?>" >Приложение</a>
                         </li>                                                                                                                 
                      </ul>

                      <br>

                      <div <?php if(!$_GET["type"] || $_GET["type"] == "site"){ echo 'style="display: block;"'; }else{ echo 'style="display: none;"'; } ?> >

                        <form class="form-data-keys" >
                        <?php

                        $array_lang = [];

                        if(file_exists($config["basePath"] . "/lang/" . $_GET["iso"] . "/main.php")){
                          
                          $array_lang = require $config["basePath"] . "/lang/" . $_GET["iso"] . "/main.php";

                          foreach ($array_lang  as $key => $value) {
                             ?>

                                <div class="form-group">
                                   <input type="text" name="keys[<?php echo $_GET["iso"]; ?>][<?php echo $key; ?>]" value="<?php echo removeSlash($value); ?>" class="form-control"> 
                                </div>

                             <?php
                          }

                        }else{

                          if( file_exists( $config["basePath"] . "/static/main.php" ) ){
                              copy($config["basePath"] . "/static/main.php", $config["basePath"] . "/lang/" . $_GET["iso"] . "/main.php");
                          }else{
                              copy($config["basePath"] . "/lang/".$settings["lang_site_default"]."/main.php", $config["basePath"] . "/lang/" . $_GET["iso"] . "/main.php");
                          }

                          $array_lang = require $config["basePath"] . "/lang/" . $_GET["iso"] . "/main.php";

                          foreach ($array_lang  as $key => $value) {
                             ?>

                                <div class="form-group">
                                   <input type="text" name="keys[<?php echo $_GET["iso"]; ?>][<?php echo $key; ?>]" value="<?php echo removeSlash($value); ?>" class="form-control"> 
                                </div>

                             <?php
                          }

                        }
                         
                         ?>

                          <br>

                          <p align="right" class="floating-button" >
                            <button class="btn btn-success">Сохранить</button>
                          </p>

                        <input type="hidden" name="content_type" value="<?php echo isset($_GET["type"]) ? $_GET["type"] : ""; ?>" >

                        </form>

                      </div>
                        
                      <div <?php if($_GET["type"] == "app"){ echo 'style="display: block;"'; }else{ echo 'style="display: none;"'; } ?> >

                        <form class="form-data-keys" >

                        <?php

                        $array_lang = [];

                        if(file_exists($config["basePath"] . "/lang/" . $_GET["iso"] . "/app.php")){
                          
                          $array_lang = require $config["basePath"] . "/lang/" . $_GET["iso"] . "/app.php";
                          $array_lang_static = require $config["basePath"] . "/static/app.php";

                          foreach ($array_lang_static as $value) {
                              if( isset($array_lang[md5($value)]) ){
                               ?>
                                <div class="form-group">
                                   <input type="text" name="keys[<?php echo $_GET["iso"]; ?>][<?php echo md5($value); ?>]" value="<?php echo $array_lang[md5($value)]; ?>" class="form-control"> 
                                </div>                               
                               <?php
                              }else{
                               ?>
                                <div class="form-group">
                                   <input type="text" name="keys[<?php echo $_GET["iso"]; ?>][<?php echo md5($value); ?>]" value="<?php echo removeSlash($value); ?>" class="form-control"> 
                                </div>                               
                               <?php
                              }
                          }

                        }else{

                          if( file_exists( $config["basePath"] . "/static/app.php" ) ){
                              $array_lang = require $config["basePath"] . "/static/app.php";
                          }

                          if($array_lang){
                              foreach ($array_lang  as $value) {
                                 ?>

                                    <div class="form-group">
                                       <input type="text" name="keys[<?php echo $_GET["iso"]; ?>][<?php echo md5($value); ?>]" value="<?php echo removeSlash($value); ?>" class="form-control"> 
                                    </div>

                                 <?php
                              }
                          }

                        }
                         
                         ?>

                          <br>

                          <p align="right" class="floating-button" >
                            <button class="btn btn-success">Сохранить</button>
                          </p>

                        <input type="hidden" name="content_type" value="<?php echo isset($_GET["type"]) ? $_GET["type"] : ""; ?>" >

                        </form>

                      </div>

                      <?php }else{ ?>

                        <form class="form-data-keys" >
                        <?php

                        $array_lang = [];

                        if(file_exists($config["basePath"] . "/lang/" . $_GET["iso"] . "/main.php")){
                          
                          $array_lang = require $config["basePath"] . "/lang/" . $_GET["iso"] . "/main.php";

                          foreach ($array_lang  as $key => $value) {
                             ?>

                                <div class="form-group">
                                   <input type="text" name="keys[<?php echo $_GET["iso"]; ?>][<?php echo $key; ?>]" value="<?php echo removeSlash($value); ?>" class="form-control"> 
                                </div>

                             <?php
                          }

                        }else{

                          if( file_exists( $config["basePath"] . "/static/main.php" ) ){
                              copy($config["basePath"] . "/static/main.php", $config["basePath"] . "/lang/" . $_GET["iso"] . "/main.php");
                          }else{
                              copy($config["basePath"] . "/lang/".$settings["lang_site_default"]."/main.php", $config["basePath"] . "/lang/" . $_GET["iso"] . "/main.php");
                          }

                          $array_lang = require $config["basePath"] . "/lang/" . $_GET["iso"] . "/main.php";

                          foreach ($array_lang  as $key => $value) {
                             ?>

                                <div class="form-group">
                                   <input type="text" name="keys[<?php echo $_GET["iso"]; ?>][<?php echo $key; ?>]" value="<?php echo removeSlash($value); ?>" class="form-control"> 
                                </div>

                             <?php
                          }

                        }
                         
                         ?>

                          <br>

                          <p align="right" class="floating-button" >
                            <button class="btn btn-success">Сохранить</button>
                          </p>


                        </form>

                      <?php } ?>

                     <?php } ?>

                  </div>

                  <?php if($_GET["tab"] == "category_board"){ ?>
                  <div class="tab-pane fade show active" id="j-d-tab-2" role="tabpanel" aria-labelledby="vert-tab-2">
                  <form class="form-data" >
                    <div class="row" >

                      <div class="col-lg-3" >
                        <ul class="ul-a-link" >
                          <?php 
                            $get = getAll("SELECT * FROM uni_category_board order by category_board_id_position asc");
                            if(count($get)){
                              foreach ($get as $key => $value) {

                                if($_GET["id"] == $value["category_board_id"]){
                                   $active = 'class="active"';
                                }else{
                                   $active = '';
                                }

                                 ?>
                                 <li><a <?php echo $active; ?> href="?route=multilanguage&id=<?php echo $value["category_board_id"]; ?>&tab=category_board"><?php echo $value["category_board_name"]; ?></a></li>
                                 <?php
                              }
                            }
                          ?>
                        </ul>
                      </div>

                      <div class="col-lg-9" >

                       <?php
                       if($_GET["id"]){

                          $view = findOne("uni_category_board", "category_board_id=?", [ intval($_GET["id"]) ]);
                          
                          $languages = getAll("SELECT * FROM uni_languages order by id_position asc");     

                           if(count($languages)){   

                                 foreach($languages AS $array_data){

                                    if( file_exists( $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_category_board.php" ) ){
                                       $array_lang = require $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_category_board.php";
                                    }else{ $array_lang = []; }

                                    ?>

                                    <div class="box-overflow-header" >
                                      <img src="<?php echo Exists($config["media"]["other"],$array_data["image"],$config["media"]["no_image"]); ?>" width="32px" ><br>
                                    <?php
                                      echo $array_data["name"];
                                    ?>
                                    </div>

                                     <div class="form-group">
                                       <label>Название</label>
                                       <input type="text" name="form[uni_category_board][<?php echo $array_data["iso"]; ?>][<?php echo md5("category_board_name_" . $view["category_board_name"]) ?>]" value="<?php if( $array_lang[ md5("category_board_name_" . $view["category_board_name"]) ] ){ echo removeSlash($array_lang[ md5("category_board_name_" . $view["category_board_name"]) ]); }else{ echo removeSlash($view["category_board_name"]); } ?>" class="form-control">
                                     </div>
                                     <?php if($view["category_board_title"]){ ?>
                                     <div class="form-group">
                                       <label>Заголовок (Meta title)</label>
                                       <input type="text" name="form[uni_category_board][<?php echo $array_data["iso"]; ?>][<?php echo md5("category_board_title_" . $view["category_board_title"]) ?>]" value="<?php if( $array_lang[ md5("category_board_title_" . $view["category_board_title"]) ] ){ echo removeSlash($array_lang[ md5("category_board_title_" . $view["category_board_title"]) ]); }else{ echo removeSlash($view["category_board_title"]); } ?>" class="form-control">
                                     </div>
                                     <?php } ?>
                                     <?php if($view["category_board_h1"]){ ?>
                                     <div class="form-group">
                                       <label>Заголовок (h1)</label>
                                       <input type="text" name="form[uni_category_board][<?php echo $array_data["iso"]; ?>][<?php echo md5("category_board_h1_" . $view["category_board_h1"]) ?>]" value="<?php if( $array_lang[ md5("category_board_h1_" . $view["category_board_h1"]) ] ){ echo removeSlash($array_lang[ md5("category_board_h1_" . $view["category_board_h1"]) ]); }else{ echo removeSlash($view["category_board_h1"]); } ?>" class="form-control">
                                     </div>
                                     <?php } ?>                                     
                                     <?php if($view["category_board_description"]){ ?>
                                     <div class="form-group">
                                       <label>Краткое описание</label>
                                       <textarea class="form-control" name="form[uni_category_board][<?php echo $array_data["iso"]; ?>][<?php echo md5("category_board_description_" . urldecode($view["category_board_description"])) ?>]" ><?php if( $array_lang[ md5("category_board_description_" . urldecode($view["category_board_description"])) ] ){ echo removeSlash($array_lang[ md5("category_board_description_" . urldecode($view["category_board_description"])) ]); }else{ echo removeSlash(urldecode($view["category_board_description"])); } ?></textarea>
                                     </div>
                                     <?php } ?> 
                                     <?php if($view["category_board_text"]){ ?>
                                     <div class="form-group">
                                       <label>Полное описание</label>
                                       <textarea class="ckeditor" name="form[uni_category_board][<?php echo $array_data["iso"]; ?>][<?php echo md5("category_board_text_" . urldecode($view["category_board_text"])) ?>]" class="form-control" name="text" ><?php if( $array_lang[ md5("category_board_text_" . urldecode($view["category_board_text"])) ] ){ echo removeSlash($array_lang[ md5("category_board_text_" . urldecode($view["category_board_text"])) ]); }else{ echo removeSlash(urldecode($view["category_board_text"])); } ?></textarea>
                                     </div>
                                     <?php } ?>  

                                  <?php 
                                }
        
                           }else{
                             ?>
                               <div class="plug" >
                                  <i class="la la-exclamation-triangle"></i>
                                  <p>Нет активных языков</p>
                               </div>                          
                             <?php                              
                           }

                          ?>
                            <p align="right" class="floating-button" >
                              <button class="btn btn-success">Сохранить</button>
                            </p>                          
                          <?php

                        }else{
                          ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Выберите категорию</p>
                            </div>                          
                          <?php
                        }                 
                        ?>
                       
                      </div>
                    </div>

                  </form>

                  </div>
                  <?php }elseif($_GET["tab"] == "category_board_filters"){ ?>

                  <div class="tab-pane fade show active" id="j-d-tab-3" role="tabpanel" aria-labelledby="vert-tab-3">

                  <form class="form-data" >
                    <div class="row" >

                      <div class="col-lg-3" >
                        <ul class="ul-a-link" >
                          <?php 
                            $get = getAll("SELECT * FROM uni_ads_filters order by ads_filters_position asc");
                            if(count($get)){
                              foreach ($get as $key => $value) {

                                if($_GET["id"] == $value["ads_filters_id"]){
                                   $active = 'class="active"';
                                }else{
                                   $active = '';
                                }

                                 ?>
                                 <li><a <?php echo $active; ?> href="?route=multilanguage&id=<?php echo $value["ads_filters_id"]; ?>&tab=category_board_filters"><?php echo $value["ads_filters_name"]; ?></a></li>
                                 <?php
                              }
                            }
                          ?>
                        </ul>
                      </div>

                      <div class="col-lg-9" >

                       <?php
                       if($_GET["id"]){

                          $languages = getAll("SELECT * FROM uni_languages order by id_position asc");     

                           if(count($languages) > 0){   

                                 foreach($languages AS $array_data){

                                    if( file_exists( $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_ads_filters.php" ) ){
                                       $array_lang = require $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_ads_filters.php";
                                    }else{ $array_lang = []; }

                                      ?>
                                      <div class="box-overflow-header" >
                                          <img src="<?php echo Exists($config["media"]["other"],$array_data["image"],$config["media"]["no_image"]); ?>" width="32px" ><br>
                                        <?php
                                          echo $array_data["name"];
                                        ?>
                                      </div>

                                      <?php
                                      $view = findOne("uni_ads_filters", "ads_filters_id=?", [ intval($_GET["id"]) ]);
                                      ?>

                                       <div class="form-group">
                                         <label>Название</label>
                                         <input type="text" name="form[uni_ads_filters][<?php echo $array_data["iso"]; ?>][<?php echo md5("ads_filters_name_" . $view["ads_filters_name"]) ?>]" value="<?php if( $array_lang[ md5("ads_filters_name_" . $view["ads_filters_name"]) ] ){ echo removeSlash($array_lang[ md5("ads_filters_name_" . $view["ads_filters_name"]) ]); }else{ echo removeSlash($view["ads_filters_name"]); } ?>" class="form-control">
                                       </div>

                                       <?php
                                       if( $view["ads_filters_type"] != "input" ){
                                       ?>

                                       <div class="form-group">
                                         <label><strong>Значения</strong></label><br>
                                         
                                         <?php
                                           $view_items = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=".intval($view['ads_filters_id'])); 

                                           if(count($view_items) > 0){

                                              foreach ($view_items as $key => $value) {

                                                     ?>
                                                       <input type="text" style="margin-bottom: 5px;" name="form[uni_ads_filters][<?php echo $array_data["iso"]; ?>][<?php echo md5("ads_filters_items_value_" . $value["ads_filters_items_value"]) ?>]" value="<?php if( $array_lang[ md5("ads_filters_items_value_" . $value["ads_filters_items_value"]) ] ){ echo removeSlash($array_lang[ md5("ads_filters_items_value_" . $value["ads_filters_items_value"]) ]); }else{ echo removeSlash($value["ads_filters_items_value"]); } ?>" class="form-control">
                                                     <?php                                                

                                              }

                                           }
                                         ?>
                                         
                                       </div>                                              
  
                                  <?php 
                                      }
                                }
        
                            }else{
                             ?>
                               <div class="plug" >
                                  <i class="la la-exclamation-triangle"></i>
                                  <p>Нет активных языков</p>
                               </div>                          
                             <?php                              
                           }

                          ?>
                            <p align="right" class="floating-button" >
                              <button class="btn btn-success">Сохранить</button>
                            </p>                          
                          <?php

                        }else{
                          ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Выберите фильтр</p>
                            </div>                          
                          <?php
                        }                 
                        ?>
                       
                      </div>
                    </div>

                  </form>                    

                  </div>

                  <?php }elseif($_GET["tab"] == "category_blog"){ ?>
                  <div class="tab-pane fade show active" id="j-d-tab-4" role="tabpanel" aria-labelledby="vert-tab-4">
                  <form class="form-data" >
                    <div class="row" >

                      <div class="col-lg-3" >
                        <ul class="ul-a-link" >
                          <?php 
                            $get = getAll("SELECT * FROM uni_blog_category order by blog_category_id_position asc");
                            if(count($get)){
                              foreach ($get as $key => $value) {

                                if($_GET["id"] == $value["blog_category_id"]){
                                   $active = 'class="active"';
                                }else{
                                   $active = '';
                                }

                                 ?>
                                 <li><a <?php echo $active; ?> href="?route=multilanguage&id=<?php echo $value["blog_category_id"]; ?>&tab=category_blog"><?php echo $value["blog_category_name"]; ?></a></li>
                                 <?php
                              }
                            }
                          ?>
                        </ul>
                      </div>

                      <div class="col-lg-9" >

                       <?php
                       if($_GET["id"]){

                          $languages = getAll("SELECT * FROM uni_languages order by id_position asc");     

                           if(count($languages) > 0){   

                                 foreach($languages AS $array_data){ 

                                    if( file_exists( $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_blog_category.php" ) ){
                                       $array_lang = require $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_blog_category.php";
                                    }else{ $array_lang = []; }

                                  ?>


                                      <div class="box-overflow-header" >
                                        <img src="<?php echo Exists($config["media"]["other"],$array_data["image"],$config["media"]["no_image"]); ?>" width="32px" ><br>
                                      <?php
                                        echo $array_data["name"];
                                      ?>
                                      </div>

                                        <?php
                                          $view = findOne("uni_blog_category", "blog_category_id=?", [ intval($_GET["id"]) ]);
                                        ?>

                                         <div class="form-group">
                                           <label>Название</label>
                                           <input type="text" name="form[uni_blog_category][<?php echo $array_data["iso"]; ?>][<?php echo md5("blog_category_name_" . $view["blog_category_name"]) ?>]" value="<?php if( $array_lang[ md5("blog_category_name_" . $view["blog_category_name"]) ] ){ echo removeSlash($array_lang[ md5("blog_category_name_" . $view["blog_category_name"]) ]); }else{ echo removeSlash($view["blog_category_name"]); } ?>" class="form-control">
                                         </div>
                                         <?php if($view["blog_category_title"]){ ?>
                                         <div class="form-group">
                                           <label>Заголовок</label>
                                           <input type="text" name="form[uni_blog_category][<?php echo $array_data["iso"]; ?>][<?php echo md5("blog_category_title_" . $view["blog_category_title"]) ?>]" value="<?php if( $array_lang[ md5("blog_category_title_" . $view["blog_category_title"]) ] ){ echo removeSlash($array_lang[ md5("blog_category_title_" . $view["blog_category_title"]) ]); }else{ echo removeSlash($view["blog_category_title"]); } ?>" class="form-control">
                                         </div>
                                         <?php } ?>
                                         <?php if($view["blog_category_desc"]){ ?>
                                         <div class="form-group">
                                           <label>Краткое описание</label>
                                           <textarea class="form-control" name="form[uni_blog_category][<?php echo $array_data["iso"]; ?>][<?php echo md5("blog_category_desc_" . urldecode($view["blog_category_desc"])) ?>]" ><?php if( $array_lang[ md5("blog_category_desc_" . urldecode($view["blog_category_desc"])) ] ){ echo removeSlash($array_lang[ md5("blog_category_desc_" . urldecode($view["blog_category_desc"])) ]); }else{ echo removeSlash(urldecode($view["blog_category_desc"])); } ?></textarea>
                                         </div> 
                                         <?php } ?>
                                         <?php if($view["blog_category_text"]){ ?>
                                         <div class="form-group">
                                           <label>Полное описание</label>
                                           <textarea class="ckeditor" name="form[uni_blog_category][<?php echo $array_data["iso"]; ?>][<?php echo md5("blog_category_text_" . urldecode($view["blog_category_text"])) ?>]" class="form-control" name="text" ><?php if( $array_lang[ md5("blog_category_text_" . urldecode($view["blog_category_text"])) ] ){ echo removeSlash($array_lang[ md5("blog_category_text_" . urldecode($view["blog_category_text"])) ]); }else{ echo removeSlash(urldecode($view["blog_category_text"])); } ?></textarea>
                                         </div> 
                                         <?php } ?> 

                                  <?php }
        
                           }else{
                             ?>
                               <div class="plug" >
                                  <i class="la la-exclamation-triangle"></i>
                                  <p>Нет активных языков</p>
                               </div>                          
                             <?php                              
                           }

                          ?>
                            <p align="right" class="floating-button" >
                              <button class="btn btn-success">Сохранить</button>
                            </p>                          
                          <?php

                        }else{
                          ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Выберите категорию</p>
                            </div>                          
                          <?php
                        }                 
                        ?>
                       
                      </div>
                    </div>

                  </form>

                  </div>  
                  <?php }elseif($_GET["tab"] == "articles"){ ?>
                  <div class="tab-pane fade show active" id="j-d-tab-6" role="tabpanel" aria-labelledby="vert-tab-6">
                  <form class="form-data" >
                    <div class="row" >

                      <div class="col-lg-3" >
                        <ul class="ul-a-link" >
                          <?php 
                            $get = getAll("SELECT * FROM uni_blog_articles order by blog_articles_id desc");
                            if(count($get)){
                              foreach ($get as $key => $value) {

                                if($_GET["id"] == $value["blog_articles_id"]){
                                   $active = 'class="active"';
                                }else{
                                   $active = '';
                                }

                                 ?>
                                 <li><a <?php echo $active; ?> href="?route=multilanguage&id=<?php echo $value["blog_articles_id"]; ?>&tab=articles"><?php echo $value["blog_articles_title"]; ?></a></li>
                                 <?php
                              }
                            }
                          ?>
                        </ul>
                      </div>

                      <div class="col-lg-9" >

                       <?php
                       if($_GET["id"]){

                          $languages = getAll("SELECT * FROM uni_languages order by id_position asc");     

                           if(count($languages) > 0){   

                                 foreach($languages AS $array_data){

                                    if( file_exists( $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_blog_articles.php" ) ){
                                       $array_lang = require $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_blog_articles.php";
                                    }else{ $array_lang = []; }

                                  ?>

                                      <div class="box-overflow-header" >
                                        <img src="<?php echo Exists($config["media"]["other"],$array_data["image"],$config["media"]["no_image"]); ?>" width="32px" ><br>
                                      <?php
                                        echo $array_data["name"];
                                      ?>
                                      </div>

                                         <?php
                                         $view = findOne("uni_blog_articles", "blog_articles_id=?", [ intval($_GET["id"]) ]);
                                         ?>

                                         <div class="form-group">
                                           <label>Заголовок</label>
                                           <input type="text" name="form[uni_blog_articles][<?php echo $array_data["iso"]; ?>][<?php echo md5("blog_articles_title_" . $view["blog_articles_title"]) ?>]" value="<?php if( $array_lang[ md5("blog_articles_title_" . $view["blog_articles_title"]) ] ){ echo removeSlash($array_lang[ md5("blog_articles_title_" . $view["blog_articles_title"]) ]); }else{ echo removeSlash($view["blog_articles_title"]); } ?>" class="form-control">
                                         </div>
                                         <?php if($view["blog_articles_desc"]){ ?>
                                         <div class="form-group">
                                           <label>Краткое описание</label>
                                           <textarea class="form-control" name="form[uni_blog_articles][<?php echo $array_data["iso"]; ?>][<?php echo md5("blog_articles_desc_" . urldecode($view["blog_articles_desc"])) ?>]" ><?php if( $array_lang[ md5("blog_articles_desc_" . urldecode($view["blog_articles_desc"])) ] ){ echo removeSlash($array_lang[ md5("blog_articles_desc_" . urldecode($view["blog_articles_desc"])) ]); }else{ echo removeSlash(urldecode($view["blog_articles_desc"])); } ?></textarea>
                                         </div>
                                         <?php } ?>
                                         <?php if($view["blog_articles_text"]){ ?>
                                         <div class="form-group">
                                           <label>Полное описание</label>
                                           <textarea class="ckeditor" name="form[uni_blog_articles][<?php echo $array_data["iso"]; ?>][<?php echo md5("blog_articles_text_" . urldecode($view["blog_articles_text"])) ?>]" class="form-control" name="text" ><?php if( $array_lang[ md5("blog_articles_text_" . urldecode($view["blog_articles_text"])) ] ){ echo removeSlash($array_lang[ md5("blog_articles_text_" . urldecode($view["blog_articles_text"])) ]); }else{ echo removeSlash(urldecode($view["blog_articles_text"])); } ?></textarea>
                                         </div>
                                         <?php } ?>   

                                  <?php }
        
                           }else{
                             ?>
                               <div class="plug" >
                                  <i class="la la-exclamation-triangle"></i>
                                  <p>Нет активных языков</p>
                               </div>                          
                             <?php                              
                           }

                          ?>
                            <p align="right" class="floating-button" >
                              <button class="btn btn-success">Сохранить</button>
                            </p>                          
                          <?php

                        }else{
                          ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Выберите публикацию</p>
                            </div>                          
                          <?php
                        }                 
                        ?>
                       
                      </div>
                    </div>

                  </form>

                  </div>
                  <?php }elseif($_GET["tab"] == "cities"){ ?>
                  <div class="tab-pane fade show active" id="j-d-tab-7" role="tabpanel" aria-labelledby="vert-tab-7">

                  <form class="form-data" >
                    <div class="row" >

                      <div class="col-lg-3" >
                        <ul class="ul-a-link" >
                          <?php 
                            $get = getAll("SELECT * FROM uni_country WHERE country_status='1' order by country_name asc");
                            if(count($get) > 0){
                              foreach ($get as $key => $value) {

                                if($_GET["country_id"] == $value["country_id"]){
                                   $active = 'class="active"';
                                }else{
                                   $active = '';
                                }

                                 ?>
                                 <li><a <?php echo $active; ?> href="?route=multilanguage&country_id=<?php echo $value["country_id"]; ?>&tab=cities&table=country"><?php echo $value["country_name"]; ?></a>

                                  <ul class="lang-country-region" >
                                     <?php
                                     
                                     if($_GET["country_id"] == $value["country_id"]){

                                       $region = getAll("SELECT * FROM uni_region WHERE country_id = ".intval($_GET["country_id"])." order by region_name asc");
                                       if(count($region) > 0){
                                         foreach ($region as $region_key => $region_value) {

                                          if($_GET["region_id"] == $region_value["region_id"]){
                                             $active = 'class="active"';
                                          }else{
                                             $active = '';
                                          }

                                            ?>
                                            <li><a <?php echo $active; ?> href="?route=multilanguage&region_id=<?php echo $region_value["region_id"]; ?>&tab=cities&country_id=<?php echo $value["country_id"]; ?>&table=region"><?php echo $region_value["region_name"]; ?></a>

                                                <ul class="lang-country-city" >
                                                   <?php
                                                   if($_GET["region_id"] == $region_value["region_id"]){
                                                     $city = getAll("SELECT * FROM uni_city WHERE region_id = ".intval($_GET["region_id"])." order by city_name asc");
                                                     if(count($city) > 0){
                                                       foreach ($city as $city_key => $city_value) {

                                                          if($_GET["city_id"] == $city_value["city_id"]){
                                                             $active = 'class="active"';
                                                          }else{
                                                             $active = '';
                                                          }

                                                          ?>
                                                          <li><a <?php echo $active; ?> href="?route=multilanguage&city_id=<?php echo $city_value["city_id"]; ?>&tab=cities&country_id=<?php echo $value["country_id"]; ?>&table=city&region_id=<?php echo $city_value["region_id"]; ?>"><?php echo $city_value["city_name"]; ?></a></li>
                                                          <?php
                                                       }
                                                     }
                                                   }
                                                   ?>
                                                </ul>

                                            </li>
                                            <?php
                                         }
                                        }

                                      }
                                      
                                     ?>
                                  </ul>
                                 </li>
                                 <?php
                              }
                            }else{
                             ?>
                               <div class="plug" >
                                  <i class="la la-exclamation-triangle"></i>
                                  <p>Нет активных языков</p>
                               </div>                          
                             <?php                              
                           }
                          ?>
                        </ul>
                      </div>

                      <div class="col-lg-9" >

                       <?php
                       if($_GET["country_id"]){

                          $languages = getAll("SELECT * FROM uni_languages order by id_position asc");     

                           if(count($languages) > 0){   

                                 foreach($languages AS $array_data){

                                    if( file_exists( $config["basePath"] . "/lang/" . $array_data["iso"] . "/geo.php" ) ){
                                       $array_lang = require $config["basePath"] . "/lang/" . $array_data["iso"] . "/geo.php";
                                    }else{ $array_lang = []; }

                                 ?>

                                      <div class="box-overflow-header" >
                                        <img src="<?php echo Exists($config["media"]["other"],$array_data["image"],$config["media"]["no_image"]); ?>" width="32px" ><br>
                                      <?php
                                        echo $array_data["name"];
                                      ?>
                                      </div>

                                        <?php
                                        if($_GET["table"] == "country"){
                                          $view = getOne("select * from uni_country where country_id=".intval($_GET["country_id"]));
                                           ?>
                                             <div class="form-group">
                                               <label>Название</label>
                                               <input type="text" name="form[geo][<?php echo $array_data["iso"]; ?>][<?php echo md5("geo_name_" . $view["country_name"]) ?>]" value="<?php if( $array_lang[ md5("geo_name_" . $view["country_name"]) ] ){ echo removeSlash($array_lang[ md5("geo_name_" . $view["country_name"]) ]); }else{ echo removeSlash($view["country_name"]); } ?>" class="form-control">
                                             </div> 
                                             <?php if($view["country_declination"]){ ?>
                                             <div class="form-group">
                                               <label>Склонение</label>
                                               <input name="form[geo][<?php echo $array_data["iso"]; ?>][<?php echo md5("geo_declination_" . $view["country_declination"]) ?>]" type="text" class="form-control" value="<?php if( $array_lang[ md5("geo_declination_" . $view["country_declination"]) ] ){ echo removeSlash($array_lang[ md5("geo_declination_" . $view["country_declination"]) ]); }else{ echo removeSlash(urldecode($view["country_declination"])); } ?>" >
                                             </div>
                                             <?php } ?>                                             
                                             <?php if($view["country_desc"]){ ?>
                                             <div class="form-group">
                                               <label>Описание</label>
                                               <textarea name="form[geo][<?php echo $array_data["iso"]; ?>][<?php echo md5("geo_desc_" . $view["country_desc"]) ?>]" class="form-control"><?php if( $array_lang[ md5("geo_desc_" . $view["country_desc"]) ] ){ echo removeSlash($array_lang[ md5("geo_desc_" . $view["country_desc"]) ]); }else{ echo removeSlash(urldecode($view["country_desc"])); } ?></textarea>
                                             </div>
                                             <?php } ?>                                                                                       
                                           <?php
                                        }elseif($_GET["table"] == "region"){
                                           $view = getOne("select * from uni_region where region_id=".intval($_GET["region_id"]));
                                           ?>
                                             <div class="form-group">
                                               <label>Название</label>
                                               <input type="text" name="form[geo][<?php echo $array_data["iso"]; ?>][<?php echo md5("geo_name_" . $view["region_name"]) ?>]" value="<?php if( $array_lang[ md5("geo_name_" . $view["region_name"]) ] ){ echo removeSlash($array_lang[ md5("geo_name_" . $view["region_name"]) ]); }else{ echo removeSlash($view["region_name"]); } ?>" class="form-control">
                                             </div> 
                                             <?php if($view["region_declination"]){ ?>
                                             <div class="form-group">
                                               <label>Склонение</label>
                                               <input name="form[geo][<?php echo $array_data["iso"]; ?>][<?php echo md5("geo_declination_" . $view["region_declination"]) ?>]" type="text" class="form-control" value="<?php if( $array_lang[ md5("geo_declination_" . $view["region_declination"]) ] ){ echo removeSlash($array_lang[ md5("geo_declination_" . $view["region_declination"]) ]); }else{ echo removeSlash(urldecode($view["region_declination"])); } ?>" >
                                             </div>
                                             <?php } ?>                                             
                                             <?php if($view["region_desc"]){ ?>
                                             <div class="form-group">
                                               <label>Описание</label>
                                               <textarea name="form[geo][<?php echo $array_data["iso"]; ?>][<?php echo md5("geo_desc_" . $view["region_desc"]) ?>]" class="form-control"><?php if( $array_lang[ md5("geo_desc_" . $view["region_desc"]) ] ){ echo removeSlash($array_lang[ md5("geo_desc_" . $view["region_desc"]) ]); }else{ echo removeSlash($view["region_desc"]); } ?></textarea>
                                             </div>
                                             <?php } ?>                                                                                       
                                           <?php
                                        }elseif($_GET["table"] == "city"){
                                           $view = getOne("select * from uni_city where city_id=".intval($_GET["city_id"]));
                                           ?>
                                             <div class="form-group">
                                               <label>Название</label>
                                               <input type="text" name="form[geo][<?php echo $array_data["iso"]; ?>][<?php echo md5("geo_name_" . $view["city_name"]) ?>]" value="<?php if( $array_lang[ md5("geo_name_" . $view["city_name"]) ] ){ echo removeSlash($array_lang[ md5("geo_name_" . $view["city_name"]) ]); }else{ echo removeSlash($view["city_name"]); } ?>" class="form-control">
                                             </div>
                                             <?php if($view["city_declination"]){ ?>
                                             <div class="form-group">
                                               <label>Склонение</label>
                                               <input name="form[geo][<?php echo $array_data["iso"]; ?>][<?php echo md5("geo_declination_" . $view["city_declination"]) ?>]" type="text" class="form-control" value="<?php if( $array_lang[ md5("geo_declination_" . $view["city_declination"]) ] ){ echo removeSlash($array_lang[ md5("geo_declination_" . $view["city_declination"]) ]); }else{ echo removeSlash(urldecode($view["city_declination"])); } ?>" >
                                             </div>
                                             <?php } ?>                                             
                                             <?php if($view["city_desc"]){ ?> 
                                             <div class="form-group">
                                               <label>Описание</label>
                                               <textarea  class="form-control" name="form[geo][<?php echo $array_data["iso"]; ?>][<?php echo md5("geo_desc_" . $view["city_desc"]) ?>]" ><?php if( $array_lang[ md5("geo_desc_" . $view["city_desc"]) ] ){ echo removeSlash($array_lang[ md5("geo_desc_" . $view["city_desc"]) ]); }else{ echo removeSlash($view["city_desc"]); } ?></textarea>
                                             </div>
                                             <?php } ?>                                                                                       
                                           <?php
                                        }

                                            
                                  }
        
                           }else{
                             ?>
                               <div class="plug" >
                                  <i class="la la-exclamation-triangle"></i>
                                  <p>Нет активных языков</p>
                               </div>                          
                             <?php                              
                           }

                          ?>
                            <p align="right" class="floating-button" >
                              <button class="btn btn-success">Сохранить</button>
                            </p>                          
                          <?php

                        }else{
                          ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Выберите город</p>
                            </div>                          
                          <?php
                        }                 
                        ?>
                       
                      </div>
                    </div>

                  </form>

                  </div>
                  <?php }elseif($_GET["tab"] == "pages"){ ?>

                  <div class="tab-pane fade show active" id="j-d-tab-8" role="tabpanel" aria-labelledby="vert-tab-8">
                  <form class="form-data" >
                    <div class="row" >

                      <div class="col-lg-3" >
                        <ul class="ul-a-link" >
                          <?php 
                            $get = getAll("SELECT * FROM uni_pages order by id desc");
                            if(count($get)){
                              foreach ($get as $key => $value) {

                                if($_GET["id"] == $value["id"]){
                                   $active = 'class="active"';
                                }else{
                                   $active = '';
                                }

                                 ?>
                                 <li><a <?php echo $active; ?> href="?route=multilanguage&id=<?php echo $value["id"]; ?>&tab=pages"><?php echo $value["name"]; ?></a></li>
                                 <?php
                              }
                            }
                          ?>
                        </ul>
                      </div>

                      <div class="col-lg-9" >

                       <?php
                       if($_GET["id"]){

                          $languages = getAll("SELECT * FROM uni_languages order by id_position asc");     

                           if(count($languages) > 0){   

                                 foreach($languages AS $array_data){

                                    if( file_exists( $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_pages.php" ) ){
                                       $array_lang = require $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_pages.php";
                                    }else{ $array_lang = []; }

                                 ?>

                                      <div class="box-overflow-header" >
                                        <img src="<?php echo Exists($config["media"]["other"],$array_data["image"],$config["media"]["no_image"]); ?>" width="32px" ><br>
                                      <?php
                                        echo $array_data["name"];
                                      ?>
                                      </div>

                                        <?php
                                          $view = findOne("uni_pages", "id=?", [ intval($_GET["id"]) ]);
                                        ?>

                                         <div class="form-group">
                                           <label>Название</label>
                                           <input type="text" name="form[uni_pages][<?php echo $array_data["iso"]; ?>][<?php echo md5("name_" . $view["name"]) ?>]" value="<?php if( $array_lang[ md5("name_" . $view["name"]) ] ){ echo removeSlash($array_lang[ md5("name_" . $view["name"]) ]); }else{ echo removeSlash($view["name"]); } ?>" class="form-control">
                                         </div>
                                         <?php if($view["title"]){ ?>
                                         <div class="form-group">
                                           <label>Заголовок</label>
                                           <input type="text" name="form[uni_pages][<?php echo $array_data["iso"]; ?>][<?php echo md5("title_" . $view["title"]) ?>]" value="<?php if( $array_lang[ md5("title_" . $view["title"]) ] ){ echo removeSlash($array_lang[ md5("title_" . $view["title"]) ]); }else{ echo removeSlash($view["title"]); } ?>" class="form-control">
                                         </div>
                                         <?php } ?>
                                         <?php if($view["seo_text"]){ ?>
                                         <div class="form-group">
                                           <label>Краткое описание</label>
                                           <textarea class="form-control" name="form[uni_pages][<?php echo $array_data["iso"]; ?>][<?php echo md5("seo_text_" . urldecode($view["seo_text"])) ?>]" ><?php if( $array_lang[ md5("seo_text_" . urldecode($view["seo_text"])) ] ){ echo removeSlash($array_lang[ md5("seo_text_" . urldecode($view["seo_text"])) ]); }else{ echo removeSlash(urldecode($view["seo_text"])); } ?></textarea>
                                         </div> 
                                         <?php } ?>
                                         <?php if($view["text"]){ ?>
                                         <div class="form-group">
                                           <label>Полное описание</label>
                                           <textarea class="ckeditor" name="form[uni_pages][<?php echo $array_data["iso"]; ?>][<?php echo md5("text_" . urldecode($view["text"])) ?>]" class="form-control" name="text" ><?php if( $array_lang[ md5("text_" . urldecode($view["text"])) ] ){ echo removeSlash($array_lang[ md5("text_" . urldecode($view["text"])) ]); }else{ echo removeSlash(urldecode($view["text"])); } ?></textarea>
                                         </div> 
                                         <?php } ?>  

                                  <?php }
        
                           }else{
                             ?>
                               <div class="plug" >
                                  <i class="la la-exclamation-triangle"></i>
                                  <p>Нет активных языков</p>
                               </div>                          
                             <?php                              
                           }

                          ?>
                            <p align="right" class="floating-button" >
                              <button class="btn btn-success">Сохранить</button>
                            </p>                          
                          <?php

                        }else{
                          ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Выберите страницу</p>
                            </div>                          
                          <?php
                        }                 
                        ?>
                       
                      </div>
                    </div>

                  </form>

                  </div>

                <?php }elseif($_GET["tab"] == "services_ad"){ ?>

                  <div class="tab-pane fade show active" id="j-d-tab-9" role="tabpanel" aria-labelledby="vert-tab-9">
                  <form class="form-data" >
                    <div class="row" >

                      <div class="col-lg-3" >
                        <ul class="ul-a-link" >
                          <?php 
                            $get = getAll("SELECT * FROM uni_services_ads order by services_ads_id desc");
                            if(count($get)){
                              foreach ($get as $key => $value) {

                                if($_GET["id"] == $value["services_ads_id"]){
                                   $active = 'class="active"';
                                }else{
                                   $active = '';
                                }

                                 ?>
                                 <li><a <?php echo $active; ?> href="?route=multilanguage&id=<?php echo $value["services_ads_id"]; ?>&tab=services_ad"><?php echo $value["services_ads_name"]; ?></a></li>
                                 <?php
                              }
                            }
                          ?>
                        </ul>
                      </div>

                      <div class="col-lg-9" >

                       <?php
                       if($_GET["id"]){

                          $languages = getAll("SELECT * FROM uni_languages order by id_position asc");     

                           if(count($languages) > 0){   

                                 foreach($languages AS $array_data){

                                    if( file_exists( $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_services_ads.php" ) ){
                                       $array_lang = require $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_services_ads.php";
                                    }else{ $array_lang = []; }

                                  ?>

                                  <div class="box-overflow-header" >
                                    <img src="<?php echo Exists($config["media"]["other"],$array_data["image"],$config["media"]["no_image"]); ?>" width="32px" ><br>
                                  <?php
                                    echo $array_data["name"];
                                  ?>
                                  </div>

                                  <?php
                                    $view = findOne("uni_services_ads", "services_ads_id=?", [ intval($_GET["id"]) ]);
                                  ?>

                                   <div class="form-group">
                                     <label>Название</label>
                                     <input type="text" name="form[uni_services_ads][<?php echo $array_data["iso"]; ?>][<?php echo md5("services_ads_name_" . $view["services_ads_name"]) ?>]" value="<?php if( $array_lang[ md5("services_ads_name_" . $view["services_ads_name"]) ] ){ echo removeSlash($array_lang[ md5("services_ads_name_" . $view["services_ads_name"]) ]); }else{ echo removeSlash($view["services_ads_name"]); } ?>" class="form-control">
                                   </div>
                                   <?php if($view["services_ads_text"]){ ?>
                                   <div class="form-group">
                                     <label>Описание</label>
                                     <textarea class="form-control" name="form[uni_services_ads][<?php echo $array_data["iso"]; ?>][<?php echo md5("services_ads_text_" . $view["services_ads_text"]) ?>]" class="form-control" name="services_ads_text" ><?php if( $array_lang[ md5("services_ads_text_" . $view["services_ads_text"]) ] ){ echo removeSlash($array_lang[ md5("services_ads_text_" . $view["services_ads_text"]) ]); }else{ echo removeSlash($view["services_ads_text"]); } ?></textarea>
                                   </div> 
                                   <?php } ?>   

                                  <?php }
        
                           }else{
                             ?>
                               <div class="plug" >
                                  <i class="la la-exclamation-triangle"></i>
                                  <p>Нет активных языков</p>
                               </div>                          
                             <?php                              
                           }

                          ?>
                            <p align="right" class="floating-button" >
                              <button class="btn btn-success">Сохранить</button>
                            </p>                          
                          <?php

                        }else{
                          ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Выберите услугу</p>
                            </div>                          
                          <?php
                        }                 
                        ?>
                       
                      </div>
                    </div>

                  </form>

                  </div>
                <?php }elseif($_GET["tab"] == "user_tariffs"){ ?>

                  <div class="tab-pane fade show active" id="j-d-tab-14" role="tabpanel" aria-labelledby="vert-tab-14">
                  <form class="form-data" >
                    <div class="row" >

                      <div class="col-lg-3" >
                        <ul class="ul-a-link" >
                          <?php 
                            $get = getAll("SELECT * FROM uni_services_tariffs_checklist order by services_tariffs_checklist_id desc");
                            if(count($get)){
                              foreach ($get as $key => $value) {

                                if($_GET["id"] == $value["services_tariffs_checklist_id"]){
                                   $active = 'class="active"';
                                }else{
                                   $active = '';
                                }

                                 ?>
                                 <li><a <?php echo $active; ?> href="?route=multilanguage&id=<?php echo $value["services_tariffs_checklist_id"]; ?>&tab=user_tariffs"><?php echo $value["services_tariffs_checklist_name"]; ?></a></li>
                                 <?php
                              }
                            }
                          ?>
                        </ul>
                      </div>

                      <div class="col-lg-9" >

                       <?php
                       if($_GET["id"]){

                          $languages = getAll("SELECT * FROM uni_languages order by id_position asc");     

                           if(count($languages) > 0){   

                                 foreach($languages AS $array_data){

                                    if( file_exists( $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_services_tariffs_checklist.php" ) ){
                                       $array_lang = require $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_services_tariffs_checklist.php";
                                    }else{ $array_lang = []; }

                                  ?>

                                  <div class="box-overflow-header" >
                                    <img src="<?php echo Exists($config["media"]["other"],$array_data["image"],$config["media"]["no_image"]); ?>" width="32px" ><br>
                                  <?php
                                    echo $array_data["name"];
                                  ?>
                                  </div>

                                  <?php
                                    $view = findOne("uni_services_tariffs_checklist", "services_tariffs_checklist_id=?", [ intval($_GET["id"]) ]);
                                  ?>

                                   <div class="form-group">
                                     <label>Название</label>
                                     <input type="text" name="form[uni_services_tariffs_checklist][<?php echo $array_data["iso"]; ?>][<?php echo md5("services_tariffs_checklist_name_" . $view["services_tariffs_checklist_name"]) ?>]" value="<?php if( $array_lang[ md5("services_tariffs_checklist_name_" . $view["services_tariffs_checklist_name"]) ] ){ echo removeSlash($array_lang[ md5("services_tariffs_checklist_name_" . $view["services_tariffs_checklist_name"]) ]); }else{ echo removeSlash($view["services_tariffs_checklist_name"]); } ?>" class="form-control">
                                   </div>
                                   <?php if($view["services_tariffs_checklist_desc"]){ ?>
                                   <div class="form-group">
                                     <label>Описание</label>
                                     <textarea class="form-control" name="form[uni_services_tariffs_checklist][<?php echo $array_data["iso"]; ?>][<?php echo md5("services_tariffs_checklist_desc_" . $view["services_tariffs_checklist_desc"]) ?>]" class="form-control" name="services_tariffs_checklist_desc" ><?php if( $array_lang[ md5("services_tariffs_checklist_desc_" . $view["services_tariffs_checklist_desc"]) ] ){ echo removeSlash($array_lang[ md5("services_tariffs_checklist_desc_" . $view["services_tariffs_checklist_desc"]) ]); }else{ echo removeSlash($view["services_tariffs_checklist_desc"]); } ?></textarea>
                                   </div> 
                                   <?php } ?>   

                                  <?php }
        
                           }else{
                             ?>
                               <div class="plug" >
                                  <i class="la la-exclamation-triangle"></i>
                                  <p>Нет активных языков</p>
                               </div>                          
                             <?php                              
                           }

                          ?>
                            <p align="right" class="floating-button" >
                              <button class="btn btn-success">Сохранить</button>
                            </p>                          
                          <?php

                        }else{
                          ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Выберите тариф</p>
                            </div>                          
                          <?php
                        }                 
                        ?>
                       
                      </div>
                    </div>

                  </form>

                  </div>
                <?php }elseif($_GET["tab"] == "category_board_filters_alias"){ ?>

                  <div class="tab-pane fade show active" id="j-d-tab-10" role="tabpanel" aria-labelledby="vert-tab-10">
                  <form class="form-data" >
                    <div class="row" >

                      <div class="col-lg-3" >
                        <ul class="ul-a-link" >
                          <?php 
                            $get = getAll("SELECT * FROM uni_ads_filters_alias order by ads_filters_alias_id desc");
                            if(count($get)){
                              foreach ($get as $key => $value) {

                                if($_GET["id"] == $value["ads_filters_alias_id"]){
                                   $active = 'class="active"';
                                }else{
                                   $active = '';
                                }

                                 ?>
                                 <li><a <?php echo $active; ?> href="?route=multilanguage&id=<?php echo $value["ads_filters_alias_id"]; ?>&tab=category_board_filters_alias"><?php echo $value["ads_filters_alias_title"]; ?></a></li>
                                 <?php
                              }
                            }
                          ?>
                        </ul>
                      </div>

                      <div class="col-lg-9" >

                       <?php
                       if($_GET["id"]){

                          $languages = getAll("SELECT * FROM uni_languages order by id_position asc");     

                           if(count($languages) > 0){   

                                 foreach($languages AS $array_data){

                                    if( file_exists( $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_ads_filters_alias.php" ) ){
                                       $array_lang = require $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_ads_filters_alias.php";
                                    }else{ $array_lang = []; }

                                  ?>

                                  <div class="box-overflow-header" >
                                    <img src="<?php echo Exists($config["media"]["other"],$array_data["image"],$config["media"]["no_image"]); ?>" width="32px" ><br>
                                  <?php
                                    echo $array_data["name"];
                                  ?>
                                  </div>

                                  <?php
                                    $view = findOne("uni_ads_filters_alias", "ads_filters_alias_id=?", [ intval($_GET["id"]) ]);
                                  ?>

                                   <div class="form-group">
                                     <label>Заголовок (meta title)</label>
                                     <input type="text" name="form[uni_ads_filters_alias][<?php echo $array_data["iso"]; ?>][<?php echo md5("ads_filters_alias_title_" . $view["ads_filters_alias_title"]) ?>]" value="<?php if( $array_lang[ md5("ads_filters_alias_title_" . $view["ads_filters_alias_title"]) ] ){ echo removeSlash($array_lang[ md5("ads_filters_alias_title_" . $view["ads_filters_alias_title"]) ]); }else{ echo removeSlash($view["ads_filters_alias_title"]); } ?>" class="form-control">
                                   </div>   

                                   <div class="form-group">
                                     <label>Заголовок (h1)</label>
                                     <input type="text" name="form[uni_ads_filters_alias][<?php echo $array_data["iso"]; ?>][<?php echo md5("ads_filters_alias_h1_" . $view["ads_filters_alias_h1"]) ?>]" value="<?php if( $array_lang[ md5("ads_filters_alias_h1_" . $view["ads_filters_alias_h1"]) ] ){ echo removeSlash($array_lang[ md5("ads_filters_alias_h1_" . $view["ads_filters_alias_h1"]) ]); }else{ echo removeSlash($view["ads_filters_alias_h1"]); } ?>" class="form-control">
                                   </div>

                                   <div class="form-group">
                                     <label>Название в ссылке</label>
                                     <input type="text" name="form[uni_ads_filters_alias][<?php echo $array_data["iso"]; ?>][<?php echo md5("ads_filters_alias_name_" . $view["ads_filters_alias_name"]) ?>]" value="<?php if( $array_lang[ md5("ads_filters_alias_name_" . $view["ads_filters_alias_name"]) ] ){ echo removeSlash($array_lang[ md5("ads_filters_alias_name_" . $view["ads_filters_alias_name"]) ]); }else{ echo removeSlash($view["ads_filters_alias_name"]); } ?>" class="form-control">
                                   </div>
                                   
                                   <?php if( $view["ads_filters_alias_desc"] ){ ?>
                                   <div class="form-group">
                                     <label>Краткое описание</label>
                                     <input type="text" name="form[uni_ads_filters_alias][<?php echo $array_data["iso"]; ?>][<?php echo md5("ads_filters_alias_desc_" . $view["ads_filters_alias_desc"]) ?>]" value="<?php if( $array_lang[ md5("ads_filters_alias_desc_" . $view["ads_filters_alias_desc"]) ] ){ echo removeSlash($array_lang[ md5("ads_filters_alias_desc_" . $view["ads_filters_alias_desc"]) ]); }else{ echo removeSlash($view["ads_filters_alias_desc"]); } ?>" class="form-control">
                                   </div>
                                   <?php } ?>

                                   <?php if( $view["ads_filters_alias_text"] ){ ?>
                                   <div class="form-group">
                                     <label>Дополнительное описание (под объявлениями)</label>
                                     <input type="text" name="form[uni_ads_filters_alias][<?php echo $array_data["iso"]; ?>][<?php echo md5("ads_filters_alias_text_" . $view["ads_filters_alias_text"]) ?>]" value="<?php if( $array_lang[ md5("ads_filters_alias_text_" . $view["ads_filters_alias_text"]) ] ){ echo removeSlash($array_lang[ md5("ads_filters_alias_text_" . $view["ads_filters_alias_text"]) ]); }else{ echo removeSlash($view["ads_filters_alias_text"]); } ?>" class="form-control">
                                   </div>
                                   <?php } ?>

                                  <?php }
        
                           }else{
                             ?>
                               <div class="plug" >
                                  <i class="la la-exclamation-triangle"></i>
                                  <p>Нет активных языков</p>
                               </div>                          
                             <?php                              
                           }

                          ?>
                            <p align="right" class="floating-button" >
                              <button class="btn btn-success">Сохранить</button>
                            </p>                          
                          <?php

                        }else{
                          ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Выберите алиас</p>
                            </div>                          
                          <?php
                        }                 
                        ?>
                       
                      </div>
                    </div>

                  </form>

                  </div>
                <?php }elseif($_GET["tab"] == "seo_filters"){ ?>

                  <div class="tab-pane fade show active" id="j-d-tab-11" role="tabpanel" aria-labelledby="vert-tab-11">
                  <form class="form-data" >
                    <div class="row" >

                      <div class="col-lg-3" >
                        <ul class="ul-a-link" >
                          <?php 
                            $get = getAll("SELECT * FROM uni_seo_filters order by seo_filters_id desc");
                            if(count($get)){
                              foreach ($get as $key => $value) {

                                if($_GET["id"] == $value["seo_filters_id"]){
                                   $active = 'class="active"';
                                }else{
                                   $active = '';
                                }

                                 ?>
                                 <li><a <?php echo $active; ?> href="?route=multilanguage&id=<?php echo $value["seo_filters_id"]; ?>&tab=seo_filters"><?php echo $value["seo_filters_title"]; ?></a></li>
                                 <?php
                              }
                            }
                          ?>
                        </ul>
                      </div>

                      <div class="col-lg-9" >

                       <?php
                       if($_GET["id"]){

                          $languages = getAll("SELECT * FROM uni_languages order by id_position asc");     

                           if(count($languages) > 0){   

                                 foreach($languages AS $array_data){

                                    if( file_exists( $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_seo_filters.php" ) ){
                                       $array_lang = require $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_seo_filters.php";
                                    }else{ $array_lang = []; }

                                  ?>

                                  <div class="box-overflow-header" >
                                    <img src="<?php echo Exists($config["media"]["other"],$array_data["image"],$config["media"]["no_image"]); ?>" width="32px" ><br>
                                  <?php
                                    echo $array_data["name"];
                                  ?>
                                  </div>

                                  <?php
                                    $view = findOne("uni_seo_filters", "seo_filters_id=?", [ intval($_GET["id"]) ]);
                                  ?>

                                   
                                   <div class="form-group">
                                     <label>Заголовок (meta title)</label>
                                     <input type="text" name="form[uni_seo_filters][<?php echo $array_data["iso"]; ?>][<?php echo md5("seo_filters_title_" . $view["seo_filters_title"]) ?>]" value="<?php if( $array_lang[ md5("seo_filters_title_" . $view["seo_filters_title"]) ] ){ echo removeSlash($array_lang[ md5("seo_filters_title_" . $view["seo_filters_title"]) ]); }else{ echo removeSlash($view["seo_filters_title"]); } ?>" class="form-control">
                                   </div>
                                   
                                   <div class="form-group">
                                     <label>Заголовок (h1)</label>
                                     <input type="text" name="form[uni_seo_filters][<?php echo $array_data["iso"]; ?>][<?php echo md5("seo_filters_h1_" . $view["seo_filters_h1"]) ?>]" value="<?php if( $array_lang[ md5("seo_filters_h1_" . $view["seo_filters_h1"]) ] ){ echo removeSlash($array_lang[ md5("seo_filters_h1_" . $view["seo_filters_h1"]) ]); }else{ echo removeSlash($view["seo_filters_h1"]); } ?>" class="form-control">
                                   </div>

                                   <?php if($view["seo_filters_desc"]){ ?>
                                   <div class="form-group">
                                     <label>Краткое описание</label>
                                     <textarea class="form-control" name="form[uni_seo_filters][<?php echo $array_data["iso"]; ?>][<?php echo md5("seo_filters_desc_" . urldecode($view["seo_filters_desc"])) ?>]" ><?php if( $array_lang[ md5("seo_filters_desc_" . urldecode($view["seo_filters_desc"])) ] ){ echo removeSlash($array_lang[ md5("seo_filters_desc_" . urldecode($view["seo_filters_desc"])) ]); }else{ echo removeSlash(urldecode($view["seo_filters_desc"])); } ?></textarea>
                                   </div> 
                                   <?php } ?>
                                   <?php if($view["seo_filters_text"]){ ?>
                                   <div class="form-group">
                                     <label>Полное описание</label>
                                     <textarea class="ckeditor" name="form[uni_seo_filters][<?php echo $array_data["iso"]; ?>][<?php echo md5("seo_filters_text_" . urldecode($view["seo_filters_text"])) ?>]" class="form-control" name="text" ><?php if( $array_lang[ md5("seo_filters_text_" . urldecode($view["seo_filters_text"])) ] ){ echo removeSlash($array_lang[ md5("seo_filters_text_" . urldecode($view["seo_filters_text"])) ]); }else{ echo removeSlash(urldecode($view["seo_filters_text"])); } ?></textarea>
                                   </div> 
                                   <?php } ?>   

                                  <?php 
                                }
        
                           }else{
                             ?>
                               <div class="plug" >
                                  <i class="la la-exclamation-triangle"></i>
                                  <p>Нет активных языков</p>
                               </div>                          
                             <?php                              
                           }

                          ?>
                            <p align="right" class="floating-button" >
                              <button class="btn btn-success">Сохранить</button>
                            </p>                          
                          <?php

                        }else{
                          ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Выберите фильтр</p>
                            </div>                          
                          <?php
                        }                 
                        ?>
                       
                      </div>
                    </div>

                  </form>

                  </div>
                <?php }elseif($_GET["tab"] == "promo_slider"){ ?>

                  <div class="tab-pane fade show active" id="j-d-tab-12" role="tabpanel" aria-labelledby="vert-tab-12">
                  <form class="form-data" >
                    <div class="row" >

                      <div class="col-lg-3" >
                        <ul class="ul-a-link" >
                          <?php 
                            $get = getAll("SELECT * FROM uni_sliders order by sliders_id desc");
                            if(count($get)){
                              foreach ($get as $key => $value) {

                                if($_GET["id"] == $value["sliders_id"]){
                                   $active = 'class="active"';
                                }else{
                                   $active = '';
                                }

                                 ?>
                                 <li><a <?php echo $active; ?> href="?route=multilanguage&id=<?php echo $value["sliders_id"]; ?>&tab=promo_slider"><?php echo $value["sliders_title1"]; ?></a></li>
                                 <?php
                              }
                            }
                          ?>
                        </ul>
                      </div>

                      <div class="col-lg-9" >

                       <?php
                       if($_GET["id"]){

                          $languages = getAll("SELECT * FROM uni_languages order by id_position asc");     

                           if(count($languages) > 0){   

                                 foreach($languages AS $array_data){

                                    if( file_exists( $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_sliders.php" ) ){
                                       $array_lang = require $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_sliders.php";
                                    }else{ $array_lang = []; }

                                  ?>

                                  <div class="box-overflow-header" >
                                    <img src="<?php echo Exists($config["media"]["other"],$array_data["image"],$config["media"]["no_image"]); ?>" width="32px" ><br>
                                  <?php
                                    echo $array_data["name"];
                                  ?>
                                  </div>

                                  <?php
                                    $view = findOne("uni_sliders", "sliders_id=?", [ intval($_GET["id"]) ]);
                                  ?>

                                   
                                   <div class="form-group">
                                     <label>Основной заголовок</label>
                                     <input type="text" name="form[uni_sliders][<?php echo $array_data["iso"]; ?>][<?php echo md5("sliders_title1_" . $view["sliders_title1"]) ?>]" value="<?php if( $array_lang[ md5("sliders_title1_" . $view["sliders_title1"]) ] ){ echo removeSlash($array_lang[ md5("sliders_title1_" . $view["sliders_title1"]) ]); }else{ echo removeSlash($view["sliders_title1"]); } ?>" class="form-control">
                                   </div>
                                      
                                   <div class="form-group">
                                     <label>Подзаголовок</label>
                                     <input type="text" name="form[uni_sliders][<?php echo $array_data["iso"]; ?>][<?php echo md5("sliders_title2_" . $view["sliders_title2"]) ?>]" value="<?php if( $array_lang[ md5("sliders_title2_" . $view["sliders_title2"]) ] ){ echo removeSlash($array_lang[ md5("sliders_title2_" . $view["sliders_title2"]) ]); }else{ echo removeSlash($view["sliders_title2"]); } ?>" class="form-control">
                                   </div>                                      

                                  <?php 
                                }
        
                           }else{
                             ?>
                               <div class="plug" >
                                  <i class="la la-exclamation-triangle"></i>
                                  <p>Нет активных языков</p>
                               </div>                          
                             <?php                              
                           }

                          ?>
                            <p align="right" class="floating-button" >
                              <button class="btn btn-success">Сохранить</button>
                            </p>                          
                          <?php

                        }else{
                          ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Выберите слайд</p>
                            </div>                          
                          <?php
                        }                 
                        ?>
                       
                      </div>
                    </div>

                  </form>

                  </div>
                <?php }elseif($_GET["tab"] == "area"){ ?>

                  <div class="tab-pane fade show active" id="j-d-tab-13" role="tabpanel" aria-labelledby="vert-tab-13">
                  <form class="form-data" >
                    <div class="row" >

                      <div class="col-lg-3" >
                        <ul class="ul-a-link" >
                          <?php 
                            $get = getAll("SELECT * FROM uni_city_area order by city_area_id desc");
                            if(count($get)){
                              foreach ($get as $key => $value) {

                                if($_GET["id"] == $value["city_area_id"]){
                                   $active = 'class="active"';
                                }else{
                                   $active = '';
                                }

                                 ?>
                                 <li><a <?php echo $active; ?> href="?route=multilanguage&id=<?php echo $value["city_area_id"]; ?>&tab=area"><?php echo $value["city_area_name"]; ?></a></li>
                                 <?php
                              }
                            }
                          ?>
                        </ul>
                      </div>

                      <div class="col-lg-9" >

                       <?php
                       if($_GET["id"]){

                          $languages = getAll("SELECT * FROM uni_languages order by id_position asc");     

                           if(count($languages) > 0){   

                                 foreach($languages AS $array_data){

                                    if( file_exists( $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_city_area.php" ) ){
                                       $array_lang = require $config["basePath"] . "/lang/" . $array_data["iso"] . "/uni_city_area.php";
                                    }else{ $array_lang = []; }

                                  ?>

                                  <div class="box-overflow-header" >
                                    <img src="<?php echo Exists($config["media"]["other"],$array_data["image"],$config["media"]["no_image"]); ?>" width="32px" ><br>
                                  <?php
                                    echo $array_data["name"];
                                  ?>
                                  </div>

                                  <?php
                                    $view = findOne("uni_city_area", "city_area_id=?", [ intval($_GET["id"]) ]);
                                  ?>

                                   
                                   <div class="form-group">
                                     <label>Название</label>
                                     <input type="text" name="form[uni_city_area][<?php echo $array_data["iso"]; ?>][<?php echo md5("city_area_name_" . $view["city_area_name"]) ?>]" value="<?php if( $array_lang[ md5("city_area_name_" . $view["city_area_name"]) ] ){ echo removeSlash($array_lang[ md5("city_area_name_" . $view["city_area_name"]) ]); }else{ echo removeSlash($view["city_area_name"]); } ?>" class="form-control">
                                   </div>
                                                                            

                                  <?php 
                                }
        
                           }else{
                             ?>
                               <div class="plug" >
                                  <i class="la la-exclamation-triangle"></i>
                                  <p>Нет активных языков</p>
                               </div>                          
                             <?php                              
                           }

                          ?>
                            <p align="right" class="floating-button" >
                              <button class="btn btn-success">Сохранить</button>
                            </p>                          
                          <?php

                        }else{
                          ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Выберите район</p>
                            </div>                          
                          <?php
                        }                 
                        ?>
                       
                      </div>
                    </div>

                  </form>

                  </div>
                <?php } ?>


         </div>
      </div>
   </div>
</div>


<script type="text/javascript" src="include/modules/multilanguage/script.js"></script>
     

