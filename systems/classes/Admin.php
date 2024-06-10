<?php
/**
 * UniSite CMS
 *
 * @copyright   2018 Artur Zhur
 * @link    https://unisitecms.ru
 * @author    Artur Zhur
 *
 */
 
class Admin{

	function getPages(){
	    global $settings;
	    $arr = array();
	      if($settings["array_pages"]){
	         $settings["array_pages"] = json_decode($settings["array_pages"],true);
	           foreach($settings["array_pages"] AS $pages=>$path){
	              $arr[$pages] = $path;
	           }
	         return $arr;  
	      }
	}

    function checkPages($page){
	   $array = $this->getPages();
	      if(isset($array[$page])){ 
	          return $array[$page]; 
	      }else{
	          return "/index/index"; 
	      }      
    } 

	function setPrivileges($privileges){ 
	  if($privileges){
	    $exp = explode(",",$privileges);
	      if(count($exp)>0){  
	         foreach($exp AS $value){
	            $_SESSION["cp_".$value] = 1;
	         }
	      }
	  }
	}

	function salesOrders($query=[]){

		$Main = new Main();

		if($query){

		  $all = getOne("select sum(orders_price) as total from uni_orders where orders_status_pay=1 and ".implode(" and ",$query))["total"];
		  $now = getOne("select sum(orders_price) as total from uni_orders where orders_status_pay=1 and date(orders_date) = date(now()) and ".implode(" and ",$query))["total"];
		  $month = getOne("select sum(orders_price) as total from uni_orders where orders_status_pay=1 and YEAR(orders_date) = YEAR(now()) AND MONTH(orders_date) = MONTH(now()) and ".implode(" and ",$query))["total"];

		}else{

		  $all = getOne("select sum(orders_price) as total from uni_orders where orders_status_pay=1")["total"];
		  $now = getOne("select sum(orders_price) as total from uni_orders where orders_status_pay=1 and date(orders_date) = date(now())")["total"];
		  $month = getOne("select sum(orders_price) as total from uni_orders where orders_status_pay=1 and YEAR(orders_date) = YEAR(now()) AND MONTH(orders_date) = MONTH(now())")["total"];

		}
	  
	  return [ "all" => $Main->price($all), "now" => $Main->price($now), "month" => $Main->price($month) ];

	}

	function areaOrders(){

	    $x=0;
	    while ($x++<30){
	       $month[ date('Y-m-d', strtotime("-".$x." day")) ] = date('Y-m-d', strtotime("-".$x." day"));
	    }

	    $month[ date('Y-m-d') ] = date('Y-m-d');

	    ksort($month);

	    foreach ($month as $key => $value) {
	    	$data[] = round( getOne("select sum(orders_price) as total from uni_orders where orders_status_pay=? and date(orders_date)=?", [1,$value])["total"], 2 );
	    	$date[] = date( "d", strtotime($value) );
	    }

	    return [ "data" => json_encode($data),"date" => json_encode($date) ]; 

	}

	function notifications($action, $param = array()){
	   global $settings,$config;

	   $static_msg = require $config["basePath"] . "/static/msg.php";
	   
	   $Main = new Main();
	   $geo = (new Geo())->geoIp( $_SERVER["REMOTE_ADDR"], false );

	   if($action == "ads"){

        $title = "Публикация объявления";
        $link = "board";

	      if($settings["notification_method_new_ads"]){
	       $notification_method_new_ads = explode(",",$settings["notification_method_new_ads"]);
	           if(in_array("email", $notification_method_new_ads)){
	            
	             $data = array("{ADS_TITLE}"=>$param["title"],
	                           "{ADS_LINK}"=>$param["link"],
	                           "{USER_NAME}"=>$param["user_name"],
	                           "{USER_LINK}"=>_link( "user/".$param["id_hash_user"]),                           
	                           "{ADS_IMAGE_LINK}"=>Exists( $config["media"]["medium_image_ads"],$param["image"],$config["media"]["no_image"] ),
	                           "{EMAIL_TO}"=>$settings["email_alert"]
	                           );

	             email_notification( array( "variable" => $data, "code" => "ADMIN_NEW_ADS" ) );

	           }
	           if(in_array("telegram", $notification_method_new_ads)){
	              telegram( $static_msg["36"]."\n\n".$static_msg["37"]." - ".$settings["site_name"]."\n".$static_msg["38"]." - <a href=\"".$param["link"]."\" >".$param["title"]."</a>\n".$static_msg["39"]." - ". $param["user_name"] ." ".$static_msg["40"]." <a href=\""._link( "user/".$param["id_hash_user"])."\" >".$static_msg["41"]."</a>" );
	           }
	      }
	   }elseif($action == "buy"){

        $title = "Продажа";
        $link = "orders";

	      if($settings["notification_method_new_buy"]){
	       $notification_method_new_buy = explode(",",$settings["notification_method_new_buy"]);
	           if(in_array("email", $notification_method_new_buy)){
	            
	             $data = array("{ORDER_TITLE}"=>$param["title"],
	                           "{ORDER_PRICE}"=>$Main->price($param["price"]),
	                           "{USER_NAME}"=>$param["user_name"],
	                           "{USER_LINK}"=>_link( "user/".$param["id_hash_user"]),
	                           "{EMAIL_TO}"=>$settings["email_alert"]
	                           );

	              email_notification( array( "variable" => $data, "code" => "ADMIN_NEW_BUY" ) );

	           }
	           if(in_array("telegram", $notification_method_new_buy)){
	              $status = telegram( $static_msg["42"]."\n\n".$static_msg["37"]." - " . $settings["site_name"] . "\n".$static_msg["43"]." - " . $param["title"] . "\n".$static_msg["44"]." - " . $Main->price($param["price"]) . "\n".$static_msg["45"]." - " . $param["user_name"] . " " . $static_msg["40"] . " <a href=\""._link( "user/".$param["id_hash_user"])."\" >".$static_msg["41"]."</a>" );
	           }
	      }

	   }elseif($action == "user"){

        $title = "Регистрация пользователя";
        $link = "clients";

	      if($settings["notification_method_new_user"]){
	       $notification_method_new_user = explode(",",$settings["notification_method_new_user"]);
	           if(in_array("email", $notification_method_new_user)){
	            
	             $data = array("{USER_NAME}"=>$param["user_name"],
	                           "{USER_EMAIL}"=>$param["user_email"],
	                           "{USER_PHONE}"=>$param["user_phone"],
	                           "{EMAIL_TO}"=>$settings["email_alert"],
	                           "{USER_GEO}"=>$geo,
	                           );

	              email_notification( array( "variable" => $data, "code" => "ADMIN_NEW_USER" ) );

	           }
	           if(in_array("telegram", $notification_method_new_user)){
	              telegram($static_msg["46"]."\n\n".$static_msg["37"]." - ". $settings["site_name"] ."\n".$static_msg["47"]." - ". $param["user_name"] ."\n".$static_msg["14"]." - ". $param["user_email"] ."\n".$static_msg["48"]." - ". $param["user_phone"] ."\n".$static_msg["49"]." - " . $geo);
	           }
	      }

	   }elseif($action == "chat_message"){

        $title = "Новое сообщение в чате";
        $link = "chat";

	      if($settings["notification_method_new_chat_message"]){
	       $notification_method_new_chat_message = explode(",",$settings["notification_method_new_chat_message"]);
	           if(in_array("email", $notification_method_new_chat_message)){
	            
	             $data = array("{EMAIL_TO}"=>$settings["email_alert"]);

	             email_notification( array( "variable" => $data, "code" => "ADMIN_NEW_CHAT_MESSAGE" ) );

	           }
	           if(in_array("telegram", $notification_method_new_chat_message)){
	              telegram($static_msg["56"]);
	           }
	      }

	   }elseif($action == "feedback"){

        $title = "Обращение с формы feedback";
        $link = "";

	      if($settings["notification_method_feedback"]){
	       $notification_method_feedback = explode(",",$settings["notification_method_feedback"]);
	           if(in_array("email", $notification_method_feedback)){
	            
	             $data = array("{EMAIL_TO}"=>$settings["email_alert"],"{TEXT}"=>$static_msg["13"]." - ". $param["name"] ."<br>".$static_msg["14"]." - ". $param["email"]."<br><br>".$param["text"],"{SUBJECT}"=>$title);

	             email_notification( array( "variable" => $data, "code" => "ADMIN_NOTIFICATIONS" ) );

	           }
	           if(in_array("telegram", $notification_method_feedback)){
	              telegram($static_msg["63"]."\n\n".$static_msg["13"]." - ". $param["name"] ."\n".$static_msg["14"]." - ". $param["email"] ."\n".$static_msg["15"].": ". $param["text"]);
	           }
	      }

	   }elseif($action == "complaint"){

        $title = "Жалоба";
        $link = "complaints";

	      if($settings["notification_method_complaint"]){
	       $notification_method_complaint = explode(",",$settings["notification_method_complaint"]);
	           if(in_array("email", $notification_method_complaint)){
	            
	             $data = array("{EMAIL_TO}"=>$settings["email_alert"],"{SUBJECT}"=>$title, "{TEXT}"=>"");

	             email_notification( array( "variable" => $data, "code" => "ADMIN_NOTIFICATIONS" ) );

	           }
	           if(in_array("telegram", $notification_method_complaint)){
	              telegram($title);
	           }
	      }

	   }elseif($action == "review"){

        $title = "Добавлен новый отзыв";
        $link = "reviews";

	      if($settings["notification_method_reviews"]){
	       $notification_method_reviews = explode(",",$settings["notification_method_reviews"]);
	           if(in_array("email", $notification_method_reviews)){
	            
	             $data = array("{EMAIL_TO}"=>$settings["email_alert"],"{SUBJECT}"=>$title, "{TEXT}"=>"");

	             email_notification( array( "variable" => $data, "code" => "ADMIN_NOTIFICATIONS" ) );

	           }
	           if(in_array("telegram", $notification_method_reviews)){
	              telegram($title);
	           }
	      }

	   }elseif($action == "user_story"){

        $title = "Добавлен новый сторис";
        $link = "stories";

	      if($settings["notification_method_stories"]){
	       $notification_method_stories = explode(",",$settings["notification_method_stories"]);
	           if(in_array("email", $notification_method_stories)){
	            
	             $data = array("{EMAIL_TO}"=>$settings["email_alert"],"{SUBJECT}"=>$title, "{TEXT}"=>"");

	             email_notification( array( "variable" => $data, "code" => "ADMIN_NOTIFICATIONS" ) );

	           }
	           if(in_array("telegram", $notification_method_stories)){
	              telegram($title);
	           }
	      }

	   }elseif($action == "verification"){

        $title = "Запрос на верификацию";
        $link = "clients_verifications";

	      if($settings["notification_method_verification"]){
	       $notification_method_verification = explode(",",$settings["notification_method_verification"]);
	           if(in_array("email", $notification_method_verification)){
	            
	             $data = array("{EMAIL_TO}"=>$settings["email_alert"],"{SUBJECT}"=>$title, "{TEXT}"=>"");

	             email_notification( array( "variable" => $data, "code" => "ADMIN_NOTIFICATIONS" ) );

	           }
	           if(in_array("telegram", $notification_method_verification)){
	              telegram($title);
	           }
	      }

	   }elseif($action == "secure"){

        $title = "Оформлена безопасная сделка";
        $link = "secure";

	      if($settings["notification_method_secure"]){
	       $notification_method_secure = explode(",",$settings["notification_method_secure"]);
	           if(in_array("email", $notification_method_secure)){
	            
	             $data = array("{EMAIL_TO}"=>$settings["email_alert"],"{SUBJECT}"=>$title, "{TEXT}"=>"");

	             email_notification( array( "variable" => $data, "code" => "ADMIN_NOTIFICATIONS" ) );

	           }
	           if(in_array("telegram", $notification_method_secure)){
	              telegram($title);
	           }
	      }

	   }elseif($action == "booking"){

        $title = "Запрос на выплату аренды/бронирования";
        $link = "booking";

	      if($settings["notification_method_booking"]){
	       $notification_method_booking = explode(",",$settings["notification_method_booking"]);
	           if(in_array("email", $notification_method_booking)){
	            
	             $data = array("{EMAIL_TO}"=>$settings["email_alert"],"{SUBJECT}"=>$title, "{TEXT}"=>"");

	             email_notification( array( "variable" => $data, "code" => "ADMIN_NOTIFICATIONS" ) );

	           }
	           if(in_array("telegram", $notification_method_booking)){
	              telegram($title);
	           }
	      }

	   }elseif($action == "shops"){

        $title = "Открытие магазина";
        $link = "shops";

	      if($settings["notification_method_shops"]){
	       $notification_method_shops = explode(",",$settings["notification_method_shops"]);
	           if(in_array("email", $notification_method_shops)){
	            
	             $data = array("{EMAIL_TO}"=>$settings["email_alert"],"{SUBJECT}"=>$title, "{TEXT}"=>"");

	             email_notification( array( "variable" => $data, "code" => "ADMIN_NOTIFICATIONS" ) );

	           }
	           if(in_array("telegram", $notification_method_shops)){
	              telegram($title);
	           }
	      }

	   }elseif($action == "shops_edit"){

        $title = "Запрос на модерацию магазина";
        $link = "shops";

	      if($settings["notification_method_shops"]){
	       $notification_method_shops = explode(",",$settings["notification_method_shops"]);
	           if(in_array("email", $notification_method_shops)){
	            
	             $data = array("{EMAIL_TO}"=>$settings["email_alert"],"{SUBJECT}"=>$title, "{TEXT}"=>$param["shop_name"]."<br>".$param["shop_link"]);

	             email_notification( array( "variable" => $data, "code" => "ADMIN_NOTIFICATIONS" ) );

	           }
	           if(in_array("telegram", $notification_method_shops)){
								telegram($title."\n\n".$param["shop_name"]."\n".$param["shop_link"]);
	           }
	      }

	   }

	   if($link){
		   smart_insert("uni_notifications",[
		      "title"=>$title,
		      "datetime"=>date("Y-m-d H:i:s"),
		      "code"=>$action,
		      "link"=>$link,
		      "data"=>json_encode($param),
		   ]);
	   }

	}

	function browser($user_agent){
	  if (strpos($user_agent, "Firefox/") !== false) $browser = "Firefox";
	  elseif (strpos($user_agent, "Opera/") !== false || strpos($user_agent, 'OPR/') !== false ) $browser = "Opera";
	  elseif (strpos($user_agent, "YaBrowser/") !== false) $browser = "Yandex";      
	  elseif (strpos($user_agent, "Chrome/") !== false) $browser = "Chrome";
	  elseif (strpos($user_agent, "MSIE/") !== false || strpos($user_agent, 'Trident/') !== false ) $browser = "Explorer";
	  elseif (strpos($user_agent, "Safari/") !== false) $browser = "Safari";
	  else $browser = "Undefined";
	  return $browser;    
	}

	function setMode(){
		global $config;
		update("UPDATE uni_admin SET datetime_view = ? WHERE id=?", array(date("Y-m-d H:i:s"),$_SESSION['cp_auth'][ $config["private_hash"] ]["id"]));
	}

	function manager_filesize($filesize)
	{

	   if($filesize > 1024)
	   {
	       $filesize = ($filesize/1024);
	       if($filesize > 1024)
	       {
	            $filesize = ($filesize/1024);
	           if($filesize > 1024)
	           {
	               $filesize = ($filesize/1024);
	               $filesize = round($filesize, 1);
	               return $filesize." Gb";       
	           }
	           else
	           {
	               $filesize = round($filesize, 1);
	               return $filesize." Mb";   
	           }       
	       }
	       else
	       {
	           $filesize = round($filesize, 1);
	           return $filesize." Kb";   
	       }  
	   }
	   else
	   {
	       $filesize = round($filesize, 1);
	       return $filesize." byte";   
	   }
	}

	function manager_total_size( $dir = "" ){
		global $config;
	    if(is_dir($dir)){
	    	$name = scandir($dir);
	        for($i=2; $i<=(sizeof($name)-1); $i++) {
	           if(is_file($dir.$name[$i]) && $name[$i] != '.'){ 
	            $total += filesize($dir.$name[$i]);
	           }
	        }
	      return get_filesize($total);  
	    }     
	}

	function getFile($dir){
        if(file_exists($dir)){ 

         $fp = @fopen($dir, 'r' );
          if ($fp) {
              $size = @filesize($dir);
              $content = @fread($fp, $size);
              @fclose ($fp); 
          }

          return trim($content);
        }		
	}

	function warningSystems(){
	global $settings,$config;

	   if(!$settings["cron_systems_status"]){
	   	  $data["cron"] = 1;
	   	  $count_warning += 1;
	   }

	   if($data["cron"]){
	   	  $alert = "alert-warning";
	   	  $check = "";
	   }else{
	   	  $alert = "alert-success";
	   	  $check = '<span class="alert-status-check" ><i class="la la-check"></i></span>';
	   }

	   if( $settings["cron_datetime_update"] ){
	   	   $cron_datetime_update = '<br><strong>Последнее выполнение:</strong> ' . date( "d.m.Y H:i:s", strtotime($settings["cron_datetime_update"]) );
	   }

	   $warning .= '
				  <div class="alert-custom '.$alert.'">
				    '.$check.'
				    Необходимо включить cron для выполнения системных функций сайта. Создайте запись в cron журнале вашего хостинга или сервера.
				        <hr> 
				        <strong>Скрипт:</strong> '.$config["urlPath"].'/systems/cron/cron_systems.php?key='.$config["cron_key"].'<br> 
				        <strong>Интервал выполнения:</strong> 1 минута
				        '.$cron_datetime_update.'
                        <hr>
				        <a href="https://unisite.org/doc/kak-dobavit-cron-zapis" ><i class="la la-question-circle" style="font-size: 18px;" ></i> Документация по настройке cron</a>
				  </div>
	   ';
    
        if(!$settings["robots_index_site"]){
            $count_warning += 1;
	    }

	   if(!$settings["robots_index_site"]){
	   	  $alert = "alert-warning";
	   	  $check = "";
	   }else{
	   	  $alert = "alert-success";
	   	  $check = '<span class="alert-status-check" ><i class="la la-check"></i></span>';
	   }

        $warning .= '
			  <div class="alert-custom '.$alert.'" >
			  '.$check.'
			  Сайт отключен от индексации поисковыми системами, после настройки сайта вам необходимо включить индексацию в настройках <a href="?route=settings&tab=robots">robots.txt</a>
			  </div>
		';

        if(!$settings["site_name"] || !$settings["title"]){
	        $data["site_name"] = 1;
	        $count_warning += 1;
        }

	   if($data["site_name"]){
	   	  $alert = "alert-warning";
	   	  $check = "";
	   }else{
	   	  $alert = "alert-success";
	   	  $check = '<span class="alert-status-check" ><i class="la la-check"></i></span>';
	   }
        
        $warning .= '
			  <div class="alert-custom '.$alert.'">
			    '.$check.'
			    Необходимо указать в настройках сайта "Название сайта/проекта" и "Заголовок сайта" эти данные будут отображаться на сайте и в email сообщениях.
			  </div>
        ';

        if(!$settings["sms_service_login"] && !$settings["sms_service_id"]){
	       $data["sms"] = 1;
	       $count_warning += 1;
        }

	   if($data["sms"]){
	   	  $alert = "alert-warning";
	   	  $check = "";
	   }else{
	   	  $alert = "alert-success";
	   	  $check = '<span class="alert-status-check" ><i class="la la-check"></i></span>';
	   }

        $warning .= '
			  <div class="alert-custom '.$alert.'">
			    '.$check.'
			    Необходимо произвести интеграцию с сервисом СМС рассылок. Для этого перейдите в <a href="?route=settings&tab=integrations" >интеграции</a>
			  </div>
        ';

        if(!$settings["map_yandex_key"] && !$settings["map_google_key"] && !$settings["map_openstreetmap_key"]){
	       $data["map"] = 1;
	       $count_warning += 1;
        }

	   if($data["map"]){
	   	  $alert = "alert-warning";
	   	  $check = "";
	   }else{
	   	  $alert = "alert-success";
	   	  $check = '<span class="alert-status-check" ><i class="la la-check"></i></span>';
	   }

        $warning .= '
			  <div class="alert-custom '.$alert.'">
			    '.$check.'
			    Необходимо настроить интеграцию с интерактивной картой. Для этого перейдите в <a href="?route=settings&tab=integrations" >интеграции</a>
			  </div>
        ';

        if(!$settings["payment_variant"]){
	       $data["payment_variant"] = 1;
	       $count_warning += 1;
        }

	   if($data["payment_variant"]){
	   	  $alert = "alert-warning";
	   	  $check = "";
	   }else{
	   	  $alert = "alert-success";
	   	  $check = '<span class="alert-status-check" ><i class="la la-check"></i></span>';
	   }

        $warning .= '
			  <div class="alert-custom '.$alert.'">
			   '.$check.'
			   Необходимо настроить платежную систему для приема оплаты с сайта. Для этого перейдите в <a href="?route=settings&tab=payments" >настройку платежных систем</a>
			  </div>
        ';
	    
		if($settings["functionality"]["booking"]){
			if(!$settings["booking_payment_service_name"]){
				$alert = "alert-warning";
				$check = "";
			}else{
				$alert = "alert-success";
				$check = '<span class="alert-status-check" ><i class="la la-check"></i></span>';
			}

			$warning .= '
				<div class="alert-custom '.$alert.'">
				'.$check.'
				Необходимо выбрать платежную систему для приема оплаты онлайн бронирования и аренды. Для этого перейдите в <a href="?route=settings&tab=booking" >настройку бронирования</a>
				</div>
			';
	    }

       return ["html"=>$warning, "count"=>$count_warning];

	}

	function dir_size($dir) {
	   $totalsize=0;

	   if( !is_dir($dir) ) return 0;
	   
	   if ($dirstream = @opendir($dir)) {
	      while (false !== ($filename = readdir($dirstream))) {
	         if ($filename!="." && $filename!=".."){
	            if (is_file($dir."/".$filename)) $totalsize+=filesize($dir."/".$filename);
	            if (is_dir($dir."/".$filename)) $totalsize+=$this->dir_size($dir."/".$filename);
	         }
	      }
	   }
	   closedir($dirstream);
	   return $totalsize;
	}

	function adminRole($id = 0){
		$set = array(1 => "Администратор", 2 => "Менеджер", 3 => "Копирайтер", 4 => "Модератор", 5 => "Дизайнер", 6 => "Программист", 7 => "Сеошник", 8 => "Арбитражник", 9 => "Лингвист");
        if($id){
        	return $set[$id];
        }else{
        	return $set;
        }
	}

	function accessAdmin( $session = 0 ){
		global $config;

		if(!$_SESSION['cp_auth'][ $config["private_hash"] ]){
		   return false;
		}else{
		   if($session){
		   	  return true;
		   }else{
		   	  return false;
		   }
		}

	}

	function randQuotes(){
		$data = ["Чтобы достичь успеха, перестаньте гнаться за деньгами, гонитесь за мечтой","Просыпаясь утром, спроси себя: «Что я должен сделать?» Вечером, прежде чем заснуть: «Что я сделал?","Заработайте себе репутацию, и она будет работать на вас!","Есть только один способ проделать большую работу — полюбить её!","Для того, чтобы преуспеть, мы первым делом должны верить, что мы можем","Успех не приходит к вам. Это вы идете к нему","Если ты чувствуешь, что сдаешься, вспомни, ради чего ты держался до этого","Если ты не можешь быть первым — будь лучшим. Если ты не можешь быть лучшим — будь первым","Самая великая слава приходит ни к тому, кто никогда не падал, а к тому, кто поднимается как можно выше после каждого своего падения","Не ошибается лишь тот, кто ничего не делает! Не бойтесь ошибаться — бойтесь повторять ошибки!","Если ты собираешься в один прекрасный день создать что-то великое, помни – один прекрасный день – это сегодня"];
		return $data[ mt_rand(0, count($data)-1 ) ];
	}

	function getAllMessagesSupport($notification=false){

        $total = 0;
        $usersIdHash = [];

        $getChatUsers = getAll("select * from uni_chat_users where chat_users_id_interlocutor=? group by chat_users_id_hash", array(0));

        if(count($getChatUsers)){
              
              foreach ($getChatUsers as $key => $value) {
                $usersIdHash[$value["chat_users_id_hash"]] = "'".$value["chat_users_id_hash"]."'";
              }

          	  if($notification){
          	  	 $total = (int)getOne("select count(*) as total from uni_chat_messages where chat_messages_id_hash IN(".implode(',',$usersIdHash).") and chat_messages_status=? and chat_messages_id_user!=? and chat_messages_notification=?",array(0,0,0))["total"];
          	  	 update("update uni_chat_messages set chat_messages_notification=? where chat_messages_id_hash IN(".implode(',',$usersIdHash).") and chat_messages_id_user!=?", [1,0]);
          	  }else{
          	  	 $total = (int)getOne("select count(*) as total from uni_chat_messages where chat_messages_id_hash IN(".implode(',',$usersIdHash).") and chat_messages_status=? and chat_messages_id_user!=?",array(0,0))["total"];
          	  }

        }

        return $total;
    }

    function menuCheckPrivileges($privileges=""){
    	if($privileges){
    		 $privilegesArray = explode(",", $privileges);
    		 foreach ($privilegesArray as $value) {
    		 	  if(isset($_SESSION[$value])){
							return true;
    		 	  }
    		 }
    		 return false;
    	}
    }

    function menuItemRoutes($id=0){
    	$routes = [];
    	if($id){
    		$getSubmenu = getAll("select * from uni_dashboard_menu where parent_id=?", [$id]);
    		if($getSubmenu){
    			foreach ($getSubmenu as $value) {
    				$routes[] = $value["route"];
    			}
    		}
    	}
    	return $routes;
    }


}

?>
