
<h3 class="mb35 user-title" > <strong><?php echo $ULang->t("Календарь бронирования"); ?></strong> </h3>

<?php if($_SESSION["profile"]["tariff"]["services"]["booking_calendar"]){ ?>

<div class="row" >

   <div class="col-lg-4 mb5" >
       <div class="uni-select profile-booking-calendar-change-ad" data-status="0">

           <div class="uni-select-name" data-name="<?php echo $ULang->t("Все объявления"); ?>"> <span><?php echo $ULang->t("Все объявления"); ?></span> <i class="la la-angle-down"></i> </div>
           <div class="uni-select-list">
                
                <label> <input type="radio" value="<?php echo _link("user/".$user["clients_id_hash"]."/booking-calendar"); ?>"> <span><?php echo $ULang->t("Все объявления"); ?></span> <i class="la la-check"></i> </label>

                <?php
                $getAds = $Ads->getAll( [ "navigation" => false, "query" => "ads_id_user='".$user["clients_id"]."' and ads_booking='1' and ads_status!='8'", "sort" => "order by ads_id desc" ] );

                if($getAds['count']){
                    foreach ($getAds['all'] as $value) {
                        
                        if($_GET['ad']){
                            if($value['ads_id'] == intval($_GET['ad'])){
                                $active = 'class="uni-select-item-active"';
                            }else{
                                $active = '';
                            }
                        }

                        echo '<label '.$active.' > <input type="radio" value="'._link("user/".$value["clients_id_hash"]."/booking-calendar?ad=".$value['ads_id']).'"> <span>'.$value['ads_title'].'</span> <i class="la la-check"></i> </label>';
                    }
                }

                ?>

           </div>
          
        </div>
    </div>

</div>

<div class="profile-booking-calendar-container mt15" >
    <div class="profile-booking-calendar" >
        
      <div class="preload" >

          <div class="spinner-grow mt35 preload-spinner" role="status">
            <span class="sr-only"></span>
          </div>

      </div>
        
    </div>
</div>

<input type="hidden" value="<?php echo isset($_GET['ad']) ? intval($_GET['ad']) : 0; ?>" class="booking-calendar-input-change-ad" >

<?php }else{ ?>

   <div class="user-block-promo" >
     
     <div class="row no-gutters" >
        <div class="col-lg-8" >
          <div class="user-block-promo-content" >
            <p>
               <?php echo $ULang->t("Календарь бронирования. С помощью календаря Вы сможете просматривать заказы по бронированию и аренде по дням, месяцам и годам, а так же управлять днями, какие дни свободные для аренды, а какие нет."); ?>
            </p>
            <a class="btn-custom btn-color-blue mt15 display-inline" href="<?php echo _link('tariffs'); ?>" ><?php echo $ULang->t("Подключить тариф"); ?></a>
          </div>
        </div>
        <div class="col-lg-4 d-none d-lg-block" style="text-align: center;" >
            <img src="<?php echo $settings["path_tpl_image"]; ?>/promo-tariff-calendar.png" height="180px" style="margin-top: 25px;" >
        </div>
     </div>

   </div>

<?php } ?>