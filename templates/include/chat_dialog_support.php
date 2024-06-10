<div class="module-chat-dialog-header" >

	<div class="module-chat-dialog-prev" >
	 <span> <i class="las la-arrow-left"></i> <?php echo $ULang->t("Назад"); ?> </span>
	</div>

	<div class="module-chat-dialog-header-block-1" >
		<img src="<?php echo $settings["path_tpl_image"].'/supportChat.png'; ?>" >
	</div>
	<div class="module-chat-dialog-header-block-2" >
		<p><strong><?php echo $ULang->t("Поддержка"); ?> <?php echo custom_substr($settings["site_name"],40, "..."); ?></strong></p>
		<p><?php echo $ULang->t("Напишите нам - ответим в ближайшее время!"); ?></p>
	</div>

	<div class="clr" ></div>

</div>
<div class="module-chat-dialog-content" >

	<?php
    if(count($getDialog)){

	   foreach ($getDialog as $key => $value) {
          
          if($value["chat_messages_action"] == 0){
              $list[ date("d.m.Y", strtotime( $value["chat_messages_date"] ) ) ][] = $value;
          }else{
              if(intval($_SESSION['profile']['id']) != $value["chat_messages_id_user"]){
              	 $list[ date("d.m.Y", strtotime( $value["chat_messages_date"] ) ) ][] = $value;
              }
          }
	   	  

	   }
       
       if($list){
	   foreach ($list as $date => $array) {
           
	   	   ?>
	   	   <div class="dialog-content-date" >
	   	   	  <?php echo $date; ?>
	   	   </div>
	   	   <?php

		   foreach ($array as $key => $value) {

		   	   $value["chat_messages_text"] = decrypt($value["chat_messages_text"]);

		   	   if($value["chat_messages_id_user"]) $get = $Profile->oneUser(" where clients_id=?" , array( $value["chat_messages_id_user"] ) );

		   	   if($value["chat_messages_attach"]){
		   	   	 	$attach = json_decode($value["chat_messages_attach"], true);
		   	   }else{
		   	   	  $attach = [];
		   	   }
		   	
		   	   ?>
			   	  <div class="dialog-content-item">
              
              <div class="dialog-content-item-box" >
	                <div class="dialog-content-flex" >
					   	  	  <div class="dialog-content-circle-img" >
					   	  	  	<img src="<?php if($value["chat_messages_id_user"]) echo $Profile->userAvatar($get); else echo $settings["path_tpl_image"].'/supportChat.png'; ?>">
					   	  	  </div>
				   	  	  </div>

				   	  	  <div class="dialog-content-msg" >

				   	  	  	<?php if($value["chat_messages_id_user"]){ ?>
				   	  	  		<a href="<?php echo _link("user/".$get["clients_id_hash"]); ?>"><?php echo $Profile->name($get); ?></a>
				   	  	  	<?php }else{ ?>
				   	  	  		<span><strong><?php echo $ULang->t("Менеджер"); ?></strong></span>
				   	  	  	<?php } ?>
				   	  	  	
				   	  	  	<?php if($value["chat_messages_text"]){ ?>
				   	  	  	<p class="dialog-content-msg-text" ><?php echo nl2br($value["chat_messages_text"]); ?></p>
				   	  	  	<?php
				   	  	    }

				   	  	  	if($attach["images"]){
				   	  	  		 foreach ($attach["images"] as $attach_name) {
				   	  	  		 	  ?>
				   	  	  		 	  <div class="dialog-content-attach" >
				   	  	  		 	  	 <a href="<?php echo $config["urlPath"] . "/" . $config["media"]["attach"] . "/" . $attach_name; ?>" target="_blank" ><img src="<?php echo $config["urlPath"] . "/" . $config["media"]["attach"] . "/" . $attach_name; ?>"></a>
				   	  	  		 	  </div>
				   	  	  		 	  <?php
				   	  	  		 }
				   	  	  	}
				   	  	  	?>
				   	  	  </div>
			   	  	 </div>

			   	  	 <div class="dialog-content-msg-date" ><span><?php echo date( "H:i", strtotime( $value["chat_messages_date"] ) ); ?></span></div>

			   	  </div>
		   	   <?php

		   }

	   }

	  }

	 }else{

	 	  ?>
	   	  <div class="dialog-content-item">
            
            <div class="dialog-content-item-box" >       
		            <div class="dialog-content-flex" >
				   	  	  <div class="dialog-content-circle-img" >
				   	  	  	<img src="<?php echo $settings["logotip"]; ?>">
				   	  	  </div>
			   	  	  </div>
			   	  	  <div class="dialog-content-msg" >
			   	  	  	<p class="dialog-content-msg-text" >
										<strong><?php echo $ULang->t("Здравствуйте✌"); ?></strong>
										<div class="mt10" ></div>
										<?php echo $ULang->t("Здесь вам всегда ответит служба поддержки. Если кто-то представляется поддержкой"); ?> <?php echo $settings['site_name']; ?> <?php echo $ULang->t("в других чатах, это мошенники— будьте начеку!"); ?>
			   	  	  	</p>
			   	  	  </div>
						</div>

	   	  </div>
	 	  <?php

	 }

	?>
	
</div>

<div class="module-chat-dialog-footer" >

	<?php if(!$getMyLocked){ ?>
  <div class="module-chat-dialog-footer-box1" >

		<div class="chat-dialog-text-flex-box" >
			 <div class="chat-dialog-text-flex-box1" >
			 	
			 		<div class="chat-dialog-attach-change" >
			 			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.46 3h3.08c.29 0 .53 0 .76.03.7.1 1.35.47 1.8 1.03.25.3.4.64.62.96.2.28.5.46.85.48.3.02.58-.01.88.02a3.9 3.9 0 013.53 3.53c.02.18.02.37.02.65v4.04c0 1.09 0 1.96-.06 2.66a5.03 5.03 0 01-.47 1.92 4.9 4.9 0 01-2.15 2.15c-.57.29-1.2.41-1.92.47-.7.06-1.57.06-2.66.06H9.26c-1.09 0-1.96 0-2.66-.06a5.03 5.03 0 01-1.92-.47 4.9 4.9 0 01-2.15-2.15 5.07 5.07 0 01-.47-1.92C2 15.7 2 14.83 2 13.74V9.7c0-.28 0-.47.02-.65a3.9 3.9 0 013.53-3.53c.3-.03.59 0 .88-.02.34-.02.65-.2.85-.48.21-.32.37-.67.61-.96A2.9 2.9 0 019.7 3.03c.23-.03.47-.03.76-.03zm0 1.8l-.49.01a1.1 1.1 0 00-.69.4c-.2.24-.33.56-.52.82A2.9 2.9 0 016.54 7.3c-.28.01-.55-.02-.83 0a2.1 2.1 0 00-1.9 1.91l-.01.53v3.96c0 1.14 0 1.93.05 2.55.05.62.15.98.29 1.26.3.58.77 1.05 1.35 1.35.28.14.64.24 1.26.29.62.05 1.42.05 2.55.05h5.4c1.13 0 1.93 0 2.55-.05.62-.05.98-.15 1.26-.29a3.1 3.1 0 001.35-1.35c.14-.28.24-.64.29-1.26.05-.62.05-1.41.05-2.55V9.21a2.1 2.1 0 00-1.91-1.9c-.28-.03-.55 0-.83-.01a2.9 2.9 0 01-2.22-1.27c-.19-.26-.32-.58-.52-.83a1.1 1.1 0 00-.69-.39 3.92 3.92 0 00-.49-.01h-3.08z" fill="currentColor"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M12 9.8a2.7 2.7 0 100 5.4 2.7 2.7 0 000-5.4zm-4.5 2.7a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0z" fill="currentColor"></path></svg>
			 		</div>

			 </div>
			 <div class="chat-dialog-text-flex-box2" >

					<textarea maxlength="1000" class="chat-dialog-text chat-dialog-send" placeholder="<?php echo $ULang->t("Напишите сообщение..."); ?>" ></textarea>

			 </div>
			 <div class="chat-dialog-text-flex-box3" >

			 		<div class="chat-dialog-text-send" >
			 			<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="send_24__send_24"><path id="send_24__Rectangle-76" d="M0 0h24v24H0z"></path><path d="M5.74 15.75a39.14 39.14 0 00-1.3 3.91c-.55 2.37-.95 2.9 1.11 1.78 2.07-1.13 12.05-6.69 14.28-7.92 2.9-1.61 2.94-1.49-.16-3.2C17.31 9.02 7.44 3.6 5.55 2.54c-1.89-1.07-1.66-.6-1.1 1.77.17.76.61 2.08 1.3 3.94a4 4 0 003 2.54l5.76 1.11a.1.1 0 010 .2L8.73 13.2a4 4 0 00-3 2.54z" id="send_24__Mask" fill="currentColor"></path></g></g></svg>
			 		</div>

			 </div>
		</div>

		<div class="chat-dialog-attach-list" ></div>

		<input type="file" accept=".jpg,.jpeg,.png" multiple="true" style="display: none;" class="chat-dialog-attach-input" />

	</div>
	<?php }else{ ?>

		<div class="chat-dialog-text-flex-box-locked" >
			 <span><?php echo $ULang->t("Отправка сообщений ограничена"); ?></span>
		</div>

	<?php } ?>

</div>



