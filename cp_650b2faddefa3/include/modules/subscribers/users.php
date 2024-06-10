<?php 
if( !defined('unisitecms') ) exit;

$Geo = new Geo();
$Profile = new Profile();
$Main = new Main();

if(empty($_GET["page"])) $_GET["page"] = 1;

$url[] = "route=subscribers";

$LINK = "?".implode("&",$url);

?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Подписчики</h2>
      </div>
   </div>
</div>

<div class="form-group" style="margin-bottom: 25px;" >
 
 <div class="btn-group" >
   <div class="dropdown">
    <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Экспорт в
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
       <!-- <a class="dropdown-item" href="<?php echo $config["urlPath"] . "/" . $config["folder_admin"]; ?>/include/modules/subscribers/handlers/export.php?format=xls" >.xls</a> -->
       <a class="dropdown-item" href="<?php echo $config["urlPath"] . "/" . $config["folder_admin"]; ?>/include/modules/subscribers/handlers/export.php?format=txt" >.txt</a>
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

                    $count = getOne("SELECT count(*) as total FROM uni_subscription")["total"];

                    $get = getAll("SELECT * FROM uni_subscription ORDER By subscription_id DESC ".navigation_offset( array( "count"=>$count, "output"=>$_SESSION["ByShow"], "page"=>$_GET["page"] ) ));     

                     if(count($get) > 0){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Имя</th>
                            <th>E-mail</th>
                            <th>Расположение</th>
                            <th>Дата добавления</th>
                            <th style="text-align: right;" ></th>
                           </tr>
                        </thead>
                        <tbody>                     
                     <?php

                        foreach($get AS $value){

                        ?>

                             <tr>                                               
                                 <td><?php echo $value["subscription_name"]; ?></td>
                                 <td><?php echo $value["subscription_email"]; ?></td>                                 
                                 <td><?php echo $Geo->userGeo( ["ip"=>$value["subscription_ip"]] ); ?></td>  
                                 <td><?php echo datetime_format_cp($value["subscription_datetime_add"]); ?></td>                               
                                 <td class="td-actions" style="text-align: right;" >
                                  <a href="#" class="delete-subscriber" data-id="<?php echo $value["subscription_id"]; ?>" ><i class="la la-close delete"></i></a>
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
                               <p>Подписчиков нет</p>
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
            
 <script type="text/javascript" src="include/modules/subscribers/script.js"></script>
     
