<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

if( !(new Admin())->accessAdmin($_SESSION['cp_control_statistics']) ){
   exit;
}

$Main = new Main();
$Ads = new Ads();
$Profile = new Profile();
$Geo = new Geo();
$Mobile_Detect = new Mobile_Detect;

if(isAjax() == true){

    $now = date("Y-m-d");

    $x=0;
    while ($x++<6){
       $week[ date('Y-m-d', strtotime("-".$x." day")) ] = date('Y-m-d', strtotime("-".$x." day"));
    }

    $week[ date('Y-m-d') ] = date('Y-m-d');

    ksort($week);


    foreach ($week as $key => $value) {

       $getClients = getOne("select count(*) as total from uni_clients where clients_status!='3' and date(clients_datetime_add) = '".$value."'");
       $data_clients[] = array( $value ,intval($getClients["total"]) );

       $getAds = getOne("select count(*) as total from uni_ads where ads_status!='8' and date(ads_datetime_add) = '".$value."'");
       $data_ads[] = array( $value ,intval($getAds["total"]) );

       $getOrders = getOne("select sum(orders_price) as total from uni_orders where orders_status_pay=1 and date(orders_date) = '".$value."'");
       $data_orders[] = array( $value ,round($getOrders["total"],2) );

       $getTraffic = getOne("select count(*) as total from uni_metrics where date(date_view) = '".$value."'");
       $data_traffic[] = array( $value ,intval($getTraffic["total"]) );

    }


    $getAds = $Ads->getAll( array( "query" => "ads_status=0 and clients_status!=3 and clients_status!=2", "sort" => "order by ads_datetime_add desc" ) );

    ob_start();
    require __dir__ . "/include/list-ads.php";
    $list_ads = ob_get_clean();

    $getUsers = getAll("select * from uni_clients where clients_status!='3' and unix_timestamp(clients_datetime_view)+3*60 > unix_timestamp(NOW())");

    ob_start();
    require __dir__ . "/include/list-users.php";
    $list_users = ob_get_clean();
    
    $where = $settings["statistics_variant"] == 1 ? "" : "where date(date_view) = '$now'";
    if($where){
      $countMetrics = getOne("select count(*) as total from uni_metrics $where")["total"];
    }else{
      $countMetrics = getOne("select count(*) as total from uni_metrics where unique_visit=1")["total"];
    }
    $count = getOne("select count(*) as total from uni_metrics $where")["total"];
    $getMetrics = getAll("SELECT * FROM uni_metrics $where order by date_view desc " . navigation_offset( array( "count"=>$count, "output"=>20, "page"=>$_POST["page"] ) )  );
    
    ob_start();
    require __dir__ . "/include/list-traffic.php";
    $list_traffic = ob_get_clean(); 

    $getLogs = getAll("SELECT * FROM uni_notifications order by id desc");   

    ob_start();
    require __dir__ . "/include/list-log-action.php";
    $list_log_action = ob_get_clean();

    $chatMessages = [];

    if($_SESSION['cp_control_chat']){
      $getActiveDialog = getAll('select * from uni_chat_users where chat_users_id_user=? or chat_users_id_interlocutor=? group by chat_users_id_hash order by chat_users_id desc limit 3', [0,0]);

      if(count($getActiveDialog)){
         foreach ($getActiveDialog as $value) {
            $getMessage = findOne('uni_chat_messages', 'chat_messages_id_hash=? and chat_messages_status=? and chat_messages_id_user!=? order by chat_messages_date desc', [$value['chat_users_id_hash'],0,0]);
            if($getMessage) $chatMessages[] = $getMessage;
         }
      }
    }

    ob_start();
    require __dir__ . "/include/list-chat-messages.php";
    $list_chat_messages = ob_get_clean();

    if($settings["statistics_variant"] == 1){
  
      echo json_encode( 
                  array( 

                    "clients" => array( "count" => (int)getOne("select count(*) as total from uni_clients where clients_status!='3'")["total"], "data" => $data_clients ), 
                    "ads" => array( "count" => (int)$settings["total_count_ads"], "data" => $data_ads ), 
                    "orders" => array( "count" => $Main->price(getOne("select sum(orders_price) as total from uni_orders where orders_status_pay=1")["total"]) , "data" => $data_orders ), 
                    "traffic" => array( "count" => (int)getOne("select count(*) as total from uni_metrics where date(date_view) = '$now'")["total"] , "data" => $data_traffic ), 
                    "list_ads" => $list_ads,
                    "list_users" => $list_users,
                    "list_traffic" => $list_traffic,
                    "list_log_action" => $list_log_action,
                    "list_chat_messages" => $list_chat_messages,

                  ) 
      );

    }else{

      echo json_encode( 
                  array( 

                    "clients" => array( "count" => (int)getOne("select count(*) as total from uni_clients where clients_status!='3' and date(clients_datetime_add)='$now'")["total"], "data" => $data_clients ), 
                    "ads" => array( "count" => (int)getOne("select count(*) as total from uni_ads INNER JOIN `uni_clients` ON `uni_clients`.clients_id = `uni_ads`.ads_id_user where ads_status!='8' and clients_status!='3' and date(ads_datetime_add)='$now'")["total"], "data" => $data_ads ), 
                    "orders" => array( "count" => $Main->price(getOne("select sum(orders_price) as total from uni_orders where orders_status_pay=1 and date(orders_date)='$now'")["total"]) , "data" => $data_orders ), 
                    "traffic" => array( "count" => (int)getOne("select count(*) as total from uni_metrics where date(date_view) = '$now'")["total"] , "data" => $data_traffic ), 
                    "list_ads" => $list_ads,
                    "list_users" => $list_users,
                    "list_traffic" => $list_traffic,
                    "list_log_action" => $list_log_action,
                    "list_chat_messages" => $list_chat_messages,

                  ) 
      );

    }

}
?>