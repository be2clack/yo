<?php 
if( !defined('unisitecms') ) exit;

$data = getOne("select * from uni_ads_booking where ads_booking_id=?", array($id));
$getPayment = findOne('uni_ads_booking_prepayments', 'ads_booking_prepayments_id_order=?', [intval($data['ads_booking_id_order'])]);

if(!$data || !$getPayment){
   exit();
}

$Ads = new Ads();
$Profile = new Profile();
$Main = new Main();

$userFrom = findOne("uni_clients", "clients_id=?", [$data["ads_booking_id_user_from"]]);
$userTo = findOne("uni_clients", "clients_id=?", [$data["ads_booking_id_user_to"]]);

$userTo["clients_score_booking"] = decrypt($userTo["clients_score_booking"]);

$getAd = $Ads->get("ads_id=?", [$data["ads_booking_id_ad"]]);

if($settings['booking_prepayment_percent_service']) $totalSumm = $getPayment['ads_booking_prepayments_amount'] - calcPercent( $getPayment['ads_booking_prepayments_amount'], $settings['booking_prepayment_percent_service'] );
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title"> <strong>Заказ №<?php echo $data["ads_booking_id_order"]; ?> от <?php echo date("d.m.Y H:i", strtotime($data["ads_booking_date_add"]) ); ?></strong> </h2>
      </div>
   </div>
</div>

<div class="row" >
   <div class="col-lg-12" >

      <div class="widget has-shadow">

         <div class="widget-body">
           
              <div class="form-group row mb-5">
                <label class="col-lg-2 form-control-label">Статус</label>
                <div class="col-lg-7">
                     
                     <?php 

                     if($data['ads_booking_status'] != 2){

                         if( $getPayment["ads_booking_prepayments_status"] == 0 ){

                           ?>
                            <h4> <span class="secure-view-label secure-card-label-1">Не выплачено</span> </h4> 
                            <div style="margin-top: 10px" ><span class="btn btn-primary booking-order-accept" data-id="<?php echo $data['ads_booking_id_order']; ?>" >Выплачено</span></div>
                           <?php

                         }else{

                           ?>
                            <h4 > <span class="secure-view-label secure-card-label-3">Выплачено</span> </h4> 
                           <?php

                         }

                     }else{

                       ?>
                        <h4 > <span class="secure-view-label secure-card-label-4">Заказ отменен</span> </h4> 
                       <?php

                     }

                     ?>
                       
                </div>
              </div>

              <div class="form-group row mb-5">
                <label class="col-lg-2 form-control-label">Объявление</label>
                <div class="col-lg-7">
                     <a href="<?php echo $Ads->alias($getAd); ?>" target="_blank" ><?php echo $getAd["ads_title"]; ?></a>
                </div>
              </div>

              <div class="form-group row mb-5">
                <label class="col-lg-2 form-control-label">Получатель</label>
                <div class="col-lg-7">
                     <a href="?route=client_view&id=<?php echo $data["ads_booking_id_user_to"]; ?>" ><?php echo $userTo['clients_name']; ?> <?php echo $userTo['clients_surname']; ?></a>
                </div>
              </div>

              <div class="form-group row mb-5">
                <label class="col-lg-2 form-control-label">Номер карты</label>
                <div class="col-lg-7">
                     <?php echo $userTo["clients_score_booking"]; ?>
                </div>
              </div>

              <hr>

              <div class="form-group row">
                <label class="col-lg-2 form-control-label">Сумма оплаты</label>
                <div class="col-lg-7">
                     <?php echo $Main->price($getPayment['ads_booking_prepayments_amount']); ?>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-lg-2 form-control-label">Процент сервиса</label>
                <div class="col-lg-7">
                     <?php echo $settings['booking_prepayment_percent_service']; ?>%
                </div>
              </div>

              <div class="form-group row">
                <label class="col-lg-2 form-control-label">К выплате</label>
                <div class="col-lg-7">
                     <?php echo $Main->price($totalSumm); ?>
                </div>
              </div>

         </div>

      </div>


   </div>
</div>

<script type="text/javascript" src="include/modules/booking/script.js"></script>
