<?php

$id = (int)$_POST["id"];
$variant = clear($_POST["variant"]);
$booking = $_POST["booking"] == 'true' ? 1 : 0;

$getCategories = $CategoryBoard->getCategories("where category_board_visible=1");

$field_price_name = $Main->nameInputPrice($getCategories["category_board_id"][$id]["category_board_variant_price_id"]);

if(!$settings["ad_create_currency"]){

$dropdown_currency = '
      <div class="input-dropdown-box">
        <div class="uni-dropdown-align" >
           <span class="input-dropdown-name-display"> '.$settings["currency_main"]["sign"].' </span>
        </div>
      </div>
';

}else{

$getCurrency = getAll("select * from uni_currency order by id_position asc");
if ($getCurrency) {
  foreach ($getCurrency as $key => $value) {
     $list_currency .= '<span data-value="'.$value["code"].'" data-name="'.$value["sign"].'" data-input="currency" >'.$value["name"].' ('.$value["sign"].')</span>';
  }
}

$dropdown_currency = '
    <div class="input-dropdown-box">
      
        <span class="uni-dropdown-bg">
         <div class="uni-dropdown uni-dropdown-align" >
            <span class="uni-dropdown-name" > <span>'.$settings["currency_main"]["sign"].'</span> <i class="las la-angle-down"></i> </span>
            <div class="uni-dropdown-content" >
               '.$list_currency.'
            </div>
         </div>
        </span>

    </div>
';

}

$getShop = $Shop->getUserShop( $_SESSION["profile"]["id"] );

if($getShop && $getCategories["category_board_id"][$id]["category_board_rules"]["accept_promo"]){

 $data["stock"] = '
    <div class="ads-create-main-data-box-item" style="margin-bottom: 25px;" >
        <p class="ads-create-subtitle" >Акция</p>
        <div class="create-info" ><i class="las la-question-circle"></i> '.$ULang->t("Вы можете включить акцию для своего объявления. В каталоге объявлений будет показываться старая и новая цена. Акция работает только при активном магазине.").'</div>
        <div class="custom-control custom-checkbox mt15">
            <input type="checkbox" class="custom-control-input" name="stock" id="stock" value="1">
            <label class="custom-control-label" for="stock">'.$ULang->t("Включить акцию").'</label>
        </div>
    </div>
 ';

}

if($getCategories["category_board_id"][$id]["category_board_measures_price"]){

    $measuresPrice = json_decode($getCategories["category_board_id"][$id]["category_board_measures_price"], true);

    if($measuresPrice){

        foreach ($measuresPrice as $value) {
           $listMeasures .= '<label> <input type="radio" name="measure" value="'.$value.'" > <span>'.getNameMeasuresPrice($value).'</span> <i class="la la-check"></i> </label>';
        }

        $measures = '
            <div class="col-lg-6" >
                <div class="uni-select" data-status="0" >

                     <div class="uni-select-name" data-name="'.$ULang->t("Не выбрано").'" > <span>'.$ULang->t("Не выбрано").'</span> <i class="la la-angle-down"></i> </div>
                     <div class="uni-select-list" >
                         '.$listMeasures.'
                     </div>
                
                </div>
                <div class="msg-error" data-name="measure" ></div> 
            </div>
        ';

        $measures_lg4 = '
            <div class="col-lg-4" >
                <div class="uni-select" data-status="0" >

                     <div class="uni-select-name" data-name="'.$ULang->t("Не выбрано").'" > <span>'.$ULang->t("Не выбрано").'</span> <i class="la la-angle-down"></i> </div>
                     <div class="uni-select-list" >
                         '.$listMeasures.'
                     </div>
                
                </div> 
                <div class="msg-error" data-name="measure" ></div>
            </div>
        ';

    }

}

if($getCategories["category_board_id"][$id]["category_board_rules"]["measure_booking"]){
 if(!$booking) $measures = '';
}

if( $variant == "fix" ){

   $data["price"] .= '
      <div class="ads-create-main-data-box-item" style="margin-top: 0px;" >
      <div class="row" >

        <div class="col-lg-6" >

            <div class="input-dropdown" >
               <input type="text" name="price" placeholder="'.$field_price_name.'" class="ads-create-input inputNumber" maxlength="11" > 
               '.$dropdown_currency.'
            </div>
            <div class="msg-error" data-name="price" ></div>

        </div>
   ';

    $data["price"] .= $measures;
        
    if( $getCategories["category_board_id"][$id]["category_board_rules"]["free_price"] ){

        $data["price"] .= '
        <div class="col-lg-6" >

            <div class="custom-control custom-checkbox mt10">
                <input type="checkbox" class="custom-control-input" name="price_free" id="price_free" value="1">
                <label class="custom-control-label" for="price_free">'.$ULang->t("Отдам даром").'</label>
            </div>

        </div> 
        ';

    }

   $data["price"] .= '
      </div> 
      </div>          
   ';

}elseif( $variant == "auction" ){

   $data["stock"] = '';

   $data["price"] .= '
        
        <div class="ads-create-main-data-box-item" >

            <p class="ads-create-subtitle" >'.$ULang->t("С какой цены начать торг?").'</p>

            <div class="row" >
              <div class="col-lg-6" >
                  <div class="input-dropdown" >
                     <input type="text" name="price" class="ads-create-input inputNumber" maxlength="11" > 
                     '.$dropdown_currency.'
                  </div>
                  <div class="msg-error" data-name="price" ></div>
              </div>
            </div>

        </div>

        <div class="ads-create-main-data-box-item" >

            <p class="ads-create-subtitle" >'.$ULang->t("Цена продажи").'</p>
            <div class="create-info" ><i class="las la-question-circle"></i> '.$ULang->t("Укажите цену, за которую вы готовы сразу продать товар или оставьте это поле пустым если у аукциона нет ограничений по цене.").'</div>

            <div class="mt15" ></div>

            <div class="row" >
              <div class="col-lg-6" >
                  <div class="input-dropdown" >
                     <input type="text" name="auction_price_sell" class="ads-create-input inputNumber" maxlength="11" > 
                     <div class="input-dropdown-box">
                        <div class="uni-dropdown-align" >
                           <span class="input-dropdown-name-display static-currency-sign"> '.$settings["currency_main"]["sign"].' </span>
                        </div>
                     </div>
                  </div>
                  <div class="msg-error" data-name="auction_price_sell" ></div>
              </div>
            </div>

        </div>

        <div class="ads-create-main-data-box-item" >

            <p class="ads-create-subtitle" >'.$ULang->t("Длительность торгов").'</p>
            <div class="create-info" ><i class="las la-question-circle"></i> '.$ULang->t("Укажите срок действия аукциона от 1-го до 30-ти дней.").'</div>

            <div class="mt15" ></div>

            <div class="row" >
              <div class="col-lg-3" >
                  <input type="text" name="auction_duration_day" class="ads-create-input" maxlength="2" value="1" > 
                  <div class="msg-error" data-name="auction_duration_day" ></div>
              </div>
            </div>

        </div>

    ';
                   

}elseif( $variant == "stock" ){

   if($measures){

       $data["price"] .= '
       <div class="ads-create-main-data-box-item" style="margin-top: 0px;" >
          <div class="row" >
            <div class="col-lg-4" >

                <div class="input-dropdown" >
                   <input type="text" name="price" placeholder="'.$ULang->t("Старая цена").'" class="ads-create-input inputNumber" maxlength="11" > 
                   '.$dropdown_currency.'
                </div>
                <div class="msg-error" data-name="price" ></div>

            </div>
            <div class="col-lg-4" >

                <div class="input-dropdown" >
                   <input type="text" name="stock_price" placeholder="'.$ULang->t("Новая цена").'" class="ads-create-input inputNumber" maxlength="11" > 
                   <div class="input-dropdown-box">
                      <div class="uni-dropdown-align" >
                         <span class="input-dropdown-name-display static-currency-sign"> '.$settings["currency_main"]["sign"].' </span>
                      </div>
                   </div>
                </div>

            </div> 
            '.$measures_lg4.'               
          </div>
       </div>
       ';

   }else{

       $data["price"] .= '
       <div class="ads-create-main-data-box-item" style="margin-top: 0px;" >
          <div class="row" >
            <div class="col-lg-6" >

                <div class="input-dropdown" >
                   <input type="text" name="price" placeholder="'.$ULang->t("Старая цена").'" class="ads-create-input inputNumber" maxlength="11" > 
                   '.$dropdown_currency.'
                </div>
                <div class="msg-error" data-name="price" ></div>

            </div>
            <div class="col-lg-6" >

                <div class="input-dropdown" >
                   <input type="text" name="stock_price" placeholder="'.$ULang->t("Новая цена").'" class="ads-create-input inputNumber" maxlength="11" > 
                   <div class="input-dropdown-box">
                      <div class="uni-dropdown-align" >
                         <span class="input-dropdown-name-display static-currency-sign"> '.$settings["currency_main"]["sign"].' </span>
                      </div>
                   </div>
                </div>

            </div>                
          </div>
       </div>
       ';

   }


}elseif( $variant == "from" ){

   $data["price"] .= '
      <div class="ads-create-main-data-box-item" style="margin-top: 0px;" >
      <div class="row" >

        <div class="col-lg-6" >

            <div class="ads-create-main-data-box-item-flex" >
                <div class="ads-create-main-data-box-item-flex1" >
                    <span>'.$ULang->t("От").'</span>
                </div>
                <div class="ads-create-main-data-box-item-flex2" >
                    <div class="input-dropdown" >
                       <input type="text" name="price" placeholder="'.$field_price_name.'" class="ads-create-input inputNumber" maxlength="11" > 
                       '.$dropdown_currency.'
                    </div>
                    <div class="msg-error" data-name="price" ></div>
                </div>                        
            </div>

        </div>
   ';

   $data["price"] .= $measures;
        
   $data["price"] .= '
      </div> 
      </div>          
   ';

}elseif( $variant == "booking_measure" ){

   if($getCategories["category_board_id"][$id]["category_board_booking_variant"] == 0){

       $data["booking_options"] = '
           <div class="ads-create-main-data-box-item" >

               <p class="ads-create-subtitle" >'.$ULang->t("Предоплата").'</p>

               <div class="create-info"><i class="las la-question-circle"></i> '.$ULang->t("Оставьте это поле пустым если предоплата не требуется.").'</div>

               <div class="mb15" ></div>

               <div class="row" >
                
                <div class="col-lg-6" >
                    <div class="input-dropdown" >
                       <input type="number" name="booking_prepayment_percent" placeholder="'.$ULang->t("Процент предоплаты").'" class="ads-create-input" maxlength="3" > 
                       <div class="input-dropdown-box">
                          <div class="uni-dropdown-align" >
                             <span class="input-dropdown-name-display">%</span>
                          </div>
                       </div>
                    </div>
                </div>
                 
               </div>

               <div class="mb25" ></div>

               <p class="ads-create-subtitle" >'.$ULang->t("Максимальное количество гостей").'</p>

               <div class="row" >
                
                <div class="col-lg-6" >
                    <input type="number" name="booking_max_guests" class="ads-create-input" maxlength="11" value="3" >
                </div>
                 
               </div>

               <div class="mb25" ></div>

               <div class="create-info"><i class="las la-question-circle"></i> '.$ULang->t("Оставьте эти поля пустыми если ограничений нет.").'</div>

               <div class="mb15" ></div>

               <p class="ads-create-subtitle" >'.$ULang->t("Минимум дней аренды").'</p>

               <div class="row" >
                
                <div class="col-lg-6" >
                    <input type="number" name="booking_min_days" class="ads-create-input" maxlength="11" >
                </div>
                 
               </div>

               <div class="mb25" ></div>

               <p class="ads-create-subtitle" >'.$ULang->t("Максимум дней аренды").'</p>

               <div class="row" >
                
                <div class="col-lg-6" >
                    <input type="number" name="booking_max_days" class="ads-create-input" maxlength="11" >
                </div>
                 
               </div>

               <div class="mb25" ></div>

               <p class="ads-create-subtitle data-count-services" data-count-services="'.$settings['count_add_booking_additional_services'].'" >'.$ULang->t("Дополнительные услуги").' <span class="booking-additional-services-item-add btn-custom-mini btn-custom-mini-icon btn-color-blue-light" ><i class="las la-plus"></i></span></p>

               <div class="booking-additional-services-container" ></div>

               <div class="mb25" ></div>
           </div>           
       ';

   }elseif($getCategories["category_board_id"][$id]["category_board_booking_variant"] == 1){

       $data["booking_options"] = '
           <div class="ads-create-main-data-box-item" >

               <p class="ads-create-subtitle" >'.$ULang->t("Предоплата").'</p>

               <div class="create-info"><i class="las la-question-circle"></i> '.$ULang->t("Оставьте это поле пустым если предоплата не требуется.").'</div>

               <div class="mb15" ></div>

               <div class="row" >
                
                <div class="col-lg-6" >
                    <div class="input-dropdown" >
                       <input type="number" name="booking_prepayment_percent" placeholder="'.$ULang->t("Процент предоплаты").'" class="ads-create-input" maxlength="3" > 
                       <div class="input-dropdown-box">
                          <div class="uni-dropdown-align" >
                             <span class="input-dropdown-name-display">%</span>
                          </div>
                       </div>
                    </div>
                </div>
                 
               </div>

               <div class="mb25" ></div>

               <p class="ads-create-subtitle" >'.$ULang->t("Доступно").'</p>

               <div class="create-info"><i class="las la-question-circle"></i> '.$ULang->t("Укажите сколько единиц доступно для аренды. По истечению лимита аренда будет недоступна. Система автоматически вернет возможность аренды после того, как у пользователя закончится выбранный срок.").'</div>

               <div class="mb15" ></div>

               <div class="row" >
                
                <div class="col-lg-6" >
                    <input type="number" name="booking_available" placeholder="'.$ULang->t("Доступно").'" class="ads-create-input" maxlength="3" >
                </div>
                 
                <div class="col-lg-6" >
                    <div class="custom-control custom-checkbox mt10">
                        <input type="checkbox" class="custom-control-input" name="booking_available_unlimitedly" id="booking_available_unlimitedly" value="1" >
                        <label class="custom-control-label" for="booking_available_unlimitedly">'.$ULang->t("Неограниченно").'</label>
                    </div>                                                
                </div>

               </div>

               <div class="mb25" ></div>

               <p class="ads-create-subtitle data-count-services" data-count-services="'.$settings['count_add_booking_additional_services'].'" >'.$ULang->t("Дополнительные услуги").' <span class="booking-additional-services-item-add btn-custom-mini btn-custom-mini-icon btn-color-blue-light" ><i class="las la-plus"></i></span></p>

               <div class="booking-additional-services-container" ></div>

               <div class="mb25" ></div>
           </div>           
       ';

   }

   $data["price"] .= '
       <div class="ads-create-main-data-box-item" >
           <p class="ads-create-subtitle" >'.$field_price_name.'</p>           
           <div class="row" >
               <div class="col-lg-6" >
                  <div data-var="fix" class="ads-create-main-data-price-variant" >
                     <div>
                       <span class="ads-create-main-data-price-variant-name" >'.$ULang->t("Фиксированная").'</span>
                     </div>
                  </div>
               </div>
               <div class="col-lg-6" >
                  <div data-var="from" class="ads-create-main-data-price-variant" >
                     <div>
                       <span class="ads-create-main-data-price-variant-name" >'.$ULang->t("Не фиксированная").'</span>
                     </div>
                  </div>
               </div>                                    
           </div>
           <div class="mb25" ></div>
           <div class="ads-create-main-data-stock-container" ></div>
           <div class="ads-create-main-data-price-container" ></div>
       </div>
   ';

}

echo json_encode( $data );
?>