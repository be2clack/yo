<?php 
if( !defined('unisitecms') ) exit;

$url[] = "route=shops";

$_GET["page"] = empty($_GET["page"]) ? 1 : intval($_GET["page"]);

if(isset($_GET['sort'])){
    
if($_GET['sort'] == 0){                    
    $query = "WHERE clients_shops_status='0'";  
    $sort_name = "На модерации";                          
}elseif($_GET['sort'] == 1){                    
    $query = "WHERE clients_shops_status='1'";  
    $sort_name = "Активные";                          
}elseif($_GET['sort'] == 2){                    
    $query = "WHERE clients_shops_status='2'";  
    $sort_name = "Отклоненные";                          
}
           
$url[] = 'sort='.$_GET['sort'];

}

$Ads = new Ads();
$Main = new Main();

$url[] = 'sort='.$_GET['sort'];

if($_GET["search"]){

   $_GET["search"] = clearSearch($_GET["search"]);
   $query = "WHERE (clients_shops_title LIKE '%".$_GET["search"]."%' OR clients_shops_id_hash LIKE '%".$_GET["search"]."%')"; 
   $url[] = 'search='.$_GET["search"]; 

}

$LINK = "?".implode("&",$url);
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Магазины</h2>
      </div>
   </div>
</div>  

<div class="row" >
  <div class="col-lg-12" >
    
      <form method="get" action="<?php echo $config["urlPrefix"].$config["folder_admin"]; ?>" >
        <input type="text" class="form-control" placeholder="Укажите название магазина" style="height: 44px;" value="<?php echo $_GET["search"]; ?>" name="search">
        <input type="hidden" name="route" value="shops" >
      </form>

  </div>
</div>

<div class="form-group"  style="margin-bottom: 25px; margin-top: 25px;" >

 <div class="btn-group mb5" >
   <div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Сортировать <?php if(!empty($sort_name)){ echo "(".$sort_name.")"; } ?>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <a class="dropdown-item" href="?route=shops">Без сортировки</a>
      <a class="dropdown-item" href="?route=shops&sort=0">На модерации</a>
      <a class="dropdown-item" href="?route=shops&sort=1">Активные</a>
      <a class="dropdown-item" href="?route=shops&sort=2">Отклоненные</a>
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

                    $count = getOne("SELECT count(*) as total FROM uni_clients_shops $query")["total"];

                    $get = getAll("SELECT * FROM uni_clients_shops $query order by clients_shops_id desc,clients_shops_status desc ".navigation_offset( array( "count"=>$count, "output"=>$_SESSION["ByShow"], "page"=>$_GET["page"] ) ));     

                     if(count($get) > 0){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Название</th>
                            <th>Владелец</th>
                            <th>Статус</th>
                           </tr>
                        </thead>
                        <tbody>                     
                     <?php

                        foreach($get AS $value){

                              $getUser = findOne("uni_clients", "clients_id=?", array($value["clients_shops_id_user"]));

                              ?>
                              <tr>
                                  <td style="max-width: 350px; min-width: 250px;" ><a target="_blank" href="<?php echo $Shop->linkShop($value["clients_shops_id_hash"]); ?>" ><?php echo $value["clients_shops_title"]; ?></a></td>                                     
                                  <td><a href="?route=client_view&id=<?php echo $getUser["clients_id"]; ?>"><?php echo $getUser["clients_name"]; ?></a></td>
                                  <td>
                                      
                                     <?php
                                       if($value["clients_shops_status"] == 0){
                                            ?>
                                             <div class="dropdown">

                                             <button class="btn btn-warning dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                               На модерации
                                             </button>                                 
                                             
                                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                               <a class="dropdown-item change-status-shop" data-status="1" data-id="<?php echo $value["clients_shops_id"]; ?>" href="#">Опубликовать</a>
                                               <a class="dropdown-item deny-publication-shop" data-toggle="modal" data-target="#modal-deny-publication-shop" data-id="<?php echo $value["clients_shops_id"]; ?>" href="#">Отклонить</a>
                                               <a class="dropdown-item delete-shop" data-id="<?php echo $value["clients_shops_id"]; ?>" href="#">Удалить</a>
                                             </div>

                                             </div>
                                            <?php
                                       }elseif($value["clients_shops_status"] == 1){
                                            ?>
                                             <div class="dropdown">

                                             <button class="btn btn-success dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                               Опубликован
                                             </button>                                 
                                             
                                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                               <a class="dropdown-item deny-publication-shop" data-toggle="modal" data-target="#modal-deny-publication-shop" data-id="<?php echo $value["clients_shops_id"]; ?>" href="#">Отклонить</a>
                                               <a class="dropdown-item delete-shop" data-id="<?php echo $value["clients_shops_id"]; ?>" href="#">Удалить</a>
                                             </div>

                                             </div>
                                            <?php
                                       }elseif($value["clients_shops_status"] == 2){
                                            ?>
                                             <div class="dropdown">

                                             <button class="btn btn-danger dropdown-toggle btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                               Отклонен
                                             </button>                                 
                                             
                                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                               <a class="dropdown-item change-status-shop" data-status="1" data-id="<?php echo $value["clients_shops_id"]; ?>" href="#">Опубликовать</a>
                                               <a class="dropdown-item delete-shop" data-id="<?php echo $value["clients_shops_id"]; ?>" href="#">Удалить</a>
                                             </div>

                                             </div>
                                            <?php
                                       }
                                     ?>


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
                               <p>Магазинов нет</p>
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

<div id="modal-deny-publication-shop" class="modal fade">
   <div class="modal-dialog" style="max-width: 600px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Отклонить магазин</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="form-deny-publication-shop" >

                    <div class="form-group row d-flex align-items-center mb-5">
                      <label class="col-lg-4 form-control-label">Причина отклонения</label>
                      <div class="col-lg-8">
                            <textarea name="comment" class="form-control" ></textarea>
                      </div>
                    </div>

                  <input type="hidden" name="id" value="0" >
                  <input type="hidden" name="status" value="2" >
  
               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-danger action-deny-publication-shop" >Отклонить</button>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript" src="include/modules/shops/script.js"></script>

