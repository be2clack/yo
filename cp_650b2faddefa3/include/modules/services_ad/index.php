<?php 
if( !defined('unisitecms') ) exit;

$LINK = '?route=services_ad';   
$_GET["page"] = empty($_GET["page"]) ? 1 : intval($_GET["page"]);

$Main = new Main();
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Тарифы и услуги</h2>
      </div>
   </div>
</div>  


<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">
         <div class="widget-body">

            <ul class="nav nav-tabs nav-fill" role="tablist">
              <li class="nav-item">
                 <a class="nav-link <?php if(!$_GET["tab"]){ echo 'show active'; } ?>" id="just-tab-1" data-toggle="tab" href="#j-tab-1" role="tab" aria-controls="j-tab-1" aria-selected="false">Тарифы</a>
              </li>
              <li class="nav-item">
                 <a class="nav-link <?php if($_GET["tab"] == "category"){ echo 'show active'; } ?>" id="just-tab-2" data-toggle="tab" href="#j-tab-2" role="tab" aria-controls="j-tab-2" aria-selected="false">Услуги</a>
              </li>                                                                             
            </ul>

            <div class="tab-content pt-4">
                <div class="tab-pane fade <?php if(!$_GET["tab"]){ echo 'show active'; } ?>" id="j-tab-1" role="tabpanel" aria-labelledby="just-tab-1">

                     <div class="form-group">
                        <a href="#modal-tariff-add" data-toggle="modal" class="btn btn-gradient-04 mr-1 mb-2"><i class="la la-plus"></i> Добавить тариф</a>
                        <a href="#modal-tariff-services" data-toggle="modal" class="btn btn-secondary mb-2">Услуги тарифа</a>
                     </div>

                     <div class="table-responsive">

                          <?php
                             $get = getAll("SELECT * FROM uni_services_tariffs order by services_tariffs_position asc");     

                              if(count($get) > 0){   

                              ?>
                              <table class="table mb-0">
                                 <thead>
                                    <tr>
                                     <th></th>
                                     <th>Название</th>
                                     <th>Цена</th>
                                     <th>Срок</th>
                                     <th>Бонус</th>
                                     <th>Используют</th>
                                     <th>Статус</th>
                                     <th style="text-align: right;" ></th>
                                    </tr>
                                 </thead>
                                 <tbody class="sort-container-tariff" >                     
                                       <?php
                                       foreach($get AS $value){

                                       $getTariffActive = getOne('select count(*) as total from uni_services_tariffs_orders where services_tariffs_orders_id_tariff=? and (services_tariffs_orders_date_completion > now() or services_tariffs_orders_days=?)', [$value["services_tariffs_id"],0]);
                                       ?>

                                        <tr id="item<?php echo $value["services_tariffs_id"]; ?>">
                                            <td><span class="icon-move move-sort-tariff" ><i class="la la-arrows-v"></i></span></td>
                                            <td><?php echo $value["services_tariffs_name"]; ?></td>
                                            <td>
                                              <?php echo $Main->outPrices( array("new_price"=> array("price"=>$value["services_tariffs_new_price"], "tpl"=>'<span class="span_newPrice" >{price}</span>'), "price"=>array("price"=>$value["services_tariffs_price"], "tpl"=>'<span class="span_oldPrice" >{price}</span>') ) ); ?>
                                            </td>
                                            <td><?php echo $value["services_tariffs_days"] ?: 'Неограничен'; ?></td>
                                            <td><?php echo $Main->price($value["services_tariffs_bonus"]); ?></td>
                                            <td><?php echo (int)$getTariffActive['total']; ?></td>
                                            <td><?php if($value["services_tariffs_status"]){ echo 'Активен'; }else{ echo 'Не активен'; } ?></td>
                                            <td class="td-actions" style="text-align: right;" >
                                             <a class="load_edit_tariff" data-id="<?php echo $value["services_tariffs_id"]; ?>" ><i class="la la-edit edit"></i></a>
                                             <a href="#" class="delete_tariff" data-id="<?php echo $value["services_tariffs_id"]; ?>" ><i class="la la-close delete"></i></a>
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
                                        <p>Тарифов нет</p>
                                     </div>
                                  <?php
                              }                  
                           ?>

                     </div>

                </div>
                <div class="tab-pane fade <?php if($_GET["tab"] == 'services'){ echo 'show active'; } ?>" id="j-tab-2" role="tabpanel" aria-labelledby="just-tab-2">

                     <div class="table-responsive">

                          <?php
                             $get = getAll("SELECT * FROM uni_services_ads order by services_ads_id_position asc");     

                              if(count($get) > 0){   

                              ?>
                              <table class="table mb-0">
                                 <thead>
                                    <tr>
                                     <th></th>
                                     <th>Название</th>
                                     <th>Цена</th>
                                     <th style="text-align: right;" ></th>
                                    </tr>
                                 </thead>
                                 <tbody class="sort-container" >                     
                              <?php

                                 foreach($get AS $value){

                                 ?>

                                  <tr id="item<?php echo $value["services_ads_id"]; ?>" >
                                      <td><span class="icon-move move-sort" ><i class="la la-arrows-v"></i></span></td>
                                      <td>

                                       <?php echo $value["services_ads_name"]; ?>
                                         
                                      </td>

                                      <td>

                                        <?php echo $Main->outPrices( array("new_price"=> array("price"=>$value["services_ads_new_price"], "tpl"=>'<span class="span_newPrice" >{price}</span>'), "price"=>array("price"=>$value["services_ads_price"], "tpl"=>'<span class="span_oldPrice" >{price}</span>') ) ); ?>

                                      </td>

                                      <td class="td-actions" style="text-align: right;" >
                                       <a href="#" class="load_edit_services" data-id="<?php echo $value["services_ads_id"]; ?>" ><i class="la la-edit edit"></i></a>
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
                                        <p>Услуг нет</p>
                                     </div>
                                  <?php

                              }                  
                           ?>

                     </div>
                   
                </div>                
            </div>

         </div>
      </div>
   </div>
</div>

<div id="modal-services-edit" class="modal fade">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Редактирование услуги</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-services-edit" >
               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary board-edit-services">Сохранить</button>
         </div>
      </div>
   </div>
</div>
  
<div id="modal-tariff-edit" class="modal fade">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Редактирование тарифа</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-tariff-edit" >
               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary board-edit-tariff">Сохранить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-tariff-services" class="modal fade">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Услуги тарифа</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-tariff-services-edit" >

                  <?php
                     $getTariffServices = getAll('select * from uni_services_tariffs_checklist');
                     if(count($getTariffServices)){
                         foreach ($getTariffServices as $value) {
                            ?>

                              <p><strong><?php echo $value['services_tariffs_checklist_alias']; ?></strong></p>

                              <div class="form-group row d-flex align-items-center mb-5">
                                <label class="col-lg-4 form-control-label">Название</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" value="<?php echo $value['services_tariffs_checklist_name']; ?>" name="service[<?php echo $value['services_tariffs_checklist_id']; ?>][name]" >
                                </div>
                              </div>

                              <div class="form-group row d-flex align-items-center mb-5">
                                <label class="col-lg-4 form-control-label">Краткое описание</label>
                                <div class="col-lg-8">
                                    <textarea class="form-control" name="service[<?php echo $value['services_tariffs_checklist_id']; ?>][desc]" ><?php echo $value['services_tariffs_checklist_desc']; ?></textarea>
                                </div>
                              </div>

                              <hr>

                            <?php
                         }
                     }
                  ?>                  

               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary board-edit-tariff-services">Сохранить</button>
         </div>
      </div>
   </div>
</div>

<div id="modal-tariff-add" class="modal fade">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Добавление тарифа</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-tariff-add" >

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Статус</label>
                    <div class="col-lg-6">
                        <label>
                          <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="status" checked="" value="1" >
                          <span><span></span></span>
                        </label>
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Единоразовое использование</label>
                    <div class="col-lg-6">
                        <label>
                          <input class="toggle-checkbox-sm toolbat-toggle" type="checkbox" name="onetime" value="1" >
                          <span><span></span></span>
                        </label>
                        <div><small>Пользователь сможет подключить тариф только 1 раз</small></div>
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Название</label>
                    <div class="col-lg-8">
                         <input type="text" class="form-control" value="" name="name" >
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Цена</label>
                    <div class="col-lg-3">
                         <input type="number" class="form-control" value="" name="price" >
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Новая цена</label>
                    <div class="col-lg-3">
                         <input type="number" class="form-control" value="" name="new_price" >
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Срок действия (дней)</label>
                    <div class="col-lg-3">
                         <input type="number" class="form-control" value="30" name="count_day" >
                         <small>Оставьте это поле пустым если срок неограничен</small>
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Бонус</label>
                    <div class="col-lg-3">
                         <input type="number" class="form-control" value="" name="bonus" >
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Краткое описание</label>
                    <div class="col-lg-8">
                         <textarea class="form-control" name="desc" ></textarea>
                    </div>
                  </div>

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-4 form-control-label">Услуги тарифа</label>
                    <div class="col-lg-8">
                         <div class="services-tariffs-box" >

                           <?php
                              $getTariffServices = getAll('select * from uni_services_tariffs_checklist');
                              if(count($getTariffServices)){
                                  foreach ($getTariffServices as $value) {
                                     ?>

                                       <div class="custom-control custom-checkbox">
                                           <input type="checkbox" class="custom-control-input" name="services[]" value="<?php echo $value['services_tariffs_checklist_id']; ?>" id="add_services_checkbox<?php echo $value['services_tariffs_checklist_id']; ?>">
                                           <label class="custom-control-label" for="add_services_checkbox<?php echo $value['services_tariffs_checklist_id']; ?>"><?php echo $value['services_tariffs_checklist_name']; ?></label>
                                       </div>

                                     <?php
                                  }
                              }
                           ?>

                         </div>
                    </div>
                  </div>

               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary board-add-tariff">Добавить</button>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript" src="include/modules/services_ad/script.js"></script>