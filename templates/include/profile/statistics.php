
<h3 class="mb35 user-title" > <strong><?php echo $data["page_name"]; ?></strong> </h3>
<?php

if($_SESSION["profile"]["tariff"]["services"]["statistics_ad"]){

?>

<div class="row" >

   <div class="col-lg-4 mb5" >
       <div class="change-statistics-filter-date" >
           <span class="open-modal" data-id-modal="modal-statistics-filter-date" >
           <?php
             if(!$_GET['date_start'] && !$_GET['date_end']){
                echo $ULang->t("За месяц");
             }elseif($_GET['date_start'] && $_GET['date_end']){
                echo date("d.m.Y",strtotime($_GET['date_start'])) . ' - ' . date("d.m.Y",strtotime($_GET['date_end']));
             }elseif($_GET['date_start']){
                echo date("d.m.Y",strtotime($_GET['date_start']));
             }else{
                echo $ULang->t("За месяц");
             }
           ?>
           </span>
           <a class="clear-statistics-filter-date" href="<?php if($_GET['ad']){ echo _link("user/".$user["clients_id_hash"]."/statistics?ad=".$_GET['ad']); }else{ echo _link("user/".$user["clients_id_hash"]."/statistics"); } ?>" ><i class="las la-times"></i></a>
       </div>
   </div>

   <div class="col-lg-4 mb5" >
       <div class="uni-select profile-statistics-change-ad" data-status="0">

           <div class="uni-select-name" data-name="<?php echo $ULang->t("Общая статистика"); ?>"> <span><?php echo $ULang->t("Общая статистика"); ?></span> <i class="la la-angle-down"></i> </div>
           <div class="uni-select-list">
                
                <label> <input type="radio" value="<?php echo _link("user/".$user["clients_id_hash"]."/statistics"); ?>"> <span><?php echo $ULang->t("Общая статистика"); ?></span> <i class="la la-check"></i> </label>

                <?php
                $getAds = $Ads->getAll( [ "navigation" => false, "query" => "ads_id_user='".$user["clients_id"]."' and ads_status!='8'", "sort" => "order by ads_id desc" ] );

                if($getAds['count']){
                    foreach ($getAds['all'] as $value) {

                        $value = $Ads->getDataAd($value);
                        
                        if($_GET['ad']){
                            if($value['ads_id'] == intval($_GET['ad'])){
                                $active = 'class="uni-select-item-active"';
                            }else{
                                $active = '';
                            }
                        }

                        echo '<label '.$active.' > <input type="radio" value="'._link("user/".$value["clients_id_hash"]."/statistics?ad=".$value['ads_id']).'"> <span>'.$value['ads_title'].'</span> <i class="la la-check"></i> </label>';
                    }
                }

                ?>

           </div>
          
        </div>
    </div>

</div>

<div class="bg-container mt30" >
  
    <div class="profile-statistics-area1" ></div>

    <h4 class="mt30 mb30 user-title" > <strong><?php echo $ULang->t("Активные пользователи"); ?></strong> </h4>

       <?php   
           
           $getUsers = $Profile->usersActionStatistics(); 

           if($getUsers){   
           ?>
              <div class="row">                   
              <?php 
              foreach($getUsers AS $from_user_id => $value){
                  ?>
                  <div class="col-lg-3 col-6 col-sm-4 col-md-4" >
                       <div class="profile-statistics-user-item" >
                            <div class="profile-statistics-user-item-avatar" >
                                <img class="image-autofocus" src="<?php echo $Profile->userAvatar($value); ?>">
                            </div>
                            <div class="profile-statistics-user-item-name" >
                                <a href="<?php echo _link("user/".$value["clients_id_hash"]); ?>"><?php echo $value['clients_name']; ?></a>
                            </div>
                            <span class="btn-custom-mini btn-color-blue mt10 open-modal statistics-load-info-user" data-id-modal="modal-statistics-load-info-user" data-id="<?php echo $value["clients_id"]; ?>" ><?php echo $ULang->t("Подробнее"); ?></span>                      
                       </div>
                  </div> 
                  <?php                                         
              } 
              ?>
              </div>
              <?php               
           }else{
              ?>
               <div class="user-block-no-result" >
                  <img src="<?php echo $settings["path_tpl_image"]; ?>/zdun-icon.png">
                  <p><?php echo $ULang->t("Пользователей нет"); ?></p>
               </div>
              <?php
           }                  
        ?>

</div>

<?php

}else{

?>

   <div class="user-block-promo" >
     
     <div class="row no-gutters" >
        <div class="col-lg-8" >
          <div class="user-block-promo-content" >
            <p>
               <?php echo $ULang->t("Подробная статистика ваших объявлений. С помощью расширенной статистики вы сможете детально смотреть кто интересовался вашими объявлениями, сколько раз просматривали номер телефона, добавляли в избранное и многое другое."); ?>
            </p>
            <a class="btn-custom btn-color-blue mt15 display-inline" href="<?php echo _link('tariffs'); ?>" ><?php echo $ULang->t("Подключить тариф"); ?></a>
          </div>
        </div>
        <div class="col-lg-4 d-none d-lg-block" style="text-align: center;" >
            <img src="<?php echo $settings["path_tpl_image"]; ?>/graphic_statistics_chart_analytics_icon_0.png" height="230px" >
        </div>
     </div>

   </div>

<?php

}