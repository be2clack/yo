<?php 
if( !defined('unisitecms') ) exit;

$LINK = '?route=pages';   
$_GET["page"] = empty($_GET["page"]) ? 1 : intval($_GET["page"]);
?>


<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Сервисные страницы</h2>
      </div>
   </div>
</div>  

<div class="form-group">
<a  href="?route=add_page" class="btn btn-gradient-04 mr-1 mb-2">Добавить</a>
</div>

<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
            <div class="table-responsive">

                 <?php

                    $count = getOne("SELECT count(*) as total FROM uni_pages")["total"];

                    $get = getAll("SELECT * FROM uni_pages order by id_position asc ".navigation_offset( array( "count"=>$count, "output"=>$_SESSION["ByShow"], "page"=>$_GET["page"] ) ));     

                     if(count($get) > 0){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th></th>
                            <th>Название</th>
                            <th style="text-align: right;" >Статус</th>
                            <th style="text-align: right;" ></th>
                           </tr>
                        </thead>
                        <tbody class="sort-container" >                     
                     <?php

                        foreach($get AS $array_data){
 
                        ?>

                         <tr id="item<?php echo $array_data["id"]; ?>" >
                             <td><span class="icon-move move-sort" ><i class="la la-arrows-v"></i></span></td>
                             <td><a href="<?php echo $config["urlPath"] . "/" . $array_data["alias"]; ?>" target="_blank" ><?php echo $array_data["name"]; ?></a></td>
                             <td style="text-align: right;" >

                               <label style="margin-top: 10px;" >
                                 <input class="toggle-checkbox-sm toolbat-toggle toggle-status" <?php if($array_data["visible"]){ echo 'checked=""'; } ?> type="checkbox" value="1" data-id="<?php echo $array_data["id"]; ?>" >
                                 <span> <span></span> </span>
                               </label>

                             </td> 
                             <td class="td-actions" style="text-align: right;" >
                              <a href="?route=page&id=<?php echo $array_data["id"]; ?>"><i class="la la-edit edit"></i></a>
                              <a href="#" class="delete-page" data-id="<?php echo $array_data["id"]; ?>" ><i class="la la-close delete"></i></a>
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
                               <p>Страниц нет</p>
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

<script type="text/javascript" src="include/modules/pages/script.js"></script>     
