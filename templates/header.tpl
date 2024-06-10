<header class="header-wow d-none d-lg-block" >
   
   <?php if($route_name != 'shop'){ ?>
   <div class="header-wow-top" >
        <div class="container" >
           <div class="row" >
               <div class="col-lg-2 col-2" >
                   
                   <?php if($settings["visible_lang_site"]){ ?>
                    <div class="header-wow-top-lang" >
                    <div class="toolbar-dropdown dropdown-click">
                      <span class="header-wow-top-lang-name" ><?php echo $_SESSION["langSite"]["name"]; ?></span>
                      <div class="toolbar-dropdown-box width-180 left-0 no-padding toolbar-dropdown-js">

                           <div class="dropdown-box-list-link dropdown-lang-list">

                              <?php
                                $getLang = getAll("select * from uni_languages where status=?", [1]);
                                if(count($getLang)){
                                   foreach ($getLang as $key => $value) {
                                      ?>
                                      <a href="<?php echo trim($config["urlPath"] . "/" . $value["iso"] . "/" . REQUEST_URI, "/"); ?>"> <img src="<?php echo Exists( $config["media"]["other"],$value["image"],$config["media"]["no_image"] ); ?>"> <span><?php echo $value["name"]; ?></span> </a>
                                      <?php
                                   }
                                }
                              ?>

                           </div>

                      </div>
                    </div>
                    </div>
                    <?php } ?>
                    
               </div>
               <div class="col-lg-10 col-10 text-right" >

                  <div class="header-wow-top-list <?php if( $_SESSION['cp_auth'][ $config["private_hash"] ] && $_SESSION["cp_control_tpl"] ){ echo 'header-wow-top-list-admin'; } ?>" >

                    <?php if( $_SESSION['cp_auth'][ $config["private_hash"] ] && $_SESSION["cp_control_tpl"] ){ echo '<span class="header-wow-top-list-admin-edit open-modal" data-id-modal="modal-edit-site-menu"  >'.$ULang->t("Изменить").'</span>'; } ?>

                     <?php
                        if( count($settings["frontend_menu"]) ){
                            foreach ($settings["frontend_menu"] as $key => $value) {
                               $link = strpos($value["link"], "http") !== false ? $value["link"] : _link($value["link"]);
                               $target = strpos($value["link"], "http") !== false ? 'target="_blank"' : '';
                               ?>
                               <a href="<?php echo $link; ?>" <?php echo $target; ?> ><?php echo $ULang->t($value["name"]); ?></a>
                               <?php
                            }
                        }
                     ?>

                  </div>                  
                   
               </div>
           </div>
        </div>
   </div>
   <?php } ?>
   <div class="header-wow-sticky" >
       
       <div class="header-wow-sticky-container" >

       <?php if($route_name == 'shop'){ ?>

       <div class="container" >
         
           <div class="row" >

               <div class="col-lg-2 col-md-2 col-sm-2" >
                   
                  <a class="h-logo" href="<?php echo _link(); ?>" title="<?php echo $ULang->t($settings["title"]); ?>" >
                      <img src="<?php echo $settings["logotip"]; ?>" data-inv="<?php echo $settings["logo_color_inversion"]; ?>" alt="<?php echo $ULang->t($settings["title"]); ?>">
                  </a>

               </div>

               <div class="header-flex-box" >

               <div class="header-flex-box-1" >

                  <div class="header-button-menu-catalog btn-color-blue" >
                      <span class="header-button-menu-catalog-icon-1" ><svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ui-e2"><path d="M5 6a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm4-1a1 1 0 000 2h12a1 1 0 100-2H9zm0 6a1 1 0 100 2h12a1 1 0 100-2H9zm-1 7a1 1 0 011-1h6a1 1 0 110 2H9a1 1 0 01-1-1zm-4.5-4.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM5 18a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path></svg></span>
                      <span class="header-button-menu-catalog-icon-2" ><svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ui-e2"><path d="M12 10.587l6.293-6.294a1 1 0 111.414 1.414l-6.293 6.295 6.293 6.294a1 1 0 11-1.414 1.414L12 13.416 5.707 19.71a1 1 0 01-1.414-1.414l6.293-6.294-6.293-6.295a1 1 0 111.414-1.414L12 10.587z" fill="currentColor"></path></svg></span>
                      <span><?php echo $ULang->t('Каталог магазина'); ?></span>
                  </div>                    
                   
               </div>
               
               <div class="header-flex-box-2 parents-ajax-live-search" >

                     <form class="form-ajax-live-search" method="get" >

                         <div class="row no-gutters" >
                           
                           <div class="col-lg-12" >
                            
                            <div class="main-search-shop" >
                              <div>
                                <input type="text" name="search" class="ajax-live-search" autocomplete="off" placeholder="<?php if($data["tariff"]['services']['search_shop']){ echo $ULang->t("Поиск по объявлениям магазина"); }else{ echo $ULang->t("Поиск по объявлениям"); } ?>" value="<?php echo clear($_GET["search"]); ?>" >
                                <div class="main-search-results main-search-results-shop results-ajax-live-search" ></div>
                                <div class="main-search-shop-action" >
                                  <button class="btn"><i class="las la-search"></i></button>
                                </div>
                              </div>
                            </div>

                           </div>

                         </div>

                         <input type="hidden" name="id_s" value="<?php echo $data["shop"]["clients_shops_id"]; ?>" >

                     </form>

               </div>

               <div class="header-flex-box-3" >
                    
                    <?php if( !$_SESSION['profile']['id'] ){ ?>
                    <div class="toolbar-link" >
                       <a href="<?php echo _link('auth'); ?>" class="header-wow-sticky-auth btn-color-green"><?php echo $ULang->t("Войти"); ?></a>
                    </div>
                    <?php }else{ ?>
                    <div class="toolbar-link toolbar-link-profile" >
                        
                        <?php echo $Profile->headerUserMenu(false); ?>

                    </div>                        
                    <?php } ?>

               </div>

               </div>

           </div>

       </div>

       <div class="header-big-menu" >
          
          <div class="container" >
          <div class="row no-gutters" >
             <div class="col-lg-4" >
                 <div class="header-big-category-menu-left" >

                    <a class="header-big-category-menu-all-link" href="<?php echo $Shop->linkShop($data["shop"]["clients_shops_id_hash"]); ?>" >
                    <div class="category-menu-left-image" >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" height="24" viewBox="0 0 24 24" width="24"><path clip-rule="evenodd" d="m5.49718 3.00617 4.50169-.00507c.55343 0 1.00113.44771 1.00113 1v5.99887c0 .55233-.4477 1.00003-1 1.00003h-6c-.55228 0-1-.4477-1-1.00003v-4.4938c0-1.37961 1.11757-2.49844 2.49718-2.5zm-1.49831 10.00173c-.55184.0006-.99887.4481-.99887 1v4.4921c0 1.3807 1.11929 2.5 2.5 2.5h4.5c.5523 0 1-.4477 1-1v-5.9989c0-.5523-.4477-1-1.00113-1zm10.00003 0c-.5519.0006-.9989.4481-.9989 1v5.9921c0 .5523.4477 1 1 1h4.5c1.3807 0 2.5-1.1193 2.5-2.5v-4.4989c0-.5523-.4477-1-1.0011-1zm0-10.00004c-.5519.00063-.9989.44816-.9989 1v5.99211c0 .55233.4477 1.00003 1 1.00003h6c.5523 0 1-.4477 1-1.00003v-4.49718c0-1.38071-1.1193-2.5-2.5028-2.5zm-8.9989 15.49214c0 .2761.22386.5.5.5h3.5v-3.9978l-4 .0045zm13.5.5h-3.5v-3.9933l4-.0045v3.4978c0 .2761-.2239.5-.5.5zm-9.5-10.00003v-3.99774l-3.50056.00394c-.27593.00031-.49944.22408-.49944.5v3.4938zm10 0h-4v-3.99323l3.5-.00395c.2761 0 .5.22386.5.5z" fill="currentColor" fill-rule="evenodd"></path></svg>
                    </div>
                    <div class="category-menu-left-name" ><?php echo $ULang->t("Все категории"); ?></div>
                    <div class="clr" ></div>
                    </a>

                 <?php

                    if($data["category"]["category_board_id_parent"][0]){
                          foreach ($data["category"]["category_board_id_parent"][0] as $value) {

                                if(!$value['category_board_id_parent']){
                                ?>
                                 <div data-id="<?php echo $value["category_board_id"]; ?>" >

                                    <a href="<?php echo $Shop->aliasCategory($data["shop"]["clients_shops_id_hash"],$value["category_board_chain"]); ?>" <?php echo $active; ?> >
                                    <?php if( $value["category_board_image"] ){ ?>
                                    <div class="category-menu-left-image" >
                                      <img src="<?php echo Exists($config["media"]["other"],$value["category_board_image"],$config["media"]["no_image"]); ?>" >
                                    </div>
                                    <?php } ?>
                                    <div class="category-menu-left-name" ><?php echo $ULang->t($value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ]); ?><span class="header-big-category-count" ><?php echo $CategoryBoard->getCountAd($value["category_board_id"],$data["shop"]["clients_shops_id_user"]); ?></span></div>
                                    <div class="clr" ></div>
                                    </a>

                                 </div>
                                <?php
                                }

                          }
                    }

                 ?>
                 </div>
             </div>
             <div class="col-lg-8" >
                 <div class="header-big-category-menu-right" >

                 <?php

                    $count_key = 0;

                    if($data["category"]["category_board_id_parent"][0]){
                          foreach ($data["category"]["category_board_id_parent"][0] as $key => $value) {

                               if($data["category"]["category_board_id_parent"][$value["category_board_id"]]){
                                    
                                    $show = '';

                                    if( $count_key == 0 ){
                                        $show = ' style="display: block;" ';
                                    }

                                    $count_key++;

                                    echo '
                                      <div class="header-big-subcategory-menu-list" '.$show.' data-id-parent="'.$value["category_board_id"].'" >
                                      <h4>'.$Seo->replace($ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] )).'</h4>
                                      <div class="row no-gutters" >
                                    ';

                                    foreach ($data["category"]["category_board_id_parent"][$value["category_board_id"]] as $subvalue1) {

                                        echo '
                                           <div class="col-lg-6" >
                                           <div data-id="'.$subvalue1["category_board_id"].'" >
                                             <a href="'.$Shop->aliasCategory($data["shop"]["clients_shops_id_hash"],$subvalue1["category_board_chain"]).'">'.$ULang->t($subvalue1["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ]).'<span class="header-big-category-count" >'.$CategoryBoard->getCountAd($subvalue1["category_board_id"],$data["shop"]["clients_shops_id_user"]).'</span></a>
                                           </div>
                                           </div>
                                        ';

                                    }

                                    echo '
                                      </div>
                                      </div>
                                    ';

                               }

                          }
                    }

                 ?>

                 </div>
             </div>
          </div>
          </div>
            
       </div>

       <?php }else{ ?>

       <div class="container" >
         
           <div class="row" >

               <div class="col-lg-1 col-md-1 col-sm-1" >
                   
                  <a class="h-logo" href="<?php echo _link(); ?>" title="<?php echo $ULang->t($settings["title"]); ?>" >
                      <img src="<?php echo $settings["logotip"]; ?>" data-inv="<?php echo $settings["logo_color_inversion"]; ?>" alt="<?php echo $ULang->t($settings["title"]); ?>">
                  </a>

               </div>

               <div class="header-flex-box" >

               <div class="header-flex-box-1" >

                  <div class="header-button-menu-catalog btn-color-blue" >
                      <span class="header-button-menu-catalog-icon-1" ><svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ui-e2"><path d="M5 6a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm4-1a1 1 0 000 2h12a1 1 0 100-2H9zm0 6a1 1 0 100 2h12a1 1 0 100-2H9zm-1 7a1 1 0 011-1h6a1 1 0 110 2H9a1 1 0 01-1-1zm-4.5-4.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM5 18a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path></svg></span>
                      <span class="header-button-menu-catalog-icon-2" ><svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ui-e2"><path d="M12 10.587l6.293-6.294a1 1 0 111.414 1.414l-6.293 6.295 6.293 6.294a1 1 0 11-1.414 1.414L12 13.416 5.707 19.71a1 1 0 01-1.414-1.414l6.293-6.294-6.293-6.295a1 1 0 111.414-1.414L12 10.587z" fill="currentColor"></path></svg></span>
                      <span><?php echo $ULang->t('Каталог'); ?></span>
                  </div>                    
                   
               </div>
               
               <div class="header-flex-box-2" >

                  <div class="container-search-goods parents-ajax-live-search" >
                      
                      <div class="header-wow-sticky-search" >

                          <?php if($settings["main_type_products"] == 'physical'){ ?>

                          <form class="form-ajax-live-search" method="get" action="<?php if($route_name != 'map'){ echo isset($_SESSION["geo"]["alias"]) ? _link($_SESSION["geo"]["alias"]) : _link($settings["country_default"]); }else{ echo isset($_SESSION["geo"]["alias"]) ? _link('map/'.$_SESSION["geo"]["alias"]) : _link('map/'.$settings["country_default"]); } ?>" >

                          <?php }else{ ?>

                          <form class="form-ajax-live-search" method="get" action="<?php echo _link('catalog'); ?>" >

                          <?php } ?>

                          <button class="header-wow-sticky-search-icon" ><i class="las la-search"></i></button>
                          
                          <input type="text" name="search" class="ajax-live-search" autocomplete="off" placeholder="<?php echo $ULang->t("Поиск"); ?>" value="<?php echo clear($_GET["search"]); ?>" >

                          </form>

                          <?php if($settings["main_type_products"] == 'physical'){ ?>

                          <div class="sticky-search-control-geo" >

                              <?php
                              if(!$data["city_areas"] && isset($_SESSION["geo"]["data"]["city_id"])){
                                 $data["city_areas"] = getAll("select * from uni_city_area where city_area_id_city=? order by city_area_name asc", [ intval($_SESSION["geo"]["data"]["city_id"]) ]);
                              }
                              if(!$data["city_metro"] && isset($_SESSION["geo"]["data"]["city_id"])){
                                 $data["city_metro"] = getAll("select * from uni_metro where city_id=? and parent_id!=0 Order by name ASC", [ intval($_SESSION["geo"]["data"]["city_id"]) ]);
                              }
                              ?>

                              <?php if(!$settings["city_id"]){ ?>
                              <div class="sticky-search-control-geo-name sticky-search-control-geo-change" ><i class="las la-map-marker-alt"></i> <?php if(isset($_SESSION["geo"]["data"])){ echo $ULang->t($Geo->change()["name"], [ "table"=>"geo", "field"=>"geo_name" ] ); }else{ echo $ULang->t('Выберите город'); } ?></div>
                              
                              <?php if($data["city_areas"] || $data["city_metro"]){ ?>
                              <div class="input-separator" ></div>
                              <?php } ?>

                              <?php } ?>
                              <div class="sticky-search-control-geo-area-change" >
                                  <?php 
                                      if($data["city_areas"] && $data["city_metro"]){
                                             echo $ULang->t("Метро / Районы");
                                      }elseif($data["city_areas"]){
                                             echo $ULang->t("Районы");
                                      }elseif($data["city_metro"]){
                                             echo $ULang->t("Метро");
                                      } 

                                      if((new Ads)->getCountChangeOptionsCity( $data )){ echo '<span class="city-option-count" >'.(new Ads)->getCountChangeOptionsCity( $data ).'</span>'; }
                                  ?>                                    
                              </div>
                          </div>

                          <div class="sticky-search-control-geo-area" >
                              <div class="sticky-search-control-geo-area-cancel" ><?php echo $ULang->t("Отмена"); ?></div>
                          </div>

                          <div class="sticky-search-geo-area-list" >

                              <div class="modal-geo-options-tab" >
                                  <?php if($data["city_areas"] && $data["city_metro"]){ ?>
                                          <div data-id="1" class="active" > <?php echo $ULang->t("Метро"); ?> </div>
                                          <div data-id="2" > <?php echo $ULang->t("Районы"); ?> </div>
                                  <?php }elseif(isset($data["city_areas"])){ ?> 
                                          <div data-id="2" class="active" > <?php echo $ULang->t("Районы"); ?> </div>
                                  <?php }elseif(isset($data["city_metro"])){ ?>
                                          <div data-id="1" class="active" > <?php echo $ULang->t("Метро"); ?> </div>
                                  <?php } ?>        
                              </div>
                              
                              <form class="modal-geo-options-form" >
                              <div class="modal-geo-options-tab-content" >
                                  <div data-tab="1" <?php if($data["city_metro"]){ echo 'class="active"'; } ?> >
                                   <div class="geo-options-metro" >
                                   <div class="container" >
                                       <div class="row" >
                                       <?php
                                         echo $Geo->outOptionsMetro( $data );
                                       ?>
                                       </div>
                                   </div>
                                   </div>
                                  </div>
                                  <div data-tab="2" <?php if(!$data["city_metro"] && $data["city_areas"]){ echo 'class="active"'; } ?> >
                                     <div class="geo-options-areas" >
                                     <div class="container" >
                                         <div class="row" >
                                         <?php
                                         echo $Geo->outOptionsArea( $data );
                                         ?>
                                         </div>
                                     </div>
                                     </div>
                                  </div>
                              </div>

                              </form>

                              <div class="row mt30" >
                                 <div class="col-lg-3" > <button class="btn-custom btn-color-blue width100 submit-geo-options-form" ><?php echo $ULang->t("Применить"); ?></button> </div>
                              </div>
                           
                          </div>

                          <?php } ?>

                          <div class="results-ajax-live-search main-search-results" ></div>

                      </div>
                      
                  </div>

                  <div class="container-search-geo" >
                      <div class="header-wow-sticky-search" >

                          <span class="header-wow-sticky-search-icon" ><i class="las la-map-marker-alt"></i></span>
                          
                          <input type="text" name="search" class="sticky-search-geo-input" autocomplete="off" placeholder="<?php echo $ULang->t("Поиск по городам"); ?>" >

                          <div class="sticky-search-control-geo" >
                              <div class="sticky-search-control-geo-cancel" ><?php echo $ULang->t("Отмена"); ?></div>
                          </div>

                          <div class="sticky-search-geo-list" >
                               <?php 
                                echo $Geo->cityDefault($country_alias,30);
                               ?>                              
                          </div>
                          
                          <div class="sticky-search-geo-results" ></div>

                      </div>
                  </div>

               </div>

               <div class="header-flex-box-3" >
                    
                    <?php if(isset($_SESSION["profile"]["id"])){ ?>
                    <div class="toolbar-link toolbar-link-favorites mr8" >

                        <a class="toolbar-link-title-icon" href="<?php echo _link( "user/" . $_SESSION["profile"]["data"]["clients_id_hash"] . "/favorites" ); ?>" >
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.026 4.133C4.398 4.578 3 6.147 3 8.537c0 3.51 2.228 6.371 4.648 8.432A23.633 23.633 0 0012 19.885a23.63 23.63 0 004.352-2.916C18.772 14.909 21 12.046 21 8.537c0-2.39-1.398-3.959-3.026-4.404-1.594-.436-3.657.148-5.11 2.642a1 1 0 01-1.728 0C9.683 4.281 7.62 3.697 6.026 4.133zM12 21l-.416.91-.003-.002-.008-.004-.027-.012a15.504 15.504 0 01-.433-.214 25.638 25.638 0 01-4.762-3.187C3.773 16.297 1 12.927 1 8.538 1 5.297 2.952 2.9 5.499 2.204c2.208-.604 4.677.114 6.501 2.32 1.824-2.206 4.293-2.924 6.501-2.32C21.048 2.9 23 5.297 23 8.537c0 4.39-2.772 7.758-5.352 9.955a25.642 25.642 0 01-4.762 3.186 15.504 15.504 0 01-.432.214l-.027.012-.008.004-.003.001L12 21zm0 0l.416.91c-.264.12-.568.12-.832 0L12 21z" fill="currentColor"></path></svg>
                            <span class="toolbar-link-title-icon-name" ><?php ///echo $ULang->t("Избранное"); ?></span>
                        </a>

                    </div>

                    <div class="toolbar-link mr8" >
                        
                        <span class="toolbar-link-title-icon open-modal" data-id-modal="modal-chat-user" >
                            <div class="toolbar-link-title-icon-box" >
                                <span class="label-count-message chat-message-counter BadgePulse" style="display: none;" ></span>    
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.324 4.388C5.104 2.758 7.918 2 12 2s6.897.757 8.676 2.388C22.483 6.045 23 8.394 23 11c0 2.38-.43 4.548-1.89 6.174-1.463 1.628-3.782 2.515-7.098 2.757-.929.068-1.267.096-1.473.143a2.527 2.527 0 00-.583.2c-.194.09-.407.228-1.014.633l-4.387 2.925A1 1 0 015 23v-4.254c-1.407-.697-2.402-1.683-3.045-2.934C1.237 14.415 1 12.769 1 11c0-2.606.517-4.955 2.324-6.612zm1.352 1.474C3.483 6.955 3 8.606 3 11c0 1.619.222 2.902.734 3.898.495.962 1.3 1.734 2.64 2.273a1 1 0 01.626.927v3.034l2.903-1.936c.51-.34.86-.573 1.213-.737.347-.16.6-.247.972-.333.38-.088.89-.125 1.66-.181l.118-.009c3.075-.224 4.787-1.02 5.756-2.099C20.595 14.754 21 13.189 21 11c0-2.394-.483-4.045-1.676-5.138C18.104 4.742 15.918 4 12 4c-3.918 0-6.103.743-7.324 1.862z" fill="currentColor"></path></svg>
                            </div>
                            <span class="toolbar-link-title-icon-name" ><?php ///echo $ULang->t("Сообщения"); ?></span>
                        </span>

                    </div>

                    <?php } ?>

                    <?php if( $settings["marketplace_status"] ){ ?>
                    <div class="toolbar-link mr8" >

                        <a <?php if($settings["marketplace_view_cart"] == 'modal'){ echo 'class="toolbar-link-title-icon open-modal" data-id-modal="modal-cart"'; }elseif($settings["marketplace_view_cart"] == 'sidebar'){ echo 'class="toolbar-link-title-icon sidebar-cart-open"'; }elseif($settings["marketplace_view_cart"] == 'page'){ echo 'class="toolbar-link-title-icon" href="'._link('cart').'"'; } ?>  >
                            <div class="toolbar-link-title-icon-box" >
                                <span class="label-count-cart cart-item-counter" style="display: none;" ></span>                            
                                <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg" ><path fill-rule="evenodd" clip-rule="evenodd" d="M10 2a4 4 0 00-4 4v2H.867L2.18 18.496A4 4 0 006.15 22h11.703a4 4 0 003.969-3.504L23.133 8H18V6a4 4 0 00-4-4h-4zm3 7a1 1 0 00-1-1H8V6a2 2 0 012-2h4a2 2 0 012 2v4h4.867l-1.03 8.248A2 2 0 0117.851 20H6.148a2 2 0 01-1.984-1.752L3.133 10H12a1 1 0 001-1z" fill="currentColor"></path></svg>
                            </div>
                            <span class="toolbar-link-title-icon-name" ><?php ///echo $ULang->t("Корзина"); ?></span>
                        </a>

                    </div>
                    <?php } ?>

                    <?php if( !$_SESSION['profile']['id'] ){ ?>
                    <div class="toolbar-link mr8" >
                       <a href="<?php echo _link('auth'); ?>" class="header-wow-sticky-auth btn-color-green"><?php echo $ULang->t("Войти"); ?></a>
                    </div>
                    <?php }else{ ?>
                    <div class="toolbar-link toolbar-link-profile mr12" >
                        
                        <?php echo $Profile->headerUserMenu(false); ?>

                    </div>                        
                    <?php } ?>

                    <div class="toolbar-link" >
                    <a href="<?php echo _link("ad/create"); ?>" class="header-wow-sticky-add" > <?php echo $ULang->t("Разместить бесплатно"); ?> </a>
                    </div>

               </div>

               </div>

           </div>

       </div>

       <div class="header-big-menu catalog-header-big-menu" ></div>

       <?php } ?>

       </div>

   </div>

</header>

<header class="header-wow-mobile d-block d-lg-none <?php if( $route_name == "catalog" || $route_name == "index" ){ echo 'height135'; }else{ echo 'height55'; } ?>" <?php if($route_name == "map"){ echo 'style="margin-bottom:0px;"'; } ?> >
   
<div class="header-wow-mobile-sticky" >
   <div class="header-wow-mobile-top parents-ajax-live-search" >

      <div class="container" >
         <div class="header-flex-box" >   
             <div class="header-flex-box-mobile-1" >
                  
                  <?php if( $route_name == "catalog" ){ ?>
                  <div class="toolbar-link" >
                    <span class="toolbar-link-title-icon" >
                        <a href="#" class="toolbar-link-title-icon-box action-user-route-back" >
                            <i class="las la-arrow-left"></i>
                        </a>
                    </span>
                  </div>
                  <?php }elseif( $route_name == "ad_view" ){ ?>
                      <div class="toolbar-link" >
                        <span class="toolbar-link-title-icon" >
                            <a href="#" class="toolbar-link-title-icon-box action-user-route-back" >
                                <i class="las la-arrow-left"></i>
                            </a>
                        </span>
                      </div>                    
                    <?php
                  }else{ ?>
                  <div class="toolbar-link" >
                    <span class="toolbar-link-title-icon" >
                        <div class="toolbar-link-title-icon-box mobile-fixed-menu_all-menu-open" >
                            <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg" ><path fill-rule="evenodd" clip-rule="evenodd" d="M5 6a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm4-1a1 1 0 000 2h12a1 1 0 100-2H9zm0 6a1 1 0 100 2h12a1 1 0 100-2H9zm-1 7a1 1 0 011-1h6a1 1 0 110 2H9a1 1 0 01-1-1zm-4.5-4.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM5 18a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" fill="currentColor"></path></svg>
                        </div>
                    </span>
                  </div>                    
                  <?php } ?>
                      
             </div>
             <div class="header-flex-box-mobile-2" >

                  <?php if($route_name == "catalog" || $route_name == "index" || $route_name == "shops" || $route_name == "shop" || $route_name == "map"){ ?>
                  <div class="container-search-goods-flex" >
                      <div class="container-search-goods" >

                          <?php if($settings["main_type_products"] == 'physical'){ ?>
                            <form class="form-ajax-live-search" method="get" <?php if($route_name != "shops" && $route_name != "shop"){ ?> action="<?php if($route_name != 'map'){ echo $_SESSION["geo"]["alias"] ? _link($_SESSION["geo"]["alias"]) : _link($settings["country_default"]); }else{ echo $_SESSION["geo"]["alias"] ? _link('map/'.$_SESSION["geo"]["alias"]) : _link('map/'.$settings["country_default"]); } ?>" <?php } ?> >
                          <?php }else{ ?>
                            <form class="form-ajax-live-search" method="get" <?php if($route_name != "shops" && $route_name != "shop"){ ?> action="<?php echo _link('catalog'); ?>" <?php } ?> >
                          <?php } ?>                          
                              <div class="header-wow-mobile-sticky-search" >

                                  <button class="header-wow-mobile-sticky-search-icon" ><i class="las la-search"></i></button>
                                  
                                  <input type="text" name="search" class="ajax-live-search" autocomplete="off" placeholder="<?php if($route_name == "shops"){ echo $ULang->t("Поиск по магазинам"); }elseif($route_name == "shop" && $data["tariff"]['services']['search_shop']){ echo $ULang->t("Поиск по объявлениям магазина"); }elseif($route_name == "blog"){ echo $ULang->t("Поиск по блогу"); }else{ echo $ULang->t("Поиск по объявлениям"); } ?>" value="<?php echo clear($_GET["search"]); ?>" >

                              </div>
                              <?php if($route_name == "shop"){ ?>
                              <input type="hidden" name="id_s" value="<?php echo $data["shop"]["clients_shops_id"]; ?>" >
                              <?php } ?>
                          </form>
                      </div>
                  </div>
                  <?php } ?>

             </div>
             <div class="header-flex-box-mobile-3" >

                <?php if($settings["marketplace_status"]){ ?>
                    <div class="toolbar-link mr10" >

                        <a <?php if($settings["marketplace_view_cart"] == 'modal'){ echo 'class="toolbar-link-title-icon open-modal" data-id-modal="modal-cart"'; }elseif($settings["marketplace_view_cart"] == 'sidebar'){ echo 'class="toolbar-link-title-icon sidebar-cart-open"'; }elseif($settings["marketplace_view_cart"] == 'page'){ echo 'class="toolbar-link-title-icon" href="'._link('cart').'"'; } ?>  >
                            <div class="toolbar-link-title-icon-box" >
                                <span class="label-count-cart cart-item-counter" style="display: none;" ></span>                            
                                <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg" ><path fill-rule="evenodd" clip-rule="evenodd" d="M10 2a4 4 0 00-4 4v2H.867L2.18 18.496A4 4 0 006.15 22h11.703a4 4 0 003.969-3.504L23.133 8H18V6a4 4 0 00-4-4h-4zm3 7a1 1 0 00-1-1H8V6a2 2 0 012-2h4a2 2 0 012 2v4h4.867l-1.03 8.248A2 2 0 0117.851 20H6.148a2 2 0 01-1.984-1.752L3.133 10H12a1 1 0 001-1z" fill="currentColor"></path></svg>
                            </div>
                        </a>                       

                    </div>
                <?php } ?>

                <?php if($route_name == "catalog" || $route_name == "index"){ ?>
                        <div class="toolbar-link" >

                            <span class="toolbar-link-title-icon mobile-fixed-menu_catalog_filters-open" >
                                <div class="toolbar-link-title-icon-box" >
                                    <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11 9a4.002 4.002 0 01-3 3.874V21a1 1 0 11-2 0v-8.126a4.002 4.002 0 010-7.748V3a1 1 0 112 0v2.126c1.725.444 3 2.01 3 3.874zm5 12a1 1 0 102 0v-2.126a4.002 4.002 0 000-7.748V3a1 1 0 10-2 0v8.126a4.002 4.002 0 000 7.748V21zM9 9a2 2 0 10-4 0 2 2 0 004 0zm8 4a2 2 0 110 4 2 2 0 010-4z" fill="currentColor"></path></svg>
                                </div>
                            </span>

                        </div>
                <?php }elseif($route_name == "ad_view"){ ?>
                        <div class="toolbar-link mr10" >

                            <span <?php echo $Main->modalAuth( ["attr"=>'class="toolbar-link-title-icon toggle-favorite-ad" data-id="'.$data["ad"]["ads_id"].'"', "class"=>"toolbar-link-title-icon"] ); ?> >
                                <div class="toolbar-link-title-icon-box favorite-ad-icon-box" >
                                    <?php if( !isset($_SESSION['profile']["favorite"][$data["ad"]["ads_id"]]) ){ ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.026 4.133C4.398 4.578 3 6.147 3 8.537c0 3.51 2.228 6.371 4.648 8.432A23.633 23.633 0 0012 19.885a23.63 23.63 0 004.352-2.916C18.772 14.909 21 12.046 21 8.537c0-2.39-1.398-3.959-3.026-4.404-1.594-.436-3.657.148-5.11 2.642a1 1 0 01-1.728 0C9.683 4.281 7.62 3.697 6.026 4.133zM12 21l-.416.91-.003-.002-.008-.004-.027-.012a15.504 15.504 0 01-.433-.214 25.638 25.638 0 01-4.762-3.187C3.773 16.297 1 12.927 1 8.538 1 5.297 2.952 2.9 5.499 2.204c2.208-.604 4.677.114 6.501 2.32 1.824-2.206 4.293-2.924 6.501-2.32C21.048 2.9 23 5.297 23 8.537c0 4.39-2.772 7.758-5.352 9.955a25.642 25.642 0 01-4.762 3.186 15.504 15.504 0 01-.432.214l-.027.012-.008.004-.003.001L12 21zm0 0l.416.91c-.264.12-.568.12-.832 0L12 21z" fill="currentColor"></path></svg>
                                <?php }else{ ?>
                                    <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg" class="favorite-icon-active" ><path d="M12.39 20.87a.696.696 0 01-.78 0C9.764 19.637 2 14.15 2 8.973c0-6.68 7.85-7.75 10-3.25 2.15-4.5 10-3.43 10 3.25 0 5.178-7.764 10.664-9.61 11.895z" fill="currentColor"></path></svg>
                                <?php } ?>
                                </div>
                            </span>

                        </div>
                        
                        <div class="toolbar-link" >

                            <span class="toolbar-link-title-icon open-modal" data-id-modal="modal-ad-share" >
                                <div class="toolbar-link-title-icon-box" >
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="OfferCardSidebarShare__shareIcon__14Vvg"><path fill-rule="evenodd" clip-rule="evenodd" d="M17.25 9.25a3.49 3.49 0 01-2.545-1.098l-5.122 2.776c.225.698.224 1.45-.002 2.148l5.123 2.773a3.5 3.5 0 11-.9 1.787l-5.365-2.904a3.5 3.5 0 11.004-5.46l5.361-2.906A3.5 3.5 0 1117.25 9.25zm1.5-3.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm-1.5 14a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM7.75 12a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" fill="currentColor"></path></svg>
                                </div>
                            </span>

                        </div>                   
                <?php }elseif($route_name == "shops"){ ?>

                        <div class="toolbar-link" >

                            <span class="toolbar-link-title-icon mobile-fixed-menu_shops-category-open" >
                                <div class="toolbar-link-title-icon-box" >
                                    <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11 9a4.002 4.002 0 01-3 3.874V21a1 1 0 11-2 0v-8.126a4.002 4.002 0 010-7.748V3a1 1 0 112 0v2.126c1.725.444 3 2.01 3 3.874zm5 12a1 1 0 102 0v-2.126a4.002 4.002 0 000-7.748V3a1 1 0 10-2 0v8.126a4.002 4.002 0 000 7.748V21zM9 9a2 2 0 10-4 0 2 2 0 004 0zm8 4a2 2 0 110 4 2 2 0 010-4z" fill="currentColor"></path></svg>
                                </div>
                            </span>

                        </div>

                <?php }elseif($route_name == "shop"){ ?>

                        <div class="toolbar-link" >

                            <span class="toolbar-link-title-icon mobile-fixed-menu_shop-filters-open" >
                                <div class="toolbar-link-title-icon-box" >
                                    <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11 9a4.002 4.002 0 01-3 3.874V21a1 1 0 11-2 0v-8.126a4.002 4.002 0 010-7.748V3a1 1 0 112 0v2.126c1.725.444 3 2.01 3 3.874zm5 12a1 1 0 102 0v-2.126a4.002 4.002 0 000-7.748V3a1 1 0 10-2 0v8.126a4.002 4.002 0 000 7.748V21zM9 9a2 2 0 10-4 0 2 2 0 004 0zm8 4a2 2 0 110 4 2 2 0 010-4z" fill="currentColor"></path></svg>
                                </div>
                            </span>

                        </div>

                <?php }elseif($route_name == "blog"){ ?>

                        <div class="toolbar-link" >

                            <span class="toolbar-link-title-icon mobile-fixed-menu_blog-category-open" >
                                <div class="toolbar-link-title-icon-box" >
                                    <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11 9a4.002 4.002 0 01-3 3.874V21a1 1 0 11-2 0v-8.126a4.002 4.002 0 010-7.748V3a1 1 0 112 0v2.126c1.725.444 3 2.01 3 3.874zm5 12a1 1 0 102 0v-2.126a4.002 4.002 0 000-7.748V3a1 1 0 10-2 0v8.126a4.002 4.002 0 000 7.748V21zM9 9a2 2 0 10-4 0 2 2 0 004 0zm8 4a2 2 0 110 4 2 2 0 010-4z" fill="currentColor"></path></svg>
                                </div>
                            </span>

                        </div>

                <?php }elseif($route_name == "map"){ ?>

                        <div class="toolbar-link" >

                            <span class="toolbar-link-title-icon mobile-fixed-menu_map-filters-open open-modal" data-id-modal="modal-map-filters" >
                                <div class="toolbar-link-title-icon-box" >
                                    <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11 9a4.002 4.002 0 01-3 3.874V21a1 1 0 11-2 0v-8.126a4.002 4.002 0 010-7.748V3a1 1 0 112 0v2.126c1.725.444 3 2.01 3 3.874zm5 12a1 1 0 102 0v-2.126a4.002 4.002 0 000-7.748V3a1 1 0 10-2 0v8.126a4.002 4.002 0 000 7.748V21zM9 9a2 2 0 10-4 0 2 2 0 004 0zm8 4a2 2 0 110 4 2 2 0 010-4z" fill="currentColor"></path></svg>
                                </div>
                            </span>

                        </div>

                <?php } ?>

             </div>             
         </div>
      </div>

      <div class="results-ajax-live-search main-search-results" ></div>

   </div>

   <?php if( $route_name == "catalog" || $route_name == "index" ){ ?>
   <div class="header-wow-mobile-category" >
       
       <div class="header-wow-mobile-category-slider" >

           <div>
               <a class="header-wow-mobile-all-category mobile-fixed-menu_all-category-open" >
                
                <div class="header-wow-mobile-all-category-image" >
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" height="24" viewBox="0 0 24 24" width="24"><path clip-rule="evenodd" d="m5.49718 3.00617 4.50169-.00507c.55343 0 1.00113.44771 1.00113 1v5.99887c0 .55233-.4477 1.00003-1 1.00003h-6c-.55228 0-1-.4477-1-1.00003v-4.4938c0-1.37961 1.11757-2.49844 2.49718-2.5zm-1.49831 10.00173c-.55184.0006-.99887.4481-.99887 1v4.4921c0 1.3807 1.11929 2.5 2.5 2.5h4.5c.5523 0 1-.4477 1-1v-5.9989c0-.5523-.4477-1-1.00113-1zm10.00003 0c-.5519.0006-.9989.4481-.9989 1v5.9921c0 .5523.4477 1 1 1h4.5c1.3807 0 2.5-1.1193 2.5-2.5v-4.4989c0-.5523-.4477-1-1.0011-1zm0-10.00004c-.5519.00063-.9989.44816-.9989 1v5.99211c0 .55233.4477 1.00003 1 1.00003h6c.5523 0 1-.4477 1-1.00003v-4.49718c0-1.38071-1.1193-2.5-2.5028-2.5zm-8.9989 15.49214c0 .2761.22386.5.5.5h3.5v-3.9978l-4 .0045zm13.5.5h-3.5v-3.9933l4-.0045v3.4978c0 .2761-.2239.5-.5.5zm-9.5-10.00003v-3.99774l-3.50056.00394c-.27593.00031-.49944.22408-.49944.5v3.4938zm10 0h-4v-3.99323l3.5-.00395c.2761 0 .5.22386.5.5z" fill="currentColor" fill-rule="evenodd"></path></svg>
                </div>

                <div class="header-wow-mobile-all-category-name" ><?php echo $ULang->t('Все'); ?></div>

               </a>
           </div>

          <?php
          $manualTitles = [
              "Дома",
              "Авто",
              "Техника",
              "Вакансии"
          ];
          
          $counter = 0; // Счетчик для отслеживания числа итераций
          
          foreach ($getCategoryBoard["category_board_id_parent"][0] as $value) {
              if ($counter < 4) { // Первые четыре элемента из массива manualTitles
                  $title = isset($manualTitles[$counter]) ? $manualTitles[$counter] : ''; // Получаем значение из массива manualTitles
              } else { // Остальные элементы из базы данных
                  $title = $ULang->t($value["category_board_name"], ["table" => "uni_category_board", "field" => "category_board_name"]);
              }
          
              ?>
              <div>
                  <a href="<?php echo $CategoryBoard->alias($value["category_board_chain"]); ?>">
                      <?php if ($value["category_board_image"]) { ?>
                          <div class="header-wow-mobile-category-image">
                              <img src="<?php echo Exists($config["media"]["other"], $value["category_board_image"], $config["media"]["no_image"]); ?>">
                          </div>
                      <?php } ?>
          
                      <div class="header-wow-mobile-category-name">
                          <?php echo $title; ?>
                      </div>
          
                  </a>
              </div>
              <?php
          
              $counter++; // Увеличиваем счетчик
              if ($counter >= 4) { // Выходим из цикла после первых 4 элементов
                  break;
              }
          }
          ?>

        </div>

    </div>
    <?php } ?>

</div>

</header>

<noindex>
<div class="mobile-fixed-menu mobile-fixed-menu_all-category" >
    <div class="mobile-fixed-menu-header" >
        <span class="mobile-fixed-menu-header-close" ><i class="las la-times"></i></span>
        <span class="mobile-fixed-menu-header-title" ><?php echo $ULang->t('Все категории'); ?></span>
    </div>
    <div class="mobile-fixed-menu-content mobile-fixed-menu-content-link deny-margin-15" >
        
          <?php
              if(isset($getCategoryBoard["category_board_id_parent"][0])){
                foreach ($getCategoryBoard["category_board_id_parent"][0] as $value) {

                   ?>
                   <a class="mobile-fixed-menu_link-category" href="<?php echo $CategoryBoard->alias($value["category_board_chain"]); ?>" data-id="<?php echo $value["category_board_id"]; ?>" data-parent="<?php if(isset($getCategoryBoard["category_board_id_parent"][$value["category_board_id"]])){ echo 'true'; }else{ echo 'false'; } ?>"  >
                    
                    <span class="mobile-fixed-menu_name-category" ><?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?></span>
                    <span class="mobile-fixed-menu_count-category" ><?php echo $CategoryBoard->getCountAd( $value["category_board_id"] ); ?></span>

                   </a>
                   <?php

                }
              }
          ?>

    </div>
</div>

</noindex>

<?php
if( $_SESSION['cp_auth'][ $config["private_hash"] ] && $_SESSION["cp_control_tpl"] ){

  ?>

    <div class="modal-custom-bg"  id="modal-edit-site-menu" style="display: none;" >
        <div class="modal-custom animation-modal" style="max-width: 600px;" >

          <span class="modal-custom-close" ><i class="las la-times"></i></span>

          <h4> <strong>Редактирование меню</strong> </h4>

          <div class="mt30" ></div>
          
          <form class="modal-edit-site-menu-form" >
          <div class="modal-edit-site-menu-list" >

             <?php
                if( count($settings["frontend_menu"]) ){
                    foreach ($settings["frontend_menu"] as $key => $value) {

                       $key = uniqid();
                       ?>
                       <div class="modal-edit-site-menu-item" >
                          <div class="row" >
                             <div class="col-lg-6 col-6" >
                                <input type="text" name="menu[<?php echo $key; ?>][name]" class="form-control" placeholder="Название" value="<?php echo $value["name"]; ?>" >
                             </div>
                             <div class="col-lg-5 col-5" >
                                <input type="text" name="menu[<?php echo $key; ?>][link]" class="form-control" placeholder="Ссылка" value="<?php echo $value["link"]; ?>" >
                             </div>
                             <div class="col-lg-1 col-1" >
                                <span class="modal-edit-site-menu-delete" > <i class="las la-trash"></i> </span>
                             </div>                                                
                          </div>
                       </div>                       
                       <?php

                    }
                }
             ?>

          </div>
          
          <div class="mt10" ></div>

          <span class="modal-edit-site-menu-add btn-custom-mini btn-color-light">Добавить</span>

          </form>

          <div class="mt30" ></div>

          <button class="button-style-custom schema-color-button color-green mb10 width100 modal-edit-site-menu-save" >Сохранить</button>

        </div>
    </div>    

  <?php

}
?>


<?php echo $Banners->out( ["position_name"=>"stretching", "current_id_cat"=>$data["category"]["category_board_id"], "categories"=>$getCategoryBoard] ); ?>