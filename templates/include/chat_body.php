<div class="module-chat" >

  <div class="row no-gutters" >
     <div class="col-lg-4 col-12" >
        <div class="module-chat-users" >

           <div data-id="<?php echo md5('support'.$_SESSION['profile']['id']); ?>" data-support="1" class="module-chat-users-support-item" >

              <div class="module-chat-users-img" >
                <img src="<?php echo $settings["path_tpl_image"].'/supportChat.png'; ?>" >
              </div>
              <div class="module-chat-users-info" >
                 <p class="module-chat-users-info-client" ><strong><?php echo $ULang->t("Поддержка"); ?> <?php echo custom_substr($settings["site_name"],20, "..."); ?></strong></p>
                 <p class="module-chat-users-info-title" ><?php echo $ULang->t("Будем рады помочь"); ?></p>
                 <?php echo $Profile->countChatMessages(md5('support'.$_SESSION['profile']['id'])); ?>
              </div>

              <div class="clr" ></div>
            
           </div>

          <?php echo $list_chat_users; ?>
          
        </div>
     </div>
     <div class="col-lg-8 col-12" >
        <div class="module-chat-dialog" >

            <div class="chat-dialog-empty" >
                <div>
                <svg width="184" height="136" viewBox="0 0 184 136" ><defs><linearGradient id="dialog-stub_svg__a" x1="100%" x2="0%" y1="0%" y2="100%"><stop offset="0%" stop-color="#BAF8FF"></stop><stop offset="100%" stop-color="#D2D4FF"></stop></linearGradient><linearGradient id="dialog-stub_svg__b" x1="0%" x2="100%" y1="100%" y2="0%"><stop offset="0%" stop-color="#B7F2FF"></stop><stop offset="100%" stop-color="#C1FFE5"></stop></linearGradient><linearGradient id="dialog-stub_svg__c" x1="100%" x2="0%" y1="0%" y2="100%"><stop offset="0%" stop-color="#FFF0BF"></stop><stop offset="100%" stop-color="#FFE0D4"></stop></linearGradient></defs><g fill="none" fill-rule="evenodd"><path fill="#FFF" d="M-88-141h360v592H-88z"></path><g transform="translate(12 8)"><path fill="#FFF" d="M0 3.993A4 4 0 0 1 3.995 0h152.01A3.996 3.996 0 0 1 160 3.993v112.014a4 4 0 0 1-3.995 3.993H3.995A3.996 3.996 0 0 1 0 116.007V3.993z"></path><rect width="24" height="24" x="12" y="8" fill="url(#dialog-stub_svg__a)" rx="4"></rect><path fill="#F5F5F5" d="M71 13H44v6h27zm77 0h-17v6h17zm-35.5 10H44v6h68.5z"></path><circle cx="35" cy="11" r="6" fill="#E6EDFF" stroke="#FFF" stroke-width="2"></circle><rect width="24" height="24" x="12" y="47" fill="url(#dialog-stub_svg__b)" rx="4"></rect><path fill="#F5F5F5" d="M71 52H44v6h27zm77 0h-17v6h17zm-35.5 10H44v6h68.5z"></path><circle cx="35" cy="50" r="6" fill="#E6EDFF" stroke="#FFF" stroke-width="2"></circle><rect width="24" height="24" x="12" y="86" fill="url(#dialog-stub_svg__c)" rx="4"></rect><path fill="#F5F5F5" d="M71 91H44v6h27zm77 0h-17v6h17zm-35.5 10H44v6h68.5z"></path><circle cx="35" cy="89" r="6" fill="#E6EDFF" stroke="#FFF" stroke-width="2"></circle></g></g></svg>
                <p><?php echo $ULang->t("Выберите чат для общения"); ?></p>
                </div>
            </div>
          
        </div>                         
     </div>
  </div>

</div>

