
<h3 class="mb35 user-title" > <strong><?php echo $data["page_name"]; ?></strong> </h3>
<?php

if($_SESSION["profile"]["tariff"]["services"]["scheduler"]){

?>

<div class="bg-container" >
  
  <div class="table-responsive">

       <?php   
           $get = $Ads->getAll( [ "navigation" => false, "query" => "ads_id_user='".$_SESSION["profile"]["id"]."' and ads_auto_renewal='1'", "sort" => "order by ads_id desc" ], [], false ); 

           if($get['count']){   

           ?>
              <table class="table table-borderless">
              <thead>
                 <tr>
                  <th><?php echo $ULang->t("Объявления"); ?></th>
                  <th><?php echo $ULang->t("Ближайшее продление"); ?></th>
                  <th></th>
                 </tr>
              </thead>
              <tbody class="sort-container" >                     
           <?php

              foreach($get['all'] AS $value){

              $value = $Ads->getDataAd($value);

              ?>
                   <tr>
                       <td><a href="<?php echo $Ads->alias($value); ?>"><?php echo $value["ads_title"]; ?></a></td>
                       <td><?php echo datetime_format($value["ads_period_publication"]); ?></td> 
                       <td class="text-right" >
                            <span class="btn-custom-mini-icon btn-color-danger profile-scheduler-delete" data-id="<?php echo $value["ads_id"]; ?>" ><i class="las la-times"></i></span>                                               
                       </td>                      
                   </tr> 
       
               <?php                                         
              } 

              ?>

              </tbody>
              </table>

              <?php               
           }else{
              ?>

               <div class="user-block-no-result" >

                  <img src="<?php echo $settings["path_tpl_image"]; ?>/zdun-icon.png">
                  <p><?php echo $ULang->t("Задач пока нет. Чтобы они появились добавьте к объявлению автопродление."); ?></p>
                 
               </div>

              <?php
           }                  
        ?>

  </div> 

</div>

<?php

}else{

?>

   <div class="user-block-promo" >
     
     <div class="row no-gutters" >
        <div class="col-lg-8" >
          <div class="user-block-promo-content" >
            <p>
               <?php echo $ULang->t("Планируйте задачи и экономьте время на работу с объявлениями! Планировщик будет автоматически продлевать Ваши объявления! Просто при подаче объявления выберите автопродление и эти объявления будут отображаться на этой странице."); ?>
            </p>
            <a class="btn-custom btn-color-blue mt15 display-inline" href="<?php echo _link('tariffs'); ?>" ><?php echo $ULang->t("Подключить тариф"); ?></a>
          </div>
        </div>
        <div class="col-lg-4 d-none d-lg-block" style="text-align: center;" >
            <img src="<?php echo $settings["path_tpl_image"]; ?>/free-time-4341276_120579.png" height="230px" >
        </div>
     </div>

   </div>

<?php

}