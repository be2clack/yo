<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_chat']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

$Profile = new Profile();

$idHash = clear($_POST['hash']);
$idUser = (int)$_POST['id'];

if(!$idHash) exit;

$getDialog = getAll("select * from uni_chat_messages where chat_messages_id_hash=? order by chat_messages_date asc", [$idHash]);

$getUser = $Profile->oneUser(" where clients_id=?",[$idUser]);

update("update uni_chat_messages set chat_messages_status=? where chat_messages_id_hash=? and chat_messages_id_user!=?", array(1,$idHash,0));

$getMyLocked = findOne("uni_clients_blacklist", "clients_blacklist_user_id=? and clients_blacklist_user_id_locked=?", array(0,$idUser));

?>

<div class="admin-chat-users-dialog-header" >

    <div class="admin-chat-users-dialog-header-block-1" >
        <img src="<?php echo $Profile->userAvatar($getUser, false); ?>">
    </div>
    <div class="admin-chat-users-dialog-header-block-2" >
        <a href="?route=client_view&id=<?php echo $getUser["clients_id"]; ?>"><?php echo $getUser["clients_name"]; ?> <?php echo $getUser["clients_surname"]; ?></a>
    </div>
    <div class="admin-chat-users-dialog-header-block-3" >
        <span class="admin-chat-users-dialog-ban" data-id="<?php echo $idUser; ?>" >
            <?php if($getMyLocked){ ?>
                <i class="la la-ban" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Разблокировать" ></i>
            <?php }else{ ?>
                <i class="la la-ban" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Заблокировать" ></i>
            <?php } ?>
        </span>
        <span class="admin-chat-users-dialog-delete" data-id="<?php echo $idUser; ?>" data-hash="<?php echo $idHash; ?>" ><i class="la la-trash" ></i></span>
    </div>

</div>

<hr>

<div class="admin-chat-users-dialog-content" >
    
<?php

if(count($getDialog)){

    foreach ($getDialog as $value) {
      
      $list[ date("d.m.Y", strtotime( $value["chat_messages_date"] ) ) ][] = $value;
      
    }

    if($list){
       foreach ($list as $date => $array) {
           
           ?>
           <div class="admin-chat-users-dialog-date" >
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
                  <div class="admin-chat-users-dialog-item">
                      
                      <div class="admin-chat-users-dialog-item-box" >

                          <div class="admin-chat-users-dialog-item-msg" >
                            <span class="admin-chat-users-dialog-item-name" ><?php if($value["chat_messages_id_user"]) echo $Profile->name($get); else echo 'Менеджер'; ?></span>
                            
                            <?php if($value["chat_messages_text"]){ ?>
                            <p class="admin-chat-users-dialog-item-text" ><?php echo nl2br($value["chat_messages_text"]); ?></p>
                            <?php
                            }

                            if($attach["images"]){
                                 foreach ($attach["images"] as $attach_name) {
                                      ?>
                                      <div class="admin-chat-users-dialog-item-attach" >
                                         <a href="<?php echo $config["urlPath"] . "/" . $config["media"]["attach"] . "/" . $attach_name; ?>" target="_blank" ><img src="<?php echo $config["urlPath"] . "/" . $config["media"]["attach"] . "/" . $attach_name; ?>"></a>
                                      </div>
                                      <?php
                                 }
                            }
                            ?>
                          </div>

                         <div class="admin-chat-users-dialog-item-date" ><span><?php echo date( "H:i", strtotime( $value["chat_messages_date"] ) ); ?></span></div>

                     </div>

                  </div>
               <?php

           }

       }

    }

}else{
    ?>

        <div style="display: flex; align-items: center; text-align: center; justify-content: center; height: 100%;" >
            <div>
                <svg width="184" height="136" viewBox="0 0 184 136" ><defs><linearGradient id="dialog-stub_svg__a" x1="100%" x2="0%" y1="0%" y2="100%"><stop offset="0%" stop-color="#BAF8FF"></stop><stop offset="100%" stop-color="#D2D4FF"></stop></linearGradient><linearGradient id="dialog-stub_svg__b" x1="0%" x2="100%" y1="100%" y2="0%"><stop offset="0%" stop-color="#B7F2FF"></stop><stop offset="100%" stop-color="#C1FFE5"></stop></linearGradient><linearGradient id="dialog-stub_svg__c" x1="100%" x2="0%" y1="0%" y2="100%"><stop offset="0%" stop-color="#FFF0BF"></stop><stop offset="100%" stop-color="#FFE0D4"></stop></linearGradient></defs><g fill="none" fill-rule="evenodd"><path fill="#FFF" d="M-88-141h360v592H-88z"></path><g transform="translate(12 8)"><path fill="#FFF" d="M0 3.993A4 4 0 0 1 3.995 0h152.01A3.996 3.996 0 0 1 160 3.993v112.014a4 4 0 0 1-3.995 3.993H3.995A3.996 3.996 0 0 1 0 116.007V3.993z"></path><rect width="24" height="24" x="12" y="8" fill="url(#dialog-stub_svg__a)" rx="4"></rect><path fill="#F5F5F5" d="M71 13H44v6h27zm77 0h-17v6h17zm-35.5 10H44v6h68.5z"></path><circle cx="35" cy="11" r="6" fill="#E6EDFF" stroke="#FFF" stroke-width="2"></circle><rect width="24" height="24" x="12" y="47" fill="url(#dialog-stub_svg__b)" rx="4"></rect><path fill="#F5F5F5" d="M71 52H44v6h27zm77 0h-17v6h17zm-35.5 10H44v6h68.5z"></path><circle cx="35" cy="50" r="6" fill="#E6EDFF" stroke="#FFF" stroke-width="2"></circle><rect width="24" height="24" x="12" y="86" fill="url(#dialog-stub_svg__c)" rx="4"></rect><path fill="#F5F5F5" d="M71 91H44v6h27zm77 0h-17v6h17zm-35.5 10H44v6h68.5z"></path><circle cx="35" cy="89" r="6" fill="#E6EDFF" stroke="#FFF" stroke-width="2"></circle></g></g></svg>
                <h5 style="color: #98a8b4; margin-top: 10px;" >Сообщений нет</h5>
            </div>
        </div>

    <?php
}

?>

</div>

<hr>

<div class="admin-chat-users-dialog-footer" >
    
    <?php if($getMyLocked){ ?>

    <div class="admin-chat-users-dialog-footer-locked-info" >
         <span>Отправка сообщений для данного пользователя ограничена</span>
    </div>    

    <?php } ?>
    
    <div class="admin-chat-users-dialog-footer-flex-box" >
         <div class="admin-chat-users-dialog-footer-flex-box1" >
            
                <div class="admin-chat-users-dialog-footer-attach-change" >
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.46 3h3.08c.29 0 .53 0 .76.03.7.1 1.35.47 1.8 1.03.25.3.4.64.62.96.2.28.5.46.85.48.3.02.58-.01.88.02a3.9 3.9 0 013.53 3.53c.02.18.02.37.02.65v4.04c0 1.09 0 1.96-.06 2.66a5.03 5.03 0 01-.47 1.92 4.9 4.9 0 01-2.15 2.15c-.57.29-1.2.41-1.92.47-.7.06-1.57.06-2.66.06H9.26c-1.09 0-1.96 0-2.66-.06a5.03 5.03 0 01-1.92-.47 4.9 4.9 0 01-2.15-2.15 5.07 5.07 0 01-.47-1.92C2 15.7 2 14.83 2 13.74V9.7c0-.28 0-.47.02-.65a3.9 3.9 0 013.53-3.53c.3-.03.59 0 .88-.02.34-.02.65-.2.85-.48.21-.32.37-.67.61-.96A2.9 2.9 0 019.7 3.03c.23-.03.47-.03.76-.03zm0 1.8l-.49.01a1.1 1.1 0 00-.69.4c-.2.24-.33.56-.52.82A2.9 2.9 0 016.54 7.3c-.28.01-.55-.02-.83 0a2.1 2.1 0 00-1.9 1.91l-.01.53v3.96c0 1.14 0 1.93.05 2.55.05.62.15.98.29 1.26.3.58.77 1.05 1.35 1.35.28.14.64.24 1.26.29.62.05 1.42.05 2.55.05h5.4c1.13 0 1.93 0 2.55-.05.62-.05.98-.15 1.26-.29a3.1 3.1 0 001.35-1.35c.14-.28.24-.64.29-1.26.05-.62.05-1.41.05-2.55V9.21a2.1 2.1 0 00-1.91-1.9c-.28-.03-.55 0-.83-.01a2.9 2.9 0 01-2.22-1.27c-.19-.26-.32-.58-.52-.83a1.1 1.1 0 00-.69-.39 3.92 3.92 0 00-.49-.01h-3.08z" fill="currentColor"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M12 9.8a2.7 2.7 0 100 5.4 2.7 2.7 0 000-5.4zm-4.5 2.7a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0z" fill="currentColor"></path></svg>
                </div>

         </div>
         <div class="admin-chat-users-dialog-footer-flex-box2" >

                <textarea maxlength="5000" class="admin-chat-users-dialog-footer-text admin-chat-users-dialog-footer-send form-control" placeholder="Напишите сообщение..." ></textarea>

         </div>
    </div>

    <div class="admin-chat-users-dialog-footer-attach-list" ></div>

    <input type="file" accept=".jpg,.jpeg,.png" multiple="true" style="display: none;" class="admin-chat-users-dialog-footer-attach-input" />

</div>
 

