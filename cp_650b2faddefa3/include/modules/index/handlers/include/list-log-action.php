<div class="widget-list-log-action" >
<?php  
   
   $notifications = [];

   if(count($getLogs)){   

   ?>
      <div class="widget-title-flex" >
         <h3 class="widget-title" >Уведомления</h3> <span class="delete-notification" >Очистить</span>
      </div>

      <div class="widget-list-log-action-item" >
         
         <?php
         foreach($getLogs AS $value){
            $count = (int)getOne("select count(*) as total from uni_notifications where code=?", [$value['code']])["total"];
            $notifications[$value['code']] = '<div><a href="?route='.$value['link'].'">'.$value['title'].' <span class="label-count" >'.$count.'</span></a> </div> ';                                     
         } 

         foreach ($notifications as $key => $value) {
            echo $value;
         }
         ?>

      </div>

      <?php               
   }else{
      ?>
        <div class="infoIcon" >
          <span><i class="la la-exclamation-circle"></i></span>
          <p>Уведомлений нет</p>
        </div>
      <?php
   }                  
?>
</div>