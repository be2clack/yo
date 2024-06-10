<?php 
if( !defined('unisitecms') ) exit;

$LINK = '?route=booking';   
$_GET["page"] = empty($_GET["page"]) ? 1 : intval($_GET["page"]);

$Ads = new Ads();
$Profile = new Profile();
$Main = new Main();

if(!empty($_GET["search"])){

  $_GET["search"] = clearSearch($_GET["search"]);

   $query = "and (ads_booking_id_order LIKE '%".$_GET["search"]."%' OR ads_title LIKE '%".$_GET["search"]."%')"; 
   $url[] = 'search='.$_GET["search"];
   
}

if(!$settings["functionality"]["booking"]){
   ?>
     <div class="alert alert-warning" role="alert">
       Модуль онлайн бронирования недоступен. Подключить его можно в разделе <a href="?route=modules" ><strong>модули</strong></a> 
     </div>             
   <?php
}
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title">Оплата бронирования/аренды</h2>
      </div>
   </div>
</div>  

<div class="row" >
  <div class="col-lg-12" >
    
      <form method="get" action="<?php echo $config["urlPrefix"].$config["folder_admin"]; ?>" >
        <input type="text" class="form-control" placeholder="Укажите номер заказа или название объявления" style="height: 44px;" value="<?php echo $_GET["search"]; ?>" name="search">
        <input type="hidden" name="route" value="booking" >
      </form>

  </div>
</div>

<div class="row"  style="margin-top: 25px;" >
   <div class="col-lg-12" >
      <div class="widget has-shadow">

         <div class="widget-body">
            <div class="table-responsive">

                 <?php

                     $count = getOne("SELECT count(*) as total FROM uni_ads_booking INNER JOIN `uni_ads` ON `uni_ads`.ads_id = `uni_ads_booking`.ads_booking_id_ad where `uni_ads`.ads_booking_prepayment_percent!='0' and ads_booking_status_pay='1' $query")["total"];

                     $get = getAll("select * from uni_ads_booking INNER JOIN `uni_ads` ON `uni_ads`.ads_id = `uni_ads_booking`.ads_booking_id_ad where `uni_ads`.ads_booking_prepayment_percent!='0' and ads_booking_status_pay='1' $query order by ads_booking_id desc ".navigation_offset( array( "count"=>$count, "output"=>$_SESSION["ByShow"], "page"=>$_GET["page"] ) ));    

                     if(count($get) > 0){   

                     ?>
                     <table class="table mb-0">
                        <thead>
                           <tr>
                            <th>Заказ</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                            <th></th>
                           </tr>
                        </thead>
                        <tbody>                     
                        <?php
                        foreach($get AS $value){

                           $getPayment = findOne('uni_ads_booking_prepayments', 'ads_booking_prepayments_id_order=?', [$value['ads_booking_id_order']]);
                        ?>

                             <tr>                                               
                                 <td><a href="?route=booking_view&id=<?php echo $value['ads_booking_id']; ?>">№<?php echo $value['ads_booking_id_order']; ?></a></td>
                                 <td><?php echo $Main->price($getPayment['ads_booking_prepayments_amount']); ?></td>                                 
                                 <td>
                                    <?php 
                                    if($value['ads_booking_status'] != 2){
                                        if($getPayment['ads_booking_prepayments_status'] == 0){ echo '<span class="badge badge-warning">Не выплачено</span>'; }else{ echo '<span class="badge badge-success">Выплачено</span>'; } 
                                    }else{
                                        echo '<span class="badge badge-danger">Заказ отменен</span>';
                                    }
                                    ?></td>   
                                 <td class="td-actions" style="text-align: right;">
                                    <a href="?route=booking_view&id=<?php echo $value['ads_booking_id']; ?>"><i class="la la-eye edit"></i></a>
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
                               <p>Оплаченых сделок пока нет</p>
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

<script type="text/javascript" src="include/modules/booking/script.js"></script>     
