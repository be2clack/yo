<?php 
if( !defined('unisitecms') ) exit;

$Geo = new Geo();
$Profile = new Profile();
$Main = new Main();

$_GET["page"] = empty($_GET["page"]) ? 1 : intval($_GET["page"]);

$url[] = "route=clients_verifications";

$LINK = "?".implode("&",$url);

?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Верификации</h2>
      </div>
   </div>
</div>

<div class="row" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
            <div class="table-responsive">

                 <?php

                    $count = getOne("SELECT count(*) as total FROM uni_clients_verifications $query ")["total"];

                    $get = getAll("SELECT * FROM uni_clients_verifications $query ORDER By id DESC ".navigation_offset( array( "count"=>$count, "output"=>$_SESSION["ByShow"], "page"=>$_GET["page"] ) ));     

                     if(count($get) > 0){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Пользователь</th>
                            <th>Код проверки</th>
                            <th>Документы/Фото</th>
                            <th>Создан</th>
                            <th>Статус</th>
                            <th style="text-align: right;" ></th>
                           </tr>
                        </thead>
                        <tbody>                     
                     <?php

                        foreach($get AS $value){

                            $user = findOne("uni_clients", "clients_id=?", [$value["user_id"]]);

                            if($user){
                            ?>

                             <tr>                                               
                                 <td>
                                  <a href="?route=client_view&id=<?php echo $user["clients_id"]; ?>" ><?php echo $Profile->name($user, false); ?></a>
                                 </td>
                                 <td>
                                   <strong><?php echo $user["clients_verification_code"]; ?></strong>
                                 </td>   
                                 <td>
                                    <div> <span class="btn-verification-open" data-id="<?php echo $value["id"]; ?>" data-toggle="modal" data-target="#modal-verification-files" >Открыть</span> </div>
                                 </td>                                                                                                
                                 <td>
                                   <?php echo datetime_format_cp($value["date_create"]); ?>
                                 </td>                                 
                                 <td>
                                   <div class="dropdown">

                                    <?php 

                                     if($value["status"] == 0){

                                        ?>

                                        <button class="btn btn-warning dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Не подтвержден
                                        </button>

                                        <div class="dropdown-menu" >
                                          <a class="dropdown-item change-status-verification-user" data-id="<?php echo $value["id"]; ?>" href="#">Подтвердить</a>
                                          <a class="dropdown-item refused-status-verification-user" data-toggle="modal" data-target="#modal-refused-verification" data-id="<?php echo $value["id"]; ?>" href="#">Отклонить</a>
                                        </div>

                                        <?php

                                     }elseif($value["status"] == 1){

                                        ?>

                                        <button class="btn btn-success btn-sm">
                                          Подтвержден
                                        </button>

                                        <?php

                                     }elseif($value["status"] == 2){

                                        ?>

                                        <button class="btn btn-default btn-sm">
                                          Отклонен
                                        </button>

                                        <?php

                                     }

                                     ?>

                                    </div>                               
                                 </td>
                                 <td class="td-actions" style="text-align: right;" >
                                  <a href="#" class="delete-verification-user" data-id="<?php echo $value["id"]; ?>" ><i class="la la-close delete"></i></a>
                                 </td>
                             </tr> 
                 
                            <?php
                            }  

                        } 

                        ?>

                           </tbody>
                        </table>

                        <?php               
                     }else{
                         
                         ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Запросов нет</p>
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

<div id="modal-verification-files" class="modal fade">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Вложенные файлы</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
            <div class="modal-verification-files-container" ></div>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
         </div>
      </div>
   </div>
</div>
            
<script type="text/javascript" src="include/modules/clients/script.js"></script>
     
