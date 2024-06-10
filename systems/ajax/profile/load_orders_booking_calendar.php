<?php

$date = $_POST['date'] ? date('Y-m-d', strtotime($_POST['date'])) : '';
$id_ad = intval($_POST['id_ad']);

if($date){
if($id_ad){
    $getDates = getAll("select * from uni_ads_booking_dates where date(ads_booking_dates_date)=? and ads_booking_dates_id_user=? and ads_booking_dates_id_ad=? and ads_booking_dates_id_order!=?", [ $date,intval($_SESSION['profile']['id']),$id_ad,0 ]);
}else{
    $getDates = getAll("select * from uni_ads_booking_dates where date(ads_booking_dates_date)=? and ads_booking_dates_id_user=? and ads_booking_dates_id_order!=?", [ $date,intval($_SESSION['profile']['id']),0 ]);
}
if($getDates){
    ?>
    <div class="row" >
    <?php
        foreach ($getDates as $date_value) {
            
        $getOrders = getAll("select * from uni_ads_booking where ads_booking_id=?", [$date_value['ads_booking_dates_id_order']]);
        
        if($getOrders){
            foreach ($getOrders as $value) {
                include $config["template_path"] . "/include/booking_order_list.php";
            }
        }
            
        }
    ?>
    </div>
    <?php
}else{
    ?>
    <div class="user-block-no-result mt25 mb25" >
        <img src="<?php echo $settings["path_tpl_image"]; ?>/card-placeholder.svg">
        <p><?php echo $ULang->t("Заказов нет"); ?></p>
    </div>
    <?php 
    if($id_ad && $date >= date('Y-m-d')){ 
        $checkCancelDate = findOne('uni_ads_booking_dates', 'date(ads_booking_dates_date)=? and ads_booking_dates_id_user=? and ads_booking_dates_id_ad=? and ads_booking_dates_id_order=?', [$date,intval($_SESSION['profile']['id']),$id_ad,0]);
        if(!$checkCancelDate){
        ?>
        <button class="button-style-custom color-blue modal-booking-calendar-cancel-date width100" data-id-ad="<?php echo $id_ad; ?>" data-date="<?php echo $date; ?>" ><?php echo $ULang->t("Запретить бронь на эту дату"); ?></button>
        <?php              
        }else{
        ?>
        <button class="button-style-custom color-green modal-booking-calendar-allow-date width100" data-id-ad="<?php echo $id_ad; ?>" data-date="<?php echo $date; ?>" ><?php echo $ULang->t("Разрешить бронь"); ?></button>
        <?php 
        }
    }
}
}

?>