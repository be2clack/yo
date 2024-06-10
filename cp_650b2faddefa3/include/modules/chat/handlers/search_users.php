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

if(!empty($_POST["q"])) $q = clearSearch($_POST["q"]); else $q = "";

$Profile = new Profile();


if($q){

$searchUsers = getAll("SELECT * FROM uni_clients WHERE clients_email LIKE '%$q%' or clients_phone LIKE '%$q%' or clients_name LIKE '%$q%' or clients_surname LIKE '%$q%' or clients_id LIKE '$q' Order by clients_name ASC");

?>
<h5 style="margin-top: 15px; margin-bottom: 0px; color: #828282;" >Найдено <strong><?php echo count($searchUsers); ?></strong></h5>
<hr>
<div class="admin-chat-users-list-scroll" >
<?php

if(count($searchUsers)){

    foreach ($searchUsers as $value) {
        ?>
          <div class="admin-chat-users-list-item" data-hash="<?php echo md5('support'.$value["clients_id"]); ?>" data-id="<?php echo $value["clients_id"]; ?>" >
              <div class="admin-chat-users-list-item-avatar" >
                  <img src="<?php echo $Profile->userAvatar($value, false); ?>">
              </div>
              <div class="admin-chat-users-list-item-name" ><span><?php echo $value["clients_name"]; ?> <?php echo $value["clients_surname"]; ?></span></div>
          </div>        
        <?php
    }

}

?>
</div>
<?php

}else{

    $getActiveDialog = getAll('select * from uni_chat_users where chat_users_id_interlocutor=? group by chat_users_id_hash', [0]);

    if (count($getActiveDialog)) {
        ?>
        <h5 style="margin-top: 15px; margin-bottom: 0px; color: #828282;" >Диалоги</h5>
        <hr>
        <div class="admin-chat-users-list-scroll" >
        <?php
            foreach ($getActiveDialog as $key => $value) {
                $idUser = $value['chat_users_id_user'] != 0 ? $value['chat_users_id_user'] : $value['chat_users_id_interlocutor'];
                $getUser = findOne('uni_clients', 'clients_id=?', [$idUser]);
                $countMessage = getOne("select count(*) as total from uni_chat_messages where chat_messages_id_hash=? and chat_messages_status=? and chat_messages_id_user!=?", array($value['chat_users_id_hash'],0,0) );
                ?>
                  <div class="admin-chat-users-list-item" data-hash="<?php echo $value["chat_users_id_hash"]; ?>" data-id="<?php echo $getUser["clients_id"]; ?>" >
                      <div class="admin-chat-users-list-item-avatar" >
                          <img src="<?php echo $Profile->userAvatar($getUser); ?>">
                      </div>
                      <div class="admin-chat-users-list-item-name" ><span><?php echo $getUser["clients_name"]; ?> <?php echo $getUser["clients_surname"]; ?></span></div>
                      <div class="admin-chat-users-list-item-count" ><?php if($countMessage['total']){ ?><span><?php echo $countMessage['total']; ?></span><?php } ?></div>
                  </div>
                <?php
            }
        ?>
        </div>
        <?php 
    }else{
        ?>
        <div class="icon-subtitle" ><i class="la la-exclamation-circle"></i><span>Диалогов нет</span></div>
        <?php
    }   

}

?>
 

