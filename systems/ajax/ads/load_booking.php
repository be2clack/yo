<?php

 $id_ad = (int)$_POST['id_ad'];
 $booking_guests = (int)$_POST['booking_guests'] ?: 1;
 $additional_services_total_price = 0;
 $booking_hour_count = (int)$_POST['booking_hour_count'] ?: 1;
 $booking_hour_start = clear($_POST['booking_hour_start']) ?: '12:00';

 $getAd = $Ads->get("ads_id=?",[$id_ad]);

 if(!$getAd) exit();

 $booking_date_start = $_POST['booking_date_start'] ? date('d.m.Y', strtotime($_POST['booking_date_start'])) : date('d.m.Y');

 if($_POST['booking_date_end']){
    $booking_date_end = date('d.m.Y', strtotime($_POST['booking_date_end']));
 }else{
    if($getAd["ads_booking_min_days"]){ 
        $booking_date_end = date('d.m.Y', strtotime('+'.$getAd["ads_booking_min_days"].' days')); 
    }else{ 
        $booking_date_end = date('d.m.Y', strtotime('+1 days')); 
    }
 }

 $difference_days = difference_days($booking_date_end,$booking_date_start) ?: 1;

 $booking_additional_services = json_decode($getAd["ads_booking_additional_services"], true);

 if($_POST['booking_additional_services'] && $getAd["ads_booking_additional_services"]){
    foreach ($_POST['booking_additional_services'] as $key => $value) {
        if($booking_additional_services[$key]){
            $additional_services_total_price += $booking_additional_services[$key]['price'];
        }
    }
 }

 if($getAd['ads_price_measure'] == 'hour'){
    $total = ($booking_hour_count * $getAd["ads_price"]) + $additional_services_total_price;
    $prepayment = calcPercent($booking_hour_count * $getAd["ads_price"], $getAd["ads_booking_prepayment_percent"]);
 }else{
    $total = ($difference_days * $getAd["ads_price"]) + $additional_services_total_price;
    $prepayment = calcPercent($difference_days * $getAd["ads_price"], $getAd["ads_booking_prepayment_percent"]);
 }

 if($getAd["category_board_booking_variant"] == 0){

 ?>

    <h4> <strong><?php echo $ULang->t("Бронирование"); ?></strong> </h4>

    <div class="modal-booking-errors" ></div>

    <div class="booking-change-date-box mt15" >
        <div class="row" >
            <div class="col-lg-12" >
                <p><?php echo $ULang->t('Заселение'); ?></p>
                <input type="text" class="form-control" name="booking_date_start" value="<?php echo $booking_date_start; ?>" >                    
            </div>
            <div class="col-lg-12 mt15" >
                <p><?php echo $ULang->t('Выезд'); ?></p>
                <input type="text" class="form-control" name="booking_date_end" value="<?php echo $booking_date_end; ?>" >
            </div>                        
        </div>
    </div>

    <?php if($getAd["ads_booking_max_guests"]){ ?>
    <p class="mt15 mb10" ><?php echo $ULang->t('Количество гостей'); ?></p>

    <div class="booking-max-guests-box" >
        <div class="row" >
            <div class="col-lg-12" >
                <input type="number" class="form-control" name="booking_guests" placeholder="Максимум <?php echo $getAd["ads_booking_max_guests"]; ?>" value="<?php echo $booking_guests; ?>" >
            </div>                        
        </div>
    </div>
    <?php } ?>

<?php }else{ ?>

    <h4> <strong><?php echo $ULang->t("Аренда"); ?></strong> </h4>

    <div class="modal-booking-errors" ></div>

    <?php if($getAd['ads_price_measure'] == 'hour'){ ?>
    <div class="booking-change-date-box mt15" >
        <div class="row" >
            <div class="col-lg-12" >
                <p><?php echo $ULang->t('Дата начала'); ?></p>
                <input type="text" class="form-control" name="booking_date_start" value="<?php echo $booking_date_start; ?>" >                    
            </div>
            <div class="col-lg-12 mt15" >
                <p><?php echo $ULang->t('Время начала'); ?></p>
                <input type="time" class="form-control" name="booking_hour_start" value="<?php echo $booking_hour_start; ?>" >
            </div>                        
        </div>
    </div>

    <div class="booking-change-time-box mt15 mb10" >
        <div class="row" >
            <div class="col-lg-12" >
                <p><strong><?php echo $ULang->t('Количество часов'); ?></strong></p>
                <select class="form-control" name="booking_hour_count" >
                    <option value="1" <?php if($booking_hour_count == 1){ echo 'selected=""'; } ?> >1</option>
                    <option value="2" <?php if($booking_hour_count == 2){ echo 'selected=""'; } ?> >2</option>
                    <option value="3" <?php if($booking_hour_count == 3){ echo 'selected=""'; } ?> >3</option>
                    <option value="4" <?php if($booking_hour_count == 4){ echo 'selected=""'; } ?> >4</option>
                    <option value="5" <?php if($booking_hour_count == 5){ echo 'selected=""'; } ?> >5</option>
                    <option value="6" <?php if($booking_hour_count == 6){ echo 'selected=""'; } ?> >6</option>
                    <option value="7" <?php if($booking_hour_count == 7){ echo 'selected=""'; } ?> >7</option>
                    <option value="8" <?php if($booking_hour_count == 8){ echo 'selected=""'; } ?> >8</option>
                    <option value="9" <?php if($booking_hour_count == 9){ echo 'selected=""'; } ?> >9</option>
                    <option value="10" <?php if($booking_hour_count == 10){ echo 'selected=""'; } ?> >10</option>
                    <option value="11" <?php if($booking_hour_count == 11){ echo 'selected=""'; } ?> >11</option>
                    <option value="12" <?php if($booking_hour_count == 12){ echo 'selected=""'; } ?> >12</option>
                    <option value="13" <?php if($booking_hour_count == 13){ echo 'selected=""'; } ?> >13</option>
                    <option value="14" <?php if($booking_hour_count == 14){ echo 'selected=""'; } ?> >14</option>
                    <option value="15" <?php if($booking_hour_count == 15){ echo 'selected=""'; } ?> >15</option>
                    <option value="16" <?php if($booking_hour_count == 16){ echo 'selected=""'; } ?> >16</option>
                    <option value="17" <?php if($booking_hour_count == 17){ echo 'selected=""'; } ?> >17</option>
                    <option value="18" <?php if($booking_hour_count == 18){ echo 'selected=""'; } ?> >18</option>
                    <option value="19" <?php if($booking_hour_count == 19){ echo 'selected=""'; } ?> >19</option>
                    <option value="20" <?php if($booking_hour_count == 20){ echo 'selected=""'; } ?> >20</option>
                    <option value="21" <?php if($booking_hour_count == 21){ echo 'selected=""'; } ?> >21</option>
                    <option value="22" <?php if($booking_hour_count == 22){ echo 'selected=""'; } ?> >22</option>
                    <option value="23" <?php if($booking_hour_count == 23){ echo 'selected=""'; } ?> >23</option>
                    <option value="24" <?php if($booking_hour_count == 24){ echo 'selected=""'; } ?> >24</option>
                </select>                    
            </div>                        
        </div>
    </div>        
    <?php }else{ ?>
    <div class="booking-change-date-box mt15" >
        <div class="row" >
            <div class="col-lg-12" >
                <p><?php echo $ULang->t('Дата начала'); ?></p>
                <input type="text" class="form-control" name="booking_date_start" value="<?php echo $booking_date_start; ?>" >                    
            </div>
            <div class="col-lg-12 mt15" >
                <p><?php echo $ULang->t('Дата окончания'); ?></p>
                <input type="text" class="form-control" name="booking_date_end" value="<?php echo $booking_date_end; ?>" >
            </div>                        
        </div>
    </div>            
    <?php } ?>

<?php } ?>

<?php if($booking_additional_services){ ?>
    <p class="mt15" ><strong><?php echo $ULang->t('Дополнительные услуги'); ?></strong></p>

    <div class="booking-additional-services-box" >
        <?php
          foreach ($booking_additional_services as $key => $value) {

              $checked = '';

              if($_POST['booking_additional_services']){ 
                 if($_POST['booking_additional_services'][$key]){
                    $checked = 'checked=""';
                 }
              }

              ?>
              <div class="booking-additional-services-box-item" >
                  <div class="row" >
                      <div class="col-lg-8" >
                          <label class="checkbox">
                            <input type="checkbox" <?php echo $checked; ?> name="booking_additional_services[<?php echo $key; ?>]" value="1" >
                            <span></span>
                            <?php echo $value['name']; ?>
                          </label>                                          
                      </div>
                      <div class="col-lg-4 text-right" >
                          <strong><?php echo $Main->price($value['price']); ?></strong>
                      </div>
                  </div>                                  
              </div>
              <?php

          }
        
        ?>
    </div>
<?php } ?>


<div class="modal-booking-box-total mt25" >

    <?php if($getAd["category_board_booking_variant"] == 0){ ?>
        <span class="modal-booking-box-total-title1" ><?php echo $Main->price($getAd["ads_price"]); ?> × <?php echo $difference_days; ?> <?php echo ending($difference_days, $ULang->t('день'), $ULang->t('дня'), $ULang->t('дней')); ?></span>
    <?php }else{ ?>

        <?php if($getAd['ads_price_measure'] == 'hour'){ ?>
        <span class="modal-booking-box-total-title1" ><?php echo $Main->price($getAd["ads_price"]); ?> × <?php echo $booking_hour_count; ?> <?php echo ending($booking_hour_count, $ULang->t('час'), $ULang->t('часа'), $ULang->t('часов')); ?></span>
        <?php }else{ ?>
        <span class="modal-booking-box-total-title1" ><?php echo $Main->price($getAd["ads_price"]); ?> × <?php echo $difference_days; ?> <?php echo ending($difference_days, $ULang->t('день'), $ULang->t('дня'), $ULang->t('дней')); ?></span>
        <?php } ?>

    <?php } ?>

   <?php if($getAd["ads_booking_prepayment_percent"]){ ?>
        <span class="modal-booking-box-total-title1" ><?php echo $ULang->t('Предоплата'); ?> <?php echo $Main->price($prepayment); ?></span>  
   <?php } ?>  

    <span class="modal-booking-box-total-title2" ><?php echo $ULang->t('Итого:'); ?> <span class="modal-booking-box-total-price" ><?php echo $Main->price($total); ?></span></span>

</div>

<div class="mt25" >

    <button class="button-style-custom color-green mb5 modal-booking-add-order" ><?php echo $ULang->t('Оформить заказ'); ?></button>

</div>