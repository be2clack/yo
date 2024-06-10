<?php
/**
 * UniSite CMS
 *
 * @copyright 	2018 Artur Zhur
 * @link 		https://unisitecms.ru
 * @author 		Artur Zhur
 *
 */

class Shop{

    function viewShop( $id = 0 ){

      if(detectRobots($_SERVER['HTTP_USER_AGENT']) == false){
        if($id){    
            if(!isset($_SESSION["view-shop"][$id])){ 
              update("update uni_clients_shops set clients_shops_count_view=clients_shops_count_view+1 where clients_shops_id=?", [$id]);
              $_SESSION["view-shop"][$id] = 1;
            }  
        }
      }       

    }

    function linkShops(){
        global $settings;
        return _link($settings['user_shop_alias_url_all']);
    }

    function linkShopsCategory($chain_category=""){
        global $settings;
        return _link($settings['user_shop_alias_url_all']."/".$chain_category);
    }

    function linkShop($id_hash=""){
        global $settings;
        return _link($settings['user_shop_alias_url_page']."/".$id_hash);
    }

    function aliasCategory($id_hash="", $chain_category=""){
        global $settings;
        return _link($settings['user_shop_alias_url_page']."/".$id_hash.'/'.$chain_category);
    }

    function aliasPage($id_hash="", $alias_page=""){
        global $settings;
        return _link($settings['user_shop_alias_url_page']."/".$id_hash.'/page/'.$alias_page);
    }

    function buildCategories($getCategories=array(),$id=0){

      if($getCategories){

        if($getCategories["category_board_id"][$id]['category_board_id_parent']!=0){
            $return[] = $this->buildCategories($getCategories,$getCategories["category_board_id"][$id]['category_board_id_parent']);  
        }

        $return[] = $id;

        return implode(",", $return);

      } 
               
    }

    function adCategories($id_user = 0){

        $groupBy = [];
        $list_ids = [];

        $getCategories = (new CategoryBoard())->getCategories("where category_board_visible=1");
        
        $getAds = $this->getAdsUser( [ "id_user" => $id_user ] );

        if( $getAds["count"] ){
            foreach ($getAds["all"] as $key => $value) {

               if( !in_array( $value["ads_id_cat"], $groupBy ) ){
                   
                    $buildCategories = $this->buildCategories( $getCategories, $value["ads_id_cat"] );

                    if($buildCategories){

                        foreach ( explode(",", $buildCategories) as $id_cat) {
                            $list_ids[ $id_cat ] = $id_cat;
                        }

                    }

                  $groupBy[ $value["ads_id_cat"] ] = $value["ads_id_cat"];
               }

            }
        }

        if( count($list_ids) ){
            return (new CategoryBoard())->getCategories("where category_board_id IN(".implode(",", $list_ids).")");
        }
        
        return [];

    }

    function outCategories($getCategories = [], $id_parent = 0, $id_hash, $current_id_category = 0){

        $ULang = new ULang();
        
        if (isset($getCategories["category_board_id_parent"][$id_parent])) {
        $tree = '<ul data-id-parent="'.$id_parent.'" '.($id_parent ? 'style="display:none;"' : '').' >';
        $tree .= '<li><a href="'.$this->aliasCategory($id_hash,$getCategories["category_board_id"][$id_parent]["category_board_chain"]).'" >'.$ULang->t('Все категории').'</a> ';

            foreach($getCategories["category_board_id_parent"][$id_parent] as $value){
                
                $activeLink = '';

                if( $current_id_category == $value['category_board_id'] ){
                    $activeLink = 'class="active"';
                }

                if($getCategories["category_board_id_parent"][$value["category_board_id"]]){
                    $nested = 'true';
                    $icon = '<i class="las la-angle-down"></i>';
                }else{ $nested = 'false'; $icon = ''; }

                $tree .= '<li><a data-nested="'.$nested.'" '.$activeLink.' href="'.$this->aliasCategory($id_hash,$value['category_board_chain']).'" >'.$ULang->t($value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ]).$icon.'</a> ';
                $tree .=  $this->outCategories($getCategories,$value['category_board_id'],$id_hash,$current_id_category);
                $tree .= '</li>';
            }

        $tree .= '</ul>';
        }

        return $tree;
    }

    function getAdsUser( $param = [] ){

        $Elastic = new Elastic();
        $Ads = new Ads();

        $param_search = $Elastic->paramAdquery();
        $param_search["query"]["bool"]["filter"][]["term"]["ads_id_user"] = $param["id_user"];
        $param_search["sort"]["ads_sorting"] = [ "order" => "desc" ];
        $param_search["sort"]["ads_id"] = [ "order" => "desc" ];

        if( $param["limit"] ){
            return $Ads->getAll( ["query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_user='{$param["id_user"]}'", "sort" => "order by ads_sorting desc, ads_id desc limit {$param["limit"]}", "param_search" => $param_search, "output" => $param["limit"] ] );
        }else{
            return $Ads->getAll( ["query"=>"ads_status='1' and clients_status IN(0,1) and ads_period_publication > now() and ads_id_user='{$param["id_user"]}'", "sort" => "order by ads_sorting desc, ads_id desc", "navigation" => $param["navigation"], "param_search" => $param_search ] );
        }


    }

    function getUserShop( $id_user = 0 ){
       if($id_user){
           return findOne("uni_clients_shops", "clients_shops_time_validity > now() and clients_shops_status=? and clients_shops_id_user=?", [1,$id_user]);
       }
    }

    function deleteShop($id){

        $getShop = $this->getShop(['shop_id'=>$id]);

        if($getShop){

              unlink( $config["basePath"] . "/" . $config["media"]["other"] . "/" . $getShop["clients_shops_logo"] );

              $getSliders = getAll( "select * from uni_clients_shops_slider where clients_shops_slider_id_shop=?", [$id] );

              if( count($getSliders) ){
                  foreach ($getSliders as $key => $value) {
                      unlink( $config["basePath"] . "/" . $config["media"]["users"] . "/" . $value["clients_shops_slider_image"] );
                  }
              }

              update( "delete from uni_clients_shops where clients_shops_id=?", [ $id ] );
              update( "delete from uni_clients_shops_page where clients_shops_page_id_shop=?", [ $id ] );
              update( "delete from uni_clients_shops_slider where clients_shops_slider_id_shop=?", [ $id ] );
              update( "delete from uni_clients_subscriptions where clients_subscriptions_id_shop=?", [ $id ] );

        }

    }

    function getShop($data=[]){
        if(isset($data['shop_id'])){
            if($data['conditions']){
                return findOne("uni_clients_shops", "clients_shops_id=? and clients_shops_time_validity > now() and clients_shops_status=?", [$data['shop_id'],1]);
            }else{
                return findOne("uni_clients_shops", "clients_shops_id=?", [$data['shop_id']]);
            }
        }elseif(isset($data['user_id'])){
            if($data['conditions']){
                return findOne("uni_clients_shops", "clients_shops_id_user=? and clients_shops_time_validity > now() and clients_shops_status=?", [$data['user_id'],1]);
            }else{
                return findOne("uni_clients_shops", "clients_shops_id_user=?", [$data['user_id']]);
            }            
        }
    }

       
}

?>