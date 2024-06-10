<?php
if( !defined('unisitecms') ) exit;

if(empty($_GET["page"])) $_GET["page"] = 1;

$query = "";

if(!empty($_GET["search"])){
   $query = " WHERE country_name LIKE '%".clearSearch($_GET["search"])."%'"; 
   $LINK = '?route=cities&search='.$_GET["search"]; 
}else{
   $LINK = '?route=cities'; 
}

?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Страны (<?php echo getCount("uni_country"); ?>)</h2>
      </div>
   </div>
</div>

<div class="form-group" style="margin-bottom: 25px;" >

 <div class="btn-group" >  
 <a href="#modal_country_add" data-toggle="modal" class="btn btn-gradient-04">Добавить страну</a>
 </div>

 <div class="btn-group" >
 <div class="dropdown">

    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Поиск <?php if(!empty($_GET["search"])){ echo '('.$_GET["search"].')'; } ?>
    </button>

    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <form method="get" style="padding: 0 10px;" action="/<?php echo $config["folder_admin"]; ?>" >
        <input type="text" class="form-control" style="width: 300px;" placeholder="Укажите название страны" value="<?php if(!empty($_GET["search"])){ echo $_GET["search"]; } ?>" name="search">
        <input type="hidden" name="route" value="cities" >
      </form>
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
                    $count = (int)getOne("SELECT count(*) as total FROM uni_country $query")["total"];
                    
                    $get = getAll("select *, (select count(*) from uni_region where country_id = uni_country.country_id) as count_region, (select count(*) from uni_city where country_id = uni_country.country_id) as count_city from uni_country $query order by country_status DESC, country_name ASC ".navigation_offset( array( "count"=>$count, "output"=>$_SESSION["ByShow"], "page"=>$_GET["page"] ) ));   

                     if(count($get) > 0){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Страна</th>
                            <th>Регионов</th>
                            <th>Городов</th>
                            <th>Статус</th>
                            <th style="text-align: right;" ></th>
                           </tr>
                        </thead>
                        <tbody class="sort-container" >                     
                     <?php

                        foreach($get AS $array_data){
 
                        ?>

                         <tr >
                           <td><a href="?route=view_country&id=<?php echo $array_data["country_id"]; ?>" ><?php echo $array_data["country_name"]; ?></a></td>
                           <td><?php echo $array_data["count_region"]; ?></td>
                           <td><?php echo $array_data["count_city"]; ?></td>
                             <td>
                               <?php if($array_data["country_status"]){ ?>
                                <span class="badge-text badge-text-small info">Активен</span>
                               <?php }else{ ?>
                                <span class="badge-text badge-text-small danger">Не активен</span>
                               <?php } ?>
                             </td> 
                             <td class="td-actions" style="text-align: right;" >
                              <a href="?route=view_country&id=<?php echo $array_data["country_id"]; ?>" ><i class="la la-map edit"></i></a>
                              <a href="#modal_country_edit" class="load_edit_country" data-id="<?php echo $array_data["country_id"]; ?>" data-toggle="modal"><i class="la la-edit edit"></i></a>
                              <a href="#" class="delete-country" data-id="<?php echo $array_data["country_id"]; ?>" ><i class="la la-close delete"></i></a>
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
                               <p>Стран нет</p>
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
 <?php echo out_navigation( array("count"=>$count, "output"=>$_SESSION["ByShow"], "url"=>$LINK, "prev"=>'<i class="la la-long-arrow-left"></i>', "next"=>'<i class="la la-arrow-right"></i>', "page_count" => $_GET["page"], "page_variable" => "page") );?>
</ul>


<script type="text/javascript" src="include/modules/cities/script.js"></script> 


<div id="modal_country_add" class="modal fade">
   <div class="modal-dialog" style="max-width: 650px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Добавить страну</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
            <form method="post" id="form-data-country-add" >

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Статус видимости</label>
                  <div class="col-lg-9">
                      <label>
                        <input class="toggle-checkbox-sm" type="checkbox" name="status" checked="" value="1" >
                        <span><span></span></span>
                      </label>
                  </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Изображение</label>
                  <div class="col-lg-9">
                      <div class="small-image-container" >
                        <span class="small-image-delete" style="display: none;" > <i class="la la-trash"></i> </span>

                        <img class="change-img" src="<?php echo $config["urlPath"] . "/" . $config["media"]["other"]; ?>/icon_photo_add.png" width="50px" >

                        <input type="hidden" name="image_delete" value="0" >
                      </div>

                      <input type="file" name="image" class="input-img" >
                  </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Название</label>
                  <div class="col-lg-9">
                       <input type="text" class="form-control setTranslate" value="" name="country" >
                  </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Склонение</label>
                  <div class="col-lg-9">
                       <input type="text" class="form-control" value="" name="declination" >
                  </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Алиас</label>
                  <div class="col-lg-9">
                       <input type="text" class="form-control outTranslate" value="" name="alias" >
                  </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Описание</label>
                  <div class="col-lg-9">
                       <textarea name="desc" class="form-control" ></textarea>
                  </div>
                </div>

                <hr>

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Телефонный код страны</label>
                  <div class="col-lg-9">
                       <select class="selectpicker" name="code_phone" data-live-search="true" >
                          <option value="" >Без кода</option>
                          <?php
                              if($config['format_phone']){
                                 foreach ($config['format_phone'] as $value) {
                                     ?>
                                     <option value="<?php echo $value['iso']; ?>" ><?php echo $value['country_ru']; ?>, <?php echo $value['code']; ?></option>
                                     <?php
                                 }
                              }
                          ?>
                       </select>
                  </div>
                </div>

                <div class="country-format-phone-input" >
                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Формат номера телефона</label>
                  <div class="col-lg-9">
                       <input type="text" class="form-control" value="" name="format_phone" >
                       <small>Пример формата: +7(___) ___ ____</small>
                  </div>
                </div>
                </div>

                <hr>

                <div class="alert alert-primary alert-dissmissible fade show" style="margin-top: 10px; margin-bottom: 10px;">Укажите широту и долготу столицы страны</div>

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Широта</label>
                  <div class="col-lg-9">
                       <input type="text" class="form-control" value="" name="lat" >
                  </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Долгота</label>
                  <div class="col-lg-9">
                       <input type="text" class="form-control" value="" name="lng" >
                  </div>
                </div>

            </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary button_country_add">Добавить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal_country_edit" class="modal fade">
   <div class="modal-dialog" style="max-width: 650px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Редактирование</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body modal-country-edit"></div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary button_country_edit">Сохранить</button>
         </div>
      </div>
   </div>
</div>