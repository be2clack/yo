<?php
if( !defined('unisitecms') ) exit;

$findOne = findOne("uni_country", "country_id=?", array( $id ));

$get = getAll("SELECT * FROM uni_city INNER JOIN `uni_region` ON `uni_region`.region_id = `uni_city`.region_id WHERE `uni_city`.country_id='$id' order by `uni_city`.city_name asc"); 
if(count($get) > 0){
  foreach ($get as $key => $value) {
     $list_city .= '<option value="'.$value["city_id"].'" >'.$value["city_name"].', '.$value["region_name"].'</option>';
  }
}

?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title"><?php echo $findOne->country_name; ?></h2>
      </div>
   </div>
</div>


<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">


            <ul class="nav nav-tabs nav-fill" role="tablist">
                     <li class="nav-item">
                        <a class="nav-link active" id="just-tab-1" data-toggle="tab" href="#panel1" role="tab" aria-controls="j-tab-1" aria-selected="true">Регионы</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" id="just-tab-2" data-toggle="tab" href="#panel2" role="tab" aria-controls="j-tab-2" aria-selected="false">Города</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" id="just-tab-3" data-toggle="tab" href="#panel3" role="tab" aria-controls="j-tab-3" aria-selected="false">Метро</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" id="just-tab-4" data-toggle="tab" href="#panel4" role="tab" aria-controls="j-tab-4" aria-selected="false">Районы</a>
                     </li>                                               
                  </ul>

                  <div class="tab-content pt-3">
                    <div id="panel1" class="tab-pane fade show active" role="tabpanel" aria-labelledby="just-tab-1">


                      <br>

                        <div class="btn-group-class"> 
                          
                            <div class="btn-group">
                              <button type="button" class="btn btn-info modal_country_add_region" country_id="<?php echo $id; ?>" ><i class="la la-plus"></i> Добавить регион</button>
                            </div>            
                                            
                        </div>

                      <br>
                      
                      <div class="cont-country-region" >
                      <div class="table-responsive">  
                       
                      <?php            
                       $get = getAll("SELECT * FROM uni_region WHERE country_id=? order by region_name asc", array($id) );
                       
                        if (count($get) > 0)
                        { 
                                             
                             ?>

                            <table class="table mb-0">
                            <thead>
                                 <tr>
                                  <th>Название</th>
                                  <th style="text-align: right;" ></th>
                                 </tr>
                             </thead>
                             <tbody>

                             <?php       
                          
                             foreach($get AS $array_data){ 

                                ?>

                                 <tr>
                                 
                                     <td><span><?php echo $array_data["region_name"]; ?></span></td>
                                     <td style="text-align: right;" class="td-actions" >
                                      <a data-id="<?php echo $array_data["region_id"]; ?>" class="edit_item_region"><i class="la la-edit edit"></i></a>
                                      <a data-id="<?php echo $array_data["region_id"]; ?>" country_id="<?php echo $id; ?>" class="delete_item_region"><i class="la la-close delete"></i></a>
                                    </td>
                                       
                                 </tr>

                                <?php

                            }

                            ?>
                            </tbody></table>
                            <?php
                             
                        } else
                        {
                           ?>
                              <div class="plug" >
                                 <i class="la la-exclamation-triangle"></i>
                                 <p>Регионов нет</p>
                              </div>
                           <?php
                        }

                          
                        ?>
                            
                     </div>
                    </div>


                    </div>
                    <div id="panel2" class="tab-pane fade" role="tabpanel" aria-labelledby="just-tab-2">
                      
                      <br>

                          <select name="region_id" class="form-control change_region_id" style="width: 100%;" ><option value="null" >Выберите регион</option>
                             <?php 
                                $get = getAll("SELECT * FROM uni_region WHERE country_id='$id' order by region_name asc"); 
                                if(count($get) > 0){
                                  foreach ($get as $key => $value) {
                                     ?>
                                     <option value="<?php echo $value["region_id"]; ?>" ><?php echo $value["region_name"]; ?></option>
                                     <?php
                                  }
                                }
                             ?>
                          </select>

                        <br>

                        <div class="btn-group-class"> 
                          
                            <div class="btn-group">
                              <button class="btn btn-info modal_country_add_city" ><i class="la la-plus"></i> Добавить город</button>
                            </div>            


                             <div class="btn-group" >
                             <div class="dropdown">

                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <i class="la la-search"></i>  Поиск
                                </button>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                   <div class="city-input-search" style="padding: 0 10px;" ><input type="text" style="min-width: 300px;" placeholder="Укажите название города" class="form-control"></div>
                                </div>

                            </div>
                            </div>

                                            
                        </div>

                        <br>

                        <div class="cont-country-city" >      
                        </div>


                    </div>
                    <div id="panel3" class="tab-pane fade" role="tabpanel" aria-labelledby="just-tab-3">
                      
                      <br>

                          <select class="form-control metro_change_city_id" style="width: 100%;" ><option value="null" >Выберите город</option>
                             <?php echo $list_city; ?>
                          </select>

                        <br>

                        <div class="btn-group-class"> 
                          
                            <div class="btn-group">
                              <button type="button" class="btn btn-info modal_metro_add_city" ><i class="la la-plus"></i> Добавить ветку</button>
                            </div>            
                           
                        </div>

                        <br>

                        <div class="cont-metro-city" ></div>

                    </div>
                    <div id="panel4" class="tab-pane fade" role="tabpanel" aria-labelledby="just-tab-4">
                      
                      <br>

                          <select class="form-control area_change_city_id" style="width: 100%;" ><option value="null" >Выберите город</option>
                             <?php echo $list_city; ?>
                          </select>

                        <br>

                        <div class="btn-group-class"> 
                          
                            <div class="btn-group">
                              <button type="button" class="btn btn-info modal_area_add_city" ><i class="la la-plus"></i> Добавить район </button>
                            </div>            
                           
                        </div>

                        <br>

                        <div class="cont-area-city" ></div>

                    </div>
                
                  </div>


         </div>

      </div>
   </div>
</div>


<script type="text/javascript" src="include/modules/cities/script.js"></script> 


<div id="modal_add_metro" class="modal fade">
   <div class="modal-dialog"  style="max-width: 600px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Добавление метро</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">

               <form method="post" id="form-data-metro-add" >

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Цвет ветки</label>
                  <div class="col-lg-9">
                       <input type="text" class="form-control" value="" name="color" >
                       <small>Например red, #5d5386 и.т п</small>
                  </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                  <label class="col-lg-3 form-control-label">Название</label>
                  <div class="col-lg-9">
                       <input type="text" class="form-control" value="" name="name" >
                  </div>
                </div>

               </form>
           
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary button_metro_add">Добавить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal_edit_metro" class="modal fade">
   <div class="modal-dialog" style="max-width: 600px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Редактирование ветки</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body"></div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary button_metro_edit">Сохранить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal_edit_region" class="modal fade">
   <div class="modal-dialog" style="max-width: 600px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Редактирование региона</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body"></div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary button_region_edit">Сохранить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal_edit_city" class="modal fade">
   <div class="modal-dialog" style="max-width: 600px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Редактирование города</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body"></div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary button_city_edit">Сохранить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal_add_city" class="modal fade">
   <div class="modal-dialog" style="max-width: 600px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Добавление города</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">

            <form id="form-data-city-add" >

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Название</label>
                <div class="col-lg-9">
                     <input type="text" class="form-control setTranslate" name="name" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Алиас</label>
                <div class="col-lg-9">
                     <input type="text" class="form-control outTranslate" name="alias" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Склонение</label>
                <div class="col-lg-9">
                     <input type="text" class="form-control" name="declination" >
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Описание</label>
                <div class="col-lg-9">
                     <textarea name="desc" class="form-control" ></textarea>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Широта (latitude)</label>
                <div class="col-lg-9">
                     <input name="lat" class="form-control" ></input>
                </div>
              </div>

              <div class="form-group row d-flex align-items-center mb-5">
                <label class="col-lg-3 form-control-label">Долгота (longitude)</label>
                <div class="col-lg-9">
                     <input name="lng" class="form-control" ></input>
                </div>
              </div>

              <input type="hidden" name="region_id" value="0" >
              <input type="hidden" name="country_id" value="<?php echo $id; ?>" >
                                                             
            </form>  

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary button_city_add">Добавить</button>
         </div>
      </div>
   </div>
</div>