<?php
/**
 * UniSite CMS
 *
 * @copyright   2018 Artur Zhur
 * @link        https://unisitecms.ru
 * @author      Artur Zhur
 *
 */

class Profile{

    function oneUser($query = "", $param = []){

        return getOne("SELECT * FROM uni_clients LEFT JOIN `uni_city` ON `uni_city`.city_id = `uni_clients`.clients_city_id $query ",$param);

    }

    function userLink($data = [], $shopConditions = true){

        $Shop = new Shop();

        if($shopConditions){
            $getShop = $Shop->getShop(['user_id'=>$data["clients_id"],'conditions'=>true]);

            if($getShop){
                return $Shop->linkShop($getShop["clients_shops_id_hash"]);
            }
        }

        return _link("user/" . $data["clients_id_hash"]);

    }

    function getMessage($user_id=0,$id_hash=""){

        $results = [];
        $results['total'] = 0;

        if(!$user_id) return 0;

        if(!$id_hash){
            $groupBy = [];
            $getAll = getAll("select * from uni_chat_users where chat_users_id_user=?", array($user_id));
            if(count($getAll)){

               foreach ($getAll as $key => $value) {

                  if($value["chat_users_id_interlocutor"]){
                      $get = findOne("uni_clients", "clients_id=?", [$value["chat_users_id_interlocutor"]]);
                      if( $get ){
                          $groupBy[ $value["chat_users_id_hash"] ] = $value["chat_users_id_hash"];
                      }
                  }else{
                      $groupBy[ $value["chat_users_id_hash"] ] = $value["chat_users_id_hash"];
                  }

               }

               if( count($groupBy) ){
                   foreach ($groupBy as $id_hash) {

                      $count = (int)getOne("select count(*) as total from uni_chat_messages where chat_messages_id_hash=? and chat_messages_status=? and chat_messages_id_user!=?", array($id_hash,0,$user_id) )["total"];
                      
                      if($count){
                        $results['hash_counts'][$id_hash] = $count;
                      }

                      $results['total'] += $count;

                   }
               }

            }
            return $results;
        }else{
            $results['total'] = (int)getOne("select count(*) as total from uni_chat_messages where chat_messages_id_hash=? and chat_messages_status=? and chat_messages_id_user!=?", array($id_hash,0,$user_id) )["total"];
            return $results;
        }

    }

    function activation(){
      global $config,$settings;

      $Subscription = new Subscription();

      if(!empty($_GET["activation_hash"])){
         $get = findOne("uni_clients_hash_email","clients_hash_email_hash=?",[clear($_GET['activation_hash'])]);
         if($get){

          $getUser = findOne("uni_clients", "clients_id=?", [$get["clients_hash_email_id_user"]]);

          if(!$getUser["clients_email"]){

               if($settings["bonus_program"]["email"]["status"] && $settings["bonus_program"]["email"]["price"]){
                   $this->actionBalance(array("id_user"=>$get["clients_hash_email_id_user"],"summa"=>$settings["bonus_program"]["email"]["price"],"title"=>$settings["bonus_program"]["email"]["name"],"id_order"=>generateOrderId(),"email" => $get["clients_hash_email_email"],"name" => $getUser->clients_name, "note" => $settings["bonus_program"]["email"]["name"]),"+");             
               }

          }
          
          update("UPDATE uni_clients SET clients_email=? WHERE clients_id=?", [$get["clients_hash_email_email"],$get["clients_hash_email_id_user"]]);
          $Subscription->add(array("email"=>$get["clients_hash_email_email"],"user_id"=>$get["clients_hash_email_id_user"],"name"=>$getUser["clients_name"],"status" => 1));

          update("delete from uni_clients_hash_email where clients_hash_email_hash=?", [clear($_GET['activation_hash'])]);
           
         }
      }

    }

    function checkAuth(){
      
      $Shop = new Shop();

      if(isset($_SESSION['profile']['id'])){
         $get = findOne("uni_clients", "clients_id=?", [intval($_SESSION['profile']['id'])]);
      }elseif(isset($_COOKIE["tokenAuth"])){
         $get = findOne("uni_clients", "clients_cookie_token=?", [clear($_COOKIE["tokenAuth"])]);
      }

      if($get){

         $_SESSION['profile']['id'] = $get->clients_id;

         if($get->clients_status == 2 || $get->clients_status == 3){
           update('update uni_clients set clients_cookie_token=? where clients_id=?',['',$get->clients_id]);
           unset($_SESSION['profile']); 
           setcookie("tokenAuth", "", time() - 2592000);
         }else{
           $_SESSION["profile"]["data"] = $get;
           $_SESSION["profile"]["tariff"] = $this->getOrderTariff($get->clients_id);
           $getShop = $Shop->getShop(['user_id'=>$get->clients_id,'conditions'=>false]);
           if($getShop){
              $_SESSION["profile"]['shop'] = $getShop;
           }else{
              $_SESSION["profile"]['shop'] = [];
           }
           unset($_SESSION["profile"]["data"]["clients_pass"]);
           unset($_SESSION["profile"]["data"]["clients_cookie_token"]);
         }

      }else{
         unset($_SESSION['profile']); 
         setcookie("tokenAuth", "", time() - 2592000);
      }

    }

    function chatDialog($id_hash = 0, $support = 0){
      global $config, $settings;

      $Ads = new Ads();
      $Profile = new Profile();
      $Main = new Main();
      $ULang = new ULang();
      $Admin = new Admin();

      if($id_hash){

        if(!$support){

            $getChatUser = getOne("select * from uni_chat_users where chat_users_id_hash=? and chat_users_id_user=?", array($id_hash,intval($_SESSION['profile']['id'])) );

            if($getChatUser["chat_users_id_ad"]){

                $getAd = $Ads->get("ads_id=?", [$getChatUser["chat_users_id_ad"]]);

                $getAd["ads_images"] = $Ads->getImages($getAd["ads_images"]);

                if( $id_hash == md5( $getChatUser["chat_users_id_ad"] . $getChatUser["chat_users_id_interlocutor"] ) || $id_hash == md5( $getChatUser["chat_users_id_ad"] . $getChatUser["chat_users_id_user"] ) ){

                  update("update uni_chat_messages set chat_messages_status=? where chat_messages_id_hash=? and chat_messages_id_user!=?", array(1,$id_hash,$_SESSION['profile']['id']));

                  $getDialog = getAll("select * from uni_chat_messages where chat_messages_id_hash=? order by chat_messages_date asc", array($id_hash) );

                  $getLocked = findOne( "uni_clients_blacklist", "clients_blacklist_user_id=? and clients_blacklist_user_id_locked=?", array(intval($_SESSION['profile']['id']),$getChatUser["chat_users_id_interlocutor"]) );

                  $getMyLocked = findOne( "uni_clients_blacklist", "clients_blacklist_user_id=? and clients_blacklist_user_id_locked=?", array( $getChatUser["chat_users_id_interlocutor"],intval($_SESSION['profile']['id'])) );

                  ob_start();
                  require $config["template_path"] . "/include/chat_dialog.php";
                  $list_dialog = ob_get_clean();

                  return $list_dialog;

                }

            }else{

                if( $id_hash == md5( $getChatUser["chat_users_id_user"] . $getChatUser["chat_users_id_interlocutor"] ) || $id_hash == md5( $getChatUser["chat_users_id_interlocutor"] . $getChatUser["chat_users_id_user"] ) ){

                  update("update uni_chat_messages set chat_messages_status=? where chat_messages_id_hash=? and chat_messages_id_user!=?", array(1,$id_hash,$_SESSION['profile']['id']));

                  $getDialog = getAll("select * from uni_chat_messages where chat_messages_id_hash=? order by chat_messages_date asc", array($id_hash) );

                  $getLocked = findOne( "uni_clients_blacklist", "clients_blacklist_user_id=? and clients_blacklist_user_id_locked=?", array(intval($_SESSION['profile']['id']),$getChatUser["chat_users_id_interlocutor"]) );

                  $getMyLocked = findOne( "uni_clients_blacklist", "clients_blacklist_user_id=? and clients_blacklist_user_id_locked=?", array( $getChatUser["chat_users_id_interlocutor"],intval($_SESSION['profile']['id'])) );

                  $getUser = findOne("uni_clients", "clients_id=?", [$getChatUser["chat_users_id_interlocutor"]]);

                  ob_start();
                  require $config["template_path"] . "/include/chat_dialog_users.php";
                  $list_dialog = ob_get_clean();

                  return $list_dialog;

                }                

            }

        }else{

            $getChatUser = getOne("select * from uni_chat_users where chat_users_id_hash=? and chat_users_id_user=?", array($id_hash,intval($_SESSION['profile']['id'])) );

            if($id_hash == md5('support' . $_SESSION['profile']['id'])){

                update("update uni_chat_messages set chat_messages_status=? where chat_messages_id_hash=? and chat_messages_id_user!=?", array(1,$id_hash,$_SESSION['profile']['id']));

                $getDialog = getAll("select * from uni_chat_messages where chat_messages_id_hash=? order by chat_messages_date asc", array($id_hash) );

                $getMyLocked = findOne( "uni_clients_blacklist", "clients_blacklist_user_id=? and clients_blacklist_user_id_locked=?", array( $getChatUser["chat_users_id_interlocutor"],intval($_SESSION['profile']['id'])) );

                ob_start();
                require $config["template_path"] . "/include/chat_dialog_support.php";
                $list_dialog = ob_get_clean();

                return $list_dialog;

            }

        }

      }else{

         $get = getOne("select count(*) as total from uni_chat_users where chat_users_id_user=? group by chat_users_id_hash", array(intval($_SESSION['profile']['id'])) );

         if($get["total"]){
           return '
            <div class="chat-dialog-empty" >
                <div>
                <svg width="184" height="136" viewBox="0 0 184 136" ><defs><linearGradient id="dialog-stub_svg__a" x1="100%" x2="0%" y1="0%" y2="100%"><stop offset="0%" stop-color="#BAF8FF"></stop><stop offset="100%" stop-color="#D2D4FF"></stop></linearGradient><linearGradient id="dialog-stub_svg__b" x1="0%" x2="100%" y1="100%" y2="0%"><stop offset="0%" stop-color="#B7F2FF"></stop><stop offset="100%" stop-color="#C1FFE5"></stop></linearGradient><linearGradient id="dialog-stub_svg__c" x1="100%" x2="0%" y1="0%" y2="100%"><stop offset="0%" stop-color="#FFF0BF"></stop><stop offset="100%" stop-color="#FFE0D4"></stop></linearGradient></defs><g fill="none" fill-rule="evenodd"><path fill="#FFF" d="M-88-141h360v592H-88z"></path><g transform="translate(12 8)"><path fill="#FFF" d="M0 3.993A4 4 0 0 1 3.995 0h152.01A3.996 3.996 0 0 1 160 3.993v112.014a4 4 0 0 1-3.995 3.993H3.995A3.996 3.996 0 0 1 0 116.007V3.993z"></path><rect width="24" height="24" x="12" y="8" fill="url(#dialog-stub_svg__a)" rx="4"></rect><path fill="#F5F5F5" d="M71 13H44v6h27zm77 0h-17v6h17zm-35.5 10H44v6h68.5z"></path><circle cx="35" cy="11" r="6" fill="#E6EDFF" stroke="#FFF" stroke-width="2"></circle><rect width="24" height="24" x="12" y="47" fill="url(#dialog-stub_svg__b)" rx="4"></rect><path fill="#F5F5F5" d="M71 52H44v6h27zm77 0h-17v6h17zm-35.5 10H44v6h68.5z"></path><circle cx="35" cy="50" r="6" fill="#E6EDFF" stroke="#FFF" stroke-width="2"></circle><rect width="24" height="24" x="12" y="86" fill="url(#dialog-stub_svg__c)" rx="4"></rect><path fill="#F5F5F5" d="M71 91H44v6h27zm77 0h-17v6h17zm-35.5 10H44v6h68.5z"></path><circle cx="35" cy="89" r="6" fill="#E6EDFF" stroke="#FFF" stroke-width="2"></circle></g></g></svg>
                <p>'.$ULang->t("Выберите чат для общения").'</p>
                </div>
            </div>
           ';
         }else{
           return '
            <div class="chat-dialog-empty" >
                <div>
                <svg width="184" height="136" viewBox="0 0 184 136" ><defs><linearGradient id="dialog-stub_svg__a" x1="100%" x2="0%" y1="0%" y2="100%"><stop offset="0%" stop-color="#BAF8FF"></stop><stop offset="100%" stop-color="#D2D4FF"></stop></linearGradient><linearGradient id="dialog-stub_svg__b" x1="0%" x2="100%" y1="100%" y2="0%"><stop offset="0%" stop-color="#B7F2FF"></stop><stop offset="100%" stop-color="#C1FFE5"></stop></linearGradient><linearGradient id="dialog-stub_svg__c" x1="100%" x2="0%" y1="0%" y2="100%"><stop offset="0%" stop-color="#FFF0BF"></stop><stop offset="100%" stop-color="#FFE0D4"></stop></linearGradient></defs><g fill="none" fill-rule="evenodd"><path fill="#FFF" d="M-88-141h360v592H-88z"></path><g transform="translate(12 8)"><path fill="#FFF" d="M0 3.993A4 4 0 0 1 3.995 0h152.01A3.996 3.996 0 0 1 160 3.993v112.014a4 4 0 0 1-3.995 3.993H3.995A3.996 3.996 0 0 1 0 116.007V3.993z"></path><rect width="24" height="24" x="12" y="8" fill="url(#dialog-stub_svg__a)" rx="4"></rect><path fill="#F5F5F5" d="M71 13H44v6h27zm77 0h-17v6h17zm-35.5 10H44v6h68.5z"></path><circle cx="35" cy="11" r="6" fill="#E6EDFF" stroke="#FFF" stroke-width="2"></circle><rect width="24" height="24" x="12" y="47" fill="url(#dialog-stub_svg__b)" rx="4"></rect><path fill="#F5F5F5" d="M71 52H44v6h27zm77 0h-17v6h17zm-35.5 10H44v6h68.5z"></path><circle cx="35" cy="50" r="6" fill="#E6EDFF" stroke="#FFF" stroke-width="2"></circle><rect width="24" height="24" x="12" y="86" fill="url(#dialog-stub_svg__c)" rx="4"></rect><path fill="#F5F5F5" d="M71 91H44v6h27zm77 0h-17v6h17zm-35.5 10H44v6h68.5z"></path><circle cx="35" cy="89" r="6" fill="#E6EDFF" stroke="#FFF" stroke-width="2"></circle></g></g></svg>
                <p>'.$ULang->t("У вас пока нет диалогов").'</p>
                </div>
            </div>
           ';
         }


      }      

    }

    function getUserLocked( $user_id=0, $locked_id=0 ){
       return findOne( "uni_clients_blacklist", "clients_blacklist_user_id=? and clients_blacklist_user_id_locked=?", array($user_id,$locked_id) );
    }

    function getCountFavorites($id_ad=0){
       return (int)getOne( "select count(*) as total from uni_favorites where favorites_id_ad=?", array($id_ad) )["total"];
    }

    function sendChat($param = array()){

       global $config, $settings;

       $Ads = new Ads();

       $messages = [];
       $attach = [];

       $getUserLocked = $this->getUserLocked($param["user_to"],$param["user_from"]);

       if($getUserLocked) return false;

       if($param["attach"]){

          foreach ($param["attach"] as $name) {
              if(file_exists($config["basePath"] . "/" . $config["media"]["temp_images"] . "/" . $name)){
                @copy( $config["basePath"] . "/" . $config["media"]["temp_images"] . "/" . $name , $config["basePath"] . "/" . $config["media"]["attach"] . "/" . $name );
                $attach['images'][] = $name;
              }
          }
          
       }

       if($param["voice"]){
          $attach['voice'] = $param["voice"];
          $attach['duration'] = $param["duration"];
       }

       if($param["text"] || $attach || $param["action"]){

           if($param["text"]) $encrypt_text = encrypt($param["text"]);
           
           if(!$param["support"]){

               if($param["id_ad"]){

                   $getAd = $Ads->get("ads_id=".intval($param["id_ad"]) );

                   if(!$param["id_hash"]){
                       
                       $param["id_hash"] = md5( $param["id_ad"] . $param["user_from"] );

                   }else{

                       if( $param["id_hash"] != md5( $param["id_ad"] . $param["user_from"] ) && $param["id_hash"] != md5( $param["id_ad"] . $param["user_to"] ) ){
                          exit;
                       }

                   }

               }else{

                   if(!$param["id_hash"]){
                       
                       $param["id_hash"] = md5( $param["user_to"] . $param["user_from"] );

                   }else{

                       if( $param["id_hash"] != md5( $param["user_to"] . $param["user_from"] ) && $param["id_hash"] != md5( $param["user_from"] . $param["user_to"] ) ){
                          exit;
                       }

                   }

               }

           }else{

               if( $param["id_hash"] != md5( 'support' . $param["user_from"] ) && $param["id_hash"] != md5( 'support' . $param["user_to"] ) ){
                  exit;
               }

           }


           $getChatUserFrom = findOne("uni_chat_users", "chat_users_id_hash=? and chat_users_id_user=?", [$param["id_hash"],$param["user_from"]]);

           if(!$getChatUserFrom){

               if(!$param["action"]){
               insert("INSERT INTO uni_chat_users(chat_users_id_ad,chat_users_id_user,chat_users_id_hash,chat_users_id_interlocutor,chat_users_id_responder)VALUES(?,?,?,?,?)", array(intval($param["id_ad"]),$param["user_from"], $param["id_hash"], $param["user_to"],intval($param["id_responder"])));
               }

           }

           $getChatUserTo = findOne("uni_chat_users", "chat_users_id_hash=? and chat_users_id_user=?", [$param["id_hash"],$param["user_to"]]);

           if(!$getChatUserTo){
               insert("INSERT INTO uni_chat_users(chat_users_id_ad,chat_users_id_user,chat_users_id_hash,chat_users_id_interlocutor,chat_users_id_responder)VALUES(?,?,?,?,?)", array(intval($param["id_ad"]),$param["user_to"], $param["id_hash"], $param["user_from"], intval($param["id_responder"])));
           }

           insert("INSERT INTO uni_chat_messages(chat_messages_text,chat_messages_date,chat_messages_id_hash,chat_messages_id_user,chat_messages_action,chat_messages_attach,chat_messages_id_responder)VALUES(?,?,?,?,?,?,?)", array($encrypt_text, date("Y-m-d H:i:s"),$param["id_hash"],$param["user_from"],intval($param["action"]),json_encode($attach),intval($param["id_responder"])));

           
           if($param["firebase"] && $param["action"] == 0){
               if(!$param["support"]){
                   $getUserFrom = findOne('uni_clients','clients_id=?', [$param["user_from"]]);  
                   $getMessageToken = findOne('uni_clients_fcm_tokens','user_id=?', [$param["user_to"]]);  
                   if($getMessageToken) $messages[$getMessageToken['token']] = ['title'=>$getUserFrom['clients_name'], 'text'=>custom_substr($param["text"], 255, "..."), 'screen'=>'chat', 'id_hash'=>$param["id_hash"], 'only_notification'=>false, 'support'=>$param["support"]]; 
               }else{
                   $getMessageToken = findOne('uni_clients_fcm_tokens','user_id=?', [$param["user_to"]]);  
                   if($getMessageToken) $messages[$getMessageToken['token']] = ['title'=>$settings['site_name'], 'text'=>custom_substr($param["text"], 255, "..."), 'screen'=>'chat', 'id_hash'=>$param["id_hash"], 'only_notification'=>false, 'support'=>$param["support"]];
               }

               if($messages) sendMessageFirebase($messages);
           }

       }

    }

   function setMode(){
     if($_SESSION['profile']['id']){
        update("UPDATE uni_clients SET clients_datetime_view=NOW() WHERE clients_id=?", array( intval($_SESSION['profile']['id']) ));
     }  
   }

   function chatUsers( $chat_users_id_hash = "", $newMessage = false ){
       global $config;

       $Ads = new Ads();
       $ULang = new ULang();

       $listUsers = [];

       $get = getAll("select * from uni_chat_users where chat_users_id_user=? order by chat_users_id desc",[intval($_SESSION['profile']['id'])]);

       if( count($get) ){
           foreach ($get as $key => $value) {
              $listUsers[ $value["chat_users_id_hash"] ] = $value;
           }
       }

       if(count($listUsers)){
          foreach ($listUsers as $key => $value) {

             $getMsg = getOne("select * from uni_chat_messages where chat_messages_id_hash=? order by chat_messages_date desc", array($value["chat_users_id_hash"]));

             if($getMsg || $newMessage){

                 $getMsg["chat_messages_text"] = decrypt($getMsg["chat_messages_text"]);

                 if( $chat_users_id_hash == $value["chat_users_id_hash"] ){
                    $active_user = 'class="active"';
                 }else{
                    $active_user = '';
                 }

                 $getAd = $Ads->get("ads_id=?",[$value["chat_users_id_ad"]]);

                 if($getAd){

                 $getAd["ads_images"] = $Ads->getImages($getAd["ads_images"]);

                 if($value["chat_users_id_interlocutor"] == $_SESSION['profile']['id']){

                     ?>
                       <div data-id="<?php echo $value["chat_users_id_hash"]; ?>" <?php echo $active_user; ?> >

                          <div class="module-chat-users-img" >
                            <img src="<?php echo Exists($config["media"]["small_image_ads"],$getAd["ads_images"][0],$config["media"]["no_image"]); ?>" >
                          </div>
                          <div class="module-chat-users-info" >
                             <span class="module-chat-users-info-date" ><?php echo datetime_format($getMsg["chat_messages_date"], false); ?></span>
                             <p class="module-chat-users-info-client" ><?php echo custom_substr($this->name($getAd),10, "..."); ?></p>
                             <p class="module-chat-users-info-title" ><?php echo custom_substr($getAd["ads_title"],20, "..."); ?></p>
                             <p class="module-chat-users-info-msg" >
                              <?php 
                              
                              if($getMsg["chat_messages_action"] == 0){

                                  if($getMsg["chat_messages_id_user"] == $value["chat_users_id_user"]){ echo $ULang->t("Вы:").' '; }

                                  if($getMsg["chat_messages_text"]){
                                       echo custom_substr($getMsg["chat_messages_text"], 20, "...");
                                  }else{

                                       if($getMsg["chat_messages_attach"]){
                                          $attach = json_decode($getMsg["chat_messages_attach"], true);
                                          if($attach['voice']){
                                              echo custom_substr($ULang->t("Голосовое"), 20, "...");
                                          }elseif($attach['images']){
                                              echo custom_substr($ULang->t("Фото"), 20, "...");
                                          }
                                       }

                                  }

                              }elseif($getMsg["chat_messages_action"] == 1 && intval($_SESSION['profile']['id']) != $getMsg["chat_messages_id_user"]){

                                  echo custom_substr($ULang->t("Покупатель добавил объявление в избранное"), 20, "...");

                              }elseif($getMsg["chat_messages_action"] == 2 && intval($_SESSION['profile']['id']) != $getMsg["chat_messages_id_user"]){

                                  echo custom_substr($ULang->t("Ваш номер просмотрели"), 20, "...");

                              }elseif($getMsg["chat_messages_action"] == 3 && intval($_SESSION['profile']['id']) != $getMsg["chat_messages_id_user"]){

                                  echo custom_substr($ULang->t("Оформление заказа"), 20, "...");

                              }elseif($getMsg["chat_messages_action"] == 4 && intval($_SESSION['profile']['id']) != $getMsg["chat_messages_id_user"]){

                                  echo custom_substr($ULang->t("У вас новый отзыв"), 20, "...");

                              }elseif($getMsg["chat_messages_action"] == 5 && intval($_SESSION['profile']['id']) != $getMsg["chat_messages_id_user"]){

                                  echo custom_substr($ULang->t("Вы победили в аукционе"), 20, "...");

                              }elseif($getMsg["chat_messages_action"] == 6 && intval($_SESSION['profile']['id']) != $getMsg["chat_messages_id_user"]){

                                  echo custom_substr($ULang->t("Ваша ставка перебита"), 20, "...");

                              }elseif($getMsg["chat_messages_action"] == 7 && intval($_SESSION['profile']['id']) != $getMsg["chat_messages_id_user"]){

                                  echo custom_substr($ULang->t("Оформление заказа на бронирование"), 20, "...");

                              }elseif($getMsg["chat_messages_action"] == 8 && intval($_SESSION['profile']['id']) != $getMsg["chat_messages_id_user"]){

                                  echo custom_substr($ULang->t("Оформление заказа на аренду"), 20, "...");

                              }
                              
                              ?>
                             </p>
                             
                             <span class="module-chat-users-info-view" >
                                    <div class="chat-users-info-read" <?php if($getMsg["chat_messages_status"] && intval($_SESSION['profile']['id']) == $getMsg["chat_messages_id_user"]){ echo 'style="display: block"'; } ?> ><svg width="16" height="16" viewBox="0 0 16 16"><path fill="#77c226" fill-rule="evenodd" d="M11.226 3.5l.748.664-6.924 8.164L-.022 8.27l.644-.82 4.328 3.486L11.226 3.5zm4 0l.748.664-6.924 8.164-.776-.643.676-.749L15.226 3.5z"></path></svg></div>
                                    <div class="chat-users-info-notread" <?php if(!$getMsg["chat_messages_status"] && intval($_SESSION['profile']['id']) == $getMsg["chat_messages_id_user"]){ echo 'style="display: block"'; } ?> ><svg width="16" height="16" viewBox="0 0 16 16"><path fill="#77c226" fill-rule="evenodd" d="M13.248 3.5l.748.664-6.924 8.164L2 8.215l.644-.765 4.328 3.486z"></path></svg></div>
                             </span>

                             <?php echo $this->countChatMessages($value["chat_users_id_hash"],$_SESSION['profile']['id']); ?>

                          </div>

                          <div class="clr" ></div>
                        
                       </div>
                     <?php

                 }else{

                  $get = findOne("uni_clients", "clients_id=?", [$value["chat_users_id_interlocutor"]]);

                  if($get){

                     ?>
                       <div data-id="<?php echo $value["chat_users_id_hash"]; ?>" <?php echo $active_user; ?> >

                          <div class="module-chat-users-img" >
                            <img src="<?php echo Exists($config["media"]["small_image_ads"],$getAd["ads_images"][0],$config["media"]["no_image"]); ?>" >
                          </div>
                          <div class="module-chat-users-info" >
                             <span class="module-chat-users-info-date" ><?php echo datetime_format($getMsg["chat_messages_date"], false); ?></span>
                             <p class="module-chat-users-info-client" ><?php echo custom_substr($this->name($get),10, "..."); ?></p>
                             <p class="module-chat-users-info-title" ><?php echo custom_substr($getAd["ads_title"],20, "..."); ?></p>
                             <p class="module-chat-users-info-msg" >
                              <?php 
                              
                              if($getMsg["chat_messages_action"] == 0){

                                  if($getMsg["chat_messages_id_user"] == $value["chat_users_id_user"]){ echo 'Вы: '; }

                                  if($getMsg["chat_messages_text"]){
                                       echo custom_substr($getMsg["chat_messages_text"], 20, "...");
                                  }else{

                                       if($getMsg["chat_messages_attach"]){
                                          $attach = json_decode($getMsg["chat_messages_attach"], true);
                                          if($attach['voice']){
                                              echo custom_substr($ULang->t("Голосовое"), 20, "...");
                                          }elseif($attach['images']){
                                              echo custom_substr($ULang->t("Фото"), 20, "...");
                                          }
                                       }

                                  }

                              }elseif($getMsg["chat_messages_action"] == 1 && intval($_SESSION['profile']['id']) != $getMsg["chat_messages_id_user"]){

                                  echo custom_substr($ULang->t("Покупатель добавил объявление в избранное"), 20, "...");

                              }elseif($getMsg["chat_messages_action"] == 2 && intval($_SESSION['profile']['id']) != $getMsg["chat_messages_id_user"]){

                                  echo custom_substr($ULang->t("Ваш номер просмотрели"), 20, "...");

                              }elseif($getMsg["chat_messages_action"] == 3 && intval($_SESSION['profile']['id']) != $getMsg["chat_messages_id_user"]){

                                  echo custom_substr($ULang->t("Оформление заказа"), 20, "...");

                              }elseif($getMsg["chat_messages_action"] == 4 && intval($_SESSION['profile']['id']) != $getMsg["chat_messages_id_user"]){

                                  echo custom_substr($ULang->t("У вас новый отзыв"), 20, "...");

                              }elseif($getMsg["chat_messages_action"] == 5 && intval($_SESSION['profile']['id']) != $getMsg["chat_messages_id_user"]){

                                  echo custom_substr($ULang->t("Вы победили в аукционе"), 20, "...");

                              }elseif($getMsg["chat_messages_action"] == 6 && intval($_SESSION['profile']['id']) != $getMsg["chat_messages_id_user"]){

                                  echo custom_substr($ULang->t("Ваша ставка перебита"), 20, "...");

                              }elseif($getMsg["chat_messages_action"] == 7 && intval($_SESSION['profile']['id']) != $getMsg["chat_messages_id_user"]){

                                  echo custom_substr($ULang->t("Оформление заказа на бронирование"), 20, "...");

                              }elseif($getMsg["chat_messages_action"] == 8 && intval($_SESSION['profile']['id']) != $getMsg["chat_messages_id_user"]){

                                  echo custom_substr($ULang->t("Оформление заказа на аренду"), 20, "...");

                              }
                              
                              ?>
                             </p>

                             <span class="module-chat-users-info-view" >
                                    <div class="chat-users-info-read" <?php if($getMsg["chat_messages_status"] && intval($_SESSION['profile']['id']) == $getMsg["chat_messages_id_user"]){ echo 'style="display: block"'; } ?> ><svg width="16" height="16" viewBox="0 0 16 16"><path fill="#77c226" fill-rule="evenodd" d="M11.226 3.5l.748.664-6.924 8.164L-.022 8.27l.644-.82 4.328 3.486L11.226 3.5zm4 0l.748.664-6.924 8.164-.776-.643.676-.749L15.226 3.5z"></path></svg></div>
                                    <div class="chat-users-info-notread" <?php if(!$getMsg["chat_messages_status"] && intval($_SESSION['profile']['id']) == $getMsg["chat_messages_id_user"]){ echo 'style="display: block"'; } ?> ><svg width="16" height="16" viewBox="0 0 16 16"><path fill="#77c226" fill-rule="evenodd" d="M13.248 3.5l.748.664-6.924 8.164L2 8.215l.644-.765 4.328 3.486z"></path></svg></div>
                             </span>

                             <?php echo $this->countChatMessages($value["chat_users_id_hash"],$_SESSION['profile']['id']); ?>

                          </div>

                          <div class="clr" ></div>

                       </div>
                     <?php 
                     }             

                 }

                 }else{

                     if($value["chat_users_id_interlocutor"] == $_SESSION['profile']['id']){

                      $get = findOne("uni_clients", "clients_id=?", [$value["chat_users_id_user"]]);

                      if($get){

                         ?>
                           <div data-id="<?php echo $value["chat_users_id_hash"]; ?>" <?php echo $active_user; ?> >

                              <div class="module-chat-users-img" >
                                <img src="<?php echo $this->userAvatar($get); ?>" >
                              </div>
                              <div class="module-chat-users-info" >
                                 <span class="module-chat-users-info-date" ><?php echo datetime_format($getMsg["chat_messages_date"], false); ?></span>
                                 <p class="module-chat-users-info-client" ><?php echo custom_substr($this->name($get),10, "..."); ?></p>
                                 <p class="module-chat-users-info-msg" >
                                     <?php
                                          if($getMsg["chat_messages_id_user"] == $value["chat_users_id_user"]){ echo 'Вы: '; }

                                          if($getMsg["chat_messages_text"]){
                                               echo custom_substr($getMsg["chat_messages_text"], 20, "...");
                                          }else{

                                               if($getMsg["chat_messages_attach"]){
                                                  $attach = json_decode($getMsg["chat_messages_attach"], true);
                                                  if($attach['voice']){
                                                      echo custom_substr($ULang->t("Голосовое"), 20, "...");
                                                  }elseif($attach['images']){
                                                      echo custom_substr($ULang->t("Фото"), 20, "...");
                                                  }
                                               }

                                          }
                                     ?>
                                 </p>

                                 <span class="module-chat-users-info-view" >
                                    <div class="chat-users-info-read" <?php if($getMsg["chat_messages_status"] && intval($_SESSION['profile']['id']) == $getMsg["chat_messages_id_user"]){ echo 'style="display: block"'; } ?> ><svg width="16" height="16" viewBox="0 0 16 16"><path fill="#77c226" fill-rule="evenodd" d="M11.226 3.5l.748.664-6.924 8.164L-.022 8.27l.644-.82 4.328 3.486L11.226 3.5zm4 0l.748.664-6.924 8.164-.776-.643.676-.749L15.226 3.5z"></path></svg></div>
                                    <div class="chat-users-info-notread" <?php if(!$getMsg["chat_messages_status"] && intval($_SESSION['profile']['id']) == $getMsg["chat_messages_id_user"]){ echo 'style="display: block"'; } ?> ><svg width="16" height="16" viewBox="0 0 16 16"><path fill="#77c226" fill-rule="evenodd" d="M13.248 3.5l.748.664-6.924 8.164L2 8.215l.644-.765 4.328 3.486z"></path></svg></div>
                                 </span>

                                 <?php echo $this->countChatMessages($value["chat_users_id_hash"],$_SESSION['profile']['id']); ?>

                              </div>

                              <div class="clr" ></div>
                            
                           </div>
                         <?php
                      }

                     }else{

                      $get = findOne("uni_clients", "clients_id=?", [$value["chat_users_id_interlocutor"]]);

                      if($get){

                         ?>
                           <div data-id="<?php echo $value["chat_users_id_hash"]; ?>" <?php echo $active_user; ?> >

                              <div class="module-chat-users-img" >
                                <img src="<?php echo $this->userAvatar($get); ?>" >
                              </div>
                              <div class="module-chat-users-info" >
                                 <span class="module-chat-users-info-date" ><?php echo datetime_format($getMsg["chat_messages_date"], false); ?></span>
                                 <p class="module-chat-users-info-client" ><?php echo custom_substr($this->name($get),10, "..."); ?></p>
                                 <p class="module-chat-users-info-msg" >
                                     <?php
                                          if($getMsg["chat_messages_id_user"] == $value["chat_users_id_user"]){ echo 'Вы: '; }

                                          if(isset($getMsg["chat_messages_text"])){
                                               echo custom_substr($getMsg["chat_messages_text"], 20, "...");
                                          }else{

                                               if($getMsg["chat_messages_attach"]){
                                                  $attach = json_decode($getMsg["chat_messages_attach"], true);
                                                  if(isset($attach['voice'])){
                                                      echo custom_substr($ULang->t("Голосовое"), 20, "...");
                                                  }elseif($attach['images']){
                                                      echo custom_substr($ULang->t("Фото"), 20, "...");
                                                  }
                                               }

                                          }
                                     ?>                                 
                                 </p>

                                 <span class="module-chat-users-info-view" >
                                    <div class="chat-users-info-read" <?php if($getMsg["chat_messages_status"] && intval($_SESSION['profile']['id']) == $getMsg["chat_messages_id_user"]){ echo 'style="display: block"'; } ?> ><svg width="16" height="16" viewBox="0 0 16 16"><path fill="#77c226" fill-rule="evenodd" d="M11.226 3.5l.748.664-6.924 8.164L-.022 8.27l.644-.82 4.328 3.486L11.226 3.5zm4 0l.748.664-6.924 8.164-.776-.643.676-.749L15.226 3.5z"></path></svg></div>
                                    <div class="chat-users-info-notread" <?php if(!$getMsg["chat_messages_status"] && intval($_SESSION['profile']['id']) == $getMsg["chat_messages_id_user"]){ echo 'style="display: block"'; } ?> ><svg width="16" height="16" viewBox="0 0 16 16"><path fill="#77c226" fill-rule="evenodd" d="M13.248 3.5l.748.664-6.924 8.164L2 8.215l.644-.765 4.328 3.486z"></path></svg></div>
                                 </span>

                                 <?php echo $this->countChatMessages($value["chat_users_id_hash"],$_SESSION['profile']['id']); ?>

                              </div>

                              <div class="clr" ></div>

                           </div>
                         <?php 
                         }             

                     }

                 }

             }
 
          }
       }

   }


   function countChatMessages($id_hash = "", $id_user=0, $html = true){

      $countMessage = (int)getOne("select count(*) as total from uni_chat_messages where chat_messages_id_hash=? and chat_messages_status=? and chat_messages_id_user!=?", array($id_hash,0,$id_user) )["total"];
      $display = $countMessage ? '' : 'style="display:none"';
      
      if($html){
        return '<span class="module-chat-users-count-msg label-count" '.$display.' >'.$countMessage.'</span>';
      }else{
        return $countMessage;
      }

   }


   function auth_reg($array=array()){
    global $settings,$config;

    $Admin = new Admin();
    $Subscription = new Subscription();
    $ULang = new ULang();

    if($array["method"] == 1){

       $getUser = findOne("uni_clients", "clients_phone=?", [$array["phone"]]);

       if($getUser){

           if($getUser->clients_status == 2 || $getUser->clients_status == 3){
                 
               return array( "status" => false, "status_user" => $getUser->clients_status );

           }else{
           
               $_SESSION['profile']['id'] = $getUser->clients_id;

               return array( "status" => true, "reg" => 1, "data" => $getUser );

           }

       }

    }elseif($array["method"] == 2 || $array["method"] == 3){
       
       if($array["email"] && $array["phone"]){
          $getUser = findOne("uni_clients", "clients_email=? or clients_phone=?", [$array["email"],$array["phone"]]);
       }else{
          $getUser = findOne("uni_clients", "clients_email=?", [$array["email"]]);
       }

       if($getUser){
             
             if($getUser->clients_status == 2 || $getUser->clients_status == 3){
                   
                 return array( "status" => false, "status_user" => $getUser->clients_status );

             }else{

                 if($array["network"]){

                     $_SESSION['profile']['id'] = $getUser->clients_id;

                     return array( "status" => true, "reg" => 1, "data" => $getUser );

                 }else{

                     if (password_verify($array["pass"].$config["private_hash"], $getUser->clients_pass)) {  
                        
                          $_SESSION['profile']['id'] = $getUser->clients_id;

                          return array( "status" => true, "reg" => 1, "data" => $getUser );
                        
                     }else{

                          return array( "status" => false, "answer" => $ULang->t("Неверный логин и(или) пароль!") );

                     }

                 }
             
             }           

       }

    }

     $notifications = '{"messages":"1","answer_comments":"1","services":"1","answer_ad":"1"}';
    
     if(empty($array["pass"])){ $pass = generatePass(10); }else{ $pass = $array["pass"]; }
     $password_hash =  password_hash($pass.$config["private_hash"], PASSWORD_DEFAULT);

     if(!$array["name"]){
        if($array["email"]){
           $array["name"] = explode("@", $array["email"])[0];
        }else{
           $array["name"] = $array["phone"];
        }
     }
     
     $clients_id_hash = md5($array["email"] ? $array["email"] : $array["phone"]);

     $insert_id = insert("INSERT INTO uni_clients(clients_pass,clients_email,clients_phone,clients_name,clients_surname,clients_ip,clients_id_hash,clients_status,clients_datetime_add,clients_notifications,clients_social_identity,clients_avatar,clients_ref_id,clients_verification_code)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)", array($password_hash,$array["email"],$array["phone"],$array["name"],$array["surname"],clear($_SERVER["REMOTE_ADDR"]),$clients_id_hash,intval($array["activation"]), date("Y-m-d H:i:s"), $notifications,$array["social_link"],$array["avatar"],genRefId(),genVerificationCode())); 

     $_SESSION['profile']['id'] = $insert_id;

     if(isset($_COOKIE["ref_id"])){
        $ref_id = intval($_COOKIE["ref_id"]);
     }elseif($_SESSION['ref_id']){
        $ref_id = intval($_SESSION['ref_id']);
     }else{
        $getReferrer = findOne('uni_clients_ref_transitions', 'ip=?', [$_SERVER["REMOTE_ADDR"]]);
        if($getReferrer){
            $ref_id = $getReferrer['id_user_referrer'];
        }
     }

     if($ref_id){
        smart_insert('uni_clients_ref', [
            'timestamp' => date("Y-m-d H:i:s"),
            'id_user_referral' => $insert_id,
            'id_user_referrer' => $ref_id,
        ]);   
     }

     if($settings["bonus_program"]["register"]["status"] && $settings["bonus_program"]["register"]["price"]){
         $this->actionBalance(array("id_user"=>$insert_id,"summa"=>$settings["bonus_program"]["register"]["price"],"title"=>$settings["bonus_program"]["register"]["name"],"id_order"=>generateOrderId(),"email" => $array["email"],"name" => $array["name"], "note" => $settings["bonus_program"]["register"]["name"]),"+");             
     }

     $Admin->notifications("user", array("user_name" => $array["name"], "user_email" => $array["email"], "user_phone" => $array["phone"]));
  
     $Subscription->add(array("email"=>$array["email"],"user_id"=>$insert_id,"name"=>$array["name"],"status" => 1));

     return array( "status" => true, "data" => findOne("uni_clients", "clients_id=?", [$insert_id]) );    

   }


    function actionBalance($array=array(),$action=""){
      global $settings;   

      $Main = new Main();
      if(!$array["id_order"]) $array["id_order"] = generateOrderId();

      if($array["note"]){
        $note = '<p>'.$array["note"].'</p>';
      }

       if(!empty($array["id_user"])){
        if($action == "+"){
          $check = findOne("uni_history_balance","id_order=? AND id_user=?", [$array["id_order"],$array["id_user"]]);  
          if(!$check){

              update("UPDATE uni_clients SET clients_balance=clients_balance+{$array["summa"]} WHERE clients_id=?", [$array["id_user"]]); 

              $this->profileAddHistoryBalance($array,"+");

               $param      = array("{USER_NAME}"=>$array["name"],
                                   "{USER_EMAIL}"=>$array["email"],
                                   "{SUMMA}"=>$Main->price($array["summa"]),
                                   "{NOTE}"=>$note,
                                   "{UNSUBCRIBE}"=>"",
                                   "{EMAIL_TO}"=>$array["email"]); 

               email_notification( array( "variable" => $param, "code" => "BALANCE" ) );

              return true;

          }else{ return false; }   
        }else{
                
                update("UPDATE uni_clients SET clients_balance=clients_balance-{$array["summa"]} WHERE clients_id=?",[$array["id_user"]]); 

                $this->profileAddHistoryBalance($array,"-");

                return true;
            
        } 
       }else{ return false; }  

    }
    
    function profileAddHistoryBalance($array=array(),$action=""){
        insert("INSERT INTO uni_history_balance(id_user,summa,method,name,action,datetime)VALUES(?,?,?,?,?,?)", array($array["id_user"],$array["summa"],$array["method"],$array["title"],$action,date("Y-m-d H:i:s"))); 
    }
    
    function name($data = [], $shopConditions = true){

        $Shop = new Shop();

        if($shopConditions && isset($data["clients_id"])){
            $getShop = $Shop->getShop(['user_id'=>$data["clients_id"],'conditions'=>true]);

            if($getShop){
                return $getShop["clients_shops_title"];
            }
        }

        if($data["clients_surname"]!=""){
           $clients_surname = mb_strtoupper(mb_substr( $data["clients_surname"] , 0,1, "UTF-8" ) ,"UTF-8") . ".";
           return $data["clients_name"] . ' ' . $clients_surname;
        }else{
           return $data["clients_name"];
        }

    }    
    
    function userAvatar($data = [], $shopConditions = true){
        global $config;  

        $Shop = new Shop();

        if($shopConditions && isset($data["clients_id"])){
            $getShop = $Shop->getShop(['user_id'=>$data["clients_id"],'conditions'=>true]);
            if($getShop){
                if($getShop["clients_shops_logo"]){
                    return Exists($config["media"]["other"], $getShop["clients_shops_logo"], $config["media"]["no_avatar"]);
                }
            }
        }

        if(preg_match('/^(http|https|ftp):[\/]{2}/i', urldecode($data["clients_avatar"]))){
            return urldecode($data["clients_avatar"]);       
        }else{
            return Exists($config["media"]["avatar"],$data["clients_avatar"],$config["media"]["no_avatar"]);
        }

    }

    function downAvatar($link = "", $id = 0){
       global $config;
       $path = $config["basePath"] . "/" . $config["media"]["avatar"];
       if($link && $id){
         $link = file_get_contents_curl(urldecode($link));
         if($link){
            file_put_contents($path . "/" . md5($id) . ".jpg", $link);
            update("UPDATE uni_clients SET clients_avatar=? WHERE clients_id=?", array( md5($id) . ".jpg" ,$id));
         }
       }
    }
    
   function outRating($id_user=0,$rating=0){

      if($id_user) $rating = $this->ratingBalls($id_user);

      if($rating == 1){
      return '
             <span class="ion-ios-star" ></span>
             <span class="ion-ios-star-outline" ></span>
             <span class="ion-ios-star-outline" ></span>
             <span class="ion-ios-star-outline" ></span>
             <span class="ion-ios-star-outline" ></span>
      ';
      }elseif($rating == 2){
      return '
             <span class="ion-ios-star" ></span>
             <span class="ion-ios-star" ></span>
             <span class="ion-ios-star-outline" ></span>
             <span class="ion-ios-star-outline" ></span>
             <span class="ion-ios-star-outline" ></span>
      ';
      }elseif($rating == 3){
      return '
             <span class="ion-ios-star" ></span>
             <span class="ion-ios-star" ></span>
             <span class="ion-ios-star" ></span>
             <span class="ion-ios-star-outline" ></span>
             <span class="ion-ios-star-outline" ></span>
      ';
      }elseif($rating == 4){
      return '
             <span class="ion-ios-star" ></span>
             <span class="ion-ios-star" ></span>
             <span class="ion-ios-star" ></span>
             <span class="ion-ios-star" ></span>
             <span class="ion-ios-star-outline" ></span>
      ';            
      }elseif($rating == 5){
       return '
             <span class="ion-ios-star" ></span>
             <span class="ion-ios-star" ></span>
             <span class="ion-ios-star" ></span>
             <span class="ion-ios-star" ></span>
             <span class="ion-ios-star" ></span>
      ';            
      }else{
        return '
             <span class="ion-ios-star-outline" ></span>
             <span class="ion-ios-star-outline" ></span>
             <span class="ion-ios-star-outline" ></span>
             <span class="ion-ios-star-outline" ></span>
             <span class="ion-ios-star-outline" ></span>
      ';
      }

   }

  function ratingBalls($id_user){

    $array = [];

    $array["total_rating"] = 0;
    $array["rating_1"] = 0;
    $array["rating_2"] = 0;
    $array["rating_3"] = 0;
    $array["rating_4"] = 0;
    $array["rating_5"] = 0;

    $getReviews = getAll("select * from uni_clients_reviews where clients_reviews_status = ? and clients_reviews_id_user = ?", [1,intval($id_user)]);

      if(count($getReviews)){
         foreach ($getReviews as $key => $value) {

             $array["total_rating"] += (int)$value["clients_reviews_rating"];
             
             $array["rating_".$value["clients_reviews_rating"]] += (int)$value["clients_reviews_rating"];
          
         }
      }

      $array["rating_1"] = $array["rating_1"] ? $array["rating_1"] : 0;
      $array["rating_2"] = $array["rating_2"] ? $array["rating_2"] : 0;
      $array["rating_3"] = $array["rating_3"] ? $array["rating_3"] : 0;
      $array["rating_4"] = $array["rating_4"] ? $array["rating_4"] : 0;
      $array["rating_5"] = $array["rating_5"] ? $array["rating_5"] : 0;

      if($array["total_rating"]){
        $result = ($array["rating_1"]*1+$array["rating_2"]*2+$array["rating_3"]*3+$array["rating_4"]*4+$array["rating_5"]*5)/$array["total_rating"];
      }else{
        $result = 0;
      }

      if($result <= 5){
         return number_format($result, 0, '.', '');
      }else{
         return number_format(5, 0, '.', '');
      }


  }


  function arrayMenu(){
     global $settings,$config;

     $ULang = new ULang();
     
     include $config["template_path"] . "/include/profile_menu.php";

     return $menu;
     
  }

  function menuPageName( $action = "" ){
      global $settings;

      $ULang = new ULang();

      if($action == "ad"){
        return $ULang->t("Объявления");
      }elseif($action == "orders"){
        return $ULang->t("Заказы");
      }elseif($action == "favorites"){
        return $ULang->t("Избранное");
      }elseif($action == "settings"){
        return $ULang->t("Настройки");
      }elseif($action == "balance"){
        return $ULang->t("Кошелек");
      }elseif($action == "history"){
        return $ULang->t("История платежей");
      }elseif($action == "reviews"){
        return $ULang->t("Отзывы");
      }elseif($action == "subscriptions"){
        return $ULang->t("Подписки");
      }elseif($action == $settings['user_shop_alias_url_page']){
        return $ULang->t("Магазин");
      }elseif($action == "statistics"){
        return $ULang->t("Статистика");
      }elseif($action == "scheduler"){
        return $ULang->t("Планировщик задач");
      }elseif($action == "tariff"){
        return $ULang->t("Тариф");
      }elseif($action == "booking"){
        return $ULang->t("Бронирования");
      }elseif($action == "ref"){
        return $ULang->t("Реферальная программа");
      }elseif($action == "packages"){
        return $ULang->t("Пакеты размещений");
      }else{
        return $ULang->t("Объявления");
      }

  }

  function headerUserMenu( $name = true ){

    $ULang = new ULang();
    $Main = new Main();
    $menu = $this->arrayMenu();

      if(isset($_SESSION["profile"]["id"])){

         foreach ($menu as $page => $value) {

             if(isset($value['nested'])){

                 $links .= '<div class="dropdown-box-list-nested-toggle" ><a href="#" >'.$value['icon'].' '.$value["name"].' <i class="las la-angle-down"></i></a><div class="dropdown-box-list-nested" >';

                 foreach ($value['nested'] as $page_nested => $nested){
                    
                    if($nested["link"]){
                        $links .= '<a href="'.$nested["link"].'" >'.$nested["icon"].' '.$nested["name"].'</a>';
                    }else{
                        $links .= '<a href="#" class="open-modal" data-id-modal="'.$nested["modal_id"].'" >'.$nested["icon"].' '.$nested["name"].'</a>';
                    }

                 }

                 $links .= '</div></div>';

             }else{

                 if($page == "balance"){
                    $links .= '<a href="'.$value["link"].'" >'.$value["icon"].' '.$value["name"].' '.$Main->price($_SESSION["profile"]["data"]["clients_balance"]).'</a>';
                 }else{
                    if($value["link"]){
                        $links .= '<a href="'.$value["link"].'" >'.$value["icon"].' '.$value["name"].'</a>';
                    }else{
                        $links .= '<a href="#" class="open-modal" data-id-modal="'.$value["modal_id"].'" >'.$value["icon"].' '.$value["name"].'</a>';
                    }
                 }

             }
             
         }

         if($name){
            $user_name = '<span class="mini-avatar-name" >'.$this->name($_SESSION["profile"]["data"], false).'</span>';
         }

         return '
         <div class="toolbar-dropdown dropdown-click">
              <span> <span class="mini-avatar" > <span class="mini-avatar-img" ><img src="'. $this->userAvatar($_SESSION["profile"]["data"], false).'" /></span> </span> '.$user_name.'</span>
              <div class="toolbar-dropdown-box toolbar-dropdown-js width-250 right-0 no-padding" >

                   <div class="dropdown-box-list-link" >
                      '.$links.'
                   </div>

              </div>
          </div>
         ';
      }else{
         return '
         <a class="toolbar-link-icon" href="'._link("auth").'" title="'.$ULang->t("Войти в личный кабинет").'" ><i class="las la-sign-in-alt"></i></a>
         ';
      }
  }

  function outUserMenu($data=[],$balance=0){

    $Main = new Main();

    if($data["menu_links"]){

         foreach ($data["menu_links"] as $page => $value) {

             if($value['nested']){

                 $links .= '<div class="dropdown-box-list-nested-toggle" ><a href="#" >'.$value['icon'].' '.$value["name"].' <i class="las la-angle-down"></i></a><div class="dropdown-box-list-nested" >';

                 foreach ($value['nested'] as $page_nested => $nested){
                    
                    if($nested["link"]){
                        $links .= '<a href="'.$nested["link"].'" >'.$nested["icon"].' '.$nested["name"].'</a>';
                    }else{
                        $links .= '<a href="#" class="open-modal" data-id-modal="'.$nested["modal_id"].'" >'.$nested["icon"].' '.$nested["name"].'</a>';
                    }

                 }

                 $links .= '</div></div>';

             }else{

                 if($page == "balance"){
                    $links .= '<a href="'.$value["link"].'" >'.$value["icon"].' '.$value["name"].' '.$Main->price($balance).'</a>';
                 }else{
                    if($value["link"]){
                        $links .= '<a href="'.$value["link"].'" >'.$value["icon"].' '.$value["name"].'</a>';
                    }else{
                        $links .= '<a href="#" class="open-modal" data-id-modal="'.$value["modal_id"].'" >'.$value["icon"].' '.$value["name"].'</a>';
                    }
                 }

             }
             
         }

         return $links;
    }

  }

  function sessionsFavorites(){
     if($_SESSION['profile']['id']){
        $get = getAll("select * from uni_favorites where favorites_from_id_user=?", array($_SESSION['profile']['id']));
        if(count($get)){
            foreach ($get as $key => $value) {
               $_SESSION['profile']["favorite"][$value["favorites_id_ad"]] = $value["favorites_id_ad"];
            }
        }
     }
  }

  function cardUser($data = array()){
    global $settings, $config;

    $ULang = new ULang();
    $Shop = new Shop();
    
    if((strtotime($data["ad"]["clients_datetime_view"]) + 180) > time()){
      $status = '<span class="online badge-pulse-green-small" data-tippy-placement="top" title="'.$ULang->t("В сети").'"  ></span>';
    }else{
      $status = '<span class="online badge-pulse-red-small" data-tippy-placement="top" title="'.$ULang->t("Был(а) в сети:").' '.datetime_format($data["ad"]["clients_datetime_view"]).'" ></span>';
    }

    $countReviews = (int)getOne("select count(*) as total from uni_clients_reviews where clients_reviews_id_user=? and clients_reviews_status=?", [$data["ad"]["clients_id"],1])["total"];
    
    $getShop = $Shop->getShop(['user_id'=>$data["ad"]["clients_id"],'conditions'=>true]);

    if($data["ad"]["clients_verification_status"]){
        $verificationStatus = '
            <div class="user-card-verification-box">
                <span class="user-card-verification-status" >'.$ULang->t("Профиль подтвержден").'</span>
                <div><i class="las la-check"></i> '.$ULang->t("Телефон подтверждён").'</div>  
                <div><i class="las la-check"></i> '.$ULang->t("Email подтверждён").'</div>
                <div><i class="las la-check"></i> '.$ULang->t("Документы и фото проверены").'</div>                              
            </div>
        ';
    }else{
        $verificationStatus = '';
    }

    if( $getShop ){
        if($getShop["clients_shops_logo"]){
            $avatar = '<img src="'.Exists($config["media"]["other"], $getShop["clients_shops_logo"], $config["media"]["no_avatar"]).'">';
            $link = '<div class="board-view-user-label-shop" ></div> <a href="'.$Shop->linkShop($getShop["clients_shops_id_hash"]).'"  >'.$getShop["clients_shops_title"].'</a>';
        }else{
            $avatar = '<img src="'.$this->userAvatar($data["ad"]).'">';
            $link = '<a href="'._link( "user/" . $data["ad"]["clients_id_hash"] ).'"  >'.$this->name($data["ad"]).'</a>';
        }
    }else{
        $avatar = '<img src="'.$this->userAvatar($data["ad"]).'">';
        $link = '<a href="'._link( "user/" . $data["ad"]["clients_id_hash"] ).'"  >'.$this->name($data["ad"]).'</a>';
    }

    return '

      <div class="board-view-user-left" >
        '.$status.'
        <div class="board-view-user-avatar" >
        '.$avatar.'
        </div>
      </div>

      <div class="board-view-user-right" >

        '.$link.'

        <span class="board-view-user-date" >'.$ULang->t("На").' '.$settings["site_name"].' '.$ULang->t("с").' '. date("d.m.Y", strtotime($data["ad"]["clients_datetime_add"])).'</span>

         <div class="board-view-stars">
             
           '.$data["ratings"].' <a href="'._link( "user/" . $data["ad"]["clients_id_hash"] . "/reviews" ).'" >('.$countReviews.')</a>
           <div class="clr"></div>   

         </div>

      </div>

      <div class="clr" ></div>

      '.$verificationStatus.'

    ';

  }

  function cardUserAd($data = array()){
    global $settings, $config;

    $ULang = new ULang();
    $Shop = new Shop();
    
    if( (strtotime($data["ad"]["clients_datetime_view"]) + 180) > time() ){
      $status = '<span class="online badge-pulse-green-small" data-tippy-placement="top" title="'.$ULang->t("В сети").'"  ></span>';
    }else{
      $status = '<span class="online badge-pulse-red-small" data-tippy-placement="top" title="'.$ULang->t("Был(а) в сети:").' '.datetime_format($data["ad"]["clients_datetime_view"]).'" ></span>';
    }

    $countReviews = (int)getOne("select count(*) as total from uni_clients_reviews where clients_reviews_id_user=? and clients_reviews_status=?", [$data["ad"]["clients_id"],1])["total"];

    $getShop = $Shop->getShop(['user_id'=>$data["ad"]["clients_id"],'conditions'=>true]);

    if($data["ad"]["clients_verification_status"]){
        $verificationStatus = '
            <div class="user-card-verification-box">
                <span class="user-card-verification-status" >'.$ULang->t("Профиль подтвержден").'</span>
                <div><i class="las la-check"></i> '.$ULang->t("Телефон подтверждён").'</div>  
                <div><i class="las la-check"></i> '.$ULang->t("Email подтверждён").'</div>
                <div><i class="las la-check"></i> '.$ULang->t("Документы и фото проверены").'</div>                              
            </div>
        ';
    }else{
        $verificationStatus = '';
    }

    if( $getShop ){
        if($getShop["clients_shops_logo"]){
            $avatar = '<img src="'.Exists($config["media"]["other"], $getShop["clients_shops_logo"], $config["media"]["no_avatar"]).'">';
            $link = '<div class="board-view-user-label-shop" ></div><a class="ad-view-card-user-link-profile" href="'.$Shop->linkShop($getShop["clients_shops_id_hash"]).'"  >'.$getShop["clients_shops_title"].'</a>';
        }else{
            $avatar = '<img src="'.$this->userAvatar($data["ad"]).'">';
            $link = '<a class="ad-view-card-user-link-profile" href="'._link( "user/" . $data["ad"]["clients_id_hash"] ).'"  >'.$this->name($data["ad"]).'</a>';
        }
    }else{
        $avatar = '<img src="'.$this->userAvatar($data["ad"]).'">';
        $link = '<a class="ad-view-card-user-link-profile" href="'._link( "user/" . $data["ad"]["clients_id_hash"] ).'"  >'.$this->name($data["ad"]).'</a>';
    }

    return '

        <div class="row" >
            <div class="col-lg-7 col-7" >
               
                  <div class="board-view-user-left" >
                    '.$status.'
                    <div class="board-view-user-avatar" >
                    '.$avatar.'
                    </div>
                  </div>

                  <div class="board-view-user-right" >
                    '.$link.'
                    <span class="board-view-user-date" >'.$ULang->t("На").' '.$settings["site_name"].' '.$ULang->t("с").' '. date("d.m.Y", strtotime($data["ad"]["clients_datetime_add"])).'</span>
                  </div>

            </div>
            <div class="col-lg-5 col-5 text-center" >
              
                <div class="board-view-stars mb10">
                     
                   '.$data["ratings"].' <a href="'._link( "user/" . $data["ad"]["clients_id_hash"] . "/reviews" ).'" >('.$countReviews.')</a>
                   <div class="clr"></div>   

                </div>

                <a href="'._link( "user/" . $data["ad"]["clients_id_hash"] . "/reviews" ).'" class="btn-custom-mini btn-color-light" >'.$ULang->t("Отзывы").'</a>

            </div>
        </div>

      <div class="clr" ></div>

      '.$verificationStatus.'

    ';

  }

  function cardUserOrder($data = array()){
    global $settings, $config;

    $ULang = new ULang();
    $Shop = new Shop();

    $getShop = $Shop->getShop(['user_id'=>$data["user"]["clients_id"],'conditions'=>true]);

    if( (strtotime($data["user"]["clients_datetime_view"]) + 180) > time() ){
      $status = '<span class="online badge-pulse-green-small" data-tippy-placement="top" title="'.$ULang->t("В сети").'"  ></span>';
    }else{
      $status = '<span class="online badge-pulse-red-small" data-tippy-placement="top" title="'.$ULang->t("Был в сети:").' '.datetime_format($data["user"]["clients_datetime_view"]).'" ></span>';
    }

    if( $getShop ){
        $avatar = '<img src="'.Exists($config["media"]["other"], $getShop["clients_shops_logo"], $config["media"]["no_avatar"]).'">';
        $link = '<a href="'.$Shop->linkShop($getShop["clients_shops_id_hash"]).'"  >'.$getShop["clients_shops_title"].'</a>';
    }else{
        $avatar = '<img src="'.$this->userAvatar($data["user"]).'">';
        $link = '<a href="'._link( "user/" . $data["user"]["clients_id_hash"] ).'"  >'.$this->name($data["user"]).'</a>';
    }

    return '

      <div class="board-view-user-left" >
        '.$status.'
        <div class="board-view-user-avatar" >
          '.$avatar.'
        </div>
      </div>

      <div class="board-view-user-right" >

        '.$link.'

        <span class="board-view-user-date" >'.$ULang->t("На").' '.$settings["site_name"].' '.$ULang->t("с").' '. date("d.m.Y", strtotime($data["user"]["clients_datetime_add"])).'</span>

         <div class="board-view-stars">
             
           '.$data["ratings"].'
           <div class="clr"></div>   

         </div>

      </div>

      <div class="clr" ></div>

    ';

  }

    function payMethod($payment="", $paramForm = array()){
       global $config;
       $param = paymentParams($payment);

       if(file_exists( $config["basePath"] . "/systems/payment/".$payment."/form.php" )){

         insert("INSERT INTO uni_orders_parameters(orders_parameters_param,orders_parameters_id_uniq,orders_parameters_date)VALUES(?,?,?)", [json_encode($paramForm), $paramForm["id_order"], date("Y-m-d H:i:s")]);

         return include $config["basePath"] . "/systems/payment/".$payment."/form.php";

       }

    }

    function payCallBack( $id_uniq = "" ){
      global $settings,$config;

      $static_msg = require $config["basePath"] . "/static/msg.php";
      
      $Main = new Main();
      $Ads = new Ads();
      $Delivery = new Delivery();
      $Admin = new Admin();

      $getOrderParam = findOne("uni_orders_parameters","orders_parameters_id_uniq=? and orders_parameters_status=?", [$id_uniq,0]);

      if($getOrderParam){

      update("update uni_orders_parameters set orders_parameters_status=? where orders_parameters_id_uniq=?", [ 1 , $id_uniq ]);

      $output_param = json_decode($getOrderParam["orders_parameters_param"], true);
      
      $user = findOne("uni_clients", "clients_id=?", [ intval($output_param["id_user"]) ]);

          if($output_param["action"] == "balance"){

             $this->actionBalance(array("id_user"=>$output_param["id_user"],"summa"=>$output_param["amount"],"title"=>$output_param["title"],"id_order"=>generateOrderId(),"email" => $user["clients_email"],"name" => $user["clients_name"], "note" => $output_param["title"]),"+");

             if($settings["bonus_program"]["balance"]["status"] && $settings["bonus_program"]["balance"]["price"]){
                 $bonus = $this->calcBonus($output_param["amount"]);
                 $this->actionBalance(array("id_user"=>$output_param["id_user"],"summa"=>$bonus,"title"=>$settings["bonus_program"]["balance"]["name"],"id_order"=>generateOrderId(),"email" => $user["clients_email"],"name" => $user["clients_name"], "note" => $settings["bonus_program"]["balance"]["name"]),"+");             
             }

             $Main->addOrder(["id_order" => $id_uniq,"id_ad"=>0,"price"=>$output_param["amount"],"title"=>$output_param["title"],"id_user"=>$output_param["id_user"],"status_pay"=>1, "user_name" => $user["clients_name"], "id_hash_user" => $user["clients_id_hash"], 'action_name' => $output_param["action"]]);

             if($settings["referral_program_status"] && $settings["referral_program_award_percent"]){
                $bonus = $this->calcReferralAward($output_param["amount"]);
                $getRef = findOne('uni_clients_ref', 'id_user_referral=?', [$output_param["id_user"]]);
                if($getRef){
                  $getUser = findOne('uni_clients', 'clients_id=?', [$getRef['id_user_referrer']]);
                  if($getUser){

                     smart_insert('uni_clients_ref_award', [
                      'amount' => $bonus,
                      'id_user_referral' => $output_param["id_user"],
                      'id_user_referrer' => $getRef['id_user_referrer'],
                      'timestamp' => date('Y-m-d H:i:s'),
                     ]);

                     $this->actionBalance(array("id_user"=>$getRef['id_user_referrer'],"summa"=>$bonus,"title"=>$static_msg[58],"id_order"=>generateOrderId(),"email" => $getUser["clients_email"],"name" => $getUser["clients_name"], "note" => $static_msg[58]),"+");

                     $getRefUser = findOne('uni_clients', 'clients_id=?', [$output_param["id_user"]]);

                     $param      = array("{USER_NAME}"=>$getUser["clients_name"],
                                         "{REF_USER_NAME}"=>$getRefUser["clients_name"].' '.$getRefUser["clients_surname"],
                                         "{SUMMA}"=>$Main->price($bonus),
                                         "{UNSUBCRIBE}"=>"",
                                         "{EMAIL_TO}"=>$getUser["clients_email"]); 

                     $this->userNotification( [ "mail"=>["params"=>$param, "code"=>"REF_AWARD", "email"=>$getUser["clients_email"]],"method"=>1 ] );

                  }
                }             
             }

          }elseif($output_param["action"] == "secure"){

             if($output_param["auction"] == 1){
               insert("INSERT INTO uni_ads_auction(ads_auction_id_ad,ads_auction_price,ads_auction_id_user)VALUES(?,?,?)", [$output_param["id_ad"], $output_param["ad_price"], $output_param["id_user"]]);
               update("update uni_ads set ads_price=? where ads_id=?", [ $output_param["ad_price"] , $output_param["id_ad"] ], true);
             }

             $findAd = $Ads->get("ads_id=?", [$output_param["id_ad"]]);

             if($Ads->getStatusDelivery($findAd) && $output_param["delivery"]["type"] != 'self' && $settings["main_type_products"] == 'physical'){

                $deliveryGoods[] = [
                    'id'=>$findAd['ads_id'],
                    'title'=>$findAd['ads_title'],
                    'cost'=>$findAd['ads_price'],
                ];   

                $deliveryResults = $Delivery->createOrder(["delivery"=>$output_param["delivery"],"amount"=>$output_param["amount"], "id_user"=>$output_param["id_user"], "goods"=>$deliveryGoods]); 

                update("update uni_secure set secure_status=?,secure_delivery_type=?,secure_delivery_invoice_number=?,secure_delivery_track_number=?,secure_delivery_errors=? where secure_id_order=?", [ $deliveryResults['invoice_number'] ? 2 : 1, $output_param["delivery"]["type"], $deliveryResults['invoice_number'], $deliveryResults['track_number'], $deliveryResults['errors'], $output_param["id_order"] ]);

             }else{
                update("update uni_secure set secure_status=?,secure_delivery_type=? where secure_id_order=?", [ 1, $output_param["delivery"]["type"], $output_param["id_order"] ]);
             }

             if($settings["main_type_products"] == 'physical'){
                  if($findAd["category_board_marketplace"]){
                    if(!$findAd["ads_available_unlimitedly"]){
                        if($findAd["ads_available"]){
                          update("update uni_ads set ads_available=ads_available-1 where ads_id=?", [$findAd['ads_id']]);
                          if(!($findAd["ads_available"]-1)){
                            update("update uni_ads set ads_status=? where ads_id=?", [5,$findAd['ads_id']], true);
                          }
                        }else{
                          update("update uni_ads set ads_status=? where ads_id=?", [5,$findAd['ads_id']], true);
                        }
                    }
                  }else{
                    update("update uni_ads set ads_status=? where ads_id=?", [5,$findAd['ads_id']], true);
                  }
             }

             $Ads->addSecurePayments( ["id_order"=>$output_param["id_order"], "amount"=>$output_param["amount"], "id_user"=>$output_param["id_user"], "status_pay"=>1, "status"=>0, "amount_percent"=>$output_param["amount"]] );

             $getAd = $Ads->get("ads_id=?", [ $output_param["id_ad"] ]);

             $param      = array("{USER_NAME}"=>$getAd["clients_name"],
                                 "{USER_EMAIL}"=>$getAd["clients_email"],
                                 "{ADS_TITLE}"=>$getAd["ads_title"],
                                 "{ADS_LINK}"=>$Ads->alias($getAd),
                                 "{PROFILE_LINK_ORDER}"=>$output_param["link_success"],
                                 "{UNSUBCRIBE}"=>"",
                                 "{EMAIL_TO}"=>$getAd["clients_email"]); 

             $this->userNotification( [ "mail"=>["params"=>$param, "code"=>"USER_NEW_BUY", "email"=>$getAd["clients_email"]],"method"=>1 ] );
             $Admin->notifications("secure");

          }elseif($output_param["action"] == "booking"){

             update("update uni_ads_booking set ads_booking_status_pay=? where ads_booking_id_order=?", [ 1, $output_param["id_order"] ]);

             insert("INSERT INTO uni_ads_booking_prepayments(ads_booking_prepayments_id_order,ads_booking_prepayments_amount)VALUES(?,?)", array($output_param["id_order"], $output_param["amount"]));

             $Main->addActionStatistics(['ad_id'=>$output_param["id_ad"],'from_user_id'=>$output_param["from_user_id"],'to_user_id'=>$output_param["to_user_id"]],"booking");

             $getAd = $Ads->get("ads_id=?", [ $output_param["id_ad"] ]);

             $param      = array("{USER_NAME}"=>$getAd["clients_name"],
                                 "{USER_EMAIL}"=>$getAd["clients_email"],
                                 "{ADS_TITLE}"=>$getAd["ads_title"],
                                 "{ADS_LINK}"=>$Ads->alias($getAd),
                                 "{PROFILE_LINK_ORDER}"=>$output_param["link_order"],
                                 "{UNSUBCRIBE}"=>"",
                                 "{EMAIL_TO}"=>$getAd["clients_email"]); 

             if($getAd['category_board_booking_variant'] == 0){
                $this->userNotification( [ "mail"=>["params"=>$param, "code"=>"USER_NEW_ORDER_BOOKING", "email"=>$getAd["clients_email"]],"method"=>1 ] );
                $this->sendChat( array("id_ad" => $getAd["ads_id"], "action" => 7, "user_from" => intval($_SESSION["profile"]["id"]), "user_to" => $getAd["clients_id"] ) );
             }else{
                $this->userNotification( [ "mail"=>["params"=>$param, "code"=>"USER_NEW_ORDER_RENT", "email"=>$getAd["clients_email"]],"method"=>1 ] );
                $this->sendChat( array("id_ad" => $getAd["ads_id"], "action" => 8, "user_from" => intval($_SESSION["profile"]["id"]), "user_to" => $getAd["clients_id"] ) );
             }

             $Admin->notifications("booking");

          }elseif($output_param["action"] == "marketplace"){

            foreach ($output_param["cart"] as $id => $value) {
              $data_order[$value['ad']['ads_id_user']][] = $value;
            }

            foreach ($data_order as $id_user => $array) {

                 $total_price = 0;
                 $deliveryParams = [];
                 $order_id = generateOrderId();

                 foreach ($array as $value) {

                     $total_price += $value['ad']['ads_price'] * $value['count'];
                     
                     smart_insert('uni_secure_ads', ['secure_ads_ad_id'=>$value['ad']['ads_id'],'secure_ads_count'=>$value['count'],'secure_ads_total'=>$value['ad']['ads_price'] * $value['count'],'secure_ads_order_id'=>$order_id,'secure_ads_user_id'=>$value['ad']['ads_id_user']]);

                     if($settings["main_type_products"] == 'physical'){
                        if(!$value['ad']["ads_available_unlimitedly"]){
                            if($value['ad']["ads_available"]){
                              update("update uni_ads set ads_available=ads_available-".$value['count']." where ads_id=?", [$value['ad']['ads_id']]);
                              if(!($value['ad']["ads_available"]-$value['count'])){
                                update("update uni_ads set ads_status=? where ads_id=?", [5,$value['ad']['ads_id']], true);
                              }
                            }else{
                              update("update uni_ads set ads_status=? where ads_id=?", [5,$value['ad']['ads_id']], true);
                            }
                        }
                     }

                     if($Ads->getStatusDelivery($value['ad'])){
                         $deliveryGoods[] = [
                            'id'=>$value['ad']['ads_id'],
                            'title'=>$value['ad']['ads_title'],
                            'cost'=>$value['ad']['ads_price'],
                         ];                                                                       
                     }

                     $this->sendChat( array("id_ad" => $value['ad']['ads_id'], "action" => 3, "user_from" => $output_param["id_user"], "user_to" => $value['ad']["ads_id_user"] ) );

                 }

                 if($deliveryGoods && $output_param["delivery"]["type"] != 'self' && $settings["main_type_products"] == 'physical'){
  
                     $deliveryResults = $Delivery->createOrder(["delivery"=>$output_param["delivery"],"amount"=>$total_price, "id_user"=>$id_user, "goods"=>$deliveryGoods]);

                     smart_insert('uni_secure', ['secure_date'=>date("Y-m-d H:i:s"),'secure_id_user_buyer'=>$output_param["id_user"],'secure_id_user_seller'=>$id_user,'secure_id_order'=>$order_id,'secure_price'=>$total_price,'secure_status'=>$deliveryResults['invoice_number'] ? 2 : 1,'secure_delivery_type'=>$output_param["delivery"]["type"],'secure_delivery_invoice_number'=>$deliveryResults['invoice_number'],'secure_delivery_track_number'=>$deliveryResults['track_number'],'secure_delivery_errors'=>$deliveryResults['errors'],'secure_delivery_name'=>$output_param["delivery"]['delivery_name'],'secure_delivery_surname'=>$output_param["delivery"]['delivery_surname'],'secure_delivery_patronymic'=>$output_param["delivery"]['delivery_patronymic'],'secure_delivery_phone'=>$output_param["delivery"]['delivery_phone'],'secure_delivery_id_point'=>$output_param["delivery"]['delivery_id_point'],'secure_marketplace'=>1]);
                     
                 }else{
                    smart_insert('uni_secure', ['secure_date'=>date("Y-m-d H:i:s"),'secure_id_user_buyer'=>$output_param["id_user"],'secure_id_user_seller'=>$id_user,'secure_id_order'=>$order_id,'secure_price'=>$total_price,'secure_status'=>1,'secure_delivery_type'=>$output_param["delivery"]["type"],'secure_marketplace'=>1]);
                 }

                 smart_insert('uni_clients_orders', ['clients_orders_from_user_id'=>$output_param["id_user"],'clients_orders_uniq_id'=>$order_id,'clients_orders_date'=>date('Y-m-d H:i:s'),'clients_orders_to_user_id'=>$id_user,'clients_orders_secure'=>1,'clients_orders_status'=>1]);
                 $Ads->addSecurePayments( ["id_order"=>$order_id, "amount"=>$total_price, "id_user"=>$output_param["id_user"], "status_pay"=>1, "status"=>0, "amount_percent"=>$total_price] );

                 $Admin->notifications("secure");

            }

          }elseif($output_param["action"] == "ad_package"){

            $time_now = time();

            $getPackage = findOne("uni_ads_packages", "id=?", [$output_param["package_id"]]);

            $getUser = findOne("uni_clients", "clients_id=?", [$output_param["id_user"]]);

            $date_completion = date("Y-m-d H:i:s", strtotime("+{$getPackage["period"]} days", $time_now));

            insert("INSERT INTO uni_ads_packages_orders(user_id,package_id,create_date,completion_date,order_id,status_pay,cat_id,count_ad,amount)VALUES(?,?,?,?,?,?,?,?,?)", [$output_param["id_user"],$output_param["package_id"],date('Y-m-d H:i:s',$time_now),$date_completion,$output_param["id_order"],1,$output_param["cat_id"],$getPackage["count_ad"],$output_param["amount"]]);

            $Main->addOrder( ["id_package"=>$output_param["package_id"],"price"=>$output_param["amount"],"title"=>$static_msg["66"].' '.$getPackage["count_ad"] . ' ' . ending($getPackage["count_ad"], 'размещение', 'размещения', 'размещений'),"id_user"=>$output_param["id_user"],"status_pay"=>1, "user_name" => $getUser["clients_name"], "id_hash_user" => $getUser["clients_id_hash"], "action_name" => "ad_package"] );
              
            $Admin->notifications("ad_package");

          }

      }

    }

    function countOnline(){
      return (int)getOne("select count(*) as total from uni_clients where unix_timestamp(clients_datetime_view)+3*60 > unix_timestamp(NOW())")["total"];
    }

    function calcBonus($price){
       global $settings;
       return (($price / 100) * $settings["bonus_program"]["balance"]["price"]);
    }

    function calcReferralAward($price){
      global $settings;
      return (($price / 100) * $settings["referral_program_award_percent"]);
    }

    function paramNotifications($json=""){

       if($json){
          return json_decode($json, true);
       }else{
          return [];
       }

    }

    function userNotification( $data = [] ){
      
      if($data["method"] == 1){   
         if($data["mail"]["email"]){
              email_notification( array( "variable" => $data["mail"]["params"], "code" => $data["mail"]["code"] ) );
         }
      }elseif($data["method"] == 2){   
         if($data["mail"]["email"]){
              email_notification( array( "variable" => $data["mail"]["params"], "code" => $data["mail"]["code"] ) );
         }elseif($data["sms"]["phone"]){
              sms($data["sms"]["phone"], $data["sms"]["text"],'sms');
         }
      }

    }

    function authLink($name=""){
        global $settings, $config;

        $social_auth_params = json_decode(decrypt($settings["social_auth_params"]), true);

        if($name == "vk"){

            $params = array(
              'client_id'     => $social_auth_params["vk"]["id_client"],
              'redirect_uri'  => $config["urlPath"] . "/systems/ajax/oauth.php?network=vk",
              'scope'         => 'email',
              'response_type' => 'code',
              'state'         => $config["urlPath"] . "/auth",
            );
             
            return 'https://oauth.vk.com/authorize?' . urldecode(http_build_query($params));

        }elseif($name == "google"){

            $params = array(
                'client_id'     => $social_auth_params["google"]["id_client"],
                'redirect_uri'  => $config["urlPath"] . "/systems/ajax/oauth.php?network=google",
                'response_type' => 'code',
                'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
                'state'         => '123'
            );
             
            return 'https://accounts.google.com/o/oauth2/auth?' . urldecode(http_build_query($params));

        }elseif($name == "fb"){

            $params = array(
              'client_id'     => $social_auth_params["fb"]["id_app"],
              'scope'         => 'email',
              'redirect_uri'  => $config["urlPath"] . "/systems/ajax/oauth.php?network=fb",
              'response_type' => 'code',
            );
             
            return 'https://www.facebook.com/dialog/oauth?' . urldecode(http_build_query($params));

        }elseif($name == "yandex"){

            $params = array(
                'client_id'     => $social_auth_params["yandex"]["id_app"],
                'redirect_uri'  => $config["urlPath"] . "/systems/ajax/oauth.php?network=yandex",
                'response_type' => 'code',
                'state'         => '123'
            );
             
            return 'https://oauth.yandex.ru/authorize?' . urldecode(http_build_query($params));

        }

    }

    function socialAuth(){
        global $settings;
            
        $authorization_social_list = explode(",", $settings["authorization_social"]);

        if( in_array( "yandex" , $authorization_social_list ) ){
           ?>
           <a class="auth-yandex" href="<?php echo $this->authLink("yandex"); ?>" >
              <img src="<?php echo $settings["path_other"].'/media_social_yandex_61627.png'; ?>">
           </a>                           
           <?php  
        }
        if( in_array( "vk" , $authorization_social_list ) ){
           ?>
           <a class="auth-vk" href="<?php echo $this->authLink("vk"); ?>" >
              <img src="<?php echo $settings["path_other"].'/media_social_vk_vkontakte_icon_124252.png'; ?>">
           </a>                           
           <?php
        }
        if( in_array( "google" , $authorization_social_list ) ){
           ?>
           <a class="auth-google" href="<?php echo $this->authLink("google"); ?>" >
              <img src="<?php echo $settings["path_other"].'/media_social_google_62736.png'; ?>">
           </a>                           
           <?php  
        }                                               
        if( in_array( "fb" , $authorization_social_list ) ){
           ?>
           <a class="auth-fb" href="<?php echo $this->authLink("fb"); ?>" >
              <img src="<?php echo $settings["path_other"].'/media_social_facebook_59205.png'; ?>">
           </a>                           
           <?php  
        }                          
    }

    function getMessagesOrder($order_id=0){
        global $config;
        $ULang = new ULang();
        $getMessages = getAll('select * from uni_clients_orders_messages where clients_orders_messages_id_order=? order by clients_orders_messages_date asc', [$order_id]);
        if(count($getMessages)){
            foreach ($getMessages as $key => $value) {

                $attach = '';

                if($_SESSION['profile']['id'] == $value['clients_orders_messages_from_id_user']){
                    $getUser = findOne('uni_clients', 'clients_id=?', [$value['clients_orders_messages_to_id_user']]);
                    $name = '<a href="'._link( "user/" . $getUser["clients_id_hash"] ).'" class="order-messages-item-name" >'.$ULang->t('Вы:').'</a>';
                    $class = 'order-messages-from';
                }else{
                    $getUser = findOne('uni_clients', 'clients_id=?', [$value['clients_orders_messages_from_id_user']]);
                    $name = '<a href="'._link( "user/" . $getUser["clients_id_hash"] ).'" class="order-messages-item-name" >'.$getUser['clients_name'].':</a>';
                    $class = 'order-messages-to';
                }

                if($value['clients_orders_messages_files']){
                    $attach = '<div class="order-message-item-attach lightgallery" >';
                    foreach (explode(',', $value['clients_orders_messages_files']) as $file_name) {
                        if( file_exists( $config["basePath"] . "/" . $config["media"]["users"] . "/" . $file_name ) ){
                            $attach .= '<a href="'.$config["urlPath"] . "/" . $config["media"]["users"] . "/" . $file_name.'"><img class="image-autofocus" src="'.$config["urlPath"] . "/" . $config["media"]["users"] . "/" . $file_name.'" ></a>';
                        }                        
                    }
                    $attach .= '</div>';
                }

                $items .= '
                    <div>
                        <div class="order-messages-item '.$class.'" >
                            <div>
                            '.$name.'
                            <span class="order-messages-item-date" >'.datetime_format($value['clients_orders_messages_date']).'</span>
                            <div class="clr" ></div>
                            </div>
                            <span class="order-messages-item-message" >'.nl2br(decrypt($value['clients_orders_messages_message'])).'</span>
                            '.$attach.'
                        </div>
                    </div>
                ';

            }
            return $items;
        }
    }

    function getTariff($tariff_id=0){
        $results = [];
        if($tariff_id){
            $results['tariff'] = findOne('uni_services_tariffs', 'services_tariffs_id=?', [$tariff_id]);
            if($results['tariff']['services_tariffs_services']){
                $results['tariff']['services_tariffs_services'] = json_decode($results['tariff']['services_tariffs_services'], true);
                foreach ($results['tariff']['services_tariffs_services'] as $id) {
                    $getChecklist = findOne('uni_services_tariffs_checklist', 'services_tariffs_checklist_id=?', [$id]);
                    if($getChecklist) $results['services'][$getChecklist['services_tariffs_checklist_uid']] = $getChecklist;
                }
            }
        }
        return $results;
    }

    function calcPriceTariff($getTariff=[],$getTariffOrder=[]){
        
        $price_tariff = $getTariff['tariff']['services_tariffs_new_price'] ?: $getTariff['tariff']['services_tariffs_price'];
        $price_current = $getTariffOrder['services_tariffs_orders_price'];

        if($price_tariff > $price_current){

           $day = difference_days($getTariffOrder['services_tariffs_orders_date_completion'],date('Y-m-d H:i:s'))+1;
           if($day){
              $total = ($price_current / $getTariffOrder['services_tariffs_orders_days']) * $day;
              return $price_tariff - $total;
           }else{
              return $price_tariff;
           }
 
        }else{
           return $price_tariff; 
        }

    }

    function getOrderTariff($user_id=0){
        $results = [];
        $results = getOne('SELECT * FROM `uni_services_tariffs_orders` INNER JOIN `uni_services_tariffs` ON `uni_services_tariffs`.services_tariffs_id = `uni_services_tariffs_orders`.services_tariffs_orders_id_tariff WHERE services_tariffs_orders_id_user=?', [$user_id]);
        if($results['services_tariffs_services'] && (strtotime($results['services_tariffs_orders_date_completion']) > time() || !$results['services_tariffs_orders_date_completion'])){
            $results['services_tariffs_services'] = json_decode($results['services_tariffs_services'], true);
            foreach ($results['services_tariffs_services'] as $id) {
                $get = findOne('uni_services_tariffs_checklist', 'services_tariffs_checklist_id=?', [$id]);
                $results['services'][$get['services_tariffs_checklist_uid']] = $get;
            }
        }
        return $results;
    }

    function buttonPayTariff(){

        $ULang = new ULang();
        $Main = new Main();

        $getOnetime = findOne('uni_services_tariffs_onetime', 'services_tariffs_onetime_user_id=? and services_tariffs_onetime_tariff_id=?', [$_SESSION["profile"]["id"],$_SESSION["profile"]["tariff"]["services_tariffs_orders_id_tariff"]]);

        if($_SESSION["profile"]["tariff"]["services_tariffs_orders_days"] && strtotime($_SESSION["profile"]["tariff"]["services_tariffs_orders_date_completion"]) <= time() && $_SESSION["profile"]["tariff"]["services_tariffs_price"] && !$getOnetime){
           return '<span class="btn-custom-mini btn-color-danger profile-tariff-activate" data-id="'.$_SESSION["profile"]["tariff"]["services_tariffs_orders_id_tariff"].'" >'.$ULang->t("Оплатить").' '.$Main->price($_SESSION["profile"]["tariff"]["services_tariffs_new_price"] ?: $_SESSION["profile"]["tariff"]["services_tariffs_price"]).'</span>';
        }

    }

    function dataActionStatistics($action=''){

        $data = [];
        $days = [];
        $months = [];
        $years = [];
        $format = 'Y-m-d';

        $ad_id = (int)$_GET['ad'];
        $date_start = $_GET['date_start'];
        $date_end = $_GET['date_end'];

        if($date_start && $date_end){

            if(strtotime($date_end) > strtotime($date_start)){

                if(date("Y-m", strtotime($date_start)) == date("Y-m", strtotime($date_end))){
                    
                    $difference = difference_days($date_end,$date_start);

                    $days[ date($format, strtotime($date_start)) ] = date($format, strtotime($date_start));

                    $x=0;
                    while ($x++<$difference){
                       $days[ date($format, strtotime("+".$x." day", strtotime($date_start))) ] = date($format, strtotime("+".$x." day", strtotime($date_start)));
                    }

                    ksort($days);

                }elseif(date("Y", strtotime($date_start)) == date("Y", strtotime($date_end))){

                    $months[ date("Y-m", strtotime($date_start)) ] = date("Y-m", strtotime($date_start));

                    $new_m = (int)date("m", strtotime($date_end)) - (int)date("m", strtotime($date_start));

                    $x=0;
                    while ($x++<$new_m){
                       $months[ date("Y-m", strtotime("+".$x." month", strtotime($date_start))) ] = date("Y-m", strtotime("+".$x." month", strtotime($date_start)));
                    }   
                  
                }else{

                    $years[ date("Y", strtotime($date_start)) ] = date("Y", strtotime($date_start));

                    $new_y = (int)date("Y", strtotime($date_end)) - (int)date("Y", strtotime($date_start));

                    $x=0;
                    while ($x++<$new_y){
                       $years[ date("Y", strtotime("+".$x." year", strtotime($date_start))) ] = date("Y", strtotime("+".$x." year", strtotime($date_start)));
                    } 

                }

            }

        }elseif($date_start){

            $days[ date($format, strtotime($date_start)) ] = date($format, strtotime($date_start));

        }


        if(!$days && !$months && !$years){
            $x=0;
            while ($x++<30){
               $days[ date($format, strtotime("-".$x." day")) ] = date($format, strtotime("-".$x." day"));
            }

            $days[ date($format) ] = date($format);

            ksort($days);
        }

        if($action == 'display'){

            if($ad_id){
                if($days){
                    foreach ($days as $value) {
                        $data[] = (int)getOne("select sum(ads_views_display_count) as total from uni_ads_views_display where ads_views_display_id_user=? and date(ads_views_display_date)=? and ads_views_display_id_ad=?", [$_SESSION["profile"]["id"],$value,$ad_id])["total"];
                    }
                }elseif($months){
                    foreach ($months as $value) {
                        $explode = explode('-', $value);
                        $data[] = (int)getOne("select sum(ads_views_display_count) as total from uni_ads_views_display where ads_views_display_id_user=? and YEAR(ads_views_display_date)=? and MONTH(ads_views_display_date)=? and ads_views_display_id_ad=?", [$_SESSION["profile"]["id"],$explode[0],$explode[1],$ad_id])["total"];
                    }
                }elseif($years){
                    foreach ($years as $value) {
                        $data[] = (int)getOne("select sum(ads_views_display_count) as total from uni_ads_views_display where ads_views_display_id_user=? and YEAR(ads_views_display_date)=? and ads_views_display_id_ad=?", [$_SESSION["profile"]["id"],$value,$ad_id])["total"];
                    }
                }
            }else{
                if($days){
                    foreach ($days as $value) {
                        $data[] = (int)getOne("select sum(ads_views_display_count) as total from uni_ads_views_display where ads_views_display_id_user=? and date(ads_views_display_date)=?", [$_SESSION["profile"]["id"],$value])["total"];
                    }
                }elseif($months){
                    foreach ($months as $value) {
                        $explode = explode('-', $value);
                        $data[] = (int)getOne("select sum(ads_views_display_count) as total from uni_ads_views_display where ads_views_display_id_user=? and YEAR(ads_views_display_date)=? and MONTH(ads_views_display_date)=?", [$_SESSION["profile"]["id"],$explode[0],$explode[1]])["total"];
                    }
                }elseif($years){
                    foreach ($years as $value) {
                        $data[] = (int)getOne("select sum(ads_views_display_count) as total from uni_ads_views_display where ads_views_display_id_user=? and YEAR(ads_views_display_date)=?", [$_SESSION["profile"]["id"],$value])["total"];
                    }
                }                
            }

            return implode(',', $data);

        }elseif($action == 'view'){

            if($ad_id){
                if($days){
                    foreach ($days as $value) {
                        $data[] = (int)getOne("select count(*) as total from uni_ads_views where ads_views_id_user=? and date(ads_views_date)=? and ads_views_id_ad=?", [$_SESSION["profile"]["id"],$value,$ad_id])["total"];
                    }
                }elseif($months){
                    foreach ($months as $value) {
                        $explode = explode('-', $value);
                        $data[] = (int)getOne("select count(*) as total from uni_ads_views where ads_views_id_user=? and YEAR(ads_views_date)=? and MONTH(ads_views_date)=? and ads_views_id_ad=?", [$_SESSION["profile"]["id"],$explode[0],$explode[1],$ad_id])["total"];
                    }
                }elseif($years){
                    foreach ($years as $value) {
                        $data[] = (int)getOne("select count(*) as total from uni_ads_views where ads_views_id_user=? and YEAR(ads_views_date)=? and ads_views_id_ad=?", [$_SESSION["profile"]["id"],$value,$ad_id])["total"];
                    }
                }
            }else{
                if($days){
                    foreach ($days as $value) {
                        $data[] = (int)getOne("select count(*) as total from uni_ads_views where ads_views_id_user=? and date(ads_views_date)=?", [$_SESSION["profile"]["id"],$value])["total"];
                    }
                }elseif($months){
                    foreach ($months as $value) {
                        $explode = explode('-', $value);
                        $data[] = (int)getOne("select count(*) as total from uni_ads_views where ads_views_id_user=? and YEAR(ads_views_date)=? and MONTH(ads_views_date)=?", [$_SESSION["profile"]["id"],$explode[0],$explode[1]])["total"];
                    }
                }elseif($years){
                    foreach ($years as $value) {
                        $data[] = (int)getOne("select count(*) as total from uni_ads_views where ads_views_id_user=? and YEAR(ads_views_date)=?", [$_SESSION["profile"]["id"],$value])["total"];
                    }
                }                
            }

            return implode(',', $data);

        }elseif($action == 'favorites'){

            return $this->getCountActionStatistics($ad_id,$days,$months,$years,'favorite');

        }elseif($action == 'show_phone'){

            return $this->getCountActionStatistics($ad_id,$days,$months,$years,'show_phone');

        }elseif($action == 'ad_sell'){

            return $this->getCountActionStatistics($ad_id,$days,$months,$years,'ad_sell');

        }elseif($action == 'cart'){

            return $this->getCountActionStatistics($ad_id,$days,$months,$years,'add_to_cart');

        }elseif($action == 'booking'){

            return $this->getCountActionStatistics($ad_id,$days,$months,$years,'booking');

        }elseif($action == 'date'){

            if($days){
                foreach ($days as $value) {
                    $quotation_month[$value] = '"'.$value.'"';
                }
            }elseif($months){
                foreach ($months as $value) {
                    $quotation_month[$value] = '"'.$value.'"';
                }
            }elseif($years){
                foreach ($years as $value) {
                    $quotation_month[$value] = '"'.$value.'"';
                }
            }

            return implode(',',$quotation_month);

        }
    }

    function getCountActionStatistics($ad_id=0,$days=[],$months=[],$years=[],$action=''){

        $data = [];

        if($ad_id){
            if($days){
                foreach ($days as $value) {
                    $data[] = (int)getOne("select count(*) as total from uni_action_statistics where action_statistics_to_user_id=? and date(action_statistics_date)=? and action_statistics_ad_id=? and action_statistics_action=?", [$_SESSION["profile"]["id"],$value,$ad_id,$action])["total"];
                }
            }elseif($months){
                foreach ($months as $value) {
                    $explode = explode('-', $value);
                    $data[] = (int)getOne("select count(*) as total from uni_action_statistics where action_statistics_to_user_id=? and YEAR(action_statistics_date)=? and MONTH(action_statistics_date)=? and action_statistics_ad_id=? and action_statistics_action=?", [$_SESSION["profile"]["id"],$explode[0],$explode[1],$ad_id,$action])["total"];
                }
            }elseif($years){
                foreach ($years as $value) {
                    $data[] = (int)getOne("select count(*) as total from uni_action_statistics where action_statistics_to_user_id=? and YEAR(action_statistics_date)=? and action_statistics_ad_id=? and action_statistics_action=?", [$_SESSION["profile"]["id"],$value,$ad_id,$action])["total"];
                }
            }
        }else{
            if($days){
                foreach ($days as $value) {
                    $data[] = (int)getOne("select count(*) as total from uni_action_statistics where action_statistics_to_user_id=? and date(action_statistics_date)=? and action_statistics_action=?", [$_SESSION["profile"]["id"],$value,$action])["total"];
                }
            }elseif($months){
                foreach ($months as $value) {
                    $explode = explode('-', $value);
                    $data[] = (int)getOne("select count(*) as total from uni_action_statistics where action_statistics_to_user_id=? and YEAR(action_statistics_date)=? and MONTH(action_statistics_date)=? and action_statistics_action=?", [$_SESSION["profile"]["id"],$explode[0],$explode[1],$action])["total"];
                }
            }elseif($years){
                foreach ($years as $value) {
                    $data[] = (int)getOne("select count(*) as total from uni_action_statistics where action_statistics_to_user_id=? and YEAR(action_statistics_date)=? and action_statistics_action=?", [$_SESSION["profile"]["id"],$value,$action])["total"];
                }
            }                
        }

        return implode(',', $data);

    }

    function usersActionStatistics(){
        $ad_id = (int)$_GET['ad'];
        $data = [];

        if($ad_id){
            $get = getAll('select * from uni_action_statistics where action_statistics_to_user_id=? and action_statistics_ad_id=?', [$_SESSION["profile"]["id"],$ad_id]);
        }else{
            $get = getAll('select * from uni_action_statistics where action_statistics_to_user_id=?', [$_SESSION["profile"]["id"]]);
        }

        if(count($get)){
            foreach ($get as $value) {
                $getUser = findOne("uni_clients", "clients_id=?", [$value["action_statistics_from_user_id"]]);
                if($getUser) $data[$value['action_statistics_from_user_id']] = $getUser;
            }
        }
        return $data;
    }

    function refAlias($ref_id){
        return _link('ref/'.$ref_id);
    }

    function getActiveChatDialogs($userId=0){
       $groupByUsers = [];
       $getUsers = getAll("select * from uni_chat_users where chat_users_id_user=? order by chat_users_id desc", array($userId));
       if(count($getUsers)){

          foreach ($getUsers as $value) {

             $get = findOne("uni_clients", "clients_id=?", [$value["chat_users_id_interlocutor"]]);
             if($get) $groupByUsers[ $value["chat_users_id_hash"] ] = $value;

          }

       }
       return $groupByUsers;        
    }

    function getUserStories($category_id=0){
        
        $results = [];
        
        $queryLocation = "";
        $queryCategory = "";

        $CategoryBoard = new CategoryBoard();

        $getCategories = $CategoryBoard->getCategories("where category_board_visible=1");

        if(isset($_SESSION["geo"]["data"])){
            if($_SESSION["geo"]["data"]["city_id"]){
                $queryLocation = "and (clients_stories_media_city_id='".$_SESSION["geo"]["data"]["city_id"]."' or (clients_stories_media_city_id=0 and clients_stories_media_region_id=0 and clients_stories_media_country_id=0))";
            }elseif($_SESSION["geo"]["data"]["region_id"]){
                $queryLocation = "and (clients_stories_media_region_id='".$_SESSION["geo"]["data"]["region_id"]."' or (clients_stories_media_city_id=0 and clients_stories_media_region_id=0 and clients_stories_media_country_id=0))";
            }elseif($_SESSION["geo"]["data"]["country_id"]){
                $queryLocation = "and (clients_stories_media_country_id='".$_SESSION["geo"]["data"]["country_id"]."' or (clients_stories_media_city_id=0 and clients_stories_media_region_id=0 and clients_stories_media_country_id=0))";
            }
        }

        if($category_id){
            $ids_cat = idsBuildJoin($CategoryBoard->idsBuild($category_id, $getCategories), $category_id);
            $queryCategory = "and clients_stories_media_cat_id IN(".$ids_cat.")";            
        }

        $getUserStories = getAll("select * from uni_clients_stories order by clients_stories_timestamp desc");
        if($getUserStories){
            foreach ($getUserStories as $key => $value) {

                $getUser = findOne('uni_clients', 'clients_id=?', [$value['clients_stories_user_id']]);

                if($value['clients_stories_user_id'] == $_SESSION['profile']['id']){
                    if(!$category_id){
                        $getLastStory = findOne('uni_clients_stories_media', 'clients_stories_media_user_id=? and clients_stories_media_loaded=? order by clients_stories_media_timestamp desc', [$value['clients_stories_user_id'],1]);
                    }else{
                        $getLastStory = findOne('uni_clients_stories_media', 'clients_stories_media_user_id=? and clients_stories_media_loaded=? and clients_stories_media_status=? '.$queryCategory.' order by clients_stories_media_timestamp desc', [$value['clients_stories_user_id'],1,1]);
                    }
                }else{
                    $getLastStory = findOne('uni_clients_stories_media', 'clients_stories_media_user_id=? and clients_stories_media_loaded=? and clients_stories_media_status=? '.$queryLocation.' '.$queryCategory.' order by clients_stories_media_timestamp desc', [$value['clients_stories_user_id'],1,1]);
                }

                if($getLastStory && $getUser){
                    $results[] = [
                        "id" => $value['clients_stories_id'],
                        "user_id" => $value['clients_stories_user_id'],
                        "user_name" => $this->name($getUser),
                        "user_avatar" => $this->userAvatar($getUser),
                        "timestamp" => $value['clients_stories_timestamp'],
                    ];
                }

            }
        }
        return $results;
    }

    function outUserStories($add=true,$category_id=0, $margin=""){

        global $settings,$config;

        $ULang = new ULang();
        $CategoryBoard = new CategoryBoard();

        $getCategories = $CategoryBoard->getCategories("where category_board_visible=1");

        $data["stories"] = $this->getUserStories($category_id);

        $queryLocation = "";
        $queryCategory = "";

        if(isset($_SESSION["geo"]["data"])){
            if($_SESSION["geo"]["data"]["city_id"]){
                $queryLocation = "and (clients_stories_media_city_id='".$_SESSION["geo"]["data"]["city_id"]."' or (clients_stories_media_city_id=0 and clients_stories_media_region_id=0 and clients_stories_media_country_id=0))";
            }elseif($_SESSION["geo"]["data"]["region_id"]){
                $queryLocation = "and (clients_stories_media_region_id='".$_SESSION["geo"]["data"]["region_id"]."' or (clients_stories_media_city_id=0 and clients_stories_media_region_id=0 and clients_stories_media_country_id=0))";
            }elseif($_SESSION["geo"]["data"]["country_id"]){
                $queryLocation = "and (clients_stories_media_country_id='".$_SESSION["geo"]["data"]["country_id"]."' or (clients_stories_media_city_id=0 and clients_stories_media_region_id=0 and clients_stories_media_country_id=0))";
            }
        }

        if($category_id){
            $ids_cat = idsBuildJoin($CategoryBoard->idsBuild($category_id, $getCategories), $category_id);
            $queryCategory = "and clients_stories_media_cat_id IN(".$ids_cat.")";            
        }

        if($add){
            echo $margin;
        }else{
            if($data["stories"]){
                echo $margin;
            }
        }

        ?>
        <div class="slider-user-stories mb25" >

        <?php if($_SESSION['profile']['id'] && $add){ ?>
        <div>
            <div class="slider-user-stories-add open-modal" data-id-modal="modal-user-story-add" >
                <div class="slider-user-stories-item-user" >
                    <div class="slider-user-stories-item-avatar" ><img src="<?php echo $settings["path_tpl_image"]; ?>/plus-icon.png" /></div>
                    <div class="slider-user-stories-item-name" ><?php echo $ULang->t( "Добавить" ); ?></div>
                </div>
            </div>
        </div>                    
        <?php
        }

        if($data["stories"]){

            foreach ($data["stories"] as $key => $value) {

                if($value['user_id'] == $_SESSION['profile']['id']){
                    if(!$category_id){
                        $getLastStory = findOne('uni_clients_stories_media', 'clients_stories_media_user_id=? and clients_stories_media_loaded=? order by clients_stories_media_timestamp desc', [$value['user_id'],1]);
                    }else{
                        $getLastStory = findOne('uni_clients_stories_media', 'clients_stories_media_user_id=? and clients_stories_media_loaded=? and clients_stories_media_status=? '.$queryCategory.' order by clients_stories_media_timestamp desc', [$value['user_id'],1,1]);
                    }
                }else{
                    $getLastStory = findOne('uni_clients_stories_media', 'clients_stories_media_user_id=? and clients_stories_media_loaded=? and clients_stories_media_status=? '.$queryLocation.' '.$queryCategory.' order by clients_stories_media_timestamp desc', [$value['user_id'],1,1]);
                }

                if($getLastStory){
                    
                    if($getLastStory['clients_stories_media_type'] == 'image'){
                        if(file_exists($config['basePath'].'/'.$config['media']['user_stories'].'/'.$getLastStory['clients_stories_media_name'])){
                            $imageStory = $config['urlPath'].'/'.$config['media']['user_stories'].'/'.$getLastStory['clients_stories_media_name'];
                        }
                    }else{
                        if(file_exists($config['basePath'].'/'.$config['media']['user_stories'].'/'.$getLastStory['clients_stories_media_preview'])){
                            $imageStory = $config['urlPath'].'/'.$config['media']['user_stories'].'/'.$getLastStory['clients_stories_media_preview'];
                        }
                    }

                    if(isset($_COOKIE['viewStory'.$value['user_id']])){ 
                        if(strtotime($value["timestamp"]) > $_COOKIE['viewStory'.$value['user_id']]){
                            $statusViewed = "stories-item-no-viewed";
                        }else{
                            $statusViewed = "stories-item-viewed";
                        }
                    }else{
                        $statusViewed = "stories-item-no-viewed";
                    }

                    if($imageStory){
                        ?>
                        <div>
                            <div class="slider-user-stories-item" data-index="<?php echo $key; ?>" data-id="<?php echo $value["id"]; ?>" style="background-image: linear-gradient(rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0.24) 75%, rgba(0, 0, 0, 0.64)), url(<?php echo $imageStory; ?>); background-position: center center; background-size: cover;" >
                                
                                <div class="slider-user-stories-item-user" >
                                    <div class="slider-user-stories-item-avatar <?php echo $statusViewed; ?>" ><img src="<?php echo $value["user_avatar"]; ?>" class="image-autofocus" /></div>
                                    <div class="slider-user-stories-item-name"  ><?php echo $value["user_name"]; ?></div>
                                </div>
                            </div>
                        </div>                
                        <?
                    }

                }

            }

        }

        ?>
        </div>
        <?php
    }

    function countViewStories($storyId=0){
        if($storyId){
            return (int)getOne('select count(*) as total from uni_clients_stories_view where story_id=?', [$storyId])['total'];
        }
        return 0;
    }

    function getPackagesCategories(){

        $CategoryBoard = new CategoryBoard();

        $idsPackagesCategories = [];
        $getPackagesCategories = getAll("select * from uni_ads_packages_categories");

        $getCategories = $CategoryBoard->getCategories("where category_board_visible=1");

        foreach ($getPackagesCategories as $key => $value) {
          $ids_cat = $CategoryBoard->reverseId($getCategories,$value["cat_id"]);
          foreach (explode(",", $ids_cat) as $id) {
            $idsPackagesCategories[$id] = $id;
          }
        }

        if($idsPackagesCategories){
            $getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1 and category_board_id IN(".implode(",", $idsPackagesCategories).")");
            return $getCategoryBoard;
        } 

        return [];   

    }

    function addUserInterest($cat_id=0, $user_id=0){

        $CategoryBoard = new CategoryBoard();

        $getCategories = $CategoryBoard->getCategories("where category_board_visible=1");

        if($user_id){
            if($cat_id){
                $idsBuild = $CategoryBoard->idsBuild($cat_id, $getCategories);
                if($idsBuild){
                    foreach (explode(",", $idsBuild) as $value) {
                        $checkCat = findOne('uni_clients_interests', 'cat_id=? and user_id=?', [$value,$user_id]);
                        if(!$checkCat){
                            insert("INSERT INTO uni_clients_interests(cat_id,user_id,date_view)VALUES(?,?,?)", array($value,$user_id,date("Y-m-d H:i:s")));
                        }
                    }
                }else{
                    $checkCat = findOne('uni_clients_interests', 'cat_id=? and user_id=?', [$cat_id,$user_id]);
                    if(!$checkCat){
                        insert("INSERT INTO uni_clients_interests(cat_id,user_id,date_view)VALUES(?,?,?)", array($cat_id,$user_id,date("Y-m-d H:i:s")));
                    }else{
                        update("update uni_clients_interests set date_view=?, count_view=count_view+1 where id=?", [date("Y-m-d H:i:s"), $checkCat["id"]]);
                    }                    
                }
            }
        }

    }

    function deleteUserStory($story_id=0, $user_id=0, $ad_id=0){

        global $config;

        if($user_id){

            if($story_id){

                $getStory = findOne("uni_clients_stories_media", "clients_stories_media_id=? and clients_stories_media_user_id=?", array($story_id,$user_id));

                @unlink($config['basePath'].'/'.$config['media']['user_stories'].'/'.$getStory['clients_stories_media_name']);

                if($getStory['clients_stories_media_preview']) @unlink($config['basePath'].'/'.$config['media']['user_stories'].'/'.$getStory['clients_stories_media_preview']);

                update("delete from uni_clients_stories_media where clients_stories_media_id=?", array($story_id));

                update('delete from uni_clients_stories_view where story_id=?', [$story_id]);

                $checkStoryMedia = getAll('select * from uni_clients_stories_media where clients_stories_media_user_id=?', [$getStory['clients_stories_media_user_id']]);

                if(!$checkStoryMedia){
                    update("delete from uni_clients_stories where clients_stories_user_id=?", array($getStory['clients_stories_media_user_id']));
                }

            }elseif($ad_id){

                $getStories = getAll("select * from uni_clients_stories_media where clients_stories_media_ad_id=? and clients_stories_media_user_id=?", array($ad_id,$user_id));
                
                foreach ($getStories as $key => $value) {
                    
                    @unlink($config['basePath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_name']);

                    if($value['clients_stories_media_preview']) @unlink($config['basePath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_preview']);

                    update("delete from uni_clients_stories_media where clients_stories_media_id=?", array($value["clients_stories_media_id"]));

                    update('delete from uni_clients_stories_view where story_id=?', [$value["clients_stories_media_id"]]);

                }

                $checkStoryMedia = getAll('select * from uni_clients_stories_media where clients_stories_media_user_id=?', [$user_id]);

                if(!$checkStoryMedia){
                    update("delete from uni_clients_stories where clients_stories_user_id=?", array($user_id));
                }

            }

        }

    }

    function userStoryDateCompletion($user_id=0){
        global $settings;
        $Profile = new Profile();
        $getTariff = $Profile->getOrderTariff($user_id);
        if($getTariff['services']['stories_3_days']){
            return date("Y-m-d H:i:s", strtotime("+3 days", time()));
        }elseif($getTariff['services']['stories_7_days']){
            return date("Y-m-d H:i:s", strtotime("+7 days", time()));
        }else{
            return date("Y-m-d H:i:s", strtotime("+".$settings["user_stories_period_add"]." hours", time()));
        }
    }

    function addCollageAdsToStory($getAd=[],$user_id=0,$price=0){

        global $config,$settings;

        $adsImages = [];
        $adsCollage = [];

        $Ads = new Ads();
        $Profile = new Profile();
        $Admin = new Admin();

        $getTariff = $Profile->getOrderTariff($user_id);

        $storyFilename = md5("story_".$user_id."_".uniqid()).".webp";

        $adsImages = $Ads->getImages($getAd["ads_images"]);

        if($adsImages){

          foreach (array_slice($adsImages, 0, 4) as $value) {
            if(file_exists($config["basePath"]."/".$config["media"]["big_image_ads"]."/".$value)){
                $adsCollage[] = $config["basePath"] . "/" . $config["media"]["big_image_ads"]. "/" . $value;
            }
          }

          if($adsCollage){ 
            $collage = new Tzsk\Collage\MakeCollage();
            $image = $collage->make(600, 600)->padding(5)->background('#000')->from($adsCollage);
            if(!$image->save($config["basePath"] . "/" . $config["media"]["user_stories"] . "/" . $storyFilename)){
              copy($config["basePath"] . "/" . $config["template_folder"] . "/images/no-photo-ads-stories.png", $config["basePath"] . "/" . $config["media"]["user_stories"] . "/" . $storyFilename);
            }
          }else{
            copy($config["basePath"] . "/" . $config["template_folder"] . "/images/no-photo-ads-stories.png", $config["basePath"] . "/" . $config["media"]["user_stories"] . "/" . $storyFilename);
          }

        }else{

          copy($config["basePath"] . "/" . $config["template_folder"] . "/images/no-photo-ads-stories.png", $config["basePath"] . "/" . $config["media"]["user_stories"] . "/" . $storyFilename);

        }
        
        $getStory = findOne('uni_clients_stories', 'clients_stories_user_id=?', [$user_id]);

        if(!$getStory){
            smart_insert('uni_clients_stories', [
            'clients_stories_user_id'=>$user_id,
            'clients_stories_timestamp'=>date("Y-m-d H:i:s"),
            ]);   
        }else{
            update('update uni_clients_stories set clients_stories_timestamp=? where clients_stories_user_id=?', [date("Y-m-d H:i:s"),$user_id]);
        }

        smart_insert('uni_clients_stories_media', [
        'clients_stories_media_user_id'=>$user_id,
        'clients_stories_media_name'=>$storyFilename,
        'clients_stories_media_type'=>'image',
        'clients_stories_media_duration'=>intval($settings["user_stories_video_length"]),
        'clients_stories_media_ad_id'=>$getAd["ads_id"],
        'clients_stories_media_loaded'=>1,
        'clients_stories_media_payment'=>1,
        'clients_stories_media_payment_amount'=>$price,
        'clients_stories_media_timestamp'=>date("Y-m-d H:i:s"),
        'clients_stories_media_status'=>$getAd["ads_status"] == 1 ? 1 : 0,
        'clients_stories_media_city_id'=>$getAd["city_id"],
        'clients_stories_media_region_id'=>$getAd["region_id"],
        'clients_stories_media_country_id'=>$getAd["country_id"],
        'clients_stories_media_cat_id'=>$getAd["ads_id_cat"],
        'clients_stories_media_not_delete'=>$getTariff['services']['stories_without_limitation'] ? 1 : 0,
        'clients_stories_media_date_completion'=>$Profile->userStoryDateCompletion($user_id),
        ]);

        $Admin->notifications("user_story");

    }


}
?>