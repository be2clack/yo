<?php 
if( !defined('unisitecms') ) exit;

$Profile = new Profile();
$getActiveDialog = getAll('select * from uni_chat_users where chat_users_id_interlocutor=? and chat_users_id_responder=? group by chat_users_id_hash order by chat_users_id desc', [0,0]);
?>

<div class="row">
   <div class="page-header">
      <div class="">
         <div class="row" >
            <div class="col-lg-12" >
                <h2 class="page-header-title">Чат</h2>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="row" >
   <div class="col-lg-3" >
       
      <div class="admin-chat-users" >
          <div class="admin-chat-users-search" >
              <input type="text" class="form-control input-search-users" placeholder="Укажите id, email, телефон или имя пользователя" >
          </div>
          <div class="admin-chat-users-list" >

              <?php if (count($getActiveDialog)) { ?>

              <h5 style="margin-top: 15px; margin-bottom: 0px; color: #828282;" >Диалоги</h5>

              <hr>

              <div class="admin-chat-users-list-scroll" >
              <?php
                foreach ($getActiveDialog as $key => $value) {
                    $idUser = $value['chat_users_id_user'] != 0 ? $value['chat_users_id_user'] : $value['chat_users_id_interlocutor'];
                    $getUser = findOne('uni_clients', 'clients_id=?', [$idUser]);
                    if($getUser){
                    $countMessage = getOne("select count(*) as total from uni_chat_messages where chat_messages_id_hash=? and chat_messages_status=? and chat_messages_id_user!=?", array($value['chat_users_id_hash'],0,0) );
                    ?>
                      <div class="admin-chat-users-list-item" data-hash="<?php echo $value["chat_users_id_hash"]; ?>" data-id="<?php echo $getUser["clients_id"]; ?>" >
                          <div class="admin-chat-users-list-item-avatar" >
                              <img src="<?php echo $Profile->userAvatar($getUser, false); ?>">
                          </div>
                          <div class="admin-chat-users-list-item-name" ><span><?php echo $getUser["clients_name"]; ?> <?php echo $getUser["clients_surname"]; ?></span></div>
                          <div class="admin-chat-users-list-item-count" ><?php if($countMessage['total']){ ?><span><?php echo $countMessage['total']; ?></span><?php } ?></div>
                      </div>
                    <?php
                    }
                }
              ?>
              </div>

              <?php }else{ ?>

                 <div class="icon-subtitle" ><i class="la la-exclamation-circle"></i><span>Диалогов нет</span></div>

              <?php } ?>

          </div>
      </div>

   </div>
   <div class="col-lg-7" >

      <div class="widget has-shadow">

         <div class="widget-body">

            <div class="admin-chat-users-dialog" >
             
                <div style="text-align: center; padding: 90px 0;" >
                    <div>
                    <svg width="184" height="136" viewBox="0 0 184 136" ><defs><linearGradient id="dialog-stub_svg__a" x1="100%" x2="0%" y1="0%" y2="100%"><stop offset="0%" stop-color="#BAF8FF"></stop><stop offset="100%" stop-color="#D2D4FF"></stop></linearGradient><linearGradient id="dialog-stub_svg__b" x1="0%" x2="100%" y1="100%" y2="0%"><stop offset="0%" stop-color="#B7F2FF"></stop><stop offset="100%" stop-color="#C1FFE5"></stop></linearGradient><linearGradient id="dialog-stub_svg__c" x1="100%" x2="0%" y1="0%" y2="100%"><stop offset="0%" stop-color="#FFF0BF"></stop><stop offset="100%" stop-color="#FFE0D4"></stop></linearGradient></defs><g fill="none" fill-rule="evenodd"><path fill="#FFF" d="M-88-141h360v592H-88z"></path><g transform="translate(12 8)"><path fill="#FFF" d="M0 3.993A4 4 0 0 1 3.995 0h152.01A3.996 3.996 0 0 1 160 3.993v112.014a4 4 0 0 1-3.995 3.993H3.995A3.996 3.996 0 0 1 0 116.007V3.993z"></path><rect width="24" height="24" x="12" y="8" fill="url(#dialog-stub_svg__a)" rx="4"></rect><path fill="#F5F5F5" d="M71 13H44v6h27zm77 0h-17v6h17zm-35.5 10H44v6h68.5z"></path><circle cx="35" cy="11" r="6" fill="#E6EDFF" stroke="#FFF" stroke-width="2"></circle><rect width="24" height="24" x="12" y="47" fill="url(#dialog-stub_svg__b)" rx="4"></rect><path fill="#F5F5F5" d="M71 52H44v6h27zm77 0h-17v6h17zm-35.5 10H44v6h68.5z"></path><circle cx="35" cy="50" r="6" fill="#E6EDFF" stroke="#FFF" stroke-width="2"></circle><rect width="24" height="24" x="12" y="86" fill="url(#dialog-stub_svg__c)" rx="4"></rect><path fill="#F5F5F5" d="M71 91H44v6h27zm77 0h-17v6h17zm-35.5 10H44v6h68.5z"></path><circle cx="35" cy="89" r="6" fill="#E6EDFF" stroke="#FFF" stroke-width="2"></circle></g></g></svg>
                    <p></p>
                    </div>
                    <h5 style="color: #98a8b4; margin-top: 10px;" >Выберите диалог или пользователя</h5>
                </div>

            </div>

         </div>

      </div>
      
   </div>
   <div class="col-lg-2" >
       
        <a href="#" class="btn btn-gradient-04" style="width: 100%;" data-toggle="modal" data-target="#modal-add-chat-responder" >Создать рассылку</a>

        <?php
          $getResponders = getAll('select * from uni_chat_responders order by chat_responders_id desc');
          if(count($getResponders)){
             ?>
             <div class="admin-chat-users-list-responders" >
             <?php
                 foreach ($getResponders as $value) {

                     $getCountOpenResponder = (int)getOne('select count(*) as total from uni_chat_messages where chat_messages_id_responder=? and chat_messages_status=?', [$value['chat_responders_id'],1])['total'];

                     ?>
                        <div class="admin-chat-users-list-responder-item" >
                            <div class="admin-chat-users-list-responder-item-delete" data-id="<?php echo $value['chat_responders_id']; ?>" ><i class="la la-trash" ></i></div>
                            <?php if($value['chat_responders_status'] == 0){ ?>
                               <span class="badge badge-warning">В процессе</span>
                            <?php }else{ ?>
                               <span class="badge badge-success">Выполнено</span>
                            <?php } ?>
                            <p><?php echo $value['chat_responders_name'] ?></p>
                            <hr>
                            <div class="admin-chat-users-list-responder-item-stat" >
                                <div class="container" >
                                <div class="row" >
                                    <div class="col-6" >
                                        <span title="Отправлено" ><i class="la la-paper-plane"></i></span>
                                        <strong><?php echo $value['chat_responders_count_users']; ?></strong>
                                    </div>
                                    <div class="col-6" >
                                        <span title="Открыто" ><i class="la la-envelope"></i></span>
                                        <strong><?php echo $getCountOpenResponder; ?></strong>
                                    </div>                                    
                                </div>
                                </div>
                            </div>
                        </div>
                     <?php
                 }
             ?>
             </div>
             <?php
          }else{
             ?>
             <div class="icon-subtitle" ><i class="la la-exclamation-circle"></i><span>Рассылок нет</span></div>
             <?php
          }
        ?>

   </div>
</div>

<div id="modal-add-chat-responder" class="modal fade">
   <div class="modal-dialog" style="max-width: 900px;" >
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Создать рассылку</h4>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">close</span>
            </button>
         </div>
         <div class="modal-body">
            
               <form method="post" class="chat-responder-form" >

                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label">Название рассылки</label>
                    <div class="col-lg-9">
                         <input type="text" class="form-control" name="name_responder" >
                    </div>
                  </div>
  
                  <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label">Сообщение</label>
                    <div class="col-lg-9">
                         <textarea class="form-control" name="text_responder" style="min-height: 200px;" ></textarea>
                    </div>
                  </div>
                  
               </form>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-shadow" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn btn-primary chat-responder-send">Создать</button>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript" src="include/modules/chat/script.js"></script>