<?php 
if( !defined('unisitecms') ) exit;

$Geo = new Geo();
$Filters = new Filters();

if(empty($_GET["page"])) $_GET["page"] = 1;

$url[] = "route=search";
           
if($_GET['sort'] == 1){
    $query = "order by ads_keywords_users_count_view desc"; 
    $sort_name = "По запросам";  
    $url[] = 'sort='.$_GET['sort'];
}else{
    $query = "order by ads_keywords_users_id desc";
}
           
$LINK = "?".implode("&",$url);
?>

<div class="row">
   <div class="page-header">
      <h2 class="page-header-title">Поиск</h2>
   </div>
</div>

<div class="form-group" style="margin-bottom: 25px;" >

<div class="btn-group" >
   <div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Сортировать <?php if(!empty($sort_name)){ echo "(".$sort_name.")"; } ?>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <a class="dropdown-item" href="?route=search">Без сортировки</a>
      <a class="dropdown-item" href="?route=search&sort=1">По запросам</a>
    </div>
   </div>
 </div>
</div>

<div class="row" >
   <div class="col-lg-12" >
      
      <div class="widget has-shadow">

         <div class="widget-body">
             
              <ul class="nav nav-tabs nav-fill" role="tablist">
                 <li class="nav-item">
                    <a class="nav-link <?php if(!$_GET["tab"]){ echo 'show active'; } ?>" id="just-tab-1" data-toggle="tab" href="#j-tab-1" role="tab" aria-controls="j-tab-1" aria-selected="false">Запросы</a>
                 </li>
                 <li class="nav-item">
                    <a class="nav-link <?php if($_GET["tab"] == "category"){ echo 'show active'; } ?>" id="just-tab-2" data-toggle="tab" href="#j-tab-2" role="tab" aria-controls="j-tab-2" aria-selected="false">Настройка ключевых фраз</a>
                 </li>                                                                             
              </ul>

              <div class="tab-content pt-4">
                <div class="tab-pane fade <?php if(!$_GET["tab"]){ echo 'show active'; } ?>" id="j-tab-1" role="tabpanel" aria-labelledby="just-tab-1">

                 <?php

                    $count = getOne("SELECT count(*) as total FROM uni_ads_keywords_users")["total"];

                    $get = getAll("SELECT * FROM uni_ads_keywords_users $query ".navigation_offset( array( "count"=>$count, "output"=>$_SESSION["ByShow"], "page"=>$_GET["page"] ) ));     

                     if(count($get) > 0){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Фраза</th>
                            <th>Расположение</th>
                            <th>Кол-во запросов</th>
                           </tr>
                        </thead>
                        <tbody>                     
                            <?php
                            foreach($get AS $value){
                            ?>
                                 <tr>                                               
                                     <td>
                                      <?php echo $value['ads_keywords_users_text']; ?>
                                     </td>
                                     <td>
                                      <?php echo $Geo->userGeo( ["ip"=>$value["ads_keywords_users_ip"]] ); ?>
                                     </td>                                 
                                     <td>
                                       <?php echo $value["ads_keywords_users_count_view"]; ?>
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
                               <p>Фраз пока нет</p>
                            </div>
                         <?php

                     }                  
                  ?>

                </div>
                <div class="tab-pane fade <?php if($_GET["tab"] == 'category'){ echo 'show active'; } ?>" id="j-tab-2" role="tabpanel" aria-labelledby="just-tab-2">

                   <div class="row" >
                       <div class="col-lg-3" >
                            
                            <ul class="ul-a-link" >
                            <?php
                            $getCategories = getAll('select * from uni_category_board where category_board_visible=? order by category_board_id_position asc', [1]);
                            if(count($getCategories)){
                                foreach ($getCategories as $key => $value) {

                                    if($_GET["id"] == $value["category_board_id"]){
                                       $active = 'class="active"';
                                    }else{
                                       $active = '';
                                    }

                                    ?>
                                    <li><a <?php echo $active; ?> href="?route=search&id=<?php echo $value["category_board_id"]; ?>&tab=category" ><?php echo $value['category_board_name']; ?></a></li>
                                    <?php
                                }
                            }
                            ?>
                            </ul>

                       </div>
                       <div class="col-lg-9" >

                            <form class="form-data" >
                            <?php
                            if($_GET["id"]){

                            $getKeywords = getAll("select * from uni_ads_keywords where ads_keywords_id_cat=?", [intval($_GET["id"])]);

                            $category = findOne("uni_category_board", "category_board_id=?", [intval($_GET["id"])]);
                            ?>

                            <h3 style="margin-bottom: 25px" ><strong><?php echo $category['category_board_name']; ?></strong></h3>

                            <div class="alert alert-primary" role="alert">Укажите ключевые слова которые будут участвовать в формировании поиска.</div>

                             <div class="text-right" style="margin-bottom: 15px" >
                                 <div class="btn btn-outline-secondary" data-toggle="modal" data-target="#modal-list-filters" >Макросы фильтров</div>
                                 <div class="btn btn-outline-secondary item-search-add"><i class="la la-plus"></i> Добавить</div>
                             </div>
                           
                             <input type="hidden" name="id_cat" value="<?php echo intval($_GET["id"]); ?>" >

                              <div class="item-search-container" >

                                  <?php
                                  if(count($getKeywords)){ 
                                     foreach ($getKeywords as $value) {
                                          ?>
                                          <div class="item-search-gray" >
                                            <div class="item-search-gray-row" >
                                                <div class="item-search-gray-row-flex-1" >
                                                   <div class="item-search-gray-box" >
                                                     <input type="text" class="form-control" name="keywords[edit][<?php echo $value['ads_keywords_id']; ?>][text]" placeholder="Ключевое слово" value="<?php echo $value['ads_keywords_tag']; ?>" >
                                                   </div>
                                                   <div class="item-search-gray-box" >
                                                     <input type="text" class="form-control" name="keywords[edit][<?php echo $value['ads_keywords_id']; ?>][macros]" placeholder="Макросы" value="<?php echo $value['ads_keywords_macros']; ?>" >
                                                   </div>                                            
                                                </div>
                                                <div class="item-search-gray-row-flex-2" >
                                                    <div class="item-search-delete" data-id="<?php echo $value['ads_keywords_id']; ?>" ><i class="la la-trash"></i></div>    
                                                </div>                                        
                                            </div>
                                          </div>
                                          <?php
                                     }
                                  }
                                  ?>

                              </div>

                              <p align="right" class="floating-button" >
                                  <button class="btn btn-success">Сохранить</button>
                              </p>

                            <?php }else{ ?>

                             <div class="plug" >
                                <i class="la la-exclamation-triangle"></i>
                                <p>Выберите категорию</p>
                             </div>

                            <?php } ?>
                            </form>

                       </div>
                   </div>
                   

                </div>                
              </div>
         


   
         </div>

      </div>

   </div>
</div>

<div id="modal-list-filters" class="modal fade">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Фильтры</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
             <?php

                $results = [];
                $getCategoryFilters = $Filters->getCategory( [ "id_cat" => intval($_GET["id"]) ] );
                
                if($getCategoryFilters){

                    $getFilters = getAll( "select * from uni_ads_filters where ads_filters_type!='input' and ads_filters_id IN(".implode(",", $getCategoryFilters).")" );

                    if( count($getFilters) ){
                        foreach ($getFilters as $key => $value) {

                            $getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=?", [$value['ads_filters_id']]);

                            foreach ($getItems as $item_value) {

                                $results[$value['ads_filters_id']][$item_value['ads_filters_items_id_item_parent']][] = $item_value;

                            }

                        }

                        if(count($results)){

                              foreach ($results as $filters_id => $nested) {

                                    $getFilter = findOne('uni_ads_filters', 'ads_filters_id=?', [$filters_id]);

                                    ?>
                                    <div class="modal-filters-macros-box" >
                                      <span class="modal-filters-macros-toggle" ><strong><?php echo $getFilter["ads_filters_name"]; ?> <i class="la la-angle-down" ></i></strong></span>
                                      <div class="modal-filters-macros-items" >
                                      <?php
                                          foreach ($nested as $id_item_parent => $nested2) {

                                              if($id_item_parent){

                                                $getItem = findOne('uni_ads_filters_items', 'ads_filters_items_id=?', [$id_item_parent]);
                                                    
                                                    ?>
                                                    <div class="modal-filters-macros-box" >
                                                      <span class="modal-filters-macros-toggle" ><?php echo $getItem["ads_filters_items_value"]; ?> <i class="la la-angle-down" ></i></span>
                                                      <div class="modal-filters-macros-items" >
                                                         <?php
                                                              foreach ($nested2 as $item_value) {

                                                                  ?>
                                                                  <div><?php echo $item_value['ads_filters_items_value']; ?> <span class="filter-copy" >{<?php echo $item_value['ads_filters_items_id_filter']; ?>:<?php echo $item_value['ads_filters_items_alias']; ?>}</span></div>
                                                                  <?php

                                                              }
                                                         ?>
                                                      </div>
                                                    </div>
                                                    <?php

                                              }else{

                                                  foreach ($nested2 as $item_value) {

                                                      ?>
                                                      <div><?php echo $item_value['ads_filters_items_value']; ?> <span class="filter-copy" >{<?php echo $item_value['ads_filters_items_id_filter']; ?>:<?php echo $item_value['ads_filters_items_alias']; ?>}</span></div>
                                                      <?php

                                                  }

                                              }

                                          }
                                      ?>
                                      </div>
                                    </div>
                                    <?php

                              }

                        }

                    }

                }else{
                  ?>
                  <div>Фильтров нет</div>
                  <?php
                }
                
             ?>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
         </div>
      </div>
   </div>
</div>

<ul class="pagination">  
 <?php echo out_navigation( array("count"=>$count, "output"=>$_SESSION["ByShow"], "url"=>$LINK, "prev"=>'<i class="la la-long-arrow-left"></i>', "next"=>'<i class="la la-arrow-right"></i>', "page_count" => $_GET["page"], "page_variable" => "page") );?>
</ul>

<script type="text/javascript" src="include/modules/search/script.js"></script>