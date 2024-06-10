<div class="widget-list-chat-messages" >

   <?php
     if(count($chatMessages)){

        ?>
        <h3 class="widget-title" > Новые сообщения (<?php echo $Admin->getAllMessagesSupport(); ?>)</h3>
        <?php

        foreach ($chatMessages as $key => $value) {

           $value["chat_messages_text"] = custom_substr(decrypt($value["chat_messages_text"]),200, "...");

           $getUser = findOne('uni_clients', 'clients_id=?', [$value["chat_messages_id_user"]]);

           if($value["chat_messages_attach"]){
                $attach = json_decode($value["chat_messages_attach"], true);
           }else{
                $attach = [];
           }

           ?>
             <div class="widget-list-chat-message-item" >
                <div class="widget-list-chat-message-item-avatar" ><span><img src="<?php echo $Profile->userAvatar($getUser, false); ?>"></span></div>
                <div class="widget-list-chat-message-item-text" >
                  <?php 

                    echo $value["chat_messages_text"]; 

                    if($attach["images"]){
                         foreach ($attach["images"] as $attach_name) {
                              ?>
                              <div class="widget-list-chat-message-item-attach" >
                                 <a href="<?php echo $config["urlPath"] . "/" . $config["media"]["attach"] . "/" . $attach_name; ?>" target="_blank" ><img src="<?php echo $config["urlPath"] . "/" . $config["media"]["attach"] . "/" . $attach_name; ?>"></a>
                              </div>
                              <?php
                         }
                    }

                  ?>  
                </div>
             </div>                       
           <?php
        }

        ?>

        <div class="widget-list-ads-actions" >
          <a href="?route=chat" >Перейти в чат</a>
        </div>

        <?php

     }else{
       ?>
         <div class="infoIcon" >
           <span><i class="la la-exclamation-circle"></i></span>
           <p>Новых сообщений нет</p>
         </div>
       <?php
     }
   ?>
   
                 
</div>