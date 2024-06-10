<?php

class CategoryBoard{
   
    function alias( $category_alias="", $geo_alias="" ){
       global $settings;

       if($settings["main_type_products"] == 'physical'){
           if( $geo_alias ){
               return _link($geo_alias."/".$category_alias);
           }else{
               if(isset($_SESSION["geo"]["alias"])){
                  return _link($_SESSION["geo"]["alias"]."/".$category_alias);
               }else{
                  return _link($category_alias); 
               }
           }
       }else{
           return _link($category_alias);
       }
 
    }

    function allMain(){

       $ULang = new ULang();
       
       $getCategories = $this->getCategories("where category_board_visible=1");

       if($getCategories["category_board_id_parent"][0]){
           foreach ($getCategories["category_board_id_parent"][0] as $key => $value) {
              $out[] = $ULang->t($value["category_board_name"] , [ "table" => "uni_category_board", "field" => "category_board_name" ] );
           }
         return implode(",", $out);
       }

    }

    function getCategories($query = ""){
        global $settings;
        
        $array = array();

        $Cache = new Cache();

        if( $Cache->get( [ "table" => "uni_category_board", "key" => $query ] ) ){
           
           return $Cache->get( [ "table" => "uni_category_board", "key" => $query ] );

        }else{
           
           $get = getAll("SELECT * FROM uni_category_board $query ORDER By category_board_id_position ASC");
           if (count($get)) { 
          
                foreach($get AS $result){

                    if($result['category_board_id_parent']){
                      $result['category_board_chain'] = $this->aliasBuild($result['category_board_id']);
                    }else{
                      $result['category_board_chain'] = $result['category_board_alias'];
                    }
                    
                    $array['category_board_chain'][$result['category_board_chain']] = $result;
                    $array['category_board_id_parent'][$result['category_board_id_parent']][$result['category_board_id']] =  $result;
                    $array['category_board_id'][$result['category_board_id']]['category_board_id_parent'] =  $result['category_board_id_parent'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_name'] =  $result['category_board_name'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_title'] =  $result['category_board_title'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_description'] =  $result['category_board_description'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_image'] =  $result['category_board_image'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_text'] =  $result['category_board_text'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_alias'] =  $result['category_board_alias'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_id'] =  $result['category_board_id'];  
                    $array['category_board_id'][$result['category_board_id']]['category_board_chain'] =  $result['category_board_chain'];  
                    $array['category_board_id'][$result['category_board_id']]['category_board_price'] =  $result['category_board_price'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_count_free'] =  $result['category_board_count_free'];          
                    $array['category_board_id'][$result['category_board_id']]['category_board_status_paid'] =  $result['category_board_status_paid'];          
                    $array['category_board_id'][$result['category_board_id']]['category_board_display_price'] =  $result['category_board_display_price'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_variant_price_id'] =  $result['category_board_variant_price_id'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_measures_price'] =  $result['category_board_measures_price'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_auto_title'] =  $result['category_board_auto_title'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_online_view'] =  $result['category_board_online_view'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_h1'] =  $result['category_board_h1'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_auto_title_template'] =  $result['category_board_auto_title_template'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_show_index'] =  $result['category_board_show_index'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_booking'] =  $result['category_board_booking'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_booking_variant'] =  $result['category_board_booking_variant'];
                    $array['category_board_id'][$result['category_board_id']]['category_board_name_word_wrap'] =  $result['category_board_name_word_wrap'];
                    

                    if(isset($result['category_board_rules'])){
                        $category_board_rules = $result['category_board_rules'] ? json_decode($result['category_board_rules'], true) : [];
                        if($category_board_rules){
                            foreach ($category_board_rules as $value_key) {
                                $array['category_board_id'][$result['category_board_id']]['category_board_rules'][$value_key] = 1;
                            }
                        }
                    }

                    $array['category_board_id'][$result['category_board_id']]['category_board_secure'] =  $result['category_board_secure'];

                    if(isset($settings["functionality"]["auction"])){
                       $array['category_board_id'][$result['category_board_id']]['category_board_auction'] =  $result['category_board_auction'];
                    }else{
                       $array['category_board_id'][$result['category_board_id']]['category_board_auction'] =  0;
                    }

                    if(isset($settings["functionality"]["marketplace"])){
                       $array['category_board_id'][$result['category_board_id']]['category_board_marketplace'] =  $result['category_board_marketplace'];
                    }else{
                       $array['category_board_id'][$result['category_board_id']]['category_board_marketplace'] =  0;
                    }
                    
                    if(isset($settings["functionality"]["booking"])){
                       $array['category_board_id'][$result['category_board_id']]['category_board_booking'] =  $result['category_board_booking'];
                    }else{
                       $array['category_board_id'][$result['category_board_id']]['category_board_booking'] =  0;
                    }
                                            
                }  

                $Cache->set( [ "table" => "uni_category_board", "key" => $query, "data" => $array ] );

           }            

           return $array;

        }
      
    }

    function aliasBuild($id = 0){

      $out = "";

      $get = getOne("SELECT * FROM uni_category_board where category_board_id=?", array($id));
      
        if($get['category_board_id_parent']!=0){ 
            $out .= $this->aliasBuild($get['category_board_id_parent'])."/";            
        }
        $out .= $get['category_board_alias'];

        return $out; 
               
    }

    function outParent( $getCategories = [], $param = [] ){
      global $config;

      $Ads = new Ads();
      $ULang = new ULang();
      $return = '';

      if( $Ads->queryGeo() ){
         $queryGeo = " and " . $Ads->queryGeo();
      }

      if($param["category"]["category_board_id"]){

        if (isset($getCategories["category_board_id_parent"][$param["category"]["category_board_id"]])) {
            foreach ($getCategories["category_board_id_parent"][$param["category"]["category_board_id"]] as $parent_value) {

               $countAd = $this->getCountAd( $parent_value["category_board_id"] );

               if($parent_value["category_board_image"] && file_exists($config["basePath"]."/".$config["media"]["other"]."/".$parent_value["category_board_image"])){
                    $image = '<div class="image-parent-category" ><img src="'.Exists($config["media"]["other"],$parent_value["category_board_image"],$config["media"]["no_image"]).'" /></div>';
               }else{
                   $image = '';
               }
              
               $parent[] = replace(array("{PARENT_LINK}", "{PARENT_IMAGE}", "{PARENT_NAME}","{COUNT_AD}"),array($this->alias($parent_value["category_board_chain"]),$image,$ULang->t( $parent_value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ),$countAd),$param["tpl_parent"]);

               $return .=  replace(array("{PARENT_CATEGORY}"),array(implode($param["sep"],$parent)),$param["tpl"]);
               $parent = array();

            }
        }else{

          $id_parent = $getCategories["category_board_id"][$param["category"]["category_board_id_parent"]];

          if(isset($getCategories["category_board_id_parent"][$id_parent["category_board_id"]])){
            foreach ($getCategories["category_board_id_parent"][$id_parent["category_board_id"]] as $parent_value) {

              if($parent_value["category_board_id"] == $param["category"]["category_board_id"]){
                $active = 'class="active"';
              }else{
                $active = '';
              }
               
               $countAd = $this->getCountAd( $parent_value["category_board_id"] );
              
               if($parent_value["category_board_image"] && file_exists($config["basePath"]."/".$config["media"]["other"]."/".$parent_value["category_board_image"])){
                    $image = '<div class="image-parent-category" ><img src="'.Exists($config["media"]["other"],$parent_value["category_board_image"],$config["media"]["no_image"]).'" /></div>';
               }else{
                   $image = '';
               }

               $parent[] = replace(array("{PARENT_LINK}", "{PARENT_IMAGE}", "{PARENT_NAME}", "{ACTIVE}","{COUNT_AD}"),array($this->alias($parent_value["category_board_chain"]),$image,$ULang->t( $parent_value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ),$active,$countAd),$param["tpl_parent"]);

               $return .=  replace(array("{PARENT_CATEGORY}"),array(implode($param["sep"],$parent)),$param["tpl"]);
               $parent = array();

            }
          }
        }                 
       

      }else{

        if (isset($getCategories["category_board_id_parent"][0])) {
            foreach ($getCategories["category_board_id_parent"][0] as $value) {
               
               $countAd = $this->getCountAd( $value["category_board_id"] );
              
               if($value["category_board_image"] && file_exists($config["basePath"]."/".$config["media"]["other"]."/".$value["category_board_image"])){
                    $image = '<div class="image-parent-category" ><img src="'.Exists($config["media"]["other"],$value["category_board_image"],$config["media"]["no_image"]).'" /></div>';
               }else{
                   $image = '';
               }
              
               $parent[] = replace(array("{PARENT_LINK}", "{PARENT_IMAGE}", "{PARENT_NAME}","{COUNT_AD}"),array($this->alias($value["category_board_chain"]),$image,$ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ),$countAd),$param["tpl_parent"]);

               $return .=  replace(array("{PARENT_CATEGORY}"),array(implode($param["sep"],$parent)),$param["tpl"]);
               $parent = array();

            }
        }

      }

      return $return;

    }

    function outMainCategory($tpl, $tpl_parent = "", $sep = ""){

      $getCategories = $this->getCategories("where category_board_visible=1");  
      $Ads = new Ads(); 
      $ULang = new ULang();

      $return = "";
      $parent = array();
        if (isset($getCategories["category_board_id_parent"][0])) {
            foreach ($getCategories["category_board_id_parent"][0] as $value) {
              
               if($getCategories["category_board_id_parent"][$value["category_board_id"]] && $tpl_parent){
                 foreach (array_slice($getCategories["category_board_id_parent"][$value["category_board_id"]], 0, 6) as $parent_value) {
                   $parent[] = replace(array("{PARENT_LINK}", "{PARENT_IMAGE}", "{PARENT_NAME}"),array($this->alias($parent_value["category_board_chain"]),Exists($image_category,$parent_value["category_board_image"],$no_image),$ULang->t( $parent_value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] )),$tpl_parent);
                 }
               }
           
               $return .=  replace(array("{LINK}", "{IMAGE}", "{NAME}", "{PARENT_CATEGORY}"),array($this->alias($value["category_board_alias"]),Exists($image_category,$value["category_board_image"],$no_image),$ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ), implode($sep,$parent)),$tpl);
               $parent = array();
            }
        }                 
      return $return;
    }

    function idsBuild($parent_id=0, $categories = []){
        
        if(isset($categories['category_board_id_parent'][$parent_id])){

              foreach($categories['category_board_id_parent'][$parent_id] as $cat){
                       
                $ids[] = $cat['category_board_id'];
                
                if(isset($categories['category_board_id_parent'][$cat['category_board_id']])){
                  $ids[] = $this->idsBuild($cat['category_board_id'],$categories);
                }
                                            
              }

        }
        
        return isset($ids) ? implode(",", $ids) : '';

    }
    

   function viewCategory($id=0){
    if(detectRobots($_SERVER['HTTP_USER_AGENT']) == false){
      if($id){    
          if(!isset($_SESSION["view-category-ads"][$id])){
            update("UPDATE uni_category_board SET category_board_count_view=category_board_count_view+1,category_board_datetime_view=? WHERE category_board_id=?", array(date("Y-m-d H:i:s"),$id)); 
            $_SESSION["view-category-ads"][$id] = 1;
          }  
      } 
    }   
   }

    function breadcrumb($getCategories=array(),$id=0,$tpl="",$sep=""){

      $ULang = new ULang();

      if($getCategories){

        if($getCategories["category_board_id"][$id]['category_board_id_parent']!=0){
            $return[] = $this->breadcrumb($getCategories,$getCategories["category_board_id"][$id]['category_board_id_parent'],$tpl,$sep);  
        }

        $return[] = replace(array("{LINK}", "{NAME}"),array($this->alias($getCategories["category_board_id"][$id]["category_board_chain"]),$ULang->t($getCategories["category_board_id"][$id]['category_board_name'] , [ "table" => "uni_category_board", "field" => "category_board_name" ] )),$tpl);

        return implode($sep,$return);

      } 
               
    }
    
    function breadcrumbShop($getCategories=array(),$id=0,$tpl="",$shop_id_hash=""){

      $ULang = new ULang();
      $Shop = new Shop();

      if($getCategories){

        if($getCategories["category_board_id"][$id]['category_board_id_parent']!=0){
            $return[] = $this->breadcrumbShop($getCategories,$getCategories["category_board_id"][$id]['category_board_id_parent'],$tpl,$shop_id_hash);  
        }

        $return[] = replace(array("{LINK}", "{NAME}"),array($Shop->aliasCategory($shop_id_hash,$getCategories["category_board_id"][$id]["category_board_chain"]),$ULang->t($getCategories["category_board_id"][$id]['category_board_name'] , [ "table" => "uni_category_board", "field" => "category_board_name" ] )),$tpl);

        return implode('',$return);

      } 
               
    }
    
    function reverseId($getCategories=array(),$id=0){

          if($getCategories){

            if($getCategories["category_board_id"][$id]['category_board_id_parent']!=0){
                $return[] = $this->reverseId($getCategories,$getCategories["category_board_id"][$id]['category_board_id_parent']);  
            }

            $return[] = $getCategories["category_board_id"][$id]["category_board_id"];

            return implode(',',$return);

          } 
               
    }
    
    function reverseMainIds($getCategories=array(),$id=0){

          if($getCategories){

            if($getCategories["category_board_id"][$id]['category_board_id_parent']!=0){
                $return[] = $this->reverseMainIds($getCategories,$getCategories["category_board_id"][$id]['category_board_id_parent']);  
            }

            $return[] = $getCategories["category_board_id"][$id]["category_board_id"];

            return implode(',',$return);

          } 
               
    }

    function reverseMainId($getCategories=array(),$id=0){

          if($getCategories){

            if($getCategories["category_board_id"][$id]['category_board_id_parent']!=0){
                $return[] = $this->reverseMainId($getCategories,$getCategories["category_board_id"][$id]['category_board_id_parent']);  
            }

            $return[] = $getCategories["category_board_id"][$id]["category_board_id"];

            return $return[0];

          } 
               
    }

    function getCountAd($id=0, $user_id=0){
      global $settings;

      if($settings['display_count_ads_categories']){
        
          $count = 0;
          $idsBuildArray = [];

          $getCategories = $this->getCategories("where category_board_visible=1");

          $idsBuildString = idsBuildJoin($this->idsBuild($id, $getCategories),$id);

          foreach (explode(",", $idsBuildString) as $value) {
             $idsBuildArray[] = (int)$value;
          }

          if(!$user_id){

             if($_SESSION["geo"]["data"]["city_id"]){
                 $count = getOne("select sum(count_ad) as total from uni_ads_summary where cat_id IN(".implode(",", $idsBuildArray).") and city_id=?", [$_SESSION["geo"]["data"]["city_id"]])["total"];
             }elseif($_SESSION["geo"]["data"]["region_id"]){
                 $count = getOne("select sum(count_ad) as total from uni_ads_summary where cat_id IN(".implode(",", $idsBuildArray).") and region_id=?", [$_SESSION["geo"]["data"]["region_id"]])["total"];
             }elseif($_SESSION["geo"]["data"]["country_id"]){
                 $count = getOne("select sum(count_ad) as total from uni_ads_summary where cat_id IN(".implode(",", $idsBuildArray).") and country_id=?", [$_SESSION["geo"]["data"]["country_id"]])["total"];
             }else{
                 $count = getOne("select sum(count_ad) as total from uni_ads_summary where cat_id IN(".implode(",", $idsBuildArray).")")["total"];
             }

          }else{

             if($_SESSION["geo"]["data"]["city_id"]){
                 $count = (int)getOne("select count(ads_id) as total from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user where ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_cat IN(".implode(",", $idsBuildArray).") and ads_city_id=? and ads_id_user=?", [$_SESSION["geo"]["data"]["city_id"],$user_id])["total"];
             }elseif($_SESSION["geo"]["data"]["region_id"]){
                 $count = (int)getOne("select count(ads_id) as total from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user where ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_cat IN(".implode(",", $idsBuildArray).") and ads_region_id=? and ads_id_user=?", [$_SESSION["geo"]["data"]["region_id"],$user_id])["total"];
             }elseif($_SESSION["geo"]["data"]["country_id"]){
                 $count = (int)getOne("select count(ads_id) as total from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user where ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_cat IN(".implode(",", $idsBuildArray).") and ads_country_id=? and ads_id_user=?", [$_SESSION["geo"]["data"]["country_id"],$user_id])["total"];
             }else{
                 $count = (int)getOne("select count(ads_id) as total from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user where ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_cat IN(".implode(",", $idsBuildArray).") and ads_id_user=?", [$user_id])["total"];
             }

          }

          if($settings['count_ad_show_zero']){
            return $count;
          }else{
            if($count){
                return $count;
            }
          }

      }

    }
       
    function firstIdsBuild($parent_id=0, $categories = []){
        
        if(isset($categories['category_board_id_parent'][$parent_id])){

              foreach(array_slice($categories['category_board_id_parent'][$parent_id], 0,1) as $cat){
                       
                $id = $cat['category_board_id'];
                
                if(isset($categories['category_board_id_parent'][$cat['category_board_id']])){
                  $id = $this->firstIdsBuild($cat['category_board_id'],$categories);
                }
                            
              }

        }
        
        return $id ? $id : $parent_id;

    }

}


?>