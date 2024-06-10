<?php

/**
 * UniSite CMS
 *
 * @copyright   2018 Artur Zhur
 * @link    https://unisitecms.ru
 * @author    Artur Zhur
 *
 */

class Filters{

  function alias($param=[]){
     global $settings;

     if($settings["main_type_products"] == 'physical'){
         if($param["geo_alias"]){
            return _link($param["geo_alias"]."/".$param["category_alias"]."/".$param["filter_alias"]);
         }else{
            return _link($_SESSION["geo"]["alias"]."/".$param["category_alias"]."/".$param["filter_alias"]);
         }
     }else{
         return _link($param["category_alias"]."/".$param["filter_alias"]);
     }
     
  }

  function queryFilter($array = array(), $param = array()){
      global $settings;

      $Ads = new Ads();
      $CategoryBoard = new CategoryBoard();
      $Main = new Main();
      $Elastic = new Elastic();

      $binding_query = "ads_status='1' and clients_status IN(0,1) and ads_period_publication > now()";

      $param_search = $Elastic->paramAdquery();

      $flCount = 0;    
      $forming_multi_query = [];
      $forming = [];
      $ids = [];
      $ids_not = [];
      $ids_cat = '';

      $coorTopLeft = $array["coorTopLeft"] ? clear($array["coorTopLeft"]) : "";
      $coorTopRight = $array["coorTopRight"] ? clear($array["coorTopRight"]) : "";
      $coorBottomLeft = $array["coorBottomLeft"] ? clear($array["coorBottomLeft"]) : "";
      $coorBottomRight = $array["coorBottomRight"] ? clear($array["coorBottomRight"]) : "";

      if($array["filter"]["sort"]){
         if($array["filter"]["sort"] == "news"){
            $sorting = "order by ads_id desc";
            $param_search["sort"]["ads_id"] = [ "order" => "desc" ];
         }elseif($array["filter"]["sort"] == "price"){
            $sorting = "order by ads_price asc";
            $param_search["sort"]["ads_price"] = [ "order" => "asc" ];
         }else{

            if( $settings["ads_sorting_variant"] == 0 ){
                $sorting = "order by ads_sorting desc, ads_id desc";
                $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
                $param_search["sort"]["ads_id"] = [ "order" => "desc" ];                
            }elseif( $settings["ads_sorting_variant"] == 1 ){ 
                $sorting = "order by ads_sorting desc, ads_id asc";
                $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
                $param_search["sort"]["ads_id"] = [ "order" => "asc" ];
            }else{
                $sorting = "order by ads_sorting desc";
                $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
            }

         }
         unset($array["filter"]["sort"]);
      }else{

         if( $settings["ads_sorting_variant"] == 0 ){
            $sorting = "order by ads_sorting desc, ads_id desc";
            $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
            $param_search["sort"]["ads_id"] = [ "order" => "desc" ];            
         }elseif( $settings["ads_sorting_variant"] == 1 ){ 
            $sorting = "order by ads_sorting desc, ads_id asc";
            $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
            $param_search["sort"]["ads_id"] = [ "order" => "asc" ];            
         }else{
            $sorting = "order by ads_sorting desc";
            $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
         }

         $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
      }


      if($array["filter"]["price"]){

          $array["filter"]["price"]["from"] = $Main->spacePrice( $array["filter"]["price"]["from"] );
          $array["filter"]["price"]["to"] = $Main->spacePrice( $array["filter"]["price"]["to"] );

          if(!empty($array["filter"]["price"]["from"]) && !empty($array["filter"]["price"]["to"])){  

              $forming_multi_query["price"] = "(ads_price BETWEEN ".round($array["filter"]["price"]["from"],2)." AND ".round($array["filter"]["price"]["to"],2).")"; 
              $param_search["query"]["bool"]["filter"][]["range"]["ads_price"] = [ "gte" => round($array["filter"]["price"]["from"],2), "lte" => round($array["filter"]["price"]["to"],2) ];

          }else{

              if(!empty($array["filter"]["price"]["from"])){
                 $forming_multi_query["price"] = "(ads_price >= ".round($array["filter"]["price"]["from"],2).")";
                 $param_search["query"]["bool"]["filter"][]["range"]["ads_price"] = [ "gte" => round($array["filter"]["price"]["from"],2) ];
              }elseif(!empty($array["filter"]["price"]["to"])){
                 $forming_multi_query["price"] = "(ads_price <= ".round($array["filter"]["price"]["to"],2).")";
                 $param_search["query"]["bool"]["filter"][]["range"]["ads_price"] = [ "lte" => round($array["filter"]["price"]["to"],2) ];
              }

          }
          
         unset($array["filter"]["price"]);

      }

      if(intval($array["filter"]["secure"])){

         if( $settings["secure_payment_service_name"] ){

             $payment = findOne("uni_payments","code=?", array( $settings["secure_payment_service_name"] ));

             $forming_multi_query["secure"] = "category_board_secure='1' and clients_secure='1' and (ads_price BETWEEN ".round($payment["secure_min_amount_payment"],2)." AND ".round($payment["secure_max_amount_payment"],2).")";

             $param_search["query"]["bool"]["filter"][]["term"]["category_board_secure"] = 1;
             $param_search["query"]["bool"]["filter"][]["term"]["clients_secure"] = 1;
             $param_search["query"]["bool"]["filter"][]["range"]["ads_price"] = [ "gte" => round($payment["secure_min_amount_payment"],2), "lte" => round($payment["secure_max_amount_payment"],2) ];

        }else{

             $forming_multi_query["secure"] = "category_board_secure='1' and clients_secure='1'";
             $param_search["query"]["bool"]["filter"][]["term"]["category_board_secure"] = 1;
             $param_search["query"]["bool"]["filter"][]["term"]["clients_secure"] = 1;

        }

         unset($array["filter"]["secure"]);
      }

      if(intval($array["filter"]["auction"])){
         $forming_multi_query["auction"] = "category_board_auction='1' and ads_auction='1'";
         $param_search["query"]["bool"]["filter"][]["term"]["category_board_auction"] = 1;
         $param_search["query"]["bool"]["filter"][]["term"]["ads_auction"] = 1;
         unset($array["filter"]["auction"]);
      }

      if(intval($array["filter"]["vip"])){
         $forming_multi_query["auction"] = "ads_vip='1'";
         $param_search["query"]["bool"]["filter"][]["term"]["ads_vip"] = 1;
         unset($array["filter"]["vip"]);
      }

      if(intval($array["filter"]["online_view"])){
         $forming_multi_query["online_view"] = "ads_online_view='1'";
         $param_search["query"]["bool"]["filter"][]["term"]["ads_online_view"] = 1;
         unset($array["filter"]["online_view"]);
      }

      if(intval($array["filter"]["booking"])){
         $forming_multi_query["auction"] = "ads_booking='1'";
         $param_search["query"]["bool"]["filter"][]["term"]["ads_booking"] = 1;
         unset($array["filter"]["booking"]);
      }

      if(intval($array["filter"]["delivery"])){
         $forming_multi_query["auction"] = "ads_delivery_status='1'";
         $param_search["query"]["bool"]["filter"][]["term"]["ads_delivery_status"] = 1;
         unset($array["filter"]["delivery"]);
      }

      if( !$param["ads_subscriptions"] ){
          if(intval($array["filter"]["period"])){
             $forming_multi_query["period"] = "(ads_datetime_add BETWEEN NOW() - INTERVAL ".intval($array["filter"]["period"])." DAY AND NOW())";
             $param_search["query"]["bool"]["filter"][]["range"]["ads_datetime_add"] = [ "gte" => date("Y-m-d H:i:s", time() - ( intval($array["filter"]["period"]) * 86400 ) ), "lte" => date("Y-m-d H:i:s") ];
             unset($array["filter"]["period"]);
          }
      }else{

           if( $array["filter"]["period"] == "day" ){

             $forming_multi_query["period"] = "ads_datetime_add >= '".$param["ads_subscriptions_date"]."'";
             $param_search["query"]["bool"]["filter"][]["range"]["ads_datetime_add"] = [ "gte" => date("Y-m-d H:i:s", time() - 86400), "lte" => date("Y-m-d H:i:s") ];

           }elseif( $array["filter"]["period"] == "min" ){

             $forming_multi_query["period"] = "ads_datetime_add >= '".$param["ads_subscriptions_date"]."'";
             $param_search["query"]["bool"]["filter"][]["range"]["ads_datetime_add"] = [ "gte" => date("Y-m-d H:i:s", time() - 60), "lte" => date("Y-m-d H:i:s") ];

           }

           unset($array["filter"]["period"]);        
      }

      if( $array["id_u"] ){

            $forming_multi_query["user"] = "ads_id_user='".intval($array["id_u"])."'";

            $param_search["query"]["bool"]["filter"][]["terms"]["ads_id_user"] = intval($array["id_u"]);

      }
      
      if( !$param["ads_subscriptions"] ){

        if($array["id_c"]){

            $ids_cat = idsBuildJoin( $CategoryBoard->idsBuild($array["id_c"], $CategoryBoard->getCategories("where category_board_visible=1")), $array["id_c"] );
            $forming_multi_query["category"] = "ads_id_cat IN(".$ids_cat.")";

            $param_search["query"]["bool"]["filter"][]["terms"]["ads_id_cat"] = explode(",", $ids_cat);

        }

      }else{
            
            if($array["id_c"]){
               $forming_multi_query["category"] = "ads_id_cat IN(".$array["id_c"].")";
               $param_search["query"]["bool"]["filter"][]["terms"]["ads_id_cat"] = $array["id_c"];
            } 

      }
      
      if( !$param["ads_subscriptions"] ){

        if( !$param["disable_query_geo"] ){

          if($settings["main_type_products"] == 'physical'){
              if($coorTopLeft && $coorTopRight && $coorBottomLeft && $coorBottomRight){
                  $forming_multi_query["geo"] = "((ads_map_lat < '$coorTopLeft' and ads_map_lon < '$coorTopRight') and (ads_map_lat > '$coorBottomLeft' and ads_map_lon > '$coorBottomRight'))";
              }else{
                  if($Ads->queryGeo()){
                      $forming_multi_query["geo"] = $Ads->queryGeo();
                      $param_search["query"]["bool"]["filter"][]["term"] = $Ads->arrayGeo();
                  }                
              }
          }

        }

      }else{

           $forming_multi_query["geo"] = $array["geo"];
           $param_search["query"]["bool"]["filter"][]["term"] = $array["geo_array"];

      }

      if($array["filter"]["metro"]){  
 
         $get = getAll("SELECT * FROM uni_metro_variants WHERE metro_id IN(".implode(",", iteratingArray($array["filter"]["metro"], "int")).")");    
        
         if(count($get)){

            foreach ($get as $key => $value) {
              $ids_variants[$value["ads_id"]] = $value["ads_id"];
            }

         } 

      }

      if($array["filter"]["area"]){  
 
         $get = getAll("SELECT * FROM uni_city_area_variants WHERE city_area_variants_id_area IN(".implode(",", iteratingArray($array["filter"]["area"], "int")).")");    

         if(count($get)){

            foreach ($get as $key => $value) {
              $ids_variants[$value["city_area_variants_id_ad"]] = $value["city_area_variants_id_ad"];
            }
            
         } 

      }

      if($array["filter"]["date"]){  

        if($array["id_c"]){
            $ids_cat = idsBuildJoin( $CategoryBoard->idsBuild($array["id_c"], $CategoryBoard->getCategories("where category_board_visible=1")), $array["id_c"] );
        }

         if(isset($array["filter"]["date"]["start"]) && isset($array["filter"]["date"]["end"])){

             if($ids_cat){
                $get = getAll("SELECT * FROM uni_ads_booking_dates WHERE ads_booking_dates_id_cat IN(".$ids_cat.") and DATE(ads_booking_dates_date) BETWEEN '".date("Y-m-d", strtotime($array["filter"]["date"]["start"]))."' AND '".date("Y-m-d", strtotime($array["filter"]["date"]["end"]))."'");
             }else{
                $get = getAll("SELECT * FROM uni_ads_booking_dates WHERE DATE(ads_booking_dates_date) BETWEEN '".date("Y-m-d", strtotime($array["filter"]["date"]["start"]))."' AND '".date("Y-m-d", strtotime($array["filter"]["date"]["end"]))."'");
             }
                 

             if($get){

                foreach ($get as $key => $value) {
                  $ids_not[$value["ads_booking_dates_id_ad"]] = $value["ads_booking_dates_id_ad"];
                }
                
             }

         }elseif(isset($array["filter"]["date"]["start"])){

             if($ids_cat){
                $get = getAll("SELECT * FROM uni_ads_booking_dates WHERE ads_booking_dates_id_cat IN(".$ids_cat.") and DATE(ads_booking_dates_date) = '".date("Y-m-d", strtotime($array["filter"]["date"]["start"]))."'"); 
             }else{
                $get = getAll("SELECT * FROM uni_ads_booking_dates WHERE DATE(ads_booking_dates_date) = '".date("Y-m-d", strtotime($array["filter"]["date"]["start"]))."'");
             }   

             if($get){

                foreach ($get as $key => $value) {
                  $ids_not[$value["ads_booking_dates_id_ad"]] = $value["ads_booking_dates_id_ad"];
                }
                
             }

         }
  
      }

      if( $array["filter"]["metro"] || $array["filter"]["area"] ){
          if(!$ids_variants){ return [ "count" => 0, "all" => [] ]; }
      }

      unset($array["filter"]["metro"]);
      unset($array["filter"]["area"]);

      if($array["filter"]){

           foreach($array["filter"] AS $id_filter=>$filter_array){

               $getFilter = findOne("uni_ads_filters", "ads_filters_id=?", array( intval($id_filter) ));

               if($getFilter){

                 if($getFilter->ads_filters_type != "input" && $getFilter->ads_filters_type != "input_text"){

                     foreach($filter_array AS $filter_key=>$filter_val){
        
                         if($filter_val != "" && $filter_val != "null"){
                             
                             if(!$forming[$id_filter]) $flCount++;
                             $forming[$id_filter][] = "(ads_filters_variants_id_filter='".intval($id_filter)."' AND ads_filters_variants_val='".intval($filter_val)."')";
                             
                         } 
                       
                     }            
                
                 }else{

                    $flCount++;

                    $forming[$id_filter][] = "ads_filters_variants_id_filter='".intval($id_filter)."' AND (ads_filters_variants_val BETWEEN ".round($filter_array["from"],2)." AND ".round($filter_array["to"],2).")";

                 }

                 if($forming[$id_filter]) $forming_filters[] = implode(" OR ",$forming[$id_filter]);    

               }       
          
           }

      }
       

      if($forming_filters){

        if( $ids_variants ){
            $query_ids_filter = " and ads_filters_variants_product_id IN(".implode(",", $ids_variants).")";
        }

        $variants = getAll("SELECT ads_filters_variants_product_id, count(ads_filters_variants_product_id) AS cnt FROM `uni_ads_filters_variants` WHERE (".implode(" OR ",$forming_filters).") $query_ids_filter GROUP BY ads_filters_variants_product_id HAVING cnt >= ".$flCount);

         if(count($variants) > 0){
           foreach ($variants as $variant_value) {
              $ids[$variant_value["ads_filters_variants_product_id"]] = $variant_value["ads_filters_variants_product_id"];
           }
         }

         if($ids){
            $query[] = "ads_id IN(".implode(",", $ids).")";
            $param_search["query"]["bool"]["filter"][]["terms"]["ads_id"] = implode(",", $ids);
         }else{
            return [ "count" => 0, "all" => [] ];
         }

      }else{

         if($ids_variants){
             $query[] = "ads_id IN(".implode(",", $ids_variants).")";
             $param_search["query"]["bool"]["filter"][]["terms"]["ads_id"] = implode(",", $ids_variants);
         }

      }
      
      if($forming_multi_query){
          $query[] = implode(" AND ",$forming_multi_query);
      }

      if($ids_not){
          $query[] = "ads_id NOT IN(".implode(',', $ids_not).")";
      }

      if($query){
          $binding_query = $binding_query . " and " . implode(" and ", $query) . $param["extra_query"];
      }

      $return = $Ads->getAll( array("query"=>$binding_query, "sort"=>$sorting, "navigation"=>$param["navigation"], "output"=>$param["output"], "page"=>$param["page"], "param_search"=>$param_search) );

      $return["query"] = $binding_query;

      return $return;
  }


  function getFilters($query = ""){
      
      $CategoryBoard = new CategoryBoard();
      $Cache = new Cache();

      if( $Cache->get( [ "table" => "uni_ads_filters", "key" => $query ] ) ){
         
          return $Cache->get( [ "table" => "uni_ads_filters", "key" => $query ] );

      }else{

          $getFilters = getAll("SELECT * FROM uni_ads_filters $query ORDER By ads_filters_position ASC");

          if ($getFilters) { 
                         
                foreach($getFilters AS $value){
                    
                    $data['id_parent'][$value['ads_filters_id_parent']][$value['ads_filters_id']] =  $value;
                    $data['id'][$value['ads_filters_id']]['ads_filters_name'] =  $value['ads_filters_name'];
                    $data['id'][$value['ads_filters_id']]['ads_filters_type'] =  $value['ads_filters_type'];

                }

                $Cache->set( [ "table" => "uni_ads_filters", "key" => $query, "data" => $data ] );

          }

          return $data;

      }
        
  }

  function getCategory( $param = [] ){

    $ids = [];

    if($param["id_filter"]){

        $get = getAll("select * from uni_ads_filters_category where ads_filters_category_id_filter=?", [$param["id_filter"]]);

        if(count($get)){
           foreach ($get as $key => $value) {
              $ids[] = $value["ads_filters_category_id_cat"];
           }
        }

        return $ids;

    }elseif($param["id_cat"]){
      
        $get = getAll("select * from uni_ads_filters_category where ads_filters_category_id_cat=?", [$param["id_cat"]]);

        if(count($get)){
           foreach ($get as $key => $value) {
              $ids[] = $value["ads_filters_category_id_filter"];
           }
        }

        return $ids;

    }


  }
   
   function load_filters_ad($id_cat=0, $getVariants=array()){

       $ULang = new ULang();
       
       $filters_ids = $this->getCategory( ["id_cat"=>$id_cat] );

       if($filters_ids){
          $query = "ads_filters_visible='1' and ads_filters_id IN(".implode(",", $filters_ids).")";
       }else{
          $query = "ads_filters_visible='1' and ads_filters_id IN(0)";
       }

       $getFilters = $this->getFilters("where $query");

       if($getFilters["id_parent"][0]){

          foreach ($getFilters["id_parent"][0] as $id_filter => $value) {
             
             $items = "";
             
             if($value["ads_filters_required"]){
                 $always = '<span>*</span>';
                 $always_input = '<input type="hidden" name="always['.$value["ads_filters_id"].']" value="'.$value["ads_filters_id"].'" />';
             }else{
                 $always = ''; $always_input = '';
             }

             $getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=? order by ads_filters_items_sort asc", array($value["ads_filters_id"]));

             if($value["ads_filters_type"] == "select"){

               if(count($getItems)){

               foreach ($getItems as $item_key => $item_value) {

                  $active = "";
                  $checked = "";

                  if($getVariants["items"][$value["ads_filters_id"]][$item_value["ads_filters_items_id"]]){
                    $active = 'class="uni-select-item-active"';
                    $checked = 'checked=""';
                  }

                  $items .= '
                   <label '.$active.' > <input type="radio" '.$checked.' name="filter['.$value["ads_filters_id"].'][]" value="'.$item_value["ads_filters_items_id"].'" > <span>'.$ULang->t( $item_value["ads_filters_items_value"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_items_value" ] ).'</span> <i class="la la-check"></i> </label>
                  ';

               }

               $return .= '

                   <div class="filter-items" id-filter="'.$value["ads_filters_id"].'" main-id-filter="0" data-ids="'.$this->idsBuild($value["ads_filters_id"],$getFilters).'" >

                        <div class="row mb15" >
                          <div class="col-lg-5" > <label>'.$ULang->t( $value["ads_filters_name"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_name" ] ).$always.'</label> </div>
                          <div class="col-lg-7">

                              <div class="uni-select" data-status="0" >

                                 <div class="uni-select-name" data-name="'.$ULang->t("Не выбрано").'" > <span>'.$ULang->t("Не выбрано").'</span> <i class="la la-angle-down"></i> </div>
                                 <div class="uni-select-list" >
                                     <div class="uni-select-list-search" '.(count($getItems) < 10 ? 'style="display: none;"' : "").' >
                                        <input class="form-control" placeholder="'.$ULang->t("Поиск").'" />
                                     </div>
                                     <label> <input type="radio" value="null" > <span>'.$ULang->t("Не выбрано").'</span> <i class="la la-check"></i> </label>
                                     '.$items.'
                                 </div>
                                
                              </div>

                              <div class="msg-error" data-name="filter'.$value["ads_filters_id"].'" ></div>

                              '.$always_input.'
                    
                          </div>
                        </div>

                      '.$this->load_podfilters_ad($value["ads_filters_id"],$getVariants["value"][$value["ads_filters_id"]][0]["ads_filters_variants_val"],$getVariants).'
                   </div> 

               ';

                }


             }elseif($value["ads_filters_type"] == "select_multi" || $value["ads_filters_type"] == "checkbox"){

               if(count($getItems)){

               foreach ($getItems as $item_key => $item_value) {

                  $active = "";
                  $checked = "";

                  if($getVariants["items"][$value["ads_filters_id"]][$item_value["ads_filters_items_id"]]){
                    $active = 'class="uni-select-item-active"';
                    $checked = 'checked=""';
                  }

                  $items .= '
                   <label '.$active.' > <input type="checkbox" '.$checked.' name="filter['.$value["ads_filters_id"].'][]" value="'.$item_value["ads_filters_items_id"].'" > <span>'.$ULang->t( $item_value["ads_filters_items_value"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_items_value" ] ).'</span> <i class="la la-check"></i> </label>
                  ';

               }

               $return .= '

                   <div class="filter-items" id-filter="'.$value["ads_filters_id"].'" main-id-filter="0" data-ids="" >

                        <div class="row mb15" >
                          <div class="col-lg-5"> <label>'.$ULang->t( $value["ads_filters_name"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_name" ] ).$always.'</label> </div>
                          <div class="col-lg-7">

                              <div class="uni-select" data-status="0" >

                                 <div class="uni-select-name" data-name="'.$ULang->t("Не выбрано").'" > <span>'.$ULang->t("Не выбрано").'</span> <i class="la la-angle-down"></i> </div>
                                 <div class="uni-select-list" >
                                     <div class="uni-select-list-search" '.(count($getItems) < 10 ? 'style="display: none;"' : "").' >
                                        <input class="form-control" placeholder="'.$ULang->t("Поиск").'" />
                                     </div>                                 
                                     '.$items.'
                                 </div>
                                
                              </div>

                              <div class="msg-error" data-name="filter'.$value["ads_filters_id"].'" ></div>

                              '.$always_input.'
                   
                          </div>
                        </div>


                   </div>
                              
               ';

                }


             }elseif($value["ads_filters_type"] == "slider" || $value["ads_filters_type"] == "input"){


               if(count($getItems)){
               $return .= '

                   <div class="filter-items" id-filter="'.$value["ads_filters_id"].'" main-id-filter="0" data-ids="" >

                        <div class="row mb15" >
                          <div class="col-lg-5"> <label>'.$ULang->t( $value["ads_filters_name"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_name" ] ).$always.'</label> </div>
                          <div class="col-lg-7">

                              <input type="number" step="any" maxlength="11" data-min="'.intval($getItems[0]["ads_filters_items_value"]).'" data-max="'.intval($getItems[1]["ads_filters_items_value"]).'" class="form-control" name="filter['.$value["ads_filters_id"].'][]" value="'.$getVariants["value"][$value["ads_filters_id"]][0]["ads_filters_variants_val"].'" />  

                              <div class="msg-error" data-name="filter'.$value["ads_filters_id"].'" ></div> 

                              '.$always_input.'

                          </div>
                        </div>


                   </div>
                              
               ';
                }


             }elseif($value["ads_filters_type"] == "input_text"){

               $return .= '

                   <div class="filter-items" id-filter="'.$value["ads_filters_id"].'" main-id-filter="0" data-ids="" >

                        <div class="row mb15" >
                          <div class="col-lg-5"> <label>'.$ULang->t( $value["ads_filters_name"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_name" ] ).$always.'</label> </div>
                          <div class="col-lg-7">

                              <input type="text" maxlength="128" class="form-control" name="filter['.$value["ads_filters_id"].'][]" value="'.$getVariants["value"][$value["ads_filters_id"]][0]["ads_filters_variants_val"].'" />  

                              <div class="msg-error" data-name="filter'.$value["ads_filters_id"].'" ></div> 

                              '.$always_input.'

                          </div>
                        </div>


                   </div>
                              
               ';

             }

          }

       }


     return $return;

   }  

   function load_podfilters_ad($id_filter=0,$id_item=0, $getVariants=array()){
       
       $CategoryBoard = new CategoryBoard();
       $getFilters = $this->getFilters("where ads_filters_visible='1'");
       $ULang = new ULang();

       if(isset($getFilters["id_parent"][$id_filter])){

          foreach ($getFilters["id_parent"][$id_filter] as $parent_value) {

            $items = "";

             if($parent_value["ads_filters_required"]){
                 $always = '<span>*</span>';
                 $always_input = '<input type="hidden" name="always['.$parent_value["ads_filters_id"].']" value="'.$parent_value["ads_filters_id"].'" />';
             }else{
                 $always = ''; $always_input = '';
             }

            $getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=? and ads_filters_items_id_item_parent=? order by ads_filters_items_sort asc", array($parent_value["ads_filters_id"],$id_item));

             if($parent_value["ads_filters_type"] == "select"){

               if(count($getItems)){

               foreach ($getItems as $item_key => $item_value) {

                  $active = "";
                  $checked = "";

                  if($getVariants["items"][$parent_value["ads_filters_id"]][$item_value["ads_filters_items_id"]]){
                    $active = 'class="uni-select-item-active"';
                    $checked = 'checked=""';
                  }

                  $items .= '
                   <label '.$active.' > <input type="radio" '.$checked.' name="filter['.$parent_value["ads_filters_id"].'][]" value="'.$item_value["ads_filters_items_id"].'" > <span>'.$item_value["ads_filters_items_value"].'</span> <i class="la la-check"></i> </label>
                  ';

               }

               $return .= '
                   <div class="filter-items" id-filter="'.$parent_value["ads_filters_id"].'" main-id-filter="'.$id_filter.'" data-ids="'.$this->idsBuild($parent_value["ads_filters_id"],$getFilters).'" >

                    <div class="row mb15" >
                      <div class="col-lg-5"> <label>'.$ULang->t( $parent_value["ads_filters_name"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_name" ] ).$always.'</label> </div>
                      <div class="col-lg-7">

                          <div class="uni-select" data-status="0" >

                             <div class="uni-select-name" data-name="'.$ULang->t("Не выбрано").'" > <span>'.$ULang->t("Не выбрано").'</span> <i class="la la-angle-down"></i> </div>
                             <div class="uni-select-list" >
                                 <div class="uni-select-list-search" '.(count($getItems) < 10 ? 'style="display: none;"' : "").' >
                                    <input class="form-control" placeholder="'.$ULang->t("Поиск").'" />
                                 </div>                             
                                 <label> <input type="radio" value="null" > <span>'.$ULang->t("Не выбрано").'</span> <i class="la la-check"></i> </label>
                                 '.$items.'
                             </div>
                            
                          </div>

                          <div class="msg-error" data-name="filter'.$parent_value["ads_filters_id"].'" ></div>

                          '.$always_input.'
   
                      </div>
                    </div>

                    '.$this->load_podfilters_ad($parent_value["ads_filters_id"],$getVariants["value"][$parent_value["ads_filters_id"]][0]["ads_filters_variants_val"],$getVariants).'
                   </div>                              
               ';

               }


             }elseif($parent_value["ads_filters_type"] == "select_multi" || $parent_value["ads_filters_type"] == "checkbox"){

               if(count($getItems)){

               foreach ($getItems as $item_key => $item_value) {

                  $active = "";
                  $checked = "";

                  if($getVariants["items"][$parent_value["ads_filters_id"]][$item_value["ads_filters_items_id"]]){
                    $active = 'class="uni-select-item-active"';
                    $checked = 'checked=""';
                  }

                  $items .= '
                   <label '.$active.' > <input type="checkbox" '.$checked.' name="filter['.$parent_value["ads_filters_id"].'][]" value="'.$item_value["ads_filters_items_id"].'" > <span>'.$ULang->t( $item_value["ads_filters_items_value"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_items_value" ] ).'</span> <i class="la la-check"></i> </label>
                  ';

               }

               $return .= '
                   <div class="filter-items" id-filter="'.$parent_value["ads_filters_id"].'" main-id-filter="'.$id_filter.'" data-ids="" >

                    <div class="row mb15" >
                      <div class="col-lg-5"> <label>'.$ULang->t( $parent_value["ads_filters_name"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_name" ] ).$always.'</label> </div>
                      <div class="col-lg-7">

                          <div class="uni-select" data-status="0" >

                             <div class="uni-select-name" data-name="'.$ULang->t("Не выбрано").'" > <span>'.$ULang->t("Не выбрано").'</span> <i class="la la-angle-down"></i> </div>
                             <div class="uni-select-list" >
                                 <div class="uni-select-list-search" '.(count($getItems) < 10 ? 'style="display: none;"' : "").' >
                                    <input class="form-control" placeholder="'.$ULang->t("Поиск").'" />
                                 </div>                             
                                 '.$items.'
                             </div>
                            
                          </div>

                          <div class="msg-error" data-name="filter'.$parent_value["ads_filters_id"].'" ></div>

                          '.$always_input.'
  
                      </div>
                    </div>

                   </div>                              
               ';

                }


             }elseif($parent_value["ads_filters_type"] == "slider" || $parent_value["ads_filters_type"] == "input"){

               if(count($getItems)){

               $return .= '

                   <div class="filter-items" id-filter="'.$parent_value["ads_filters_id"].'" main-id-filter="'.$id_filter.'" data-ids="" >

                        <div class="row mb15" >
                          <div class="col-lg-5"> <label>'.$ULang->t( $parent_value["ads_filters_name"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_name" ] ).$always.'</label> </div>
                          <div class="col-lg-7">

                              <input type="number" step="any" data-min="'.intval($getItems[0]["ads_filters_items_value"]).'" data-max="'.intval($getItems[1]["ads_filters_items_value"]).'" class="form-control" name="filter['.$parent_value["ads_filters_id"].'][]" value="'.$getVariants["value"][$parent_value["ads_filters_id"]][0]["ads_filters_variants_val"].'" />  

                              <div class="msg-error" data-name="filter'.$parent_value["ads_filters_id"].'" ></div>

                              '.$always_input.'

                          </div>
                        </div>

                   </div>
                              
               ';

               }


             }elseif($parent_value["ads_filters_type"] == "input_text"){

               $return .= '

                   <div class="filter-items" id-filter="'.$parent_value["ads_filters_id"].'" main-id-filter="'.$id_filter.'" data-ids="" >

                        <div class="row mb15" >
                          <div class="col-lg-5"> <label>'.$ULang->t( $parent_value["ads_filters_name"] , [ "table" => "uni_ads_filters", "field" => "ads_filters_name" ] ).$always.'</label> </div>
                          <div class="col-lg-7">

                              <input type="text" class="form-control" name="filter['.$parent_value["ads_filters_id"].'][]" value="'.$getVariants["value"][$parent_value["ads_filters_id"]][0]["ads_filters_variants_val"].'" />  

                              <div class="msg-error" data-name="filter'.$parent_value["ads_filters_id"].'" ></div>

                              '.$always_input.'

                          </div>
                        </div>

                   </div>
                              
               ';

             }

          }

       }

     return $return;

   }

   function load_filters_catalog($id_cat=0, $param=[], $tpl=""){
       global $config;

       $ULang = new ULang();
       $filters_ids = $this->getCategory( ["id_cat"=>$id_cat] );

       if($filters_ids){
          $query = "ads_filters_visible='1' and ads_filters_id IN(".implode(",", $filters_ids).")";
       }else{
          $query = "ads_filters_visible='1' and ads_filters_id IN(0)";
       }

       $getFilters = $this->getFilters("where $query");

       if($getFilters["id_parent"][0]){

          foreach ($getFilters["id_parent"][0] as $id_filter => $value) {
             
             $items = "";
             
             if($this->getEmpty($param["filter"][$value["ads_filters_id"]])){
                $statusOpenItems = 'catalog-list-options-active';
             }else{
                $statusOpenItems = '';
             }

             $getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=? order by ads_filters_items_sort asc", array($value["ads_filters_id"]));

             if( $getItems ){
             
               if(file_exists($config["template_path"]."/include/".$tpl.".php")){ 
                  require $config["template_path"] . "/include/".$tpl.".php";
               }else{
                  require $config["template_path"] . "/include/filters_catalog.php";
               }

             }
             

          }

       }


     return $return;

   }

   function load_podfilters_catalog($id_filter=0,$id_item=0,$param=[],$tpl=""){
       global $config;

       $ULang = new ULang();
       $getFilters = $this->getFilters("where ads_filters_visible=1");

       if(isset($getFilters["id_parent"][$id_filter])){

          foreach ($getFilters["id_parent"][$id_filter] as $parent_value) {

            $items = "";

            $getItems = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=? and ads_filters_items_id_item_parent=? order by ads_filters_items_sort asc", array($parent_value["ads_filters_id"],$id_item));

            if($this->getEmpty($param["filter"][$parent_value["ads_filters_id"]])){
                $statusOpenItems = 'catalog-list-options-active';
            }else{
                $statusOpenItems = '';
            }

            if( $getItems ){
             
               if(file_exists($config["template_path"]."/include/".$tpl.".php")){ 
                  require $config["template_path"] . "/include/".$tpl.".php";
               }else{
                  require $config["template_path"] . "/include/podfilters_catalog.php";
               }

            }
             
          }

       }

     return $return;

   }


   function checkSelected($id = 0,$id_item = 0, $param = []){
      if($param["filter"][$id]){
         if( in_array($id_item, $param["filter"][$id]) ){
             return true;
         }
      }
   }


    function queryString($string = ""){
       if($string){
         parse_str($string, $query_params);
         unset($query_params["_"]);
         unset($query_params["page"]);
         unset($query_params["action"]);
         return http_build_query($query_params, 'flags_');
       }
    }

    function getEmpty($array = array()){
      $out = array();

        if(!$array){
           return false;
        }else{
          foreach ($array as $key => $value) {
             if(trim($value) && trim($value) != "null") $out[] = $value;
          }
        }

        if(!$out){
            return false;
        }else{
            return true;
        }

    }

    function idsBuild($parent_id=0, $filters=[]){

        if(isset($filters['id_parent'][$parent_id])){

              foreach($filters['id_parent'][$parent_id] as $id => $value){
                      
                $ids[] = $value['ads_filters_id'];
                
                if( $filters['id_parent'][$value['ads_filters_id']] ){
                  $ids[] = $this->idsBuild($value['ads_filters_id'],$filters);
                }
                                                 
              }

        }
        
        return $ids ? implode(",", $ids) : '';

    }

    function addVariants($filters = array(),$product_id=0,$cat_id=0){
        
        $query = [];
        $getFilters = $this->getFilters();

        update("DELETE FROM uni_ads_filters_variants WHERE ads_filters_variants_product_id=?", array($product_id));

        if($this->getEmptyVariants($filters)){     

              foreach($filters AS $id_filter=>$array){

                  foreach($array AS $key=>$val){

                     $filter = $getFilters["id"][intval($id_filter)];

                     if(trim($val) && $val != "null") {

                        if($filter['ads_filters_type'] != 'input_text') $val = round($val,2);

                        $query[] = "('".intval($id_filter)."','".$val."','".intval($product_id)."')"; 

                     }
                     
                  }
                 
              }

              if($query){

                insert("INSERT INTO uni_ads_filters_variants(ads_filters_variants_id_filter,ads_filters_variants_val,ads_filters_variants_product_id)VALUES ".implode(",", $query));

              }

        }

    }

    function getVariants($ad_id = 0){
      
      if($ad_id){
        $getVariants = getAll("SELECT * FROM uni_ads_filters_variants WHERE ads_filters_variants_product_id=? order by ads_filters_variants_id asc", array($ad_id));
        if ($getVariants) { 

            $data = array();    

              foreach($getVariants AS $result){

                  $data["items"][$result['ads_filters_variants_id_filter']][$result["ads_filters_variants_val"]]  =  $result;
                  $data["value"][$result['ads_filters_variants_id_filter']][]  =  $result;

              }  

        }            

        return $data;
      }else{
        return array();
      }
               
    }

    function outProductProp($product_id=0, $id_cat=0, $category = [], $city_alias=""){
      global $settings;

      $ULang = new ULang();
      $Filters = new Filters();
      $Seo = new Seo();
      $out = array();

          if($_SESSION["geo"]["alias"]){
             $city_alias = $_SESSION["geo"]["alias"];
          }else{
             $city_alias = $settings["country_default"];
          }

          $getVariants = $Filters->getVariants($product_id);
          if ($getVariants["items"]) { 

                foreach($getVariants["items"] AS $id_filter => $array){
                  
                  $value = array();

                  $getFilter = findOne("uni_ads_filters", "ads_filters_id=?", array( intval($id_filter) ));
                  if($getFilter){
                      foreach($array AS $val => $result){

                          if($getFilter->ads_filters_type == "input" || $getFilter->ads_filters_type == "input_text"){
                             $value[] = $val;
                          }else{
                             $getItem = findOne("uni_ads_filters_items", "ads_filters_items_id=?", array($val));
                             $getAlias = findOne("uni_ads_filters_alias", "ads_filters_alias_id_filter_item=? and ads_filters_alias_id_cat=?", array($val,$id_cat));
                             if($getAlias){
                               $value[] = '<a title="'.$Seo->replace($getAlias["ads_filters_alias_name"] ? $ULang->t($getAlias["ads_filters_alias_name"], [ "table" => "uni_ads_filters_alias", "field" => "ads_filters_alias_name" ]) : $ULang->t($getAlias["ads_filters_alias_title"], [ "table" => "uni_ads_filters_alias", "field" => "ads_filters_alias_title" ])).'" href="'.$Filters->alias( ["category_alias"=>$category["category_board_id"][$id_cat]["category_board_chain"], "filter_alias"=>$getAlias["ads_filters_alias_alias"], "geo_alias" => $city_alias] ).'" >'.$ULang->t( $getItem->ads_filters_items_value , [ "table" => "uni_ads_filters", "field" => "ads_filters_items_value" ] ).'</a>'; 
                             }else{
                               $value[] = $ULang->t( $getItem->ads_filters_items_value , [ "table" => "uni_ads_filters", "field" => "ads_filters_items_value" ] );
                             }
                          }

                      }
                      $out[$getFilter->ads_filters_position][] = '<div class="list-properties-item" ><div class="list-properties-box-line" ><span class="span-style span-style-strong list-properties-span1" >'.$ULang->t( $getFilter->ads_filters_name , [ "table" => "uni_ads_filters", "field" => "ads_filters_name" ] ).'</span></div><span class="list-properties-span2" >'.implode(", ", $value).'</span></div>';
                  }

                }  

          }
       
       ksort($out);

       if(count($out)){
          foreach ($out as $key => $nested) {
              foreach ($nested as $value) {
                 $return[] = $value;
              }
          }

          return implode("", $return);
       }

    }

    function outProductPropArray($product_id=0){
      
      $out = [];

          $getVariants = $this->getVariants($product_id);
          if ($getVariants["items"]) { 

                foreach($getVariants["items"] AS $id_filter => $array){
                  
                  $value = [];

                  $getFilter = findOne("uni_ads_filters", "ads_filters_id=?", array( intval($id_filter) ));
                  if($getFilter){
                      foreach($array AS $val => $result){

                          if($getFilter->ads_filters_type == "input" || $getFilter->ads_filters_type == "input_text"){
                             $value[] = html_entity_decode($val);
                          }else{
                             $getItem = findOne("uni_ads_filters_items", "ads_filters_items_id=?", array($val));
                             $value[] = html_entity_decode($getItem->ads_filters_items_value);
                          }

                      }
                      $out[$getFilter->ads_filters_position] = [ "name" => html_entity_decode($getFilter->ads_filters_name), "value" => implode(",", $value) ];
                  }

                }  

          }
       
       ksort($out);

       return $out;

    }

    function filterPosition(){
        return getOne("select MAX(ads_filters_position) as max from uni_ads_filters")["max"] + 1;
    }

    function explodeSearch($search = ""){
       $search = clearSearchBack($search);
       if($search){
        $split = preg_split("/( )+/", $search);
        if($split){
              foreach ($split as $key => $value) {
                $return[] = "(ads_title LIKE '%".$value."%' or ads_filter_tags LIKE '%".$value."%' or ads_search_tags LIKE '%".$value."%')";
              }
              return implode(" AND ",$return);
        }else{
          return "(ads_title LIKE '%".$search."%' or ads_filter_tags LIKE '%".$search."%' or ads_search_tags LIKE '%".$search."%')";
        }
       }
    }

    function getEmptyVariants($array = array()){
      $out = array();

        if($array){
          foreach ($array as $id_filter => $array_filter) {
            foreach ($array_filter as $id_item => $value) {
               if(trim($value) && trim($value) != "null") $out[] = $value;
            }
          }
        }

        if(!$out){
            return false;
        }else{
            return true;
        }

    }

    function getInputValue($id_filter=0){

        $getFilter = findOne("uni_ads_filters", "ads_filters_id=?", [$id_filter]);

        if($getFilter["ads_filters_type"] == "input"){

           $getItem = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=? order by ads_filters_items_id", [$id_filter]);

           return [ "min" => $getItem[0]["ads_filters_items_value"], "max" => $getItem[1]["ads_filters_items_value"] ];

        }

        return [];

    }

    function countFilters($id_cat = 0){

       $filters_ids = $this->getCategory( ["id_cat"=>$id_cat] );
       if($filters_ids){
         return (int)getOne("select count(*) as total from uni_ads_filters where ads_filters_id_parent=0 and ads_filters_id IN(".implode(",", $filters_ids).")")["total"];
       }

    }

    function countGetFilters( $filters = [] ){

       unset($filters["filter"]["price"]);
       unset($filters["filter"]["status"]);
       unset($filters["filter"]["period"]);
       unset($filters["filter"]["sort"]);
       unset($filters["filter"]["secure"]);
       unset($filters["filter"]["auction"]);
       unset($filters["filter"]["area"]);
       unset($filters["filter"]["vip"]);
       unset($filters["filter"]["online_view"]);
       unset($filters["filter"]["metro"]);

       return $filters["filter"] ? count($filters["filter"]) : 0;

    }

    function viewSeoFilter($id=0){
      if(detectRobots($_SERVER['HTTP_USER_AGENT']) == false){
        if($id){    
            if(!isset($_SESSION["view-seo-filter"][$id])){
              update("UPDATE uni_seo_filters SET seo_filters_count_view=seo_filters_count_view+1 WHERE seo_filters_id=?", array($id)); 
              $_SESSION["view-seo-filter"][$id] = 1;
            }  
        } 
      }   
    }

    function outSeoAliasCategory( $id_cat = 0 ){
       global $settings;

       $CategoryBoard = new CategoryBoard();
       $Seo = new Seo();
       $ULang = new ULang();

       if(!$id_cat) return false;

       $getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

       $getFilters = getAll("select * from uni_ads_filters_alias where ads_filters_alias_id_cat=? order by ads_filters_alias_name asc", [$id_cat]);

       if($settings["main_type_products"] == 'physical'){
           if($_SESSION["geo"]["alias"]){
              $geo = $_SESSION["geo"]["alias"];
           }else{
              $geo = $settings["country_default"];
           }
       }

       if( count($getFilters) ){
         
           foreach ($getFilters as $key => $value) {
              $return .= '<div class="item-label-seo-filter" ><a href="'._link( $geo . "/" . $getCategoryBoard["category_board_id"][$id_cat]["category_board_chain"] . "/" . $value["ads_filters_alias_alias"] ).'" >'.$Seo->replace($value["ads_filters_alias_name"] ? $ULang->t($value["ads_filters_alias_name"], [ "table" => "uni_ads_filters_alias", "field" => "ads_filters_alias_name" ]) : $ULang->t($value["ads_filters_alias_h1"], [ "table" => "uni_ads_filters_alias", "field" => "ads_filters_alias_h1" ])).'</a></div>';
           }

           return $return;

       }

    }

    function seoAlias( $alias = "" ){

         $alias = trim($alias, "/");

         if($_SESSION["geo"]["alias"]){
            return _link( $_SESSION["geo"]["alias"] . "/" . $alias );
         }else{
            return _link( "cities" );
         }

    }

    function buildTags( $filters = [] ){

      $tags = [];

      if($this->getEmptyVariants($filters)){ 
         foreach ($filters as $id_filter => $nested) {

           $getFilter = findOne( "uni_ads_filters", "ads_filters_id=?", [ $id_filter ] );

           if( $getFilter["ads_filters_type"] != "input" && $getFilter["ads_filters_type"] != "input_text" ){

               foreach ($nested as $value) {
                  
                  $getFilterItem = findOne( "uni_ads_filters_items", "ads_filters_items_id=?", [ $value ] );
                  if($getFilterItem){
                     $tags[] = $getFilterItem["ads_filters_items_value"];
                  }
               
               }

           }else{
              if( $nested[0] ) $tags[] = $nested[0];
           }

   
         }
       }

       return implode( ";", $tags );

    }

    function mapCountChangeFilters($params=[]){
        unset($params['area']);
        unset($params['metro']);
        return $params ? count($params) : 0;
    }

    function outFormFilters($name,$params=[]){
        global $config;
        $ULang = new ULang();
        $CategoryBoard = new CategoryBoard();
        $Geo = new Geo();
        $Ads = new Ads();
        $Filters = new Filters();
        $Main = new Main();
        if($name == 'map'){
            $data = $params['data'];
            $getCategoryBoard = $params['categories'];
            require $config['template_path'].'/include/form_filters/map.php';
        }elseif($name == 'catalog'){
            $data = $params['data'];
            $getCategoryBoard = $params['categories'];
            require $config['template_path'].'/include/form_filters/catalog.php';
        }elseif($name == 'shop'){
            $data = $params['data'];
            $getCategoryBoard = $params['categories'];
            require $config['template_path'].'/include/form_filters/shop.php';
        }
    }




}


?>