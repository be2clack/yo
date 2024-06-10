<?php

class Banners{
    
    function click(){
        $id = isset($_GET["advertising"]) ? (int)$_GET["advertising"] : 0;  
        if($id){    
          if(detectRobots($_SERVER['HTTP_USER_AGENT']) == false) update("UPDATE uni_advertising SET advertising_click=advertising_click+1 WHERE advertising_id=?", array($id));
          $link = findOne("uni_advertising","advertising_id=? AND advertising_visible=?", array($id,1));

          if($link){ 
            
            if( strpos($link->advertising_link_site, "http") !== false ){
                header("Location: ".urldecode($link->advertising_link_site));
                exit;
            }else{
                header("Location: ". _link( trim( urldecode($link->advertising_link_site), "/" ) ) );
                exit;
            }
             
          }

        }    
    }
       
    function category($param = []){

       $CategoryBoard = new CategoryBoard();
       $Blog = new Blog();

       $pos_category_display_board = ["result", "catalog_sidebar", "catalog_top", "catalog_bottom", "ad_view_top", "ad_view_sidebar", "ad_view_bottom"];

       $pos_category_display_blog = ["blog_sidebar", "blog_top", "blog_bottom", "blog_view_sidebar", "blog_view_top", "blog_view_bottom"];

       if($param["ids_cat"]){

       $ids = []; 
         
          if( $param["current_id_cat"] ){ 

            if($param["out_podcat"] == 1){

               foreach(explode(",", $param["ids_cat"]) as $id){

                  if( in_array($param["position_name"], $pos_category_display_board) ){
                    
                    $explode = explode(",", idsBuildJoin($CategoryBoard->idsBuild($id, $param["categories"]), $id) );

                  }elseif( in_array($param["position_name"], $pos_category_display_blog) ){

                    $explode = explode(",", idsBuildJoin($Blog->idsBuild($id, $param["categories"]), $id) );

                  }

                  if(count($explode)){
                      foreach($explode as $value){
                         $ids[$value] = $value; 
                      }
                  }else{
                      $ids[$id] = $id;
                  }

               }

            }else{

               $ids = explode(",", $param["ids_cat"]);

            }

           if( in_array($param["current_id_cat"],$ids) ){
               return true;  
           }else{
               return false;
           }

         }else{
            return false;
         }
         
       }else{
          return true;
       }    
    }
    
    function geo( $param = [] ){

      if($param["geo"]){ 

        if($_SESSION["geo"]){

          $geo = json_decode($param["geo"], true);

          if( in_array( $_SESSION["geo"]["data"]["city_id"],$geo["city"] ) || in_array( $_SESSION["geo"]["data"]["region_id"],$geo["region"] ) || in_array( $_SESSION["geo"]["data"]["country_id"],$geo["country"] ) ){
             return true;
          }

        }

      }else{
         return true;
      } 

    }


    function results( $param = [] ){
        global $config,$route_name,$settings;

        $get = getOne("select * from  uni_advertising where advertising_banner_position=? and advertising_visible=? order by rand()", [$param["position_name"],1]);

        if($get){

            if( $get["advertising_var_out"] == 1 ){
                if( !$_SESSION["profile"]["id"] ){
                    return "";
                }
            }elseif( $get["advertising_var_out"] == 2 ){
                if( $_SESSION["profile"]["id"] ){
                    return "";
                }
            }

            if($param["index"] == $get["advertising_index_out"]){
               
               if( ( in_array( $route_name, explode(",", $get["advertising_pages"]) ) || !$get["advertising_pages"] ) && ( (time() >= strtotime($get["advertising_date_start"]) || strtotime($get["advertising_date_start"]) == "0000-00-00 00:00:00" ) && (time() < strtotime($get["advertising_date_end"]) || $get["advertising_date_end"] == "0000-00-00 00:00:00" ) ) && $this->category( ["ids_cat"=>$get["advertising_ids_cat"], "current_id_cat"=>$param["current_id_cat"], "position_name"=>$param["position_name"], "out_podcat"=>$get["advertising_out_podcat"], "categories"=>$param["categories"]] ) && $this->geo( ["geo"=>$get["advertising_geo"]] ) ){

                     if($get["advertising_type_banner"] == 1){
                          
                          if( $_SESSION['cp_auth'][ $config["private_hash"] ] ){
                          ?>

                              <div class="place-banner" >
                              <a class="place-banner-content" target="_blank" href="<?php echo $config["urlPath"] . "/" . $config["folder_admin"] . "/?route=edit_advertising&id=".$get["advertising_id"]; ?>" >
                                <div class="place-banner-content" >
                                   <img src="<?php echo Exists($config["media"]["banners"], $get["advertising_image"],$config["media"]["no_images"]); ?>" />
                                </div>                    
                              </a>
                              </div>

                          <?php
                          }else{
                          ?>

                              <div class="place-banner" >
                              <a class="place-banner-content" target="_blank" href="<?php echo "?advertising=".$get["advertising_id"]; ?>" >
                                <div class="place-banner-content" >
                                   <img src="<?php echo Exists($config["media"]["banners"], $get["advertising_image"],$config["media"]["no_images"]); ?>" />
                                </div>
                              </a> 
                              </div>  

                          <?php
                          }

                      }elseif($get["advertising_type_banner"] == 2){
                          
                          if( $_SESSION['cp_auth'][ $config["private_hash"] ] ){
                          ?>

                              <div class="place-banner" >
                              <a class="place-banner-content" target="_blank" href="<?php echo $config["urlPath"] . "/" . $config["folder_admin"] . "/?route=edit_advertising&id=".$get["advertising_id"]; ?>" >
                                <div class="place-banner-content" >
                                   <?php echo urldecode($get["advertising_code_script"]); ?>
                                </div>                    
                              </a>
                              </div>

                          <?php
                          }else{
                          ?>

                              <div class="place-banner" >
                              <div class="place-banner-content" >
                                <div class="place-banner-content" >
                                   <?php echo urldecode($get["advertising_code_script"]); ?>
                                </div>
                              </div> 
                              </div>  

                          <?php
                          }

                      }

               }
              

            }  
   
        }

    }

    function out($param = array()){
        global $config,$route_name,$settings;

        $ULang = new ULang();

        $get = getOne("select * from  uni_advertising where advertising_banner_position=? and advertising_visible=? order by rand()", [$param["position_name"],1]);

        if($get){

        if( $get["advertising_var_out"] == 1 ){
            if( !$_SESSION["profile"]["id"] ){
                return "";
            }
        }elseif( $get["advertising_var_out"] == 2 ){
            if( $_SESSION["profile"]["id"] ){
                return "";
            }
        }

        if( ( in_array( $route_name, explode(",", $get["advertising_pages"]) ) || !$get["advertising_pages"] ) && ( (time() >= strtotime($get["advertising_date_start"]) || strtotime($get["advertising_date_start"]) == "0000-00-00 00:00:00" ) && (time() < strtotime($get["advertising_date_end"]) || $get["advertising_date_end"] == "0000-00-00 00:00:00" ) ) && $this->category( ["ids_cat"=>$get["advertising_ids_cat"], "current_id_cat"=>$param["current_id_cat"], "position_name"=>$param["position_name"], "out_podcat"=>$get["advertising_out_podcat"], "categories"=>$param["categories"]] ) && $this->geo( ["geo"=>$get["advertising_geo"]] ) ){

        if($param["position_name"] == "stretching"){ 


            if($get["advertising_type_banner"] == 1){

              if( $_SESSION['cp_auth'][ $config["private_hash"] ] ){
              ?>

                  <div class="place-banner" >
                  <a class="place-banner-content stretching-banner" target="_blank" href="<?php echo $config["urlPath"] . "/" . $config["folder_admin"] . "/?route=edit_advertising&id=".$get["advertising_id"]; ?>" >

                    <img src="<?php echo Exists($config["media"]["banners"], $get["advertising_image"],$config["media"]["no_images"]); ?>" />

                  </a>
                  </div>

              <?php
              }else{
              ?>

                  <div class="place-banner" >
                  <a class="place-banner-content stretching-banner" target="_blank" href="<?php echo "?advertising=".$get["advertising_id"]; ?>" >

                    <img src="<?php echo Exists($config["media"]["banners"], $get["advertising_image"],$config["media"]["no_images"]); ?>" />

                  </a>
                  </div>

              <?php
              }

            }elseif($get["advertising_type_banner"] == 2){

              if( $_SESSION['cp_auth'][ $config["private_hash"] ] ){
              ?>

                  <div class="place-banner" >
                  <a class="place-banner-content stretching-banner" target="_blank" href="<?php echo $config["urlPath"] . "/" . $config["folder_admin"] . "/?route=edit_advertising&id=".$get["advertising_id"]; ?>" >
                       <?php echo urldecode($get["advertising_code_script"]); ?>                  
                  </a>
                  </div>

              <?php
              }else{
              ?>

                  <div class="place-banner" >
                  <div class="place-banner-content stretching-banner" >
                       <?php echo urldecode($get["advertising_code_script"]); ?>                   
                  </div>
                  </div>

              <?php                
              }

            }

        }else{ 
             
             if($get["advertising_type_banner"] == 1){

                if( $_SESSION['cp_auth'][ $config["private_hash"] ] ){
                  ?>

                    <div class="place-banner mt15" >
                    <a class="place-banner-content" target="_blank" href="<?php echo $config["urlPath"] . "/" . $config["folder_admin"] . "/?route=edit_advertising&id=".$get["advertising_id"]; ?>" >
                         <img src="<?php echo Exists($config["media"]["banners"], $get["advertising_image"],$config["media"]["no_images"]); ?>" />
                    </a> 
                    </div>  

                  <?php
                }else{
                  ?>

                    <div class="place-banner mt15" >
                    <a class="place-banner-content" target="_blank" href="<?php echo "?advertising=".$get["advertising_id"]; ?>" >
                         <img src="<?php echo Exists($config["media"]["banners"], $get["advertising_image"],$config["media"]["no_images"]); ?>" />
                    </a> 
                    </div>  

                  <?php                  
                }

              }elseif($get["advertising_type_banner"] == 2){

                if( $_SESSION['cp_auth'][ $config["private_hash"] ] ){
                  ?>

                    <div class="place-banner mt15" >
                    <div class="place-banner-content"  >
                         <?php echo urldecode($get["advertising_code_script"]); ?>
                    </div> 
                    </div>  

                  <?php
                }else{
                  ?>

                    <div class="place-banner mt15" >
                    <div class="place-banner-content" >
                         <?php echo urldecode($get["advertising_code_script"]); ?>
                    </div> 
                    </div>  

                  <?php
                }

              } 

        }

        }

            
        }else{

          if( $_SESSION['cp_auth'][ $config["private_hash"] ] && in_array( $route_name, explode(",", $settings["advertising_pages"]) ) && $settings['banner_markup'] ){
          ?>

            <a class="place-banner mt15" target="_blank" href="<?php echo $config["urlPath"] . "/" . $config["folder_admin"] . "/?route=add_advertising&position=".$param["position_name"]."&page=".$route_name; ?>" >
                <div class="plug-banner" >
                     <h5> <strong><?php echo $ULang->t("Место для вашего баннера"); ?></strong> </h5>
                     <p><?php echo $ULang->t("Нажмите на меня"); ?></p>
                </div>
            </a>

          <?php
          }

        }


    }

    function positions(){
        return array(
          "stretching" => array("title" => "Растяжка", "name" => "Баннер под шапкой"),
          "result" => array("title" => "Каталог объявлений", "name" => "Реклама в результате выдачи"),
          "catalog_sidebar" => array("title" => "Каталог объявлений", "name" => "Боковая панель"),
          "catalog_top" => array("title" => "Каталог объявлений", "name" => "Верхняя позиция"),
          "catalog_bottom" => array("title" => "Каталог объявлений", "name" => "Нижняя позиция"),
          "ad_view_top" => array("title" => "Карточка объявления", "name" => "Верхняя позиция"),
          "ad_view_sidebar" => array("title" => "Карточка объявления", "name" => "Боковая панель"),
          "ad_view_bottom" => array("title" => "Карточка объявления", "name" => "Нижняя позиция"),
          "index_center" => array("title" => "Главная страница", "name" => "Средняя позиция"),
          "index_top" => array("title" => "Главная страница", "name" => "Верхняя позиция"),
          "index_bottom" => array("title" => "Главная страница", "name" => "Нижняя позиция"),
          "index_sidebar" => array("title" => "Главная страница", "name" => "Боковая панель"),
          "blog_top" => array("title" => "Блог", "name" => "Верхняя позиция"),
          "blog_bottom" => array("title" => "Блог", "name" => "Нижняя позиция"),
          "blog_sidebar" => array("title" => "Блог", "name" => "Боковая панель"),  
          "blog_view_sidebar" => array("title" => "Карточка статьи", "name" => "Боковая панель"),
          "blog_view_top" => array("title" => "Карточка статьи", "name" => "Верхняя позиция"),
          "blog_view_bottom" => array("title" => "Карточка статьи", "name" => "Нижняя позиция"),    
        );
    }

   function bannersPositions($id_key=""){
    $option = array();

      $banners_positions = $this->positions();

      foreach($banners_positions AS $key => $array){
          if($id_key){ if($id_key == $key){ $selected = 'selected=""'; }else{ $selected = ""; } }
          $option[$array["title"]][] .= '<option data-title="'.$array["title"].' - '.$array["name"].'" value="'.$key.'" '.$selected.' >'.$array["name"].'</option>';
      }           

      if(count($option)>0){
          foreach($option as $group_name=>$option_val){
              $return .= '<optgroup label="'.$group_name.'">'.implode("",$option_val).'</optgroup>';
          }
      }
    return $return;        
   }




        
}


?>