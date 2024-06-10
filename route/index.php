<?php

$config = require "./config.php";

$route_name = "index";
$visible_footer = true;
$visible_cities = true;

$Main = new Main();
$settings = $Main->settings();

$Ads = new Ads();
$Main = new Main();
$Seo = new Seo();
$Geo = new Geo();
$Profile = new Profile();
$CategoryBoard = new CategoryBoard();
$Banners = new Banners();
$Filters = new Filters();
$Blog = new Blog();
$ULang = new ULang();
$Elastic = new Elastic();
$Shop = new Shop();
$Cache = new Cache();

if($ref_id_hash && $settings["referral_program_status"]){
    if(!$_SESSION["profile"]["id"]){
	    $getReferrer = findOne('uni_clients', 'clients_ref_id=?', [clear($ref_id_hash)]);
	    if($getReferrer){
	    	$_SESSION['ref_id'] = $getReferrer['clients_id'];
	    	setcookie('ref_id', $getReferrer['clients_id'], time()+2678400);
	    	update('delete from uni_clients_ref_transitions where ip=?', [$_SERVER["REMOTE_ADDR"]]);
            smart_insert('uni_clients_ref_transitions', [
                'timestamp' => date("Y-m-d H:i:s"),
                'ip' => $_SERVER["REMOTE_ADDR"],
                'id_user_referrer' => $getReferrer['clients_id'],
            ]);	    	
	    }
    }
    if(isset($_GET['id_hash'])){
    	header("Location: " . _link("user/".$_GET['id_hash']));
    }else{
    	header("Location: " . _link("/"));
    }
	exit;
}

$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");

$data["h1"] = $Seo->out(array("page" => "index", "field" => "h1"));

$data["seo_text"] = $Seo->out(array("page" => "index", "field" => "text"));

echo $Main->tpl("index.tpl",compact('Seo','Geo','Main','visible_footer','route_name','settings','config','data','Profile','CategoryBoard','Banners','getCategoryBoard', 'Ads', 'Blog', 'ULang', 'Shop', 'visible_cities'));
?>