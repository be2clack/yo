<?php 
if( !defined('unisitecms') ) exit;

?>


<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">SEO Фильтры</h2>
      </div>
   </div>
</div>  

<div class="form-group">
<a href="?route=add_seo_filter" class="btn btn-gradient-04 mr-1 mb-2">Добавить фильтр</a>
</div>

<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
            <div class="table-responsive">

                 <?php

                    $get = getAll("SELECT * FROM uni_seo_filters order by seo_filters_id desc");     

                     if(count($get) > 0){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Заголовок</th>
                            <th>Переходов</th>
                            <th style="text-align: right;" ></th>
                           </tr>
                        </thead>
                        <tbody>                     
                     <?php

                        foreach($get AS $array_data){
 
                        ?>

                         <tr>
                             <td><a href="<?php echo _link($array_data["seo_filters_alias"]); ?>" target="_blank" ><?php echo $array_data["seo_filters_name"]; ?></a></td>
                             <td> <?php echo $array_data["seo_filters_count_view"]; ?> </td>
                             <td class="td-actions" style="text-align: right;" >
                              <a href="?route=edit_seo_filter&id=<?php echo $array_data["seo_filters_id"]; ?>"><i class="la la-edit edit"></i></a>
                              <a href="#" class="delete-seo-filter" data-id="<?php echo $array_data["seo_filters_id"]; ?>" ><i class="la la-close delete"></i></a>
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
                               <p>Фильтров нет</p>
                            </div>
                         <?php

                     }                  
                  ?>

            </div>
         </div>
      </div>
   </div>
</div>


<script type="text/javascript" src="include/modules/seo/script.js"></script>     
