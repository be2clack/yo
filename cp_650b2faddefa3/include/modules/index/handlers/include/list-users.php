
<div class="widget-list-users" >
   <?php
     if(count($getUsers)){
        ?>
        <h3 class="widget-title" > Сейчас на сайте (<?php echo count($getUsers); ?>)</h3>
        <?php
        foreach (array_slice($getUsers,0, 5) as $key => $value) {

           $getMetric = findOne("uni_metrics","id_user=?", [ $value["clients_id"] ]);
           ?>
             <div class="widget-list-users-item" >
                <div class="widget-list-users-avatar" ><img src="<?php echo $Profile->userAvatar($value, false); ?>"></div>
                <div class="widget-list-users-title" >
                  <a href="?route=client_view&id=<?php echo $value["clients_id"]; ?>" ><?php echo $Profile->name($value, false); ?></a>
                  
                  <div class="widget-list-users-link" >
                  <?php if($getMetric["view_page_title"]){ ?>
                    <a target="_blank" href="<?php echo $getMetric["view_page_link"]; ?>"><?php echo custom_substr($getMetric["view_page_title"],35); ?></a>
                  <?php }else{ ?>
                    <a target="_blank" href="<?php echo $getMetric["view_page_link"]; ?>"><?php echo custom_substr($getMetric["view_page_link"],35); ?></a>
                  <?php } ?>
                  </div>
                  
                </div>
                <div class="clr" ></div>
             </div>
           <?php
        }

        ?>

        <div class="widget-list-ads-actions" >
          <a href="?route=clients&sort=4" >Все пользователи онлайн</a>
        </div>

        <?php

     }else{
       ?>
         <div class="infoIcon" >
           <span><i class="la la-exclamation-circle"></i></span>
           <p>Онлайн пользователей нет</p>
         </div>
       <?php
     }
   ?>                                            
</div>