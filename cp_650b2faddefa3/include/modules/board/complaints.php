<?php 
if( !defined('unisitecms') ) exit;

$url[] = "route=complaints";

$_GET["page"] = empty($_GET["page"]) ? 1 : intval($_GET["page"]);

if(isset($_GET['sort'])){
    
if($_GET['sort'] == 2){                    
    $query = "WHERE ads_complain_status='2'";  
    $sort_name = "В архиве";                          
}else{
    $query = "WHERE ads_complain_status!='2'";   
}
           
$url[] = 'sort='.$_GET['sort'];

}else{

    $query = "WHERE ads_complain_status!='2'"; 

}

$LINK = "?".implode("&",$url);

$Ads = new Ads();
$Main = new Main();

?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Жалобы</h2>
      </div>
   </div>
</div>  

<div class="form-group" style="margin-bottom: 20px;" >

 <div class="btn-group mb5" >
   <div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Сортировать <?php if(!empty($sort_name)){ echo "(".$sort_name.")"; } ?>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <a class="dropdown-item" href="?route=complaints">Без сортировки</a>
      <a class="dropdown-item" href="?route=complaints&sort=2">В архиве</a>
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

                    $count = getOne("SELECT count(*) as total FROM uni_ads_complain $query")["total"];

                    $get = getAll("SELECT * FROM uni_ads_complain $query order by ads_complain_id desc ".navigation_offset( array( "count"=>$count, "output"=>$_SESSION["ByShow"], "page"=>$_GET["page"] ) ));     

                     if(count($get) > 0){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Обращение</th>
                            <th>Заявитель</th>
                            <th>Дата</th>
                            <th>Действие</th>
                           </tr>
                        </thead>
                        <tbody>                     
                     <?php

                        foreach($get AS $value){

                           if($value['ads_complain_action'] == 'ad'){

                              $getAd = $Ads->get("ads_id=? ", array($value["ads_complain_id_ad"]));
                              $getFromUser = findOne("uni_clients", "clients_id=?", array($value["ads_complain_from_user_id"]));
                              $getToUser = findOne("uni_clients", "clients_id=?", array($value["ads_complain_to_user_id"]));

                              ?>
                                  <tr>
                                      <td style="max-width: 350px; min-width: 250px;" >
                                        Жалоба на объявление <i class="la la-arrow-right"></i> <a target="_blank"  href="<?php echo $Ads->alias($getAd); ?>"><?php echo $getAd["ads_title"]; ?></a> <i class="la la-user"></i> <a href="?route=client_view&id=<?php echo $value["ads_complain_to_user_id"]; ?>"><?php echo $getToUser["clients_name"]; ?></a>
                                        <div class="complaints-text" ><?php echo $value["ads_complain_text"]; ?></div>
                                      </td>                                     
                                      <td><a href="?route=client_view&id=<?php echo $value["ads_complain_from_user_id"]; ?>"><?php echo $getFromUser["clients_name"]; ?></a></td>
                                      <td><?php echo datetime_format_cp($value["ads_complain_date"]); ?></td>
                                      <td>
                                       
                                         <div class="dropdown">

                                         <button class="btn btn-danger dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                           Выберите действие
                                         </button>                                 
                                         
                                         <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                           <?php if($getAd["ads_status"] != 3 && $getAd["ads_status"] != 8){ ?>
                                           <a class="dropdown-item change-status-ads" data-id="<?php echo $value["ads_complain_id_ad"]; ?>" now-status="<?php echo $getAd["ads_status"] ?>" data-status="3" href="#">Заблокировать объявление</a>
                                           <?php } ?>
                                           <a class="dropdown-item archive-complaint" data-id="<?php echo $value["ads_complain_id"]; ?>" href="#">В архив</a>
                                           <a class="dropdown-item delete-complaint" data-id="<?php echo $value["ads_complain_id"]; ?>" href="#">Удалить</a>
                                         </div>

                                         </div>


                                      </td>                          
                                  </tr>
                              <?php

                           }else{

                              $getFromUser = findOne("uni_clients", "clients_id=?", array($value["ads_complain_from_user_id"]));
                              $getToUser = findOne("uni_clients", "clients_id=?", array($value["ads_complain_to_user_id"]));

                              ?>
                                  <tr>
                                      <td style="max-width: 350px; min-width: 250px;" >
                                        Жалоба на пользователя <i class="la la-arrow-right"></i> <a href="?route=client_view&id=<?php echo $value["ads_complain_to_user_id"]; ?>"><?php echo $getToUser["clients_name"]; ?></a>
                                        <div class="complaints-text" ><?php echo $value["ads_complain_text"]; ?></div>
                                      </td>                                     
                                      <td><a href="?route=client_view&id=<?php echo $value["ads_complain_from_user_id"]; ?>"><?php echo $getFromUser["clients_name"]; ?></a></td>
                                      <td><?php echo datetime_format_cp($value["ads_complain_date"]); ?></td>
                                      <td>
                                       
                                         <div class="dropdown">

                                         <button class="btn btn-danger dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                           Выберите действие
                                         </button>                                 
                                         
                                         <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                           <?php if($getToUser["clients_status"] != 2){ ?>
                                           <a class="dropdown-item change-status-user" data-id="<?php echo $value["ads_complain_to_user_id"]; ?>" data-status="2" href="#">Заблокировать пользователя</a>
                                           <?php } ?>
                                           <a class="dropdown-item archive-complaint" data-id="<?php echo $value["ads_complain_id"]; ?>" href="#">В архив</a>
                                           <a class="dropdown-item delete-complaint" data-id="<?php echo $value["ads_complain_id"]; ?>" href="#">Удалить</a>
                                         </div>

                                         </div>


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
                               <p>Жалоб нет</p>
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

<script type="text/javascript" src="include/modules/board/script.js"></script>

