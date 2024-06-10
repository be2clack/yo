<?php 
if( !defined('unisitecms') ) exit;

$Ads = new Ads();
$Profile = new Profile();

$_GET["page"] = empty($_GET["page"]) ? 1 : intval($_GET["page"]);

$url[] = "route=secure";

if($_GET['sort'] == 1){
    $query = "secure_status=0"; 
    $sort_name = "Ожидается оплата";  
}elseif ($_GET['sort'] == 2){                    
    $query = "secure_status=1 or secure_status=2";  
    $sort_name = "В работе";                          
}elseif ($_GET['sort'] == 3){                    
    $query = "secure_status=3 and secure_status_payment_user=1";  
    $sort_name = "Сделка завершена";                          
}elseif ($_GET['sort'] == 4){                    
    $query = "secure_status=3 and secure_status_payment_user=2"; 
    $sort_name = "Ошибка выплаты";                         
}elseif ($_GET['sort'] == 5){                    
    $query = "secure_status=4";  
    $sort_name = "Открыт спор";                         
}elseif ($_GET['sort'] == 6){                    
    $query = "secure_status=5";  
    $sort_name = "Сделка отменена";                         
}elseif ($_GET['sort'] == 7){                    
    $query = "secure_status=3 and secure_status_payment_user=0"; 
    $sort_name = "В процессе выплаты";                         
}
           
$url[] = 'sort='.$_GET['sort'];

if($_GET["search"]){

   $_GET["search"] = clearSearch($_GET["search"]);
   $query = "secure_id_order LIKE '%".$_GET["search"]."%'"; 
   $url[] = 'search='.$_GET["search"];
   
}

if($query){
   $query = " where $query";
}

$LINK = "?".implode("&",$url);

if(getCount("uni_secure")){
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Безопасные сделки</h2>
      </div>
   </div>
</div>  

<div class="row" >
  <div class="col-lg-12" >
    
      <form method="get" action="<?php echo $config["urlPrefix"].$config["folder_admin"]; ?>" >
        <input type="text" class="form-control" placeholder="Укажите номер заказа" style="height: 44px;" value="<?php echo $_GET["search"]; ?>" name="search">
        <input type="hidden" name="route" value="secure" >
      </form>

  </div>
</div>

<div class="form-group" style="margin-bottom: 25px; margin-top: 25px;" >
 
 <div class="btn-group" >
   <div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Сортировать <?php if(!empty($sort_name)){ echo "(".$sort_name.")"; } ?>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <a class="dropdown-item" href="?route=secure">Без сортировки</a>
      <a class="dropdown-item" href="?route=secure&sort=1">Ожидается оплата (<?php echo (int)getOne('select count(*) as total from uni_secure where secure_status=0')['total']; ?>)</a>
      <a class="dropdown-item" href="?route=secure&sort=2">В работе (<?php echo (int)getOne('select count(*) as total from uni_secure where secure_status=1 or secure_status=2')['total']; ?>)</a>
      <a class="dropdown-item" href="?route=secure&sort=3">Сделка завершена (<?php echo (int)getOne('select count(*) as total from uni_secure where secure_status=3 and secure_status_payment_user=1')['total']; ?>)</a>
      <a class="dropdown-item" href="?route=secure&sort=4">Ошибка выплаты (<?php echo (int)getOne('select count(*) as total from uni_secure where secure_status=3 and secure_status_payment_user=2')['total']; ?>)</a>
      <a class="dropdown-item" href="?route=secure&sort=5">Открыт спор (<?php echo (int)getOne('select count(*) as total from uni_secure where secure_status=4')['total']; ?>)</a>
      <a class="dropdown-item" href="?route=secure&sort=6">Сделка отменена (<?php echo (int)getOne('select count(*) as total from uni_secure where secure_status=5')['total']; ?>)</a>
      <a class="dropdown-item" href="?route=secure&sort=7">В процессе выплаты (<?php echo (int)getOne('select count(*) as total from uni_secure where secure_status=3 and secure_status_payment_user=0')['total']; ?>)</a>
    </div>
   </div>
 </div>

</div>

<div class="row">
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
            <div class="table-responsive">

               <?php

                  $count = getOne("SELECT count(*) as total FROM uni_secure $query")["total"];

                  $get = getAll("SELECT * FROM uni_secure $query order by secure_id desc ".navigation_offset( array( "count"=>$count, "output"=>$_SESSION["ByShow"], "page"=>$_GET["page"] ) ));     

                   if(count($get)){   

                      ?>

                           <table class="table mb-0">
                              <thead>
                                 <tr>
                                  <th>ID</th>
                                  <th>Пользователи</th>
                                  <th>Дата сделки</th>
                                  <th>Статус</th>
                                  <th style="text-align: right;" ></th>
                                 </tr>
                              </thead>
                              <tbody>                     

                                 <?php

                                    foreach($get AS $value){

                                        $image = $Ads->getImages($value["ads_images"]);

                                        $user_seller = findOne("uni_clients", "clients_id=?", [$value["secure_id_user_seller"]]);
                                        $user_buyer = findOne("uni_clients", "clients_id=?", [$value["secure_id_user_buyer"]]);
                                         
                                        ?>

                                         <tr>                                               
                                             <td><a href="?route=secure_view&id=<?php echo $value["secure_id"]; ?>"><?php echo $value["secure_id_order"]; ?></a></td>                                 
                                             <td>
                                               <div class="secure-card-users" >
                                                   <span>
                                                     <img title="<?php echo $user_seller["clients_name"]; ?>" src="<?php echo $Profile->userAvatar($user_seller["clients_avatar"]); ?>">
                                                   </span>
                                                   <span>
                                                     <img title="<?php echo $user_buyer["clients_name"]; ?>" src="<?php echo $Profile->userAvatar($user_buyer["clients_avatar"]); ?>">
                                                   </span>
                                               </div>                                         
                                             </td>                                 
                                             <td>
                                               <?php echo datetime_format_cp($value["secure_date"]); ?>
                                             </td> 
                                             <td>
                                                 <?php
                                                 
                                                      if( $value["secure_status"] == 0 ){
                                                         echo '<span class="secure-card-label secure-card-label-0" >Ожидается оплата</span>';
                                                      }elseif( $value["secure_status"] == 1 ){
                                                         echo '<span class="secure-card-label secure-card-label-1" >В работе</span>';
                                                      }elseif( $value["secure_status"] == 2 ){
                                                         echo '<span class="secure-card-label secure-card-label-2" >В работе</span>';
                                                      }elseif( $value["secure_status"] == 3 ){

                                                         if($value["secure_status_payment_user"] == 1){
                                                            echo '<span class="secure-card-label secure-card-label-3" >Сделка завершена</span>';
                                                         }elseif($value["secure_status_payment_user"] == 2){
                                                            echo '<span class="secure-card-label secure-card-label-4" >Ошибка выплаты</span>';
                                                         }else{
                                                            echo '<span class="secure-card-label secure-card-label-0" >В процессе выплаты</span>';
                                                         }

                                                      }elseif( $value["secure_status"] == 4 ){
                                                         echo '<span class="secure-card-label secure-card-label-0" >Открыт спор</span>';
                                                      }elseif( $value["secure_status"] == 5 ){

                                                         echo '<span class="secure-card-label secure-card-label-4" >Сделка отменена</span>';

                                                      }

                                                 ?>
                                             </td>                                                                              
                                             <td class="td-actions" style="text-align: right;" >
                                              <a href="?route=secure_view&id=<?php echo $value["secure_id"]; ?>"><i class="la la-eye edit"></i></a>
                                              <a href="#" class="delete-secure" data-id="<?php echo $value["secure_id"]; ?>" ><i class="la la-close delete"></i></a>
                                             </td>
                                         </tr>

                                 <?php } ?>

                              </tbody>
                           </table>

                      <?php
                   }else{
                         
                         ?>
                            <div class="plug" >
                               <i class="la la-exclamation-triangle"></i>
                               <p>Сделок нет</p>
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

<?php }else{ ?>

  <div class="circle-img-icon" >
     <img src="<?php echo $settings["path_admin_image"]; ?>/admin-secure.png">
     <h3 class="mt10" > <strong>Безопасных сделок пока нет</strong> </h3>
     <p>Как только появятся сделки - они тут отобразятся</p>
  </div>

<?php } ?>

<script type="text/javascript" src="include/modules/secure/script.js"></script>     
