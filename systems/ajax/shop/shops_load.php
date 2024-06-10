<?php

$page = (int)$_POST["page"] ? (int)$_POST["page"] : 1;
$id_c = (int)$_POST["id_c"];

if($id_c){
   $query = " and ( clients_shops_id_theme_category='{$id_c}' or clients_shops_id_theme_category='0' )";
}

$count = (int)getOne("select count(*) as total from uni_clients_shops INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_clients_shops`.clients_shops_id_user where (clients_shops_time_validity > now() or clients_shops_time_validity IS NULL) and clients_shops_status=1 and clients_status IN(0,1) {$query}")["total"];
$results = getAll( "select * from uni_clients_shops INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_clients_shops`.clients_shops_id_user where (clients_shops_time_validity > now() or clients_shops_time_validity IS NULL) and clients_shops_status=1 and clients_status IN(0,1) {$query} order by clients_shops_id desc" . navigation_offset( array( "count"=>$count, "output"=>$settings["shops_out_content"], "page"=>$page ) ) );

if($results){

  if($page <= getCountPage($count,$settings["shops_out_content"])){

      foreach ($results as $key => $value) {
         ob_start();
         include $config["template_path"] . "/include/shop_list.php";
         $content .= ob_get_clean();
      }

  }

  if($page + 1 <= getCountPage($count,$settings["shops_out_content"])){
    
    $found = true;

    if( $settings["type_content_loading"] == 1 ){
        $content .= '
          
          <div class="col-lg-12" >
          <div class="ajax-load-button action-shops-load text-center mt20" >
              <button class="btn-custom btn-color-blue width250 button-inline" > <span class="action-load-span-start" > <span class="spinner-border spinner-border-sm button-ajax-loader" role="status" aria-hidden="true"></span> '.$ULang->t("Загрузка").'</span> <span class="action-load-span-end" >'.$ULang->t("Показать еще").' <i class="la la-angle-down"></i></span> </button>
          </div>
          </div>

        ';
    }else{
        $content .= '
          
          <div class="col-lg-12" >
          <div class="text-center mt20 preload-scroll" >

              <div class="spinner-grow preload-spinner" role="status">
                <span class="sr-only"></span>
              </div>
              
          </div>
          </div>

        ';         
    }


  }else{

     $found = false;

  }


}else{

   $found = false;

   $content = '
       <div class="col-lg-12" >
       <div class="catalog-no-results" >
          <div class="catalog-no-results-box" >
              <img src="'.$settings["path_tpl_image"].'/person-shrugging_1f937.png" />
              <h5>'.$ULang->t("Ничего не найдено").'</h5>
          </div>
       </div>
       </div>
   ';

}


echo json_encode( array("content"=>$content, "found"=>$found) );

?>