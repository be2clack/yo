<?php

$id = (int)$_POST['id'];

$getCategories = $CategoryBoard->getCategories("where category_board_visible=1");

$ids_cat = $CategoryBoard->reverseId($getCategories,$id);

if($ids_cat){
  $ids_cat = explode(',', $ids_cat);
  foreach ($ids_cat as $key => $value) {
      $array_cats[$value] = $ids_cat[ $key + 1 ];
  }
}

if($array_cats){

 foreach ($array_cats as $id_main_cat => $id_sub_cat) {

      $parent_list = '';

      if($getCategories["category_board_id_parent"][$id_main_cat]){

          foreach ($getCategories["category_board_id_parent"][$id_main_cat] as $key => $parent_value) {

            if($parent_value["category_board_id"] == $id_sub_cat){ $active = 'class="uni-select-item-active"'; }else{ $active = ''; }
               
            $parent_list .=  '<label '.$active.' > <input type="radio" class="modal-filter-select-category" value="'.$parent_value["category_board_id"].'" > <span>'.$ULang->t($parent_value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ).'</span> <i class="la la-check"></i> </label>';

          }

          $select_subcategory .= '
                <div class="uni-select" data-status="0" >

                     <div class="uni-select-name" data-name="'.$ULang->t("Не выбрано").'" > <span>'.$ULang->t("Не выбрано").'</span> <i class="la la-angle-down"></i> </div>
                     <div class="uni-select-list" >
                         <label> <input type="radio" class="modal-filter-select-category" value="'.$id_main_cat.'" > <span>'.$ULang->t("Все категории").'</span> <i class="la la-check"></i> </label>
                         '.$parent_list.'
                     </div>
                
                </div>
          ';

      }

 }

}

if( $getCategories["category_board_id"][ $id ]["category_board_display_price"] ){

$filters_list = '
  <div class="row" >
     <div class="col-lg-4" >
       <label>
          '.$Main->nameInputPrice($getCategories["category_board_id"][ $id ]["category_board_variant_price_id"]).'                             
       </label>
     </div>
     <div class="col-lg-5" >
       
        <div class="filter-input" >
          <div><span>'.$ULang->t("от").'</span><input type="text" class="inputNumber" name="filter[price][from]" value="" /></div>
          <div><span>'.$ULang->t("до").'</span><input type="text" class="inputNumber" name="filter[price][to]" value="" /></div>
        </div>

     </div>
  </div>
'; 

}

$filters_list .= '
  <div class="row mt15" >
     <div class="col-lg-4" >
       <label>
          '.$ULang->t("Статус").'                             
       </label>
     </div>
     <div class="col-lg-8" >
     <div class="filter-items-spacing" >
';

if( $getCategories["category_board_id"][ $id ]["category_board_secure"] && $settings["secure_status"] ){
  $filters_list .= '
  <div class="custom-control custom-checkbox" >
      <input type="checkbox" class="custom-control-input" name="filter[secure]" id="flsecure" value="1" >
      <label class="custom-control-label" for="flsecure">'.$ULang->t("Безопасная сделка").'</label>
  </div>
  ';
}

if( $getCategories["category_board_id"][ $id ]["category_board_auction"] ){
  $filters_list .= '
  <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" name="filter[auction]" id="flauction" value="1" >
      <label class="custom-control-label" for="flauction">'.$ULang->t("Аукционный товар").'</label>
  </div>
  ';
}

if( $getCategories["category_board_id"][ $id ]["category_board_online_view"] ){
  $filters_list .= '
  <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" name="filter[online_view]" id="online_view" value="1" >
      <label class="custom-control-label" for="online_view">'.$ULang->t("Онлайн-показ").'</label>
  </div>
  ';
}

$filters_list .= '
  <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" name="filter[vip]" id="flvip" value="1" >
      <label class="custom-control-label" for="flvip">'.$ULang->t("VIP объявление").'</label>
  </div>
';

if( $getCategories["category_board_id"][ $id ]["category_board_booking"] ){

if( $getCategories["category_board_id"][ $id ]["category_board_booking_variant"] == 0 ){

    $filters_list .= '
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="filter[booking]" id="booking_variant" value="1" >
        <label class="custom-control-label" for="booking_variant">'.$ULang->t("Онлайн-бронирование").'</label>
    </div>

    <div class="catalog-list-options" >

        <span class="catalog-list-options-name" >
        '.$ULang->t("Даты").' 
        <i class="las la-angle-down"></i>
        </span>
        
        <div class="catalog-list-options-content" >

            <div class="catalog-list-options-content" >
                <div class="filter-input" >
                  <div><span>'.$ULang->t("с").'</span><input type="text" class="catalog-change-date-from" name="filter[date][start]" value="" /></div>
                  <div><span>'.$ULang->t("по").'</span><input type="text" class="catalog-change-date-to" name="filter[date][end]" value="" /></div>
                </div>                    
            </div>

        </div>

    </div>

    ';           

}elseif( $getCategories["category_board_id"][ $id ]["category_board_booking_variant"] == 1 ){

    $filters_list .= '
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="filter[booking]" id="booking_variant" value="1" >
        <label class="custom-control-label" for="booking_variant">'.$ULang->t("Онлайн-аренда").'</label>
    </div>

    <div class="catalog-list-options" >

        <span class="catalog-list-options-name" >
        '.$ULang->t("Даты").' 
        <i class="las la-angle-down"></i>
        </span>
        
        <div class="catalog-list-options-content" >

            <div class="catalog-list-options-content" >
                <div class="filter-input" >
                  <div><span>'.$ULang->t("с").'</span><input type="text" class="catalog-change-date-from" name="filter[date][start]" value="" /></div>
                  <div><span>'.$ULang->t("по").'</span><input type="text" class="catalog-change-date-to" name="filter[date][end]" value="" /></div>
                </div>                    
            </div>

        </div>

    </div>

    ';

}

}

$filters_list .= '</div></div></div>'; 

$filters_list .= $Filters->load_filters_catalog( $id , "", "filters_modal" );

echo json_encode( array("subcategory" => $select_subcategory, "filters" => $filters_list ) );

?>