<?php 
if( !defined('unisitecms') ) exit;

$data = getOne("select * from uni_secure where secure_id=?", array($id));
if(count($data) == 0){
   exit();
}

$Ads = new Ads();
$Profile = new Profile();
$Main = new Main();

$user_seller = findOne("uni_clients", "clients_id=?", [$data["secure_id_user_seller"]]);
$user_buyer = findOne("uni_clients", "clients_id=?", [$data["secure_id_user_buyer"]]);

$getDisputes = getOne("SELECT * FROM uni_secure_disputes INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_secure_disputes`.secure_disputes_id_user where secure_disputes_id_secure=?", [$data["secure_id"]]);
$getAds = getAll('select * from uni_secure_ads where secure_ads_order_id=?', [$data["secure_id_order"]]);
?>

<div class="row">
   <div class="page-header">
      <div class="d-flex align-items-center">
         <h2 class="page-header-title"> <strong>Заказ №<?php echo $data["secure_id_order"]; ?> от <?php echo date("d.m.Y H:i", strtotime($data["secure_date"]) ); ?></strong> </h2>
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

                     if( $data["secure_status"] == 0 ){

                       ?>
                        <h4 > <span class="secure-view-label secure-card-label-0">Ожидается оплата</span> </h4> 
                       <?php

                     }elseif( $data["secure_status"] == 1 ){

                       ?>
                        <h4 ><strong>Ожидается передача товара</strong></h4> 
                       <?php

                     }elseif( $data["secure_status"] == 2 ){

                       ?>
                        <h4 style="margin-bottom: 15px;"  ><strong>Продавец подтвердил передачу товара.</strong></h4>
                        <p>Ожидается подтверждение получения товара</p>

                        <button class="btn btn-success secure-pay-out" data-status="3" data-id-user="<?php echo $user_seller["clients_id"]; ?>" data-id="<?php echo $data["secure_id"]; ?>" >Подтвердить</button>
                       <?php

                     }elseif( $data["secure_status"] == 3 ){

                           if($data["secure_status_payment_user"] == 1){
                              ?>
                              <h4 ><span class="secure-view-label secure-card-label-3">Сделка завершена</span></h4>
                              <?php
                           }elseif($data["secure_status_payment_user"] == 2){
                              ?>
                              <h4 ><span class="secure-view-label secure-card-label-4">Ошибка выплаты</span></h4>
                              <?php
                           }else{
                              ?>
                              <h4 > <span class="secure-view-label secure-card-label-0">В процессе выплаты</span> </h4> 
                              <?php                            
                           }

                     }elseif( $data["secure_status"] == 4 ){

                       ?>
                        <h4 ><span class="secure-view-label secure-card-label-0">Открыт спор</span></h4> 
                       <?php

                     }elseif( $data["secure_status"] == 5 ){

                       ?>
                        <h4 > <span class="secure-view-label secure-card-label-4">Сделка отменена</span> </h4> 
                       <?php

                     }

                     if($getDisputes){

                        $secure_disputes_attach = json_decode( $getDisputes["secure_disputes_attach"], true );

                        ?>
                        <div style="margin-top: 15px;" >

                          <p style="margin-bottom: 0px;" ><strong>Комментарий:</strong></p>

                          <p style="margin-top: 5px;" ><?php echo $getDisputes["secure_disputes_text"]; ?></p>
                          
                          <?php if($secure_disputes_attach){ ?>
                          <div class="secure-attach" >
                             <?php
                               foreach ($secure_disputes_attach as $key => $value) {
                                  ?>
                                  <a href="<?php echo $config["urlPath"] . "/" . $config["media"]["user_attach"] . "/" . $value; ?>" target="_blank" ><img src="<?php echo $config["urlPath"] . "/" . $config["media"]["user_attach"] . "/" . $value; ?>" ></a>
                                  <?php
                               }
                             ?>
                          </div>
                          <?php } ?>
                          
                          <?php if( $data["secure_status"] == 4 ){ ?>

                          <p style="margin-bottom: 5px; margin-top: 25px;" ><strong>Варианты решения</strong></p>

                          <form class="form-secure-disputes" >
                        
                          <div class="secure-disputes-variants" >
                            <span data-status="0" data-info="Спор закрыт. Деньги отправлены на счет продавца." >Закрыть спор</span>
                            <span data-status="1" data-info="Полный возврат средств. Покупатель получит <?php echo $Main->price( $Ads->secureTotalAmountPercent( $data["secure_price"], false ) ); ?>." >Возврат полной суммы</span>
                            <span data-status="2" data-info="Частичный возврат средств. Продавец получит <?php echo $Main->price( $Ads->secureTotalAmountPercent( $data["secure_price"] / 2 ) ); ?>, с учетом комиссии за услугу &quot;Безопасная сделка&quot;. Покупатель получит возврат в размере <?php echo $Main->price( $Ads->secureTotalAmountPercent( $data["secure_price"] / 2, false ) ); ?>." >Возврат 50% от суммы</span>
                          </div>

                          <p style="margin-bottom: 10px; margin-top: 20px;" ><strong>Комментарий арбитра</strong></p>

                          <textarea class="form-control secure-disputes-textarea" name="text" ></textarea>
                          <p>Комментарий будет виден продавцу и покупателю.</p>

                          <input type="hidden" name="status" value="0" >
                          <input type="hidden" name="id" value="<?php echo $id; ?>" >

                          <div class="text-right secure-disputes-action-accept" >
                             <button class="btn btn-success ">Применить</button>
                          </div>

                          <?php }else{ ?>

                          <div class="secure-disputes-info" ><?php echo $getDisputes["secure_disputes_text_arbitr"]; ?></div>

                          <?php } ?>

                          </form>
                          
                        </div>
                        <?php
                     }
                     
                     ?>
                       
                </div>
              </div>

              <div class="form-group row mb-5">
                <label class="col-lg-2 form-control-label">Товары (<?php echo count($getAds); ?>)</label>
                <div class="col-lg-7">
                     
                      <?php
                        if(count($getAds)){
                            foreach ($getAds as $value) {

                                $getAd = $Ads->get("ads_id=?", [$value['secure_ads_ad_id']]);
                                
                                if($getAd){
                                    ?>
                                    <div style="margin-bottom: 10px;" >

                                      <a href="<?php echo $Ads->alias($getAd); ?>"  ><?php echo $getAd["ads_title"]; ?></a>

                                    </div>
                                    <?php
                                }else{
                                    ?>
                                    <div style="margin-bottom: 10px;" >

                                      Объявление #<?php echo $value['secure_ads_ad_id']; ?> удалено

                                    </div>
                                    <?php                                    
                                }

                            }
                        }
                      ?>

                </div>
              </div>

              <div class="form-group row mb-5">
                <label class="col-lg-2 form-control-label">Продавец</label>
                <div class="col-lg-7">
                     <a href="?route=client_view&id=<?php echo $data["secure_id_user_seller"]; ?>" ><?php echo $Profile->name( $user_seller ); ?></a>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-lg-2 form-control-label">Покупатель</label>
                <div class="col-lg-7">
                     <a href="?route=client_view&id=<?php echo $data["secure_id_user_buyer"]; ?>" ><?php echo $Profile->name( $user_buyer ); ?></a>
                </div>
              </div>

              <hr>

              <div class="form-group row">
                <label class="col-lg-2 form-control-label">Сумма заказа</label>
                <div class="col-lg-7">
                     <?php echo $Main->price($data["secure_price"]); ?>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-lg-2 form-control-label">Комиссия сервиса</label>
                <div class="col-lg-7">
                     <?php echo $Main->price($Ads->getSecureCommission($data["secure_price"])); ?>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-lg-2 form-control-label">Итого</label>
                <div class="col-lg-7">
                     <?php echo $Main->price($Ads->secureTotalAmountPercent($data["secure_price"])); ?>
                </div>
              </div>

         </div>

      </div>

      <?php
      $getSecurePayments = getAll("SELECT * FROM uni_secure_payments where secure_payments_id_order=?", [$data["secure_id_order"]]);     

      if(count($getSecurePayments)){   

      ?>

      <h3 style="margin-top: 35px; margin-bottom: 20px;" > <strong>История операций</strong> </h3>

      <div class="widget has-shadow">

         <div class="widget-body">

             <div class="table-responsive" >
             <table class="table mb-0">
                <thead>
                   <tr>
                    <th>Действие</th>
                    <th>Дата</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                   </tr>
                </thead>
                <tbody>                     
             <?php

                foreach($getSecurePayments AS $value){

                ?>

                 <tr>
                     <td>
                      <?php
                        if($value["secure_payments_status"] == 0){
                           echo '<strong>Оплата счета</strong>';
                        }elseif($value["secure_payments_status"] == 1){
                           echo '<strong>Выплата средств продавцу</strong>';
                           ?>
                           <div><?php echo 'Счет для выплаты - ' . ($user_seller["clients_score"] ? decrypt($user_seller["clients_score"]) : 'Не указан'); ?></div>
                           <?php
                        }elseif($value["secure_payments_status"] == 2){
                           echo '<strong>Возврат покупателю</strong>';

                           if($data["secure_balance_payment"]){
                               ?>
                               <div><?php echo 'Возврат на баланс кошелька'; ?></div>
                               <?php                            
                           }else{
                               ?>
                               <div><?php echo 'Счет для выплаты - ' . ($user_buyer["clients_score"] ? decrypt($user_buyer["clients_score"]) : 'Не указан'); ?></div>
                               <?php                            
                           }

                        }
                      ?>
                     </td>                  
                     <td><?php echo date("d.m.Y H:i:s", strtotime($value["secure_payments_date"]) ); ?></td> 
                     <td><?php echo $Main->price($value["secure_payments_amount_percent"]); ?></td>
                     <td>
                      <?php
                        if($settings["secure_payment_service_name"]){
                            if( !$value["secure_payments_errors"] ){

                              if($value["secure_payments_status_pay"] == 0){
                                 echo 'В процессе';
                              }elseif($value["secure_payments_status_pay"] == 1){
                                 echo 'Выполнено';
                              }elseif($value["secure_payments_status_pay"] == 2){
                                 echo 'Ошибка';
                              }

                            }else{
                              ?>
                              <span style="color: red;" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Ошибка: <?php echo clear($value["secure_payments_errors"]); ?>" >Ошибка при выплате <i class="la la-info-circle"></i></span>

                              <div>
                                <button style="margin-top: 10px;" data-id="<?php echo $value["secure_payments_id"]; ?>" class="btn btn-sm btn-warning secure-order-repaid">Повторить</button>
                              </div>
                              <?php
                            }
                        }else{
                            if($value["secure_payments_status_pay"] == 0){
                                ?>
                                  <div>
                                    <button style="margin-top: 10px;" data-id="<?php echo $value["secure_payments_id"]; ?>" class="btn btn-sm btn-warning secure-order-accept-payment">Подтвердить оплату</button>
                                  </div>                                
                                <?php
                            }elseif($value["secure_payments_status_pay"] == 1){
                                echo 'Выполнено';
                            }
                        }
                      ?>
                     </td>                                               
                 </tr> 
         
               
                 <?php                                         
                } 

                ?>

                   </tbody>
                </table>
                
                </div>

         </div>

      </div>

      <?php               
      }  
      ?>
      
      
   </div>
</div>

    

<script type="text/javascript" src="include/modules/secure/script.js"></script>
