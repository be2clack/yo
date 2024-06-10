<?php
/**
 * UniSite CMS
 *
 * @copyright 	2018 Artur Zhur
 * @link 		https://unisitecms.ru
 * @author 		Artur Zhur
 *
 */

class Subscription{

    function add($array = array()){
        if($array["email"]){
        $get = getOne("select count(*) as total from uni_subscription where subscription_email=?", [$array["email"]]);
          if(!$get["total"]){

             if(!$array["name"]) $array["name"] = explode("@", $array["email"])[0];

             insert("INSERT INTO uni_subscription(subscription_email,subscription_ip,subscription_name,subscription_user_id,subscription_status,subscription_datetime_add)VALUES(?,?,?,?,?,?)", array($array["email"],clear($_SERVER["REMOTE_ADDR"]),clear($array["name"]),intval($array["user_id"]),intval($array["status"]),date("Y-m-d H:i:s")));

             return true;
          }else{
          	 return false;
          }          
        }else{
          return false;
        }      
    }
       
}

?>