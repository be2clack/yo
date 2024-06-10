<?php
$config = require "./config.php";

$route_name = "ad_update";
$visible_footer = false;

if( !$_SESSION['cp_auth'][ $config["private_hash"] ] && !$_SESSION['cp_control_board'] ){

	if(!$_SESSION["profile"]["id"]){
	   header("location:" . _link("auth", true) );
	}

}

$Main = new Main();
$settings = $Main->settings();

$CategoryBoard = new CategoryBoard(); 
$Ads = new Ads();
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$Filters = new Filters();
$Banners = new Banners();
$ULang = new ULang();
$Shop = new Shop();
$Cart = new Cart();

if( $_SESSION['cp_control_board'] ){

   $data = $Ads->get("ads_id=?",[$id_ad]);

}else{

   $data = $Ads->get("ads_id='$id_ad' and ads_id_user='".intval($_SESSION["profile"]["id"])."' and clients_status IN(0,1) and ads_status IN(0,7,2,1)");

}

if(!$data){
	$Main->response("404");
}


$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

if(count($getCategoryBoard["category_board_id_parent"][0])){
	foreach ($getCategoryBoard["category_board_id_parent"][0] as $key => $value) {
	  $data["list_categories"] .= '<span class="box-change-category-list-item" data-name="'.$ULang->t($value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ).'" data-id="'.$key.'" >'.$ULang->t($value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ).'</span>';
	}
}

if($settings["ad_create_period"]){
	$ad_create_period_list = explode(",", $settings["ad_create_period_list"]);
	if ($ad_create_period_list) {
	  foreach ($ad_create_period_list as $key => $value) {

	  	if( $value == $data["ads_period_day"] ){
	  		$active = 'class="uni-select-item-active"';
	  	}else{
	  		$active = '';
	  	}

	  	$list_period .= '<label '.$active.' > <input type="radio" name="period" value="'.$value.'"> <span>'.$value.' '.ending($value, $ULang->t("день") , $ULang->t("дня"),$ULang->t("дней") ).'</span> <i class="la la-check"></i> </label>';
	  }
	}
}

if($data["ads_booking_additional_services"]){
	$booking_additional_services = json_decode($data["ads_booking_additional_services"], true);
	if($booking_additional_services){
		foreach ($booking_additional_services as $key => $value) {
			$data['booking_additional_services'] .= '
		           <div class="booking-additional-services-item" >
		                <div class="booking-additional-services-item-row" >
		                    <div class="booking-additional-services-item-row1" >
		                        <input type="text" name="booking_additional_services['.$key.'][name]" placeholder="'.$ULang->t("Название услуги").'" value="'.$value['name'].'" class="ads-create-input" >
		                    </div>
		                    <div class="booking-additional-services-item-row2" >
		                        <div class="input-dropdown" >
		                           <input type="text" name="booking_additional_services['.$key.'][price]" placeholder="'.$ULang->t("Цена").'" value="'.$value['price'].'" class="ads-create-input" maxlength="11" > 
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
		}
	}
}

$load_filters_ad = $Filters->load_filters_ad($data["ads_id_cat"],$Filters->getVariants($data["ads_id"]));

if( $load_filters_ad ){

 $getCategory = $Filters->getCategory( ["id_cat" => $data["ads_id_cat"]] );

 if( $getCategory ){

     $getFilters = getAll( "select * from uni_ads_filters where ads_filters_id IN(".implode(",", $getCategory).")" );

     if(count($getFilters)){

        foreach ( $getFilters as $key => $value) {
            $list_filters[] = $value["ads_filters_name"];
        }

        $data["filters"] = '
           <div class="ads-create-main-data-box-item" >
              <p class="ads-create-subtitle" >'.$ULang->t("Характеристики").'</p>

              <div class="create-info" ><i class="las la-question-circle"></i> '.$ULang->t("Укажите как можно больше параметров - это повысит интерес к объявлению.").'</div>
              <div class="mb25" ></div>
              '.$load_filters_ad.'

           </div> 
        ';

     }

 }

}

$getArea = getAll("select * from uni_city_area where city_area_id_city=? order by city_area_name asc", [$data["ads_city_id"]]);

if(count($getArea)){

	foreach ($getArea as $key => $value) {
        
        if( $data["ads_area_ids"] ){

			if( in_array($value["city_area_id"], explode(",", $data["ads_area_ids"]) ) ){
	            $active = 'class="uni-select-item-active"'; $checked = 'checked=""';			
			}else{
	            $active = ''; $checked = '';
			}

	    }

		$list_area .= '
           <label '.$active.' > <input '.$checked.' type="radio" name="area[]" value="'.$value["city_area_id"].'" > <span>'.$value["city_area_name"].'</span> <i class="la la-check"></i> </label>
		';
	}

    $data["city_options"] .= '
	    <div class="ads-create-main-data-box-item" >      
	    <p class="ads-create-subtitle" >'.$ULang->t("Район").'</p> 

	   	     <div class="ads-create-main-data-city-options-area" >
	            <div class="uni-select" data-status="0" >

	                 <div class="uni-select-name" data-name="'.$ULang->t("Не выбрано").'" > <span>'.$ULang->t("Не выбрано").'</span> <i class="la la-angle-down"></i> </div>
	                 <div class="uni-select-list" >
	                     '.$list_area.'
	                 </div>
	            
	            </div>
	         </div>

	    </div>
    ';

}


if($data["ads_metro_ids"]){

	$getMetro = getAll("select * from uni_metro where id IN(".$data["ads_metro_ids"].")");

	if(count($getMetro)){
		foreach ($getMetro as $key => $value) {
			$main = findOne("uni_metro", "id=?", [$value["parent_id"]]);
			if($main){
				$list_metro .= '
		           <span><i style="background-color:'.$main["color"].';"></i>'.$value["name"].' <i class="las la-times ads-metro-delete"></i><input type="hidden" value="'.$value["id"].'" name="metro[]"></span>
				';
		    }
		}
	}

}

$checkMetro = findOne( "uni_metro", "city_id=?", [ $data["ads_city_id"] ]);

if( $checkMetro ){

	$data["city_options"] .= '
	<div class="ads-create-main-data-box-item" >      
	<p class="ads-create-subtitle" >'.$ULang->t("Ближайшее метро").'</p>

		     <div class="ads-create-main-data-city-options-metro" >
	        <div class="container-custom-search" >
	          <input type="text" class="form-control action-input-search-metro" placeholder="'.$ULang->t("Начните вводить станции, а потом выберите ее из списка").'" >
	          <div class="custom-results SearchMetroResults" ></div>
	        </div>

	        <div class="ads-container-metro-station" >'.$list_metro.'</div>
	     </div>

	</div>
	';

}


if(!$settings["ad_create_currency"]){

	$dropdown_currency = '
	      <div class="input-dropdown-box">
	        <div class="uni-dropdown-align" >
	           <span class="input-dropdown-name-display"> '.$settings["currency_data"][ $data["ads_currency"] ]["sign"].' </span>
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
	            <span class="uni-dropdown-name" > <span>'.$settings["currency_data"][ $data["ads_currency"] ]["sign"].'</span> <i class="las la-angle-down"></i> </span>
	            <div class="uni-dropdown-content" >
	               '.$list_currency.'
	            </div>
	         </div>
	        </span>

	    </div>
	';

}


$field_price_name = $Main->nameInputPrice($getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_variant_price_id"]);
$getShop = $Shop->getUserShop( $_SESSION["profile"]["id"] );


if($getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_display_price"]){


  $data["price"] .= '
      <div class="ads-create-main-data-box-item" >
      <p class="ads-create-subtitle" >'.$field_price_name.'</p>
  ';


  if($data["ads_auction"]){

       $price = '

            <div class="ads-create-main-data-box-item" >

                <p class="ads-create-subtitle" >'.$ULang->t("С какой цены начать торг?").'</p>

                <div class="row" >
                  <div class="col-lg-6" >
                      <div class="input-dropdown" >
                         <input type="text" name="price" class="ads-create-input inputNumber" value="'.number_format($data["ads_price"],0,"."," ").'" maxlength="11" > 
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
                         <input type="text" name="auction_price_sell" class="ads-create-input inputNumber" value="'.number_format($data["ads_auction_price_sell"],0,"."," ").'" maxlength="11" > 
                         <div class="input-dropdown-box">
                            <div class="uni-dropdown-align" >
                               <span class="input-dropdown-name-display static-currency-sign"> '.$settings["currency_data"][ $data["ads_currency"] ]["sign"].' </span>
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
                      <input type="text" name="auction_duration_day" value="'.$data["ads_auction_day"].'" class="ads-create-input" maxlength="2" value="1" > 
                      <div class="msg-error" data-name="auction_duration_day" ></div>
                  </div>
                </div>

            </div>

       ';

	   $data["price"] .= '
	       <div class="row" >
	           <div class="col-lg-4" >
	              <div data-var="fix" class="ads-create-main-data-price-variant" >
	                 <div>
	                   <span class="ads-create-main-data-price-variant-name" >'.$ULang->t("Фиксированная").'</span>
	                 </div>
	              </div>
	           </div>
	           <div class="col-lg-4" >
	              <div data-var="from" class="ads-create-main-data-price-variant" >
	                 <div>
	                   <span class="ads-create-main-data-price-variant-name" >'.$ULang->t("Не фиксированная").'</span>
	                 </div>
	              </div>
	           </div>               
	           <div class="col-lg-4" >
	              <div data-var="auction" class="ads-create-main-data-price-variant ads-create-main-data-price-variant-active" >
	                 <div>
	                   <span class="ads-create-main-data-price-variant-name" >'.$ULang->t("Аукцион").'</span>
	                 </div>                          
	              </div>
	           </div>                     
	       </div>
	       <div class="mb25" ></div>
	       <div class="ads-create-main-data-stock-container" >'.$stock.'</div>
	       <div class="ads-create-main-data-price-container" >'.$price.'</div>
	   ';

  }else{

       if($getShop && $getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_rules"]["accept_promo"]){

         $stock = '
            <div class="ads-create-main-data-box-item" style="margin-bottom: 25px;" >
                <p class="ads-create-subtitle" >'.$ULang->t("Акция").'</p>
                <div class="create-info" ><i class="las la-question-circle"></i> '.$ULang->t("Вы можете включить акцию для своего объявления. В каталоге объявлений будет показываться старая и новая цена. Акция работает только при активном магизине.").'</div>
                <div class="custom-control custom-checkbox mt15">
                    <input type="checkbox" class="custom-control-input" name="stock" '.($data["ads_price_old"] ? 'checked=""' : '').' id="stock" value="1">
                    <label class="custom-control-label" for="stock">'.$ULang->t("Включить акцию").'</label>
                </div>
            </div>
         ';

       }  

       if($getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_measures_price"]){

            $measuresPrice = json_decode($getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_measures_price"], true);

            if($measuresPrice){

                foreach ($measuresPrice as $value) {

                   if($value == $data["ads_price_measure"]){
                   	  $activeMeasure = 'class="uni-select-item-active"';
                   	  $checkedMeasure = 'checked=""';
                   }else{
                   	  $activeMeasure = '';
                   	  $checkedMeasure = '';
                   }

                   $listMeasures .= '<label '.$activeMeasure.' > <input type="radio" '.$checkedMeasure.' name="measure" value="'.$value.'" > <span>'.getNameMeasuresPrice($value).'</span> <i class="la la-check"></i> </label>';
                }

                $measures = '
                    <div class="col-lg-6" >
                        <div class="uni-select" data-status="0" >

                             <div class="uni-select-name" data-name="'.$ULang->t("Не выбрано").'" value="1" > <span>'.$ULang->t("Не выбрано").'</span> <i class="la la-angle-down"></i> </div>
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
                                 <label> <input type="radio" name="measure" value="1" > <span>'.$ULang->t("Не выбрано").'</span> <i class="la la-check"></i> </label>
                                 '.$listMeasures.'
                             </div>
                        
                        </div> 
                        <div class="msg-error" data-name="measure" ></div>
                    </div>
                ';

            }

       }

       if($data["ads_price_from"]){

           $price = '
              <div class="ads-create-main-data-box-item" >
              <div class="row" >

                <div class="col-lg-6" >

                    <div class="ads-create-main-data-box-item-flex" >
                        <div class="ads-create-main-data-box-item-flex1" >
                            <span>'.$ULang->t("От").'</span>
                        </div>
                        <div class="ads-create-main-data-box-item-flex2" >
                            <div class="input-dropdown" >
                               <input type="text" name="price" placeholder="'.$field_price_name.'" value="'.number_format($data["ads_price"],0,"."," ").'" class="ads-create-input inputNumber" maxlength="11" > 
                               '.$dropdown_currency.'
                            </div>
                            <div class="msg-error" data-name="price" ></div>
                        </div>                        
                    </div>

                </div>

                '.$measures.'

              </div> 
              </div>
           ';
           
           if($getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_auction"]){    	 
			   
			   $data["price"] .= '
			       <div class="row" >
			           <div class="col-lg-4" >
			              <div data-var="fix" class="ads-create-main-data-price-variant" >
			                 <div>
			                   <span class="ads-create-main-data-price-variant-name" >'.$ULang->t("Фиксированная").'</span>
			                 </div>
			              </div>
			           </div>
			           <div class="col-lg-4" >
			              <div data-var="from" class="ads-create-main-data-price-variant ads-create-main-data-price-variant-active" >
			                 <div>
			                   <span class="ads-create-main-data-price-variant-name" >'.$ULang->t("Не фиксированная").'</span>
			                 </div>
			              </div>
			           </div>               
			           <div class="col-lg-4" >
			              <div data-var="auction" class="ads-create-main-data-price-variant" >
			                 <div>
			                   <span class="ads-create-main-data-price-variant-name" >'.$ULang->t("Аукцион").'</span>
			                 </div>                          
			              </div>
			           </div>                     
			       </div>
			       <div class="mb25" ></div>
			       <div class="ads-create-main-data-stock-container" ></div>
			       <div class="ads-create-main-data-price-container" >'.$price.'</div>
			   ';

		   }else{

			   $data["price"] .= '
			       <div class="row" >
			           <div class="col-lg-6" >
			              <div data-var="fix" class="ads-create-main-data-price-variant" >
			                 <div>
			                   <span class="ads-create-main-data-price-variant-name" >'.$ULang->t("Фиксированная").'</span>
			                 </div>
			              </div>
			           </div>
			           <div class="col-lg-6" >
			              <div data-var="from" class="ads-create-main-data-price-variant ads-create-main-data-price-variant-active" >
			                 <div>
			                   <span class="ads-create-main-data-price-variant-name" >'.$ULang->t("Не фиксированная").'</span>
			                 </div>
			              </div>
			           </div>                                    
			       </div>
			       <div class="mb25" ></div>
			       <div class="ads-create-main-data-stock-container" ></div>
			       <div class="ads-create-main-data-price-container" >'.$price.'</div>
			   ';

		   }

       }else{

       	   $measuresPrice = json_decode($getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_measures_price"], true);

	       if( $data["ads_price_old"] ){    

	       	   if( $data["ads_price_measure"] || $measuresPrice ){

		           $price = '

			           <div class="ads-create-main-data-box-item" style="margin-top: 0px;" >
			              <div class="row" >
			                <div class="col-lg-4" >

			                    <div class="input-dropdown" >
			                       <input type="text" name="price" placeholder="'.$ULang->t("Старая цена").'" value="'.number_format($data["ads_price_old"],0,"."," ").'" class="ads-create-input inputNumber" maxlength="11" > 
			                       '.$dropdown_currency.'
			                    </div>
			                    <div class="msg-error" data-name="price" ></div>

			                </div>
			                <div class="col-lg-4" >

			                    <div class="input-dropdown" >
			                       <input type="text" name="stock_price" placeholder="'.$ULang->t("Новая цена").'" value="'.number_format($data["ads_price"],0,"."," ").'" class="ads-create-input inputNumber" maxlength="11" > 
			                       <div class="input-dropdown-box">
			                          <div class="uni-dropdown-align" >
			                             <span class="input-dropdown-name-display static-currency-sign"> '.$settings["currency_data"][ $data["ads_currency"] ]["sign"].' </span>
			                          </div>
			                       </div>
			                    </div>

			                </div> 
			                '.$measures_lg4.'               
			              </div>
			           </div>

		           ';

	       	   }else{

		           $price = '

			           <div class="ads-create-main-data-box-item" style="margin-top: 0px;" >
			              <div class="row" >
			                <div class="col-lg-6" >

			                    <div class="input-dropdown" >
			                       <input type="text" name="price" placeholder="'.$ULang->t("Старая цена").'" value="'.number_format($data["ads_price_old"],0,"."," ").'" class="ads-create-input inputNumber" maxlength="11" > 
			                       '.$dropdown_currency.'
			                    </div>
			                    <div class="msg-error" data-name="price" ></div>

			                </div>
			                <div class="col-lg-6" >

			                    <div class="input-dropdown" >
			                       <input type="text" name="stock_price" placeholder="'.$ULang->t("Новая цена").'" value="'.number_format($data["ads_price"],0,"."," ").'" class="ads-create-input inputNumber" maxlength="11" > 
			                       <div class="input-dropdown-box">
			                          <div class="uni-dropdown-align" >
			                             <span class="input-dropdown-name-display static-currency-sign"> '.$settings["currency_data"][ $data["ads_currency"] ]["sign"].' </span>
			                          </div>
			                       </div>
			                    </div>

			                </div>                
			              </div>
			           </div>

		           ';

	       	   }     

	       }else{

	            $price .= '
	              <div class="ads-create-main-data-box-item" style="margin-top: 0px;" >
	              <div class="row" >

	                <div class="col-lg-6" >

	                    <div class="input-dropdown" >
	                       <input type="text" name="price" '.($data["ads_price_free"] ? 'disabled=""' : '').' placeholder="'.$field_price_name.'" value="'.number_format($data["ads_price"],0,"."," ").'" class="ads-create-input inputNumber" maxlength="11" > 
	                       '.$dropdown_currency.'
	                    </div>
	                    <div class="msg-error" data-name="price" ></div>

	                </div>
	            ';

	            $price .= $measures;

	            if($getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_rules"]["free_price"]){

	                $price .= '
	                <div class="col-lg-6" >

	                    <div class="custom-control custom-checkbox mt10">
	                        <input type="checkbox" class="custom-control-input" '.($data["ads_price_free"] ? 'checked=""' : '').' name="price_free" id="price_free" value="1">
	                        <label class="custom-control-label" for="price_free">'.$ULang->t("Отдам даром").'</label>
	                    </div>

	                </div> 
	                ';

	            }

	           $price .= '
	              </div> 
	              </div>          
	           ';         

	       }

	       if($getCategoryBoard["category_board_id"][$data["ads_id_cat"]]["category_board_auction"]){
			   
			   $data["price"] .= '
			       <div class="row" >
			           <div class="col-lg-4" >
			              <div data-var="fix" class="ads-create-main-data-price-variant ads-create-main-data-price-variant-active" >
			                 <div>
			                   <span class="ads-create-main-data-price-variant-name" >'.$ULang->t("Фиксированная").'</span>
			                 </div>
			              </div>
			           </div>
			           <div class="col-lg-4" >
			              <div data-var="from" class="ads-create-main-data-price-variant" >
			                 <div>
			                   <span class="ads-create-main-data-price-variant-name" >'.$ULang->t("Не фиксированная").'</span>
			                 </div>
			              </div>
			           </div>               
			           <div class="col-lg-4" >
			              <div data-var="auction" class="ads-create-main-data-price-variant" >
			                 <div>
			                   <span class="ads-create-main-data-price-variant-name" >'.$ULang->t("Аукцион").'</span>
			                 </div>                          
			              </div>
			           </div>                     
			       </div>
			       <div class="mb25" ></div>
			       <div class="ads-create-main-data-stock-container" >'.$stock.'</div>
			       <div class="ads-create-main-data-price-container" >'.$price.'</div>
			   ';

			}else{

			   $data["price"] .= '
			       <div class="row" >
			           <div class="col-lg-6" >
			              <div data-var="fix" class="ads-create-main-data-price-variant ads-create-main-data-price-variant-active" >
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
			       <div class="ads-create-main-data-stock-container" >'.$stock.'</div>
			       <div class="ads-create-main-data-price-container" >'.$price.'</div>
			   ';

			}

       }

  }


 $data["price"] .= '
    </div>             
 ';               

}


echo $Main->tpl("ad_update.tpl", compact( 'Seo','Geo','Main','visible_footer','Ads','route_name','Profile','CategoryBoard','getCategoryBoard','data','settings','list_period','Filters','Banners', 'ULang', 'dropdown_currency','Cart' ) );

?>