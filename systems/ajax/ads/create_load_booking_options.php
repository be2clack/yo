<?php

$rand_id = mt_rand(10000, 90000);

echo '
   <div class="booking-additional-services-item" >
        <div class="booking-additional-services-item-row" >
            <div class="booking-additional-services-item-row1" >
                <input type="text" name="booking_additional_services['.$rand_id.'][name]" placeholder="'.$ULang->t("Название услуги").'" class="ads-create-input" >
            </div>
            <div class="booking-additional-services-item-row2" >
                <div class="input-dropdown" >
                   <input type="text" name="booking_additional_services['.$rand_id.'][price]" placeholder="'.$ULang->t("Цена").'" class="ads-create-input" maxlength="11" > 
                   <div class="input-dropdown-box">
                      <div class="uni-dropdown-align" >
                         <span class="input-dropdown-name-display"> '.$settings["currency_main"]["sign"].' </span>
                      </div>
                   </div>
                </div>
            </div>
            <div class="booking-additional-services-item-row3" >
                <span class="booking-additional-services-item-delete" ><i class="las la-trash-alt"></i></span>
            </div>                                                                
        </div>
   </div>
';

?>