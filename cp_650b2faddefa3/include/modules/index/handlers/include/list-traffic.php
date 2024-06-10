<?php  

   if(count($getMetrics) > 0){   

   ?>
   <h3 class="widget-title" >Трафик сайта <?php echo number_format($countMetrics); ?> <?php echo ending( $countMetrics, "человек", "человека", "человек" ); ?></h3>

   <table class="table mb-0">
      <thead>
         <tr>
            <th>Расположение</th>
            <th>Переход с</th>
            <th>Время посещения</th>
         </tr>
      </thead>
      <tbody>                     
   <?php

      foreach($getMetrics AS $value){
         
         $geo = $Geo->geoIp($value["ip"], false);

         if($value["user_agent"]){
           $Mobile_Detect->setUserAgent($value["user_agent"]);

           if ( $Mobile_Detect->isMobile() ) {
              $device = '<span class="Mobile_Detect" ><i class="la la-mobile-phone"></i></span>';
           }elseif($Mobile_Detect->isTablet()){
              $device = '<span class="Mobile_Detect" ><i class="la la-tablet"></i></span>';
           }elseif(!$Mobile_Detect->isMobile() && !$Mobile_Detect->isTablet()){
              $device = '<span class="Mobile_Detect" ><i class="la la-laptop"></i></span>';
           }else{
              $device = '';
           }
         }

      ?>

         <tr>
            <td>

              <div class="metrics_iconbrowser" >
                <div>
                  <img class="iconbrowser" title="<?php echo $Admin->browser($value["user_agent"]);?>" src="<?php echo $config["urlPath"]; ?>/<?php echo $config["folder_admin"]; ?>/files/images/icon_<?php echo $Admin->browser($value["user_agent"]);?>.png"  >
                  <?php echo $device; ?>
                </div>
              </div>

              <div class="metrics_info" >

               <?php echo $geo; ?>

               <br>

               <?php if((strtotime($value["date_view"]) + 180) > time()){ ?>
               <span class="online badge-pulse-green-small"></span> сейчас смотрят  
               <br>
               <?php } ?>
               
               <?php if($value["view_page_title"]){ ?>
               <a title="<?php echo $value["view_page_title"]; ?>" target="_blank" href="<?php echo $value["view_page_link"]; ?>"><?php echo custom_substr($value["view_page_title"],40, "..."); ?></a>
               <?php }else{ ?>
               <a title="<?php echo $value["view_page_link"]; ?>" target="_blank" href="<?php echo $value["view_page_link"]; ?>"><?php echo custom_substr($value["view_page_link"],40, "..."); ?></a>
               <?php } ?>



              </div>   
            </td>
            <td>

               <?php if($value["referrer"]){ ?>
               <a href="<?php echo $value["referrer"]; ?>" target="_blank" ><?php echo custom_substr($value["referrer"],40, "..."); ?></a>
               <?php }else{ ?>
               -                                    
               <?php } ?>

            </td>
            <td><?php echo datetime_format_cp($value["date_view"]);?></td>
         </tr>

                                
       <?php                                         
      } 

      ?>

         </tbody>
      </table>

      <ul class="pagination">  
        <?php echo out_navigation( array("count"=>$countMetrics, "output"=>20, "url"=>"?route=index", "prev"=>'<i class="la la-long-arrow-left"></i>', "next"=>'<i class="la la-arrow-right"></i>', "page_count" => $_POST["page"], "page_variable" => "page") );?>
      </ul>      
      
      <?php               
   }else{
       
       ?>
           <div class="infoIcon" >
             <span><i class="la la-exclamation-circle"></i></span>
             <p>Данных нет</p>
           </div>
       <?php

   }                  
?>