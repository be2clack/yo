<?php
if($value["ads_filters_type"] == "select" || $value["ads_filters_type"] == "select_multi"){

     $findParent = findOne("uni_ads_filters", "ads_filters_id_parent=?", [$value["ads_filters_id"]]);

     if( $findParent ){

         if(count($getItems) > 0){
             foreach ($getItems as $item_key => $item_value) {

                $checked = "";

                if($this->checkSelected($value["ads_filters_id"],$item_value["ads_filters_items_id"],$param) == true){
                  $checked = 'checked=""';
                }

                $items .= '
                  <div class="custom-control custom-radio">
                      <input type="radio" '.$checked.' class="custom-control-input" name="filter['.$value["ads_filters_id"].'][]" value="'.$item_value["ads_filters_items_id"].'" id="fl'.$item_value["ads_filters_items_id"].'" >
                      <label class="custom-control-label" for="fl'.$item_value["ads_filters_items_id"].'">'.$ULang->t( $item_value["ads_filters_items_value"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_items_value" ] ).'</label>
                  </div>                           
                ';

             }
         }

         if(count($getItems) > 10){
            $search_items = '<div class="catalog-list-options-search" > <input class="form-control" type="text" placeholder="'.$ULang->t("Поиск").'" /> </div>';
         }else{ $search_items = ''; }

         $return .= '

            <div class="catalog-list-options toggle-list-options filter-items '.$statusOpenItems.'" id-filter="'.$value["ads_filters_id"].'" main-id-filter="0" data-ids="'.$this->idsBuild($value["ads_filters_id"],$getFilters).'" >
                <span class="catalog-list-options-name" >'.$ULang->t( $value["ads_filters_name"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_name" ] ).' <i class="las la-angle-down"></i></span>

                <div class="catalog-list-options-content" >
                    
                    '.$search_items.'

                    <div class="catalog-list-options-items" >
                  
                    '.$items.'

                    </div>

                </div>
                
            </div>

            '.$this->load_podfilters_catalog($value["ads_filters_id"],$param["filter"][$value["ads_filters_id"]][0],$param,"podfilters_catalog").'

         ';

     }else{

         if(count($getItems) > 0){
             foreach ($getItems as $item_key => $item_value) {

                $active = '';

                if($this->checkSelected($value["ads_filters_id"],$item_value["ads_filters_items_id"],$param) == true){$active = 'checked=""';}

                $items .= '
                  <div class="custom-control custom-checkbox">
                      <input type="checkbox" '.$active.' class="custom-control-input" name="filter['.$value["ads_filters_id"].'][]" value="'.$item_value["ads_filters_items_id"].'" id="fl'.$item_value["ads_filters_items_id"].'" >
                      <label class="custom-control-label" for="fl'.$item_value["ads_filters_items_id"].'">'.$ULang->t( $item_value["ads_filters_items_value"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_items_value" ] ).'</label>
                  </div>                      
                ';

             }
         }

         if(count($getItems) > 10){
            $search_items = '<div class="catalog-list-options-search" > <input class="form-control" type="text" placeholder="'.$ULang->t("Поиск").'" /> </div>';
         }else{ $search_items = ''; }

         $return .= '

            <div class="catalog-list-options toggle-list-options filter-items '.$statusOpenItems.'" id-filter="'.$value["ads_filters_id"].'" main-id-filter="0" data-ids="'.$this->idsBuild($value["ads_filters_id"],$getFilters).'" >
                <span class="catalog-list-options-name" >'.$ULang->t( $value["ads_filters_name"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_name" ] ).' <i class="las la-angle-down"></i></span>

                <div class="catalog-list-options-content" >

                    '.$search_items.'

                    <div class="catalog-list-options-items" >
                  
                    '.$items.'

                    </div>

                </div>
                
            </div>

         ';

     }

}elseif($value["ads_filters_type"] == "input"){

     if(count($getItems) > 0){

        if(isset($param["filter"][$value["ads_filters_id"]])){
           $slideStart = $param["filter"][$value["ads_filters_id"]]["from"];
           $slideEnd = $param["filter"][$value["ads_filters_id"]]["to"];
        }else{
           $slideStart = $getItems[0]["ads_filters_items_value"];
           $slideEnd = $getItems[1]["ads_filters_items_value"];                           
        }


     }

     $return .= '
        <div class="catalog-list-options toggle-list-options filter-items '.$statusOpenItems.'" id-filter="'.$value["ads_filters_id"].'" main-id-filter="0" data-ids="" >
            <span class="catalog-list-options-name" >'.$ULang->t( $value["ads_filters_name"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_name" ] ).' <i class="las la-angle-down"></i></span>

            <div class="catalog-list-options-content" >
            <div class="filter-input" >
              <div><span>'.$ULang->t("от").'</span><input type="text" name="filter['.$value["ads_filters_id"].'][from]" value="'.$param["filter"][$value["ads_filters_id"]]["from"].'" /></div>
              <div><span>'.$ULang->t("до").'</span><input type="text" name="filter['.$value["ads_filters_id"].'][to]" value="'.$param["filter"][$value["ads_filters_id"]]["to"].'" /></div>
            </div>
            </div>
            
        </div>
     ';


}
?>