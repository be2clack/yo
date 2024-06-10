<?php
function mirror(){
  global $settings;
    $getUrl = parse_url(getenv("HTTP_HOST"));
    $myUrl = parse_url($settings["url"]);
    if($settings["url"] && $getUrl["path"]) if($getUrl["path"] != $myUrl["host"]){ return true; } 
    return false; 
}


function ob_get($content)
{
  ob_start();
  include $content;
  return ob_get_clean();
}

function clear($data)
{
    return trim(htmlspecialchars($data));
}

function resize($filepath, $newfilepath, $width = 0, $height = 0, $quality = 80, $type = ''){

    if($width || $height){
        $new_image = new picture($filepath);

        if($width && $height){
            $new_image->autoimageresize($width, $height);
        }elseif($width){
            $new_image->imageresizewidth($width);
        }elseif($height){
            $new_image->imageresizeheight($height);
        }
        
        $new_image->imagesave($new_image->image_type, $newfilepath, $quality, $type);
        $new_image->imageout();
    }
}

function isAjax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}

function validateEmail($email){
  if (!preg_match("/^(?:[a-zA-Zа-яА-Я0-9\.]+(?:[-_.]?[a-zA-Zа-яА-Я0-9\_\.\-]+)?@[a-zA-Zа-яА-Я0-9\_\.\-]+(?:\.?[a-zA-Zа-яА-Я0-9]+)?\.[a-zA-Zа-яА-Я]{2,10})$/u",
      trim($email))) {
      return false;
  }else{
      return true;
  }     
}

function validatePhone($phone=""){
  global $settings, $config;

   $ULang = new ULang();

   $code_phone = [];

   $phone = preg_replace('/[^0-9]/', '', $phone);

   if(!$phone){
       return ['status'=>false, 'error'=>$ULang->t("Пожалуйста, укажите корректный номер телефона")];
   }

   $getCountry = getAll('select * from uni_country where country_code_phone!=? and country_status=?', ['',1]);

   if(count($getCountry)){
        foreach ($getCountry as $key => $value) {
            $data['code'][] = $value['country_code_phone'];
            $data['format'][] = $config["format_phone"][$value['country_code_phone']]["code"];
        }
        foreach ($data['code'] as $value) {

            if(substr($phone, 0, strlen($config["format_phone"][$value]["code"])) == $config["format_phone"][$value]["code"]){

                if(strlen($phone) != $config["format_phone"][$value]["length"]){
                   return ['status'=>false, 'error'=>$ULang->t("Пожалуйста, укажите корректный номер телефона")];
                }else{
                   return ['status'=>true]; 
                }

            }

        }

        return ['status'=>false, 'error'=>$ULang->t("Номер телефона должен начинаться с").' '.implode(', ',$data['format'])];
   }

   return ['status'=>true];

}

function getFormatPhone(){
    global $settings;
    $getDefaultCountry = findOne('uni_country', 'country_id=?', [$settings['country_id']]);
    return $getDefaultCountry['country_format_phone'];
}

function formatPhone($phone=""){
   $phone = preg_replace('/[^0-9]/', '', $phone);
   if($phone) return trim($phone);
}

function outBoxChangeFormatPhone(){
    global $settings,$config;

    $ULang = new ULang();

    $getPhoneCode = getAll("select * from uni_country where country_code_phone!=? and country_status=?", ['',1]);

    if(count($getPhoneCode) > 1){

        $getDefaultCountry = findOne('uni_country', 'country_id=?', [$settings['country_id']]);

        $out = '
        <div class="input-phone-format-box" >
              <div class="input-phone-format-change" ><img src="'.Exists($config["media"]["other"],$getDefaultCountry['country_image'],$config["media"]["no_image"]).'" /></div>
              <div class="input-phone-format-list" >
        ';
      
        
        foreach ($getPhoneCode as $value) {
            $out .= '<span data-format="'.$value['country_format_phone'].'" data-icon="'.Exists($config["media"]["other"],$value['country_image'],$config["media"]["no_image"]).'" ><img src="'.Exists($config["media"]["other"],$value['country_image'],$config["media"]["no_image"]).'" /> '.$ULang->t($value['country_name'], [ "table" => "geo", "field" => "geo_name" ]).'</span>';
        }

        $out .= '
               </div>
        </div>
        ';  

        return $out; 

    } 

}

function Exists($dir,$name,$no_image){
   global $config;
   if( !empty($name) && file_exists($config["basePath"] . "/" . $dir . "/" . $name) ){
       return trim($config["urlPath"], "/") . "/" . $dir . "/" . $name;
   }else{
       return trim($config["urlPath"], "/") . "/" . $no_image; 
   } 
}

function img( $param = array() ){
   global $config;

   if( file_exists( $config["basePath"] . "/" . $param["img1"]["path"] ) && !is_dir( $config["basePath"] . "/" . $param["img1"]["path"] ) ){
      return '<img class="'.$param["img1"]["class"].'" src="'.$config["urlPath"] . "/" . $param["img1"]["path"].'" width="'.$param["img1"]["width"].'" >';
   }else{
      if($param["img2"]) return '<img class="'.$param["img2"]["class"].'" src="'.$config["urlPath"] . "/" . $param["img2"]["path"].'" width="'.$param["img2"]["width"].'" >';
   }

}

function out_navigation($param=array()){
  
   if($param["output"] && $param["count"]){ 
   
    $total = getCountPage($param["count"],$param["output"]);
    $page = intval($param["page_count"]);
    
    if($page <= 0) $page = 1;
    if($page > $total) $page = $total;

    if($param["url"]){

      if(substr($param["url"], 0,1) != "?") $variables = '?'.$param["url"].'&'.$param["page_variable"].'='; else $variables = $param["url"].'&'.$param["page_variable"].'=';

    }else{$variables = '?'.$param["page_variable"].'=';}
    
    if($page - 3 > 0) $page3left = '<li class="page-item" ><a data-page="'.($page - 3).'" class="page-link" href="'.$variables.($page - 3).'">'.($page - 3).'</a></li>';
    if($page - 2 > 0) $page2left = '<li class="page-item" ><a data-page="'.($page - 2).'" class="page-link" href="'.$variables.($page - 2).'">'.($page - 2).'</a></li>';
    if($page - 1 > 0) $page1left = '<li class="page-item" ><a data-page="'.($page - 1).'" class="page-link" href="'.$variables.($page - 1).'">'.($page - 1).'</a></li>';
    
    if($page + 3 <= $total) $page3right = '<li class="page-item" ><a data-page="'.($page + 3).'" class="page-link" href="'.$variables.($page + 3).'">'.($page + 3).'</a></li>';
    if($page + 2 <= $total) $page2right = '<li class="page-item" ><a data-page="'.($page + 2).'" class="page-link" href="'.$variables.($page + 2).'">'.($page + 2).'</a></li>';
    if($page + 1 <= $total) $page1right = '<li class="page-item" ><a data-page="'.($page + 1).'" class="page-link" href="'.$variables.($page + 1).'">'.($page + 1).'</a></li>';
    

    if ( ($page + 3) < $total)
    {
        $link_total = '<li class="page-item" ><a data-page="'.$total.'" class="page-link" href="'.$variables.$total.'">'.$total.'</a></li>';
    }

    if ($page > 3)
    {
        $link_first = '<li class="page-item" ><a data-page="1" class="page-link" href="'.$variables.'1">1</a></li>';
    }
    
    if($param["prev"]){
       if($page - 1) $prev = '<li class="page-item pagination-arrow" ><a  data-page="'.($page - 1).'" class="page-link" href="'.$variables.($page - 1).'">'.$param["prev"].'</a></li>'; else $prev = "";
    }
    
    if($param["next"]){
       if( $page < $total ) $next = '<li class="page-item pagination-arrow" ><a  data-page="'.($page + 1).'" class="page-link" href="'.$variables.($page + 1).'">'.$param["next"].'</a></li>'; else $next = ""; 
    }
    
    if($total > 1){ 
       return $prev.$link_first.$page3left.$page2left.$page1left.'<li class="page-item active" ><a  data-page="'.$page.'" class="page-link ripple-effect current-page" href="'.$variables.$page.'" >'.$page.'</a></li>'.$page1right.$page2right.$page3right.$link_total.$next;
    }

   }
           
}

function getCountPage($count,$output){
    $result = ceil($count / $output);
    if(!$result) return 1; else return $result;
}

function navigation_offset( $param = array() ){

    $total = getCountPage($param["count"],$param["output"]);

    if( $param["page"] > $total ) $param["page"] = $total;

    if($param["page"] > 1) $start = ($param["page"] * $param["output"]) - $param["output"]; else $start = 0;      
    return " LIMIT $start, ".$param["output"];
     
}

function pageDisabled($page=0,$count=0,$output=0){
  if(getCountPage($count,$output) == 1){
    return 'disabled=""';
  }elseif(intval($page) >= getCountPage($count,$output)){
    return 'disabled=""';
  }
}


function translite($string = "")
{

    $slugify = new Slugify();
    if($string) return $slugify->slugify( $string );
    
}


function generatePass($number=7){
    
 $arr = array('a','b','c','d','e','f',

              'g','h','i','j','k','l',
            
              'm','n','o','p','r','s',
            
              't','u','v','x','y','z',
            
              'A','B','C','D','E','F',
            
              'G','H','I','J','K','L',
            
              'M','N','O','P','R','S',
            
              'T','U','V','X','Y','Z',
            
              '1','2','3','4','5','6',
            
              '7','8','9','0');

 $pass = "";

 for($i = 0; $i < $number; $i++)
     {        
         $index = rand(0, count($arr) - 1);        
         $pass .= $arr[$index];    
     }

 return $pass;    
}

function generateStringNumber($number=7){
    
 $arr = array('A','B','C','D','E','F',
            
              'G','H','I','J','K','L',
            
              'M','N','O','P','R','S',
            
              'T','U','V','X','Y','Z',
            
              '1','2','3','4','5','6',
            
              '7','8','9','0');

 $pass = "";

 for($i = 0; $i < $number; $i++)
     {        
         $index = rand(0, count($arr) - 1);        
         $pass .= $arr[$index];    
     }

 return $pass;    
}

function datetime_format($string, $time = true) {

 $ULang = new ULang();
 
 if (is_numeric($string)) {
    $string = date( "Y-m-d H:i:s", $string );
 }

 $monn = array(
   '',
   $ULang->t('января'),
   $ULang->t('февраля'),
   $ULang->t('марта'),
   $ULang->t('апреля'),
   $ULang->t('мая'),
   $ULang->t('июня'),
   $ULang->t('июля'),
   $ULang->t('августа'),
   $ULang->t('сентября'),
   $ULang->t('октября'),
   $ULang->t('ноября'),
   $ULang->t('декабря')
 );

 $a = preg_split("/[^\d]/",$string); 
 $today = date('Ymd');
 if(($a[0].$a[1].$a[2])==$today) {

   return($ULang->t("сегодня в")." ".$a[3].":".$a[4]);
   
 } else {
   $b = explode("-",date("Y-m-d"));
   $tom = date("Ymd",mktime(0,0,0,$b[1],$b[2]-1,$b[0]));
   if(($a[0].$a[1].$a[2])==$tom) {
     
     return($ULang->t("вчера в")." ".$a[3].":".$a[4]);
     
   } else {

     $mm = intval($a[1]);
     if($time){
       return($a[2]." ".$monn[$mm]." ".$a[0].", ".$a[3].":".$a[4]);
     }else{
       return($a[2]." ".$monn[$mm]." ".$a[0]); 
     }

   }
 }
}

function datetime_format_cp($string) {
 global $config;

 $static_msg = require $config["basePath"] . "/static/msg.php";

 if (is_numeric($string)) {
    $string = date( "Y-m-d H:i:s", $string );
 }
 
 $monn = array(
   '',
   $static_msg["20"],
   $static_msg["21"],
   $static_msg["22"],
   $static_msg["23"],
   $static_msg["24"],
   $static_msg["25"],
   $static_msg["26"],
   $static_msg["27"],
   $static_msg["28"],
   $static_msg["29"],
   $static_msg["30"],
   $static_msg["31"]
 );

 $a = preg_split("/[^\d]/",$string); 
 $today = date('Ymd');
 if(($a[0].$a[1].$a[2])==$today) {

   return($static_msg["32"]." ".$a[3].":".$a[4]);
   
 } else {
   $b = explode("-",date("Y-m-d"));
   $tom = date("Ymd",mktime(0,0,0,$b[1],$b[2]-1,$b[0]));
   if(($a[0].$a[1].$a[2])==$tom) {

     return($static_msg["33"]." ".$a[3].":".$a[4]);
     
   } else {

     $mm = intval($a[1]);
     return($a[2]." ".$monn[$mm]." ".$a[0].", ".$a[3].":".$a[4]);
   }
 }
}

function file_get_contents_curl($url, $timeout=2) {

    try{

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        $data = curl_exec($ch);
        curl_close($ch);

        if($data){
            return $data;
        }else{
            $data = file_get_contents($url);
            if($data){
               return $data;
            }
        }
        

    }catch (Exception $e){

        $data = file_get_contents($url);
        if($data){
           return $data;
        }

    }

}


function custom_substr($string, $len, $ellipsis = ""){
  $string = trim(html_entity_decode($string));
  if(mb_strlen( $string ) > $len){
      return mb_substr($string,0,$len).$ellipsis;
  }else{ return $string; }
}

function my_ucfirst($str){
  return mb_strtoupper(mb_substr($str, 0, 1, "UTF-8"), "UTF-8").mb_strtolower(mb_substr($str, 1, mb_strlen($str, "UTF-8"), "UTF-8"), "UTF-8");
}

function textReplace($text){
  global $settings, $config;
  return str_replace(array("{url}", "{site_name}", "{email}", "{phone}"),array($config["urlPath"], $settings["site_name"], $settings["contact_email"], $settings["contact_phone"]),$text);
}

function rotateImage($path){

      if(function_exists('exif_read_data')){
         $exif=exif_read_data($path);
         if( $exif && isset( $exif['Orientation'] ) ){
            $angles=array(3=>180,6=>270,8=>90);
            if(isset($angles[$exif['Orientation']])){

               $info = getimagesize( $path );

               if( $info["mime"] == "image/png" ){

                   $image=imagecreatefrompng($path);

               }elseif( $info["mime"] == "image/jpeg" ){
                
                   $image=imagecreatefromjpeg($path);

               }else{

                   $image=imagecreatefromjpeg($path);
                   
               }

               $image=imagerotate($image,$angles[$exif['Orientation']],0);
               imagejpeg($image,$path,100);
               imagedestroy($image);

            }
         }
      }
        
}

function makrosDefault(){
  global $settings,$config;
  $Main = new Main();
  return array( "{DOMEN}"=>$_SERVER["SERVER_NAME"], "{URL}"=>$config["urlPath"], "{LOGO}"=> $settings["logotip"], "{CONTACT_EMAIL}"=>$settings["contact_email"], "{CONTACT_PHONE}"=>$settings["contact_phone"], "{CONTACT_ADDRESS}"=>$settings["contact_address"], "{SITE_NAME}" => $settings["site_name"], "{ADMIN_LINK}" => $config["urlPath"] . "/" . $config["folder_admin"], "{SITE_TITLE}"=>$settings["title"], "{SOCIAL_LINK}" => '<div class="social" >' . $Main->socialLink() . '</div>', "{IMAGE_OTHER}" => $config["urlPath"] . "/" . $config["media"]["other"], "{LANG}" => $settings["lang_site_default"] );
}

function linkUnsubscribe( $email = "", $type = "user" ){
   global $config,$settings;

   $static_msg = require $config["basePath"] . "/static/msg.php";

   $hash = hash('sha256', $email.$config["private_hash"]);
   $unsubscribe = $config["urlPath"].'/unsubscribe?hash='.$hash.'&email='.$email.'&type='.$type;

   return '<p class="footer-unsubscribe" >'.$static_msg["34"].' '.$settings["site_name"].' <br> <a href="'.$unsubscribe.'">'.$static_msg["35"].'</a> </p>';
}

function email_notification($param = array()){
  global $settings,$config;

  $default = makrosDefault();
  
  foreach ($default as $key => $value) {
     $data[$key] = $value;
  }
  
  if($param["variable"]){
      foreach ($param["variable"] as $key => $value) {
         $data[$key] = $value;
      }
  }

  $get = findOne("uni_email_message", "code = ?", array($param["code"])); 
  if (count($get))
   {
      $text = urldecode($get->text);
      $subject = $get->subject;

      $body = @file_get_contents( $config["template_path"] . "/include/template_mail.php" );
      $text = str_replace("{BODY}", $text, $body);

      if(count($data) != 0){
          foreach($data AS $name => $val){
              $text = str_replace($name, $val, $text);
              $subject = str_replace($name, $val, $subject);
          }
      }

    return mailer($data["{EMAIL_TO}"],$subject,$text);
   }       

}


function replace($array1 = array(),$array2 = array(),$text = ""){
  return str_replace($array1,$array2,$text);
}

function geolocation($ip){

     global $config;

     if(file_exists($config["basePath"].'/systems/libs/GeoIp/GeoLite2-City.mmdb')){

         $reader = new GeoIp2\Database\Reader($config["basePath"].'/systems/libs/GeoIp/GeoLite2-City.mmdb');

         if($ip){

            $record = $reader->city($ip);

            if(!empty($record->city->names['ru']) && !empty($record->subdivisions[0]->names['ru'])){
              if($record->city->names['ru'] != $record->subdivisions[0]->names['ru']){
                return $record->city->names['ru'].', '.$record->subdivisions[0]->names['ru'];
              }else{
                return $record->city->names['ru'];
              }
            }elseif(!empty($record->city->names['ru'])){
             return $record->city->names['ru'];
            }elseif(!empty($record->subdivisions[0]->names['ru'])){
             return $record->subdivisions[0]->names['ru'];
            }elseif($record->country->names['ru']){
             return $record->country->names['ru'];
            } 

         }

     }
   
}

function ending($number, $one, $two, $five)
{
    $number = $number % 100;

    if ( ($number > 4 && $number < 21) || $number == 0 )
    {
        $ending = $five;
    }
    else
    {
        $last_digit = substr($number, -1);

        if ( $last_digit > 1 && $last_digit < 5 )
            $ending = $two;
        elseif ( $last_digit == 1 )
            $ending = $one;
        else
            $ending = $five;
    }

    return $ending;
}

function detectRobots($user_agent){
  $bots = array(
    'rambler','googlebot','aport','yahoo','msnbot','turtle','mail.ru','omsktele',
    'yetibot','picsearch','sape.bot','sape_context','gigabot','snapbot','alexa.com',
    'megadownload.net','askpeter.info','igde.ru','ask.com','qwartabot','yanga.co.uk',
    'scoutjet','similarpages','oozbot','shrinktheweb.com','aboutusbot','followsite.com',
    'dataparksearch','google-sitemaps','appEngine-google','feedfetcher-google',
    'liveinternet.ru','xml-sitemaps.com','agama','metadatalabs.com','h1.hrn.ru',
    'googlealert.com','seo-rus.com','yaDirectBot','yandeG','yandex',
    'yandexSomething','Copyscape.com','AdsBot-Google','domaintools.com',
    'Nigma.ru','bing.com','dotnetdotcom','bots','robot','AhrefsBot'
  );
  foreach($bots as $bot){
    if(stripos($user_agent, $bot) !== false){
      return true;
    }
  }
 return false;            
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

function deleteFolder( $path ) {

 if ( file_exists( $path ) AND is_dir( $path ) ) {

    $dir = @opendir($path);
    while ( false !== ( $element = readdir( $dir ) ) ) {

      if ( $element != '.' AND $element != '..' )  {
        $tmp = $path . '/' . $element;
        chmod( $tmp, 0755 );

        if ( is_dir( $tmp ) ) {

          deleteFolder( $tmp );

        } else {
          unlink( $tmp );
       }
     }
   }

    closedir($dir);

   if ( file_exists( $path ) ) {
     rmdir( $path );
   }
 }

}

function sms($phone_to="",$text="",$method="sms"){
   global $settings, $config;

   if($settings["sms_service_pass"]){
      $settings["sms_service_pass"] = decrypt($settings["sms_service_pass"]);
   }

   if($settings["sms_service_id"]){
      $settings["sms_service_id"] = decrypt($settings["sms_service_id"]);
   }

   if($settings["sms_service"] == "sms.ru" ){

      if($method == 'call'){
          $results['answer'] = json_decode(file_get_contents_curl("http://sms.ru/code/call?api_id=".$settings["sms_service_id"]."&phone=".$phone_to), true);
          $results['code'] = $results['answer']['code'];
          return json_encode($results);
      }else{
          return file_get_contents_curl("http://sms.ru/sms/send?api_id=".$settings["sms_service_id"]."&to=".$phone_to."&text=".urlencode($text));
      }

   }elseif($settings["sms_service"] == "iqsms.ru" ){

      return file_get_contents_curl("https://api.iqsms.ru/messages/v2/send/?phone=".$phone_to."&text=".urlencode($text)."&login=".$settings["sms_service_login"]."&password=".$settings["sms_service_pass"]."&sender=".$settings["sms_service_label"]);

   }elseif($settings["sms_service"] == "smsc.ru" ){

      if($method == 'call'){
          $code = mt_rand(1000,9999);
          $results['answer'] = json_decode(file_get_contents_curl("https://smsc.ru/sys/send.php?sender=".$settings["sms_service_label"]."&login=".$settings["sms_service_login"]."&psw=".$settings["sms_service_pass"]."&phones=".$phone_to."&mes=".$code."&call=1"), true);
          $results['code'] = $code;
          return json_encode($results);
      }else{
          return file_get_contents_curl("https://smsc.ru/sys/send.php?sender=".$settings["sms_service_label"]."&login=".$settings["sms_service_login"]."&psw=".$settings["sms_service_pass"]."&phones=".$phone_to."&mes=".urlencode($text));
      }

   }elseif($settings["sms_service"] == "smsc.kz" ){

      if($method == 'call'){
          $code = mt_rand(1000,9999);
          $results['answer'] = json_decode(file_get_contents_curl("https://smsc.kz/sys/send.php?sender=".$settings["sms_service_label"]."&login=".$settings["sms_service_login"]."&psw=".$settings["sms_service_pass"]."&phones=".$phone_to."&mes=".$code."&call=1"), true);
          $results['code'] = $code;
          return json_encode($results);
      }else{
          return file_get_contents_curl("https://smsc.kz/sys/send.php?sender=".$settings["sms_service_label"]."&login=".$settings["sms_service_login"]."&psw=".$settings["sms_service_pass"]."&phones=".$phone_to."&mes=".urlencode($text));
      }

   }elseif($settings["sms_service"] == "mobizon.kz" ){

      return file_get_contents_curl("https://api.mobizon.kz/service/message/sendsmsmessage?recipient=".$phone_to."&text=".urlencode($text)."&apiKey=".$settings["sms_service_id"]);

   }elseif($settings["sms_service"] == "turbosms.ua" ){

      return file_get_contents_curl("https://api.turbosms.ua/message/send.json?recipients[0]=".$phone_to."&sms[sender]=".$settings["sms_service_label"]."&sms[text]=".urlencode($text)."&token=".$settings["sms_service_id"]);

   }elseif($settings["sms_service"] == "mobizon.ua" ){

      return file_get_contents_curl("https://api.mobizon.ua/service/message/sendsmsmessage?recipient=".$phone_to."&text=".urlencode($text)."&apiKey=".$settings["sms_service_id"]);

   }elseif($settings["sms_service"] == "sms.by" ){

      return file_get_contents_curl("https://app.sms.by/api/v1/sendQuickSMS?token=".$settings["sms_service_id"]."&message=".urlencode($text)."&phone=".$phone_to."&alphaname_id=".$settings["sms_service_label"]);

   }elseif($settings["sms_service"] == "cheapglobalsms.com" ){

      return file_get_contents_curl("http://cheapglobalsms.com/api_v1?sub_account=".$settings["sms_service_login"]."&sub_account_pass=".$settings["sms_service_pass"]."&action=send_sms&sender_id=CGSMS&message=".urlencode($text)."&recipients=" . $phone_to);

   }elseif($settings["sms_service"] == "osonsms.com" ){

        $dlm = ";";
        $txn_id = uniqid();
        $str_hash = hash('sha256',$txn_id.$dlm.$settings["sms_service_login"].$dlm.$settings["sms_service_label"].$dlm.$phone_to.$dlm.$settings["sms_service_pass"]);
        $params = array(
            "from" => $settings["sms_service_label"],
            "phone_number" => $phone_to,
            "msg" => $text,
            "str_hash" => $str_hash,
            "txn_id" => $txn_id,
            "login"=>$settings["sms_service_login"],
        );

        $result = oson_sms_call_api('http://api.osonsms.com/sendsms_v1.php', "GET", $params);

        return json_decode($result['msg']);

   }elseif($settings["sms_service"] == "playmobile.uz" ){

        $params['messages'] = [
            "recipient" => trim($phone_to, '+'),
            "message-id" => mt_rand(1000000,9000000),
            "sms" => [
                "originator" => '3700',
                "content" => [
                    "text" => $text
                ]
            ]
        ];

        return json_encode(sendPostRequest('http://91.204.239.44/broker-api/send', $params, ['Content-Type: application/json', 'Authorization: Basic '.base64_encode($settings["sms_service_login"].":".$settings["sms_service_pass"])]));

   }elseif($settings["sms_service"] == "nikita.kg" ){

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>".
            "<message>".
                "<login>" . $settings["sms_service_login"] . "</login>".
                "<pwd>" . $settings["sms_service_pass"] . "</pwd>".
                "<id>" . mt_rand(1000000,9999999) . "</id>".
                "<sender>" . $settings["sms_service_label"] . "</sender>".
                "<text>" . $text . "</text>".
                "<phones>".
                "<phone>" . $phone_to . "</phone>".
                "</phones>".
            "</message>";   

        $result = smsPostContentNikita( "http://smspro.nikita.kg/api/message", $xml );

        return json_encode($result);

   }

}

function smsPostContentNikita ($url,$postdata) {
    $uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";

    $ch = curl_init( $url );
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "c://coo.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE,"c://coo.txt");

    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
}

function smsVerificationCode($phone=''){
    global $settings;

    if($phone){
        if($settings["sms_service_method_send"] == 'call'){
            $answer = json_decode(sms($phone, '', 'call'), true);
            return $answer['code'];
        }else{
            $code = mt_rand(1000,9999);
            sms($phone, $settings["sms_prefix_confirmation_code"].$code, 'sms');
            return $code;
        }
    }

}

function telegram($text){
global $settings,$config;

  $token = decrypt($settings["api_id_telegram"]);
  $chat_id = $settings["chat_id_telegram"];
  
  if($token){
    if($chat_id){
      return file_get_contents_curl("https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat_id."&parse_mode=html&text=". urlencode($text) );
    }else{
      return "No chat_id";
    }
  }else{
     return "No token";
  }


}

function getParam($URI){
   if($URI){
      return explode("/",trim($URI,"/"));
   }else{
      return array();
   }
}


function clearScript($text){
    $cut = array(
      "'<script[^>]*?>.*?</script>'si",
      "'<noscript[^>]*?>.*?</noscript>'si",
      "'<style[^>]*?>.*?</style>'si",
      "'<[\/\!]*?[^<>]*?>'si",
    );
    $to = array(" "," "," "," ");
    return preg_replace($cut, $to, $text);
}

function clearLink($text){
   $re = '@(https?://)?(([a-z0-9.-]+)?[a-z0-9-]+(!?\.[a-z]{2,4}))@';
   preg_match_all($re, $text, $links);
   if(isset($links[0])) return str_replace($links[0], '', $text); else return $text;
}

function clearText($text){
   return clearScript(clearLink($text));
}

function paymentParams($code = ""){
   global $config; 
   if($code){
       $payment = findOne("uni_payments","code=?", array($code));
       if($payment){

          $payment->param = decrypt($payment->param);

          if($payment->param && $payment->param != "[]"){
            return json_decode($payment->param, true);
          }else{
            return [];
          }

       }    
   }else{
     return [];
   }
}


function breadcrumb_count($content, $index = 2){

    preg_match_all ( '/<li.*?>(.*?)<\/li>/i' , $content , $matches); 
    if(count($matches[0]) > 0){
      foreach ($matches[0] as $key => $value) {
        $return .= str_replace(array("{INDEX}"),array($key + $index),$value);
      }
    }

   if($return) return $return; else return $content;

}

function clearSearch($string = "", $len = 150){

   $string = clearSearchFront($string);

   if($len){
     return custom_substr($string, $len);
   }else{
     return $string;
   }

}

function clearSearchFront($string = ""){

   $string = str_replace(["'","(",")","=","!",">","<","\\","/"], ["","","","","","","","",""], $string);

   return addslashes(htmlspecialchars(mb_strtolower($string, 'UTF-8')));

}

function clearSearchBack($string = ""){

   $string = str_replace(["'","(",")","=","!",">","<","\\","/"], ["","","","","","","","",""], $string);

   return custom_substr(addslashes(htmlspecialchars($string)), 150);

}

function _link($link="",$url=true){
  global $settings,$config;
  
  if( $settings["visible_lang_site"] ){

    $lang_iso = getLang() ? "/" . getLang() . "/" : "/";

    return trim( $config["urlPath"] . $lang_iso . $link, "/" );

  }else{

    return trim( $config["urlPath"] . "/" . $link, "/" );

  }
   
}

function getLang(){
  global $settings;

  if(isset($_SESSION["langSite"]["iso"])){
     return $_SESSION["langSite"]["iso"];
  }else{
     return $settings["lang_site_default"];
  }

}

function requestUri(){
   global $settings;

   $uri = trim($_SERVER['REQUEST_URI'], "/");

   if( $settings["visible_lang_site"] ){
      
      $uri = explode("/", $uri);
      unset($uri[0]);
      $uri = implode("/", $uri);

   }

   return $uri;
}

function langUri(){
   $uri = trim($_SERVER['REQUEST_URI'], "/");
   if($uri){
     $uri = explode("/", $uri);
     return $uri[0];
   }else{
     return "";
   }
}

function encrypt($plaintext) {
  global $config;
  $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
  $iv = openssl_random_pseudo_bytes($ivlen);
  $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $config["private_hex"], $options=OPENSSL_RAW_DATA, $iv);
  $hmac = hash_hmac('sha256', $ciphertext_raw, $config["private_hex"], $as_binary=true);
  return base64_encode( $iv.$hmac.$ciphertext_raw );
}
 
function decrypt($ciphertext) {
  global $config;
  if($ciphertext){
    $c = base64_decode($ciphertext);
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len=32);
    $ciphertext_raw = substr($c, $ivlen+$sha2len);
    $plaintext = openssl_decrypt($ciphertext_raw, $cipher, $config["private_hex"], $options=OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $config["private_hex"], $as_binary=true);
    if (hash_equals($hmac, $calcmac))
    {
        return $plaintext;
    }
  }
}

function unique_crypt_key($number=32){
    
 $arr = array('a','b','c','d','e','f',
            
              '1','2','3','4','5','6',
            
              '7','8','9','0');

 for($i = 0; $i < $number; $i++)
     {        
         $index = rand(0, count($arr) - 1);        
         $key .= $arr[$index];    
     }

 return $key;    
}

function parseIdAlias($string){

  $alias = explode("-", $string ); 
  $id = (int)end($alias);
  $pop = array_pop($alias);
  $alias = implode("-", $alias );

  return [ "id" => $id, "alias" => $alias];

}

function parseUriAd($string){

  $string = explode("/", $string ); 
  $alias_ad = $string[ count($string) - 1 ];
  unset( $string[ count($string) - 1 ] );

  $alias = explode("-", $alias_ad ); 
  $id = (int)end($alias);
  $pop = array_pop($alias);
  $alias = implode("-", $alias );

  return [ "id" => $id, "alias_ad" => clear($alias), "alias_cat" => clear(implode("/", $string)) ];

}

function parseUriFilter($string){

  $string = explode("/", $string ); 

  $alias_filter = end($string);
  $pop = array_pop($string);
  $alias_cat = implode("/", $string );

  return [ "alias_filter" => clear($alias_filter), "alias_cat" => clear($alias_cat) ];

}

function parseUriBlog($string,$getCategoryBlog=[]){

  $string = explode("/", $string ); 

  if( count($string) == 1 ){
     return [ "id" => 0, "alias_article" => "", "alias_cat" => clear(implode("/", $string)) ];
  }elseif( count($string) == 2 ){

     if( $getCategoryBlog["blog_category_chain"][implode("/", $string)] ){
        return [ "id" => 0, "alias_article" => "", "alias_cat" => clear(implode("/", $string)) ];
     }else{
        $alias_article = explode("-", $string[1] );
        $article_id = (int)end( $alias_article );
        $pop = array_pop( $alias_article );
        if($article_id){
          unset( $string[ count($string)-1 ] );
        }
        return [ "id" => $article_id, "alias_article" => clear(implode("-", $alias_article)), "alias_cat" => clear(implode("/", $string)) ];
     }

  }else{
     return [ "id" => 0, "alias_article" => "", "alias_cat" => clear(implode("/", $string)) ];
  }

}


function generateRandomColor() {
    $colors = [
        '#f7f8fa',
        '#efffda',
        '#e5f7da',
        '#f0e5d8',
        '#e8d8d1',
        '#d7e8e1',
        '#f7e9d8',
        '#f0d8d8',
        '#e8d7d8',
        '#f4e8d6',
        '#e7e8d8',
        '#f5e8f0',
        '#f3e8e3',
        '#eee3f0',
        '#f4e3e3',
        '#ece3d8',
        '#e8e3d8'
    ];

    static $usedColors = [];

    // Filter out colors that have already been used
    $availableColors = array_diff($colors, $usedColors);

    if (empty($availableColors)) {
        // If no available colors, reset used colors and use all colors again
        $usedColors = [];
        $availableColors = $colors;
    }

    // Pick a random color from the available colors
    $randomColor = $availableColors[array_rand($availableColors)];

    // Add the picked color to used colors
    $usedColors[] = $randomColor;

    return $randomColor;
}

function catalogLocationtUri(){
  
  $params = explode("?", trim( $_SERVER['REQUEST_URI'] , "/") );

  $uri = explode("/", trim( $params[0] , "/") );
  
  if( $params[1] ) $params = "?" . $params[1]; else $params = "";

  unset($uri[0]);

  return _link( $_SESSION["geo"]["alias"] . "/" . implode("/", $uri) . $params );

}

function idsBuildJoin($ids="",$id=0){
   if($ids){
     $ids = explode(",", trim($ids, ",") );
     if($id) $ids[] = $id;
     return implode(",", $ids);     
   }else{
     return $id;
   }
}

function iteratingArray($array=[], $type=""){

   if(count($array)){
       foreach ($array as $key => $value) {
          if($type == "int")
          $result[] = (int)$value;
          else
          $result[] = clear($value);
       }
      return $result;
   }
   
   return [];
}

function calcPercent( $amount = 0, $percent = 0 ){
   if($percent) return (($amount / 100) * $percent); else return 0;
}

function generateOrderId(){
   return mt_rand(10000000,99999999);
}

function normalize_files_array($files = array()) {

    $normalized_array = array();

    foreach($files as $index => $file) {

        if (!is_array($file['name'])) {
            $normalized_array[$index][] = $file;
            continue;
        }

        foreach($file['name'] as $idx => $name) {
            $normalized_array[$index][$idx] = [
                'name' => $name,
                'type' => $file['type'][$idx],
                'tmp_name' => $file['tmp_name'][$idx],
                'error' => $file['error'][$idx],
                'size' => $file['size'][$idx]
            ];
        }

    }

    return $normalized_array;

}

function import_load_image($images="", $count=1, $param){
   global $config, $settings;
   
   $Watermark = new Watermark();

   if(!$count) $count = 1;

   $gallery = [];

   if($images){

      $images = explode(",",$images);

      if(count($images)){

          foreach(array_slice($images, 0, $count) AS $out){

                $data = @file_get_contents_curl($out);

                if($data){
                  
                  $uid = uniqid();
                  $name = $uid.".jpg";
                  
                  file_put_contents($config["basePath"] . "/" . $config["media"]["big_image_ads"] . "/" . $name, $data);
                  $size = @filesize($config["basePath"] . "/" . $config["media"]["big_image_ads"] . "/" . $name);
                    
                    if($size > $param["min_size_image"]){

                     resize($config["basePath"] . "/" . $config["media"]["big_image_ads"] . "/" . $name, $config["basePath"] . "/" . $config["media"]["small_image_ads"] . "/" . $name, $settings["ads_images_small_width"], $settings["ads_images_small_height"], 100, $settings["ad_format_photo"]);

                     if($param["watermark"]) $Watermark->create( $config["basePath"] . "/" . $config["media"]["big_image_ads"] . "/" . $name, $config["basePath"] . "/" . $config["media"]["big_image_ads"] . "/" . $name );

                     resize($config["basePath"] . "/" . $config["media"]["big_image_ads"] . "/" . $name, $config["basePath"] . "/" . $config["media"]["big_image_ads"] . "/" . $name, $settings["ads_images_big_width"], $settings["ads_images_big_height"], 100, $settings["ad_format_photo"]);

                     @unlink( $config["basePath"] . "/" . $config["media"]["big_image_ads"] . "/" . $name );

                     $gallery[] = $uid . "." . $settings["ad_format_photo"];

                    }else{
                      @unlink($config["basePath"] . "/" . $config["media"]["big_image_ads"] . "/" . $name);
                    }

                }  
             
          }

      }

   }

   return $gallery;
}

function import_load_filters($params="",$id_cat=0){

  $Filters = new Filters();
  $return = [];

  if($params){

     $getCategoryFilters = $Filters->getCategory( ["id_cat" => $id_cat] );

     $params = explode("|",$params);

     if(count($params) && $getCategoryFilters){
        foreach($params AS $data){
          $result = explode("=",trim($data));
          if($result){

             $getFilter = getOne("select * from uni_ads_filters where ads_filters_name=? and ads_filters_id IN (".implode(",",$getCategoryFilters).")", [ $result[0] ]);

             if($getFilter){

                 if($getFilter["ads_filters_type"] != "input"){

                     $getFilterItem = findOne("uni_ads_filters_items", "ads_filters_items_id_filter=? and ads_filters_items_value=?", [ $getFilter["ads_filters_id"], $result[1] ]);
                     if($getFilterItem) $return[$getFilter["ads_filters_id"]][] = $getFilterItem["ads_filters_items_id"];

                 }else{

                     $getFilterItem = getAll("select * from uni_ads_filters_items where ads_filters_items_id_filter=?", [ $getFilter["ads_filters_id"] ] );

                     if( round($result[1],2) < $getFilterItem[0]["ads_filters_items_value"] ){
                         $return[$getFilter["ads_filters_id"]][] = $getFilterItem[0]["ads_filters_items_value"];
                     }elseif( round($result[1],2) > $getFilterItem[1]["ads_filters_items_value"] ){
                         $return[$getFilter["ads_filters_id"]][] = $getFilterItem[1]["ads_filters_items_value"];
                     }else{
                         $return[$getFilter["ads_filters_id"]][] = round($result[1],2);
                     }

                 }

             }

          }  
        }
     } 

  }
  
  return $return;

}

function videoLink($link){
    if(!empty($link)){

      if(strpos($link, "youtube.com") !== false || strpos($link, "youtu.be") !== false){

        if(strpos($link, "embed") === false){

            if( strpos($link, "watch?") !== false ){
                parse_str( explode("watch?", $link)[1] , $param);
                if($param["v"]){
                   return "https://www.youtube.com/embed/".$param["v"];
                }else{
                   return "";
                }
            }else{
                $param = explode("/", trim($link, "/") );
                if($param[3]){
                   return "https://www.youtube.com/embed/".$param[3];
                }else{
                   return "";
                }
            }
      
        }else{
            return $link;
        }

      }elseif(strpos($link, "rutube.ru") !== false){
        
        $variable = explode("?", $link);
        $link_ = end( explode("/", trim($variable[0], "/") ) );

        return "//rutube.ru/play/embed/".$link_;

      }elseif(strpos($link, "vimeo.com") !== false){
        
        $link_ = end( explode("/", trim($link, "/") ) );

        return "https://player.vimeo.com/video/".$link_;

      }

    }
}

function lang(){
   return "";
}

function getRealIp(){

    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];
     
    if(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
    else $ip = $remote;
     
    $_SERVER["REMOTE_ADDR"] = $ip;
  
}

function calcFilesize($filesize)
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

function csrf_token(){

   $key = bin2hex(random_bytes(32));

   $_SESSION['csrf_token'][$key] = $key;

   return $key;

}

function verify_csrf_token($actions=[]){
   
   $Main = new Main();

   if(in_array($_POST["action"], $actions)){
      
      if(!in_array($_POST['csrf_token'], $_SESSION['csrf_token']) || !$_POST['csrf_token'] || !$_SESSION['csrf_token']){
          echo $Main->response(403);
      }

   } 

   $headers = mb_strtolower( json_encode(apache_request_headers()), "UTF-8" );

   $headers = json_decode($headers, true);

   if( !$headers['x-csrf-token'] || !$_SESSION['csrf_token'] ){
       echo $Main->response(403);
   }

   if( !in_array($headers['x-csrf-token'], $_SESSION['csrf_token']) ){
       echo $Main->response(403);
   }

}

function base64_to_image($base64_string, $output_file) {

   if($base64_string){
      $ifp = fopen($output_file, "wb");

      $data = explode(',', $base64_string);

      fwrite($ifp, base64_decode($data[1]));
      fclose($ifp);
   }
   
}

function image_to_base64($path){
	$imageSize = getimagesize($path);
	$imageData = base64_encode(file_get_contents($path));
	return "data:{$imageSize['mime']};base64,{$imageData}";
}

function csvSplitChar($handle) {

  $header = fgets($handle);

  $s = preg_replace('/".+"/isU', '*', $header); 
  $a = [',',';','|'];
  $r;
  $i = -1;
  foreach($a as $c) {
    if(($n = sizeof(explode($c, $s))) > $i) {
      $i = $n;
      $r = $c;
    }
  }
  return $r;

}

function csvToUtf8($path) {

  $get = file_get_contents($path);
  $current_encoding = mb_detect_encoding($get, 'UTF-8', TRUE);

  if( !$current_encoding ){

      $get = iconv("windows-1251", "utf-8", $get);
      file_put_contents($path, $get);

  }
  
}

function removeSlash( $text = "" ){

    return str_replace( "\\", "", stripcslashes($text) );

}

function table_insert($filename)
{

  $templine = '';
  $fp = fopen($filename, 'r');
    
  update("SET NAMES 'utf8'");

  if($fp)
  while(!feof($fp)) {
    $line = fgets($fp);
    if (substr($line, 0, 2) != '--' && $line != '') {
      $templine .= $line;
      if (substr(trim($line), -1, 1) == ';') {
        update($templine);
        $templine = '';
      }
    }
  }
     
  fclose($fp);

}

function getTplFiles(){
    global $settings, $config;

    $arrayFiles = [];

    if(!$settings["demo_view"] && !$settings["demo_installment"]){
        $name = scandir($config["template_path"]);
        for($i=2; $i<=(sizeof($name)-1); $i++) {                         
            if(is_file($config["template_path"]."/".$name[$i]) && $name[$i] != '.' && pathinfo($name[$i], PATHINFO_EXTENSION) == 'tpl'){                           
              $arrayFiles[$name[$i]] = $name[$i];
            }
        }

        $name = scandir($config["template_path"]."/js/");
        for($i=2; $i<=(sizeof($name)-1); $i++) {                         
            if(is_file($config["template_path"]."/js/".$name[$i]) && $name[$i] != '.' && pathinfo($name[$i], PATHINFO_EXTENSION) == 'js'){                           
              $arrayFiles[$name[$i]] = $name[$i];
            }
        }
    }

    $name = scandir($config["template_path"]."/css/");
    for($i=2; $i<=(sizeof($name)-1); $i++) {                         
        if(is_file($config["template_path"]."/css/".$name[$i]) && $name[$i] != '.' && pathinfo($name[$i], PATHINFO_EXTENSION) == 'css'){                           
          $arrayFiles[$name[$i]] = $name[$i];
        }
    }

    return $arrayFiles;

}

function oson_sms_call_api($url, $method, $params){
    $curl = curl_init();
    $data = http_build_query ($params);
    if ($method == "GET") {
        curl_setopt ($curl, CURLOPT_URL, "$url?$data");
    }else if($method == "POST"){
        curl_setopt ($curl, CURLOPT_URL, $url);
        curl_setopt ($curl, CURLOPT_POSTFIELDS, $data);
    }else if($method == "PUT"){
        curl_setopt ($curl, CURLOPT_URL, $url);
        curl_setopt ($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded','Content-Length:'.strlen($data)));
        curl_setopt ($curl, CURLOPT_POSTFIELDS, $data);
    }else if ($method == "DELETE"){
        curl_setopt ($curl, CURLOPT_URL, "$url?$data");
        curl_setopt ($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
    }else{
        dd("unkonwn method");
    }
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    $arr = array();
    if ($err) {
        $arr['error'] = 1;
        $arr['msg'] = $err;
    } else {
        $res = json_decode($response);
        if (isset($res->error)){
            $arr['error'] = 1;
            $arr['msg'] = "Error Code: ". $res->error->code . " Message: " . $res->error->msg;
        }else{
            $arr['error'] = 0;
            $arr['msg'] = $response;
        }
    }
    return $arr;
}

function debug($message=null){
    global $config;
    
    if (is_array($message)) {
        $content .= var_export($message, true).PHP_EOL;
    } elseif (is_object($message)) {
        $content .= var_export($message, true).PHP_EOL;
    } else {
        $content .= $message.PHP_EOL;
    }

    if(isset($content)) {
        file_put_contents($config["basePath"].'/debug.log', $content, FILE_APPEND);
    }

}

function full_path_url()
{
    global $config;
    if($_SERVER['REQUEST_URI']){
        return $config['urlPath'].'/'.$_SERVER['REQUEST_URI'];
    }
    return $config['urlPath'];
}


function outParentExtra( $getCategories = [], $param = [] ){
      global $config;

      $Ads = new Ads();
      $ULang = new ULang();
      $CategoryBoard = new CategoryBoard();

      $return = "";

      if( $Ads->queryGeo() ){
         $queryGeo = " and " . $Ads->queryGeo();
      }

      if($param["category"]["category_board_id"]){

        if (isset($getCategories["category_board_id_parent"][$param["category"]["category_board_id"]])) {
            foreach ($getCategories["category_board_id_parent"][$param["category"]["category_board_id"]] as $parent_value) {

               if($parent_value["category_board_image"]){
                  $image = '<img src="'.Exists($config["media"]["other"],$parent_value["category_board_image"],$config["media"]["no_image"]).'" alt="'.$ULang->t( $parent_value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ).'" />';
               }else{ $image = ''; }
               
               $countAd = $CategoryBoard->getCountAd( $parent_value["category_board_id"] );
              
               $parent[] = replace(array("{PARENT_LINK}", "{PARENT_IMAGE}", "{PARENT_NAME}","{COUNT_AD}"),array($CategoryBoard->alias($parent_value["category_board_chain"]),$image,$ULang->t( $parent_value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ),$countAd),$param["tpl_parent"]);

               $return .=  replace(array("{PARENT_CATEGORY}"),array(implode($param["sep"],$parent)),$param["tpl"]);
               $parent = array();

            }
        }else{

          $id_parent = $getCategories["category_board_id"][$param["category"]["category_board_id_parent"]];

          if(isset($getCategories["category_board_id_parent"][$id_parent["category_board_id"]])){
            foreach ($getCategories["category_board_id_parent"][$id_parent["category_board_id"]] as $parent_value) {

              if($parent_value["category_board_image"]){
                  $image = '<img src="'.Exists($config["media"]["other"],$parent_value["category_board_image"],$config["media"]["no_image"]).'" alt="'.$ULang->t( $parent_value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ).'" />';
              }else{ $image = ''; }

              if($parent_value["category_board_id"] == $param["category"]["category_board_id"]){
                $active = 'class="active"';
              }else{
                $active = '';
              }
               
               $countAd = $CategoryBoard->getCountAd( $parent_value["category_board_id"] );
              
               $parent[] = replace(array("{PARENT_LINK}", "{PARENT_IMAGE}", "{PARENT_NAME}", "{ACTIVE}","{COUNT_AD}"),array($CategoryBoard->alias($parent_value["category_board_chain"]),$image,$ULang->t( $parent_value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ),$active,$countAd),$param["tpl_parent"]);

               $return .=  replace(array("{PARENT_CATEGORY}"),array(implode($param["sep"],$parent)),$param["tpl"]);
               $parent = array();

            }
          }
        }                 
       return $return;

      }

}

function outParentShopSubcategory( $getCategories = [], $param = [] ){
  global $config;

  $Ads = new Ads();
  $ULang = new ULang();
  $CategoryBoard = new CategoryBoard();
  $Shop = new Shop();

  if($param['current_category']['category_board_id']){

      if (isset($param["category"]["category_board_id_parent"][$param['current_category']['category_board_id']])) {

            foreach ($param["category"]["category_board_id_parent"][$param['current_category']['category_board_id']] as $parent_value) {

               if($parent_value["category_board_image"]){
                  $image = '<img src="'.Exists($config["media"]["other"],$parent_value["category_board_image"],$config["media"]["no_image"]).'" alt="'.$ULang->t( $parent_value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ).'" />';
               }else{ $image = ''; }
               
               $countAd = $CategoryBoard->getCountAd( $parent_value["category_board_id"], $param["shop"]["clients_shops_id_user"] );
              
               $parent[] = replace(array("{PARENT_LINK}", "{PARENT_IMAGE}", "{PARENT_NAME}","{COUNT_AD}"),array($Shop->aliasCategory($param["shop"]["clients_shops_id_hash"], $parent_value["category_board_chain"]),$image,$ULang->t( $parent_value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ),$countAd),$param["tpl_parent"]);

               $return .=  replace(array("{PARENT_CATEGORY}"),array(implode($param["sep"],$parent)),$param["tpl"]);
               $parent = array();

            }

       }else{

            $id_parent = $param["category"]["category_board_id"][$param["current_category"]["category_board_id_parent"]];

            if(isset($param["category"]["category_board_id_parent"][$id_parent["category_board_id"]])){
                foreach ($param["category"]["category_board_id_parent"][$id_parent["category_board_id"]] as $parent_value) {

                  if($parent_value["category_board_image"]){
                      $image = '<img src="'.Exists($config["media"]["other"],$parent_value["category_board_image"],$config["media"]["no_image"]).'" alt="'.$ULang->t( $parent_value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ).'" />';
                  }else{ $image = ''; }

                  if($parent_value["category_board_id"] == $param["current_category"]["category_board_id"]){
                    $active = 'class="active"';
                  }else{
                    $active = '';
                  }
                   
                   $countAd = $CategoryBoard->getCountAd( $parent_value["category_board_id"], $param["shop"]["clients_shops_id_user"] );
                  
                   $parent[] = replace(array("{PARENT_LINK}", "{PARENT_IMAGE}", "{PARENT_NAME}", "{ACTIVE}","{COUNT_AD}"),array($Shop->aliasCategory($param["shop"]["clients_shops_id_hash"], $parent_value["category_board_chain"]),$image,$ULang->t( $parent_value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ),$active,$countAd),$param["tpl_parent"]);

                   $return .=  replace(array("{PARENT_CATEGORY}"),array(implode($param["sep"],$parent)),$param["tpl"]);
                   $parent = array();

                }
            }

       }   

       return $return;

   }

}

function searchSubstr($string='', $letters=0){
    if(mb_strlen($string,'UTF-8') > 2){
        return mb_substr($string, 0, mb_strlen($string, 'UTF-8') - $letters, 'UTF-8');
    }else{
        return $string;
    }
}

function searchCheckWord($string='',$find=''){
    if(preg_match('/\b'.$find.'/u', $string)){
        return true;
    }else{
        return false;
    }
}

function difference_days($date_max='',$date_min=''){ 
    if(strtotime($date_max) > strtotime($date_min)){ 
        $date_max = new DateTime($date_max);
        $date_min = new DateTime($date_min);
        if($date_max->diff($date_min)->d){
            return $date_max->diff($date_min)->d;
        }elseif($date_max->diff($date_min)->h){
            return $date_max->diff($date_min)->h;
        }elseif($date_max->diff($date_min)->m){
            return $date_max->diff($date_min)->m;
        }
        return 0;
    }else{
        return 0;
    }
}

function difference_days_array($date_max='',$date_min=''){ 
    if(strtotime($date_max) > strtotime($date_min)){ 
        $date_max = new DateTime($date_max);
        $date_min = new DateTime($date_min);
        if($date_max->diff($date_min)->d){
            return ["count"=>$date_max->diff($date_min)->d, "format"=>"d"];
        }elseif($date_max->diff($date_min)->h){
            return ["count"=>$date_max->diff($date_min)->h, "format"=>"h"];
        }elseif($date_max->diff($date_min)->m){
            return ["count"=>$date_max->diff($date_min)->m, "format"=>"m"];
        }
    }
    return ["count"=>0];
}

function time_of_day(){
    $h = date("H");
    if($h >= 04) { $result = 'morning'; }
    if($h >= 10) { $result = 'day'; }
    if($h >= 16) { $result = 'evening'; }
    if($h >= 22 || $h < 04) { $result = 'night'; }
    return $result;
}

function verify_auth($action, $actions=[]){
    $Main = new Main();
    if(in_array($action, $actions)){
        if(!$_SESSION["profile"]["id"]){ echo $Main->response(401); }
    }
}

function back_step_user()
{
    global $config;
    if($_SESSION['user_step_route']){
        if($_SESSION['user_step_route'][ count($_SESSION['user_step_route']) - 2 ]){
            return $_SESSION['user_step_route'][ count($_SESSION['user_step_route']) - 2 ];
        }
    }
    return $config['urlPath'];
}

function verify_mass_requests(){
    $Main = new Main();
    if($_SESSION["time_last_requests_ad_create"]){
        $milliseconds = round(microtime(true) * 1000);
        $passed = $milliseconds - $_SESSION["time_last_requests_ad_create"];
        if($passed <= 15000){
            $_SESSION["time_last_requests_ad_create"] = $milliseconds;
            echo $Main->response(500);
        }
    }

    $_SESSION["time_last_requests_ad_create"] = $milliseconds;
}

function sendPostRequest($api_url, $args, $header=[])
{

    if (is_array($args)) {
        $args = json_encode($args);
    }

    if ($curl = curl_init()) {
        curl_setopt($curl, CURLOPT_URL, $api_url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $args);

        if($header) curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        $out = curl_exec($curl);
        $json = json_decode($out, true);

        curl_close($curl);

        return $json;

    }
}

function getNameMeasuresPrice($key=''){
    global $settings;

    $measuresPrice = json_decode($settings['measures_price'], true);

    return $measuresPrice[$key] ? $measuresPrice[$key] : $key;
}

function outStaticTextMakros(){
    global $settings;
    if($settings["main_type_products"] == 'physical'){
        return '{domen}, {url}, {country}, {city}, {region}, {geo}, {geo_declination}, {site_name}';
    }else{
        return '{domen}, {url}, {site_name}';
    }
}

function manualRunCron($name){

    global $config;

    $Ads = new Ads();
    $Profile = new Profile();
    $Main = new Main();
    $Filters = new Filters();
    $CategoryBoard = new CategoryBoard();
    $Blog = new Blog();
    $Cache = new Cache();
    $Geo = new Geo();
    $Shop = new Shop();
    $Admin = new Admin();

    define('unisitecms', true);

    if(file_exists($config["basePath"]."/systems/cron/{$name}.php")) @include($config["basePath"]."/systems/cron/{$name}.php");

}

function cleanFiltersVars(){
    if($_GET['filter']){
        if($_GET['filter']['price']['from']){
            $_GET['filter']['price']['from'] = round(preg_replace('/\s/', '', $_GET['filter']['price']['from']),2);
        }
        if($_GET['filter']['price']['to']){
            $_GET['filter']['price']['to'] = round(preg_replace('/\s/', '', $_GET['filter']['price']['to']),2);
        }  
        if($_GET['filter']['sort']){
            $_GET['filter']['sort'] = clear($_GET['filter']['sort']);
        }  
        if($_GET['filter'] && is_array($_GET['filter'])){ 
            foreach ($_GET['filter'] as $key => $value) {
               if(is_array($value)){
                   if($value['from']){
                      $_GET['filter'][$key]['from'] = round($_GET['filter'][$key]['from'],2);
                   }
                   if($value['to']){
                      $_GET['filter'][$key]['to'] = round($_GET['filter'][$key]['to'],2);
                   }
               }
            }
        }
    }     
}

function genRefId(){
    return md5(time().uniqid());
}

function genVerificationCode(){
    return mt_rand(1000000,9999999);
}

function sendMessageFirebase($data = []){

    global $settings, $config;

    $params = [];

    if(!$settings['fbm_params'] || !$data || !$settings["app_available_status"]) return false;

     foreach ($data as $token => $message) {

        $params["message"] = [
                "token" => $token,
                "notification" => [
                    "title" => $message['title'],
                    "body" => $message['text'],
                ],
                "data" => [
                    "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                    "screen" => $message['screen'] ? (String)$message['screen'] : 'chat',
                    "id_hash" => $message['id_hash'] ? (String)$message['id_hash'] : null,
                    "support" => $message['support'] ? '1' : '0',
                    "id_ad" => $message['id_ad'] ? (String)$message['id_ad'] : null,
                ],
                "android" => [
                    "notification" => [
                        "sound" => "default",
                    ],
                    "priority" => "high",
                ],    
                "apns" => [
                    "payload" => [
                        "aps" => [
                            "sound" => "default",
                        ],
                    ]
                ],                
        ]; 
      
     }

    if(strtotime($settings['fbm_bearer_expires_in']) <= time() || !$settings['fbm_bearer_expires_in']){

        $client = new Google\Client();

        $client->setAuthConfig(json_decode(decrypt($settings['fbm_params']), true));
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        if($token['access_token']){
            $fbm_bearer = $token['access_token'];
            update("UPDATE uni_settings SET value=? WHERE name=?", array(encrypt($fbm_bearer),'fbm_bearer'));
            update("UPDATE uni_settings SET value=? WHERE name=?", array(date('Y-m-d H:i:s', intval($token['created'])+intval($token['expires_in'])),'fbm_bearer_expires_in'));
        }

    }else{
        $fbm_bearer = decrypt($settings['fbm_bearer']);
    }


    $apiurl = 'https://fcm.googleapis.com/v1/projects/'.$settings['fbm_project_id'].'/messages:send';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $apiurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

    $headers = array(
        'Authorization: Bearer '.$fbm_bearer,
        'Content-Type: application/json'
    );

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = json_decode(curl_exec($ch), true);

    curl_close($ch);

    if(empty($result['error'])){
        return true;
    }

    return false;
}

function firebase_notification($data = [], $action=""){
    global $settings;

    $Profile = new Profile();

    if($action == "moderation_ad"){

        $text = "Объявление «".$data["ads_title"]."» прошло модерацию";

        $getMessageToken = findOne('uni_clients_fcm_tokens','user_id=?', [$data["ads_id_user"]]);
        if($getMessageToken){ 
            $messages[$getMessageToken['token']] = ['title'=>$settings['site_name'], 'text'=>$text, 'screen'=>'ad', 'id_ad'=>$data["ads_id"]]; 

            sendMessageFirebase($messages);
        }

    }elseif($action == "notification_chats"){

        $getUser = getOne("select * from uni_chat_users INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_chat_users`.chat_users_id_user where chat_users_id_hash=? and chat_users_id_interlocutor=?", array( $data["chat_messages_id_hash"], $data["chat_messages_id_user"] ));

        $totalCount = $Profile->getMessage($getUser["chat_users_id_user"])["total"];

        $text = "У вас ".$totalCount." ".ending($totalCount, "новое сообщение в чате", "новых сообщения в чате", "новых сообщений в чате");

        $getMessageToken = findOne('uni_clients_fcm_tokens','user_id=?', [$getUser["chat_users_id_user"]]);  
        if($getMessageToken){ 
            $messages[$getMessageToken['token']] = ['title'=>$settings['site_name'], 'text'=>$text, 'screen'=>'chat', 'only_notification'=>true]; 
            sendMessageFirebase($messages);
        }

    }
}

function checkAvailableFfmpeg(){
    global $config;

    return $config["video"]["ffmpeg"];

}

function curlPostRequests($apiurl="",$headers = [], $params = []){

  global $settings, $config;

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $apiurl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = json_decode(curl_exec($ch), true);

  curl_close($ch);

  return $result;

}

function mb_ucfirst($string="")
{
    $firstChar = mb_substr($string, 0, 1, "utf-8");
    $then = mb_substr($string, 1, null, "utf-8");
    return mb_strtoupper($firstChar, "utf-8") . $then;
}
?>