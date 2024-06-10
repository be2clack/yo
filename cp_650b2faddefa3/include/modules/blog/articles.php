<?php 
if( !defined('unisitecms') ) exit;

include("fn.php");

$Geo = new Geo();
$Profile = new Profile();
$Main = new Main();
$Blog = new Blog();

if(empty($_GET["page"])) $_GET["page"] = 1;

$url[] = "route=blog";
       
if($_GET["search"]){

   $_GET["search"] = clearSearch($_GET["search"]);
   $query = "(blog_category_name LIKE '%".$_GET["search"]."%' OR blog_articles_title LIKE '%".$_GET["search"]."%')"; 
   $url[] = 'search='.$_GET["search"]; 

}else{
   if($_GET["cat_id"]){
      $query = "blog_articles_id_cat='".intval($_GET["cat_id"])."'";
      $url[] = 'cat_id='.intval($_GET["cat_id"]);
   }
}

$LINK = "?".implode("&",$url);

$comments = (int)getOne("select count(*) as total from uni_blog_comments")["total"];
?>


<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Блог</h2>
      </div>
   </div>
</div>  

<ul class="nav nav-tabs" role="tablist">
   <li class="nav-item">
      <a class="nav-link <?php if(!$_GET["tab"]){ echo 'active show'; } ?>" data-route="?route=blog" id="just-tab-1" data-toggle="tab" href="#j-tab-1" role="tab" aria-controls="j-tab-1" aria-selected="false">Публикации</a>
   </li>
   <li class="nav-item">
      <a class="nav-link <?php if($_GET["tab"] == "category"){ echo 'active show'; } ?>" data-route="?route=blog&tab=category" id="just-tab-2" data-toggle="tab" href="#j-tab-2" role="tab" aria-controls="j-tab-2" aria-selected="false">Категории</a>
   </li>
   <li class="nav-item">
      <a class="nav-link <?php if($_GET["tab"] == "comments"){ echo 'active show'; } ?>" data-route="?route=blog&tab=comments" id="just-tab-3" data-toggle="tab" href="#j-tab-3" role="tab" aria-controls="j-tab-3" aria-selected="false">Комментарии <?php if($comments){ ?> <span class="badge badge-primary badge-pill"><?php echo $comments; ?></span> <?php } ?> </a>
   </li>                                                                               
</ul>

<div class="tab-content pt-3">
  <div class="tab-pane fade <?php if(!$_GET["tab"]){ echo 'active show'; } ?>" id="j-tab-1" role="tabpanel" aria-labelledby="just-tab-1">

      <div class="row" style="margin-top: 10px;" >
        <div class="col-lg-12" >
          
            <form method="get" action="/<?php echo $config["folder_admin"]; ?>" >
              <input type="text" class="form-control" placeholder="Поиск публикаций" style="height: 44px;" value="<?php echo $_GET["search"]; ?>" name="search">
              <input type="hidden" name="route" value="blog" >
            </form>

        </div>
      </div>

      <div class="form-group" style="margin-top: 25px; margin-bottom: 25px;" >

       <div class="btn-group" >
         <a  href="?route=add_article" class="btn btn-gradient-04">Добавить статью</a>
       </div>

       <div class="btn-group" >
         <div class="dropdown">
          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Категории <?php if($_GET["cat_name"]){ echo "(".$_GET["cat_name"].")"; } ?>
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="max-height: 350px; overflow: auto;" >
             <a class="dropdown-item" href="?route=blog">Все категории</a>
             <?php echo outCategoryDropdown(); ?>
          </div>
         </div>
       </div>

       <div class="action_group_block" style="display: none;" >
         <div class="btn-group" >
           <div class="dropdown">
            <button class="btn btn-gradient-01 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Действия с элементами
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" >
               <a class="dropdown-item action_group" data-action="delete" >Удалить</a>
            </div>
           </div>
         </div>
       </div>

      </div>

      <div class="row" >
         <div class="col-lg-12" >
            <div class="widget has-shadow">

               <div class="widget-body">
                  <div class="table-responsive">
                      
                      <form class="form-data-articles" >
                       <?php

                          $get = $Blog->getAll( array("navigation"=>true,"page"=>$_GET["page"],"output"=>$_SESSION["ByShow"],"query"=>$query, "sort"=>"ORDER By blog_articles_id DESC") );
           
                           if($get["count"]){   

                           ?>
                           <table class="table mb-0">
                              <thead>
                                 <tr>
                                  <th><input type="checkbox" class="checkbox_prop" ></th>
                                  <th>Название</th>
                                  <th>Категория</th>
                                  <th>Просмотров</th>
                                  <th>Опубликовано</th>
                                  <th>Статус</th>
                                  <th style="text-align: right;" ></th>
                                 </tr>
                              </thead>
                              <tbody>                     
                           <?php

                              foreach($get["all"] AS $value){
       
                              ?>

                               <tr>
                                   <td>
                                     <input type="checkbox" class="input_prop_id" value="<?php echo $value["blog_articles_id"]; ?>" name="id[]">
                                   </td>                          
                                   <td>
                                     <div style="max-width: 400px;" >
                                       <a title="<?php echo $value["blog_articles_title"]; ?>" href="<?php echo $Blog->aliasArticle($value); ?>" target="_blank" ><?php echo $value["blog_articles_title"]; ?></a>
                                     </div>
                                   </td>
                                   <td><?php echo $value["blog_category_name"]; ?></td>
                                   <td><?php echo $value["blog_articles_count_view"]; ?></td>
                                   <td><?php echo datetime_format_cp($value["blog_articles_date_add"]); ?></td>
                                   <td>
                                     <label style="margin-top: 10px;" >
                                       <input class="toggle-checkbox-sm toolbat-toggle toggle-status" <?php if($value["blog_articles_visible"]){ echo 'checked=""'; } ?> type="checkbox" value="1" data-id="<?php echo $value["blog_articles_id"]; ?>" >
                                       <span> <span></span> </span>
                                     </label>                                     
                                   </td> 
                                   <td class="td-actions" style="text-align: right;" >
                                    <a href="?route=edit_article&id=<?php echo $value["blog_articles_id"]; ?>"><i class="la la-edit edit"></i></a>
                                    <a href="#" class="delete-article" data-id="<?php echo $value["blog_articles_id"]; ?>" ><i class="la la-close delete"></i></a>
                                   </td>                          
                               </tr> 
                       
                             
                               <?php                                         
                              } 

                              ?>

                                 </tbody>
                              </table>

                              <?php               
                           }else{
                               
                               ?>
                                  <div class="plug" >
                                     <i class="la la-exclamation-triangle"></i>
                                     <p>Публикаций нет</p>
                                  </div>
                               <?php

                           }                  
                        ?>

                      </form>

                  </div>
               </div>
            </div>
         </div>
      </div>


      <ul class="pagination">  
       <?php echo out_navigation( array("count"=>$get["count"], "output"=>$_SESSION["ByShow"], "url"=>$LINK, "prev"=>'<i class="la la-long-arrow-left"></i>', "next"=>'<i class="la la-arrow-right"></i>', "page_count" => $_GET["page"], "page_variable" => "page") );?>
      </ul>

  </div>
  <div class="tab-pane fade <?php if($_GET["tab"] == "category"){ echo 'active show'; } ?>" id="j-tab-2" role="tabpanel" aria-labelledby="just-tab-2">

      <div class="form-group" style="margin-top: 10px; margin-bottom: 25px;" >

       <div class="btn-group" >
         <a  href="?route=add_category_blog" class="btn btn-gradient-04">Добавить категорию</a>
       </div>

      </div>

      <div class="row" >
         <div class="col-lg-12" >
            <div class="widget has-shadow">

               <div class="widget-body">

                   <?php
                   if($getCategories["blog_category_id_parent"]){
                      ?>
                       <table class="table mb-0">
                          <thead>
                             <tr>
                              <th>Название</th>
                              <th style="text-align: right;" ></th>
                             </tr>
                          </thead>
                          <tbody class="sort-container" >

                             <?php echo outCategory(); ?>

                          </tbody>
                      </table>                      
                      <?php
                   }else{
                      ?>
                      <div class="plug" >
                         <i class="la la-exclamation-triangle"></i>
                         <p>Категорий нет</p>
                      </div>                              
                      <?php
                   }
                   ?>

               </div>

            </div>
         </div>
      </div>


  </div> 
<div class="tab-pane fade <?php if($_GET["tab"] == "comments"){ echo 'active show'; } ?>" id="j-tab-3" role="tabpanel" aria-labelledby="just-tab-3">


      <div class="form-group" style="margin-top: 25px; margin-bottom: 25px;" >

       <div class="action_group_block" style="display: none;" >
         <div class="btn-group" >
           <div class="dropdown">
            <button class="btn btn-gradient-01 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Действия с элементами
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" >
               <a class="dropdown-item action_group" data-action="delete" >Удалить</a>
            </div>
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

                          $get = getAll("select * from uni_blog_comments INNER JOIN `uni_blog_articles` ON `uni_blog_articles`.blog_articles_id = `uni_blog_comments`.blog_comments_id_article INNER JOIN `uni_blog_category` ON `uni_blog_category`.blog_category_id = `uni_blog_articles`.blog_articles_id_cat INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_blog_comments`.blog_comments_id_user order by blog_comments_id desc");

                           if($get){   

                           ?>
                           <table class="table mb-0">
                              <thead>
                                 <tr>
                                  <th>Комментарий</th>
                                  <th>Автор</th>
                                  <th>Опубликовано</th>
                                  <th style="text-align: right;" ></th>
                                 </tr>
                              </thead>
                              <tbody>                     
                           <?php

                              foreach($get AS $value){
       
                              ?>

                               <tr>                         
                                   <td>
                                     <div style="max-width: 550px;" >
                                       <a target="_blank" href="<?php echo $Blog->aliasArticle($value); ?>"><strong><?php echo $value["blog_articles_title"]; ?></strong></a> <br>
                                       <div style="margin-top: 10px;" ><?php echo $value["blog_comments_text"]; ?></div>
                                     </div>
                                   </td>
                                   <td><a href="?route=client_view&id=<?php echo $value["blog_comments_id_user"]; ?>"><?php echo $Profile->name($value); ?></a></td>
                                   <td><?php echo datetime_format_cp($value["blog_comments_date"]); ?></td> 
                                   <td class="td-actions" style="text-align: right;" >
                                    <a href="#" class="delete-comment" data-id="<?php echo $value["blog_comments_id"]; ?>" ><i class="la la-close delete"></i></a>
                                   </td>                          
                               </tr> 
                       
                             
                               <?php                                         
                              } 

                              ?>

                                 </tbody>
                              </table>

                              <?php               
                           }else{
                               
                               ?>
                                  <div class="plug" >
                                     <i class="la la-exclamation-triangle"></i>
                                     <p>Комментариев нет</p>
                                  </div>
                               <?php

                           }                  
                        ?>

                  </div>
               </div>
            </div>
         </div>
      </div>


      <ul class="pagination">  
       <?php echo out_navigation( array("count"=>$get["count"], "output"=>$_SESSION["ByShow"], "url"=>"?route=blog&tab=comments", "prev"=>'<i class="la la-long-arrow-left"></i>', "next"=>'<i class="la la-arrow-right"></i>', "page_count" => $_GET["page_c"], "page_variable" => "page_c") );?>
      </ul>

  </div>   
</div>


<script type="text/javascript" src="include/modules/blog/script.js"></script>     
