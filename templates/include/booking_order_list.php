<div class="col-lg-12" >
  <div class="item-list-order" >

     <div class="row" >
       <div class="col-lg-12" >
          <div class="item-list-content" >
            
            <span class="item-list-order-status order-booking-status-label-<?php echo $value['ads_booking_status']; ?>" >
              <?php
                  if($value['ads_booking_status'] == 0){
                      echo $ULang->t("Новый");
                  }elseif($value['ads_booking_status'] == 1){
                      echo $ULang->t("Подтвержден");
                  }elseif($value['ads_booking_status'] == 2){
                      echo $ULang->t("Отменен");
                  }
              ?>
            </span>

            <div class="mt10" >
              <a href="<?php echo _link( "booking/" . $value["ads_booking_id_order"] ); ?>">
              <span class="item-list-order-number" ><?php echo $ULang->t("Заказ"); ?> №<?php echo $value["ads_booking_id_order"]; ?></span>
              </a>
              <span class="item-list-order-date" ><?php echo $ULang->t("создан"); ?> <?php echo datetime_format($value["ads_booking_date_add"]); ?></span>
            </div>

          </div>
       </div>
     </div>


  </div>
</div>