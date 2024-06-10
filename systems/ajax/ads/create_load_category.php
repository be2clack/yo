<?php
$id = (int)$_POST["id"];

$getCategories = $CategoryBoard->getCategories("where category_board_visible=1");

$filters = $Filters->load_filters_ad( $id );

if ( $getCategories["category_board_id_parent"][$id] ) {
    
    if( $_POST["var"] == "create" ){

        $lenght = floor(count($getCategories["category_board_id_parent"][$id]) / 2);

        $chunk = array_chunk($getCategories["category_board_id_parent"][$id], $lenght ? $lenght : 1, true);

        foreach ($chunk as $key => $nested) {
           
            $parent_list .= '<div class="col-lg-6 col-md-6 col-sm-6 col-12" >';

            foreach ($nested as $key => $parent_value) {

                $parent_list .=  '<span data-id="'.$parent_value["category_board_id"].'" >'.$ULang->t($parent_value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ).'</span>';

            }

            $parent_list .= '</div>';

        }

        if( $getCategories["category_board_id"][$id]["category_board_id_parent"] ){
          $prev = '<span class="ads-create-subcategory-prev" data-id="'.$getCategories["category_board_id"][$id]["category_board_id_parent"].'" ><i class="las la-arrow-left"></i></span>';
        }

        $data = '
          <p class="ads-create-subtitle mt30" > '.$prev.' '.$ULang->t("Выберите подкатегорию").'</p>

          <div class="ads-create-subcategory-list" >
             <div class="row" >' . $parent_list . '</div>
          </div>
        ';

    }elseif( $_POST["var"] == "update" ){

        foreach ($getCategories["category_board_id_parent"][$id] as $key => $parent_value) {
           
            $parent_list .=  '<span data-id="'.$parent_value["category_board_id"].'" data-name="'.$CategoryBoard->breadcrumb($getCategories,$parent_value["category_board_id"],'{NAME}',' &rsaquo; ').'" >'.$ULang->t($parent_value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ).'</span>';

        }

        if( $id ){
            $prev = '<span data-id="'.$getCategories["category_board_id"][$id]["category_board_id_parent"].'" ><i class="las la-arrow-left"></i> '.$ULang->t("Назад").'</span>';
        }

        $data = $prev . $parent_list;

    }
    
    echo json_encode( array("subcategory" => true, "data" => $data ) );

}else{

  $data = [];

  if( !$getCategories["category_board_id"][$id]["category_board_auto_title"] ){

     $data["title"] = '
        <div class="ads-create-main-data-box-item" style="margin-top: 0px; margin-bottom: 25px;" >
            <p class="ads-create-subtitle" >'.$ULang->t("Название").'</p>
            <input type="text" name="title" class="ads-create-input" >
            <p class="create-input-length" >'.$ULang->t("Символов").' <span>0</span> '.$ULang->t("из").' '.$settings["ad_create_length_title"].'</p>
            <div class="msg-error" data-name="title" ></div>
        </div>
     ';

  }

  if( $getCategories["category_board_id"][$id]["category_board_online_view"] ){

     $data["online_view"] = '
         <div class="ads-create-main-data-box-item" >
            <p class="ads-create-subtitle" >'.$ULang->t("Онлайн-показ").'</p>
            <div class="create-info" ><i class="las la-question-circle"></i> '.$ULang->t("Выберите, если готовы показать товар/объект с помощью видео-звонка — например, через WhatsApp, Viber, Skype или другой сервис").'</div>
            <div class="custom-control custom-checkbox mt15">
                <input type="checkbox" class="custom-control-input" name="online_view" id="online_view" value="1">
                <label class="custom-control-label" for="online_view">'.$ULang->t("Готовы показать онлайн").'</label>
            </div>
         </div>
     ';

  }

  if( $getCategories["category_board_id"][$id]["category_board_booking"] ){

     if($getCategories["category_board_id"][$id]["category_board_booking_variant"] == 0){

         $data["booking"] = '
             <div class="ads-create-main-data-box-item" >
                <p class="ads-create-subtitle" >'.$ULang->t("Онлайн-бронирование").'</p>
                <div class="create-info" ><i class="las la-question-circle"></i> '.$ULang->t("Выберите, если хотите сдавать объект в аренду. Пользователи смогут бронировать онлайн.").'</div>
                <div class="custom-control custom-checkbox mt15">
                    <input type="checkbox" class="custom-control-input" name="booking" id="booking" value="1">
                    <label class="custom-control-label" for="booking">'.$ULang->t("Онлайн-бронирование").'</label>
                </div>
             </div>
         ';

     }elseif($getCategories["category_board_id"][$id]["category_board_booking_variant"] == 1){
        
         $data["booking"] = '
             <div class="ads-create-main-data-box-item" >
                <p class="ads-create-subtitle" >'.$ULang->t("Онлайн-аренда").'</p>
                <div class="create-info" ><i class="las la-question-circle"></i> '.$ULang->t("Выберите, если хотите сдавать товар/объект в аренду. Пользователи смогут брать в аренду онлайн.").'</div>
                <div class="custom-control custom-checkbox mt15">
                    <input type="checkbox" class="custom-control-input" name="booking" id="booking" value="1">
                    <label class="custom-control-label" for="booking">'.$ULang->t("Онлайн-аренда").'</label>
                </div>
             </div>
         ';

     }

  }

  if( $Cart->modeAvailableCart($getCategories,$id,$_SESSION["profile"]["id"]) ){

     $data["available"] = '

          <div class="ads-create-main-data-box-item" >

              <p class="ads-create-subtitle" >'.$ULang->t("В наличии").'</p>

              <div class="row" >
                
                <div class="col-lg-6" >
                    <input type="text" name="available" placeholder="" class="ads-create-input" maxlength="5" disabled="" >
                    <div class="msg-error" data-name="available" ></div>
                </div>
                
                <div class="col-lg-6" >

                    <div class="custom-control custom-checkbox mt10">
                        <input type="checkbox" class="custom-control-input" name="available_unlimitedly" id="available_unlimitedly" value="1" checked="" >
                        <label class="custom-control-label" for="available_unlimitedly">'.$ULang->t("Неограниченно").'</label>
                    </div>

                </div> 

              </div>

          </div>

     ';

  }

  if($Ads->checkCategoryDelivery($id) && $settings["main_type_products"] == 'physical' && $_SESSION['profile']['data']['clients_delivery_status']){

    $data["delivery"] = '

            <div class="ads-create-main-data-box-item" >
                                
            <p class="ads-create-subtitle" >
                '.$ULang->t("Доставка").'
                <label class="checkbox ml10">
                <input type="checkbox" name="delivery_status" value="1"  >
                <span></span>
                </label>                                        
            </p>
            
            <div class="ads-create-box-delivery" >
            <p class="create-info mt10" > <i class="las la-question-circle"></i> '.$ULang->t("Укажите примерный вес товара, необходимо для службы доставки").'</p>
            
            <div class="row no-gutters mt20" >
                    <div class="col-lg-6" >

                        <div class="input-dropdown">
                            
                            <input type="text" name="delivery_weight" class="ads-create-input" maxlength="6" > 
                    
                            <div class="input-dropdown-box">
                            <div class="uni-dropdown-align">
                                <span class="input-dropdown-name-display">'.$ULang->t("грамм").'</span>
                            </div>
                            </div>
            
                        </div>                                          
                        <div class="msg-error" data-name="delivery_weight" ></div>

                    </div>                              
                </div>
            </div>
            
            </div>

    ';

  }

  if( $filters ){

     $getCategory = $Filters->getCategory( ["id_cat" => $id] );
     
     if( $getCategory ){

         $getFilters = getAll( "select * from uni_ads_filters where ads_filters_id IN(".implode(",", $getCategory).")" );

         if(count($getFilters)){

            foreach ( $getFilters as $key => $value) {
                $list_filters[] = $ULang->t( $value["ads_filters_name"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_name" ] );
            }

            $data["filters"] = '
               <div class="ads-create-main-data-box-item" >
                  <p class="ads-create-subtitle" >'.$ULang->t("Характеристики").'</p>
                  <div class="ads-create-main-data-filters-spoiler" >
                    <div class="ads-create-list-filters-show create-info" ><i class="las la-plus-circle"></i> '.implode(", ", $list_filters).'</div>
                  </div>
                  <div class="ads-create-main-data-filters-list" >
                  <div class="create-info" ><i class="las la-question-circle"></i> '.$ULang->t("Укажите как можно больше параметров - это повысит интерес к объявлению.").'</div>
                  <div class="mb25" ></div>
                  '.$filters.'
                  </div>
               </div> 
            ';

         }

     }

  }

  if( $getCategories["category_board_id"][$id]["category_board_display_price"] ){

      $field_price_name = $Main->nameInputPrice($getCategories["category_board_id"][$id]["category_board_variant_price_id"]);

      $data["price"] .= '
          <div class="ads-create-main-data-box-item" >
          <p class="ads-create-subtitle" >'.$field_price_name.'</p>
      ';

      if( $getCategories["category_board_id"][$id]["category_board_auction"] ){
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
                      <div data-var="auction" class="ads-create-main-data-price-variant" >
                         <div>
                           <span class="ads-create-main-data-price-variant-name" >'.$ULang->t("Аукцион").'</span>
                         </div>                          
                      </div>
                   </div>                     
               </div>
               <div class="mb25" ></div>
               <div class="ads-create-main-data-stock-container" ></div>
               <div class="ads-create-main-data-price-container" ></div>
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
          ';                
      }

      
       
     $data["price"] .= '
        </div>             
     ';               

  }

  echo json_encode( array( "subcategory" => false, "data" => $data ) );

}

?>