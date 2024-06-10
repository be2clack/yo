<?php 

$value = $Ads->getDataAd($value);

$service = $Ads->adServices($value["ads_id"]);
$images = $Ads->getImages($value["ads_images"]);
?>
<div class="user-list-ad-item" >
   <div class="row no-gutters" >
       <div class="col-lg-2 col-4 d-none d-lg-block" >
          
          <div class="user-list-ad-img" >
              
              <a href="<?php echo $Ads->alias($value); ?>" >
                <img class="image-autofocus" src="<?php echo Exists($config["media"]["small_image_ads"],$images[0],$config["media"]["no_image"]); ?>" >
              </a>             
          </div>

       </div>
       <div class="col-lg-10 col-12" >
            
            <div class="user-list-ad-info"  >
            <div class="row" >
               <div class="col-lg-5" >

                 <div class="user-list-ad-info-status" > <?php echo $Ads->publicationAndStatus($value); ?> </div>
                 
                 <a href="<?php echo $Ads->alias($value); ?>" ><?php echo custom_substr($value["ads_title"], 35, "..."); ?></a>

                 <div class="item-grid-price" >
                  <?php
                        echo $Ads->outPrice( ["data"=>$value,"class_price"=>"item-grid-price-now","class_price_old"=>"item-grid-price-old", "abbreviation_million" => true] );
                  ?>        
                 </div>

                 <div class="user-list-ad-info-geo-cat" >
                 <?php if($settings["main_type_products"] == 'physical'){ echo $value['city_name'] . '&bull;'; } ?> <?php echo $value['category_board_name']; ?>
                 </div>

               </div>
               <div class="col-lg-7" >
                 
                  <div class="user-list-ad-info-view" >
                     <div>
                        <div class="user-list-ad-info-view-icon" data-tippy-placement="top" title="<?php echo $ULang->t("Просмотров"); ?>" >
                        <svg width="24" height="24" viewBox="0 0 24 24"><g fill="#333" fill-opacity="0.24"><path d="M11.994 6c2.72 0 5.44 1.712 7.632 5.128a2.398 2.398 0 0 1 0 2.544c-2.192 3.416-4.912 5.128-7.632 5.128-2.72 0-5.44-1.712-7.632-5.136a2.4 2.4 0 0 1 0-2.536C6.554 7.712 9.274 6 11.994 6zm0 3.4a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"></path><circle cx="12" cy="12.5" r="1"></circle></g></svg><?php echo $Ads->getCountView($value["ads_id"]); ?>
                        </div>
                     </div>
                     <div>
                        <div class="user-list-ad-info-view-icon" data-tippy-placement="top" title="<?php echo $ULang->t("Показов"); ?>" >
                        <svg width="24" height="24" viewBox="0 0 24 24" ><path d="M4 12.001L12 3l8 9.001h-4.8V21H8.803v-8.999H4z" fill="#333" fill-opacity="0.24" fill-rule="evenodd"></path></svg><?php echo $value['ads_count_display']; ?>
                        </div>
                     </div>                     
                     <div>
                        <div class="user-list-ad-info-view-icon" data-tippy-placement="top" title="<?php echo $ULang->t("В избранном"); ?>" >
                        <svg width="24" height="24" viewBox="0 0 24 24"><path d="M12 7c.838-.726 1.945-1.5 3.158-1.5 2.616 0 4.342 1.671 4.342 4.204 0 3.696-3.557 6.622-7.165 8.713a.717.717 0 0 1-.668 0C8.036 16.333 4.5 13.4 4.5 9.704 4.5 7.17 6.226 5.5 8.842 5.5c1.213 0 2.32.774 3.158 1.5z" fill="#333" fill-opacity="0.24" fill-rule="nonzero"></path></svg><?php echo $Profile->getCountFavorites($value["ads_id"]); ?>        
                        </div>                
                     </div>
                     <?php if($value['ads_status'] == 1){ ?>
                     <div>
                        <div class="user-list-ad-info-view-icon" data-tippy-placement="top" title="<?php echo $ULang->t("Осталось до окончания"); ?>" >
                        <svg width="24" height="24" viewBox="0 0 24 24"><g fill="#333" fill-opacity="0.24" fill-rule="nonzero"><path d="M16.6 6A2.4 2.4 0 0 1 19 8.4v9.2a2.4 2.4 0 0 1-2.4 2.4H7.4A2.4 2.4 0 0 1 5 17.6V8.4A2.4 2.4 0 0 1 7.4 6h9.2zm.4 4H7v7.2a.8.8 0 0 0 .8.8h8.4a.8.8 0 0 0 .8-.8V10zM9 4a1 1 0 0 1 1 1v1H8V5a1 1 0 0 1 1-1zm6 0a1 1 0 0 1 1 1v1h-2V5a1 1 0 0 1 1-1z"></path><rect width="4" height="4" x="8" y="11" rx="0.8"></rect></g></svg><?php echo difference_days($value['ads_period_publication'],date('Y-m-d H:i:s')); ?>  
                        </div>                     
                     </div>
                     <?php } ?>
                     <div>
                        <div class="user-list-ad-info-menu" >
                           <svg width="24" height="24" viewBox="0 0 24 24" data-test-block="B2BProductCardActionsContainer" class="sc-hJxVqy drDioY"><path fill="#333" fill-rule="evenodd" d="M12 14a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0-1a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm7 1a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0-1a1 1 0 1 0 0-2 1 1 0 0 0 0 2zM5 14a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0-1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"></path></svg>
                           <div class="user-list-ad-info-menu-list" >

                              <?php if($value['ads_status'] == 0 || $value['ads_status'] == 1 || $value['ads_status'] == 2){ ?>
                              <a href="<?php echo _link('ad/update/'.$value['ads_id']); ?>" ><?php echo $ULang->t("Редактировать"); ?></a>
                              <?php } ?>
                              
                              <a href="<?php echo _link( "user/" . $user["clients_id_hash"] . "/statistics?ad=".$value['ads_id'] ); ?>" ><?php echo $ULang->t("Статистика"); ?></a>
                              <span class="open-modal action-remove-publication" data-id-modal="modal-delete-ads" data-id="<?php echo $value['ads_id']; ?>" ><?php echo $ULang->t("Удалить"); ?></span>
                              <?php if($value['ads_status'] == 1){ ?>
                                       <span class="open-modal action-remove-publication" data-id-modal="modal-remove-publication" data-id="<?php echo $value['ads_id']; ?>" ><?php echo $ULang->t("Снять с публикации"); ?></span>
                              <?php }elseif($value['ads_status'] == 2){ ?>
                                       <span class="open-modal profile-ads-publication" data-id="<?php echo $value['ads_id']; ?>" ><?php echo $ULang->t("Опубликовать"); ?></span>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                  </div>

                  <div class="user-list-ad-info-label" >
                     
                     <?php if($value['ads_status'] == 1){ ?>
                     <div class="user-list-ad-info-label" >
                        
                        <?php if($value['ads_auto_renewal']){ ?>
                        <div class="user-list-ad-info-label-renewal" >
                           <div data-tippy-placement="top" title="<?php echo $ULang->t("Автопродление"); ?>" >
                              <i class="las la-sync"></i>
                           </div>
                        </div>
                        <?php } ?>

                        <?php
                            if( $service[1] && $service[2] ){
                                echo '
                                    <div>
                                       <div data-tippy-placement="top" title="'.$ULang->t("Поднятие в ленте").'" ><span class="item-service-top" ><i class="las la-angle-double-up"></i></span> </div>
                                    </div>
                                    <div>
                                       <div data-tippy-placement="top" title="'.$ULang->t("Вип объявление").'" ><span class="item-vip" ><i class="las la-crown"></i></span> </div>
                                    </div>                                 
                                ';
                            }elseif( $service[1] ){
                                echo '
                                   <div>
                                      <div data-tippy-placement="top" title="'.$ULang->t("Поднятие в ленте").'" ><span class="item-service-top" ><i class="las la-angle-double-up"></i></span></div>
                                   </div>
                                ';
                            }elseif( $service[2] ){
                                echo '
                                   <div>
                                       <div data-tippy-placement="top" title="'.$ULang->t("Вип объявление").'" ><span class="item-vip" ><i class="las la-crown"></i></span></div>
                                   </div>
                                ';
                            }elseif( $service[3] ){
                                echo '
                                   <div>
                                       <div data-tippy-placement="top" title="'.$ULang->t("Турбо продажа").'" ><span class="item-service-turbo" ><i class="las la-rocket"></i></span></div>
                                   </div>
                                ';
                            }
                        ?>

                     </div>
                     <?php } ?>


                  </div>

               </div>
            </div>
            </div>

       </div>
   </div>
</div>