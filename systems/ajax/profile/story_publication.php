<?php

$adId = (int)$_POST["id"];
$fileName = clear($_POST["name"]);
$type = clear($_POST["type"]);

$city_id = (int)$_POST["city_id"];
$region_id = (int)$_POST["region_id"];
$country_id = (int)$_POST["country_id"];
$category_id = (int)$_POST["category_id"];

$videoPreview = '';
$paymentAmount = 0;

if($type == 'image'){
$filePath = $config["basePath"] . "/" . $config["media"]["temp_images"]. "/" . $fileName;
}elseif($type == 'video'){
$filePath = $config["basePath"] . "/" . $config["media"]["temp_video"]. "/" . $fileName;
}else{
exit;
}

if(file_exists($filePath)){

    if($settings["user_stories_paid_add"] && $settings["user_stories_price_add"] && !isset($_SESSION['profile']['tariff']['services']['stories'])){

        if($settings["user_stories_free_add"]){
        $getUser = findOne("uni_clients", "clients_id=?", [$_SESSION["profile"]["id"]]);
        if($getUser['clients_count_story_publication'] >= $settings["user_stories_free_add"]){

            if($_SESSION['profile']['data']['clients_balance'] >= $settings["user_stories_price_add"]){ 

                $paymentAmount = $settings["user_stories_price_add"];
            
                $Main->addOrder( ["price"=>$settings["user_stories_price_add"],"title"=>$static_msg["59"],"id_user"=>$_SESSION["profile"]["id"],"status_pay"=>1, "user_name" => $_SESSION['profile']['data']['clients_name'], "id_hash_user" => $_SESSION['profile']['data']['clients_id_hash'], "action_name" => "stories"] );

                $Profile->actionBalance(array("id_user"=>$_SESSION['profile']['id'],"summa"=>$settings["user_stories_price_add"],"title"=>$static_msg["59"],"id_order"=>generateOrderId()),"-");

            }else{
            
                exit(json_encode( ["status"=>false, "balance"=>false, "balance_summa"=>$Main->price($_SESSION['profile']['data']['clients_balance'])] ));

            }

        }
        }else{

        if($_SESSION['profile']['data']['clients_balance'] >= $settings["user_stories_price_add"]){ 

            $paymentAmount = $settings["user_stories_price_add"];
            
            $Main->addOrder( ["price"=>$settings["user_stories_price_add"],"title"=>$static_msg["59"],"id_user"=>$_SESSION["profile"]["id"],"status_pay"=>1, "user_name" => $_SESSION['profile']['data']['clients_name'], "id_hash_user" => $_SESSION['profile']['data']['clients_id_hash'], "action_name" => "stories"] );

            $Profile->actionBalance(array("id_user"=>$_SESSION['profile']['id'],"summa"=>$settings["user_stories_price_add"],"title"=>$static_msg["59"],"id_order"=>generateOrderId()),"-");

        }else{
            
            exit(json_encode( ["status"=>false, "balance"=>false, "balance_summa"=>$Main->price($_SESSION['profile']['data']['clients_balance'])] ));

        }

        }

    }

    if(copy($filePath, $config["basePath"] . "/" . $config["media"]["user_stories"] . "/" . $fileName)){

        if($type == 'video' && checkAvailableFfmpeg()){
        $videoPreview = md5('video_preview_'.uniqid()).'.jpg';
        exec("ffmpeg -ss 00:00:01.00 -i ".$config["basePath"] . "/" . $config["media"]["user_stories"]. "/" . $fileName." -vf 'scale=1024:720:force_original_aspect_ratio=decrease' -vframes 1 ". $config["basePath"] . "/" . $config["media"]["user_stories"]. "/" . $videoPreview);
        }

        $getStory = findOne('uni_clients_stories', 'clients_stories_user_id=?', [$_SESSION['profile']['id']]);

        if(!$getStory){
            smart_insert('uni_clients_stories', [
            'clients_stories_user_id'=>$_SESSION['profile']['id'],
            'clients_stories_timestamp'=>date("Y-m-d H:i:s"),
            ]);   
        }else{
            update('update uni_clients_stories set clients_stories_timestamp=? where clients_stories_user_id=?', [date("Y-m-d H:i:s"),$_SESSION['profile']['id']]);
        }

        smart_insert('uni_clients_stories_media', [
        'clients_stories_media_user_id'=>$_SESSION['profile']['id'],
        'clients_stories_media_name'=>$fileName,
        'clients_stories_media_preview'=>$videoPreview,
        'clients_stories_media_type'=>$type,
        'clients_stories_media_duration'=>intval($settings["user_stories_video_length"]),
        'clients_stories_media_ad_id'=>$adId,
        'clients_stories_media_loaded'=>1,
        'clients_stories_media_payment'=>1,
        'clients_stories_media_payment_amount'=>$paymentAmount,
        'clients_stories_media_timestamp'=>date("Y-m-d H:i:s"),
        'clients_stories_media_status'=>$settings["user_stories_moderation"] ? 0 : 1,
        'clients_stories_media_city_id'=>$city_id,
        'clients_stories_media_region_id'=>$region_id,
        'clients_stories_media_country_id'=>$country_id,
        'clients_stories_media_cat_id'=>$category_id,
        'clients_stories_media_not_delete'=>isset($_SESSION['profile']['tariff']['services']['stories_without_limitation']) ? 1 : 0,
        'clients_stories_media_date_completion'=>$Profile->userStoryDateCompletion($_SESSION['profile']['id']),
        ]);

        update('update uni_clients set clients_count_story_publication=clients_count_story_publication+1 where clients_id=?', [$_SESSION['profile']['id']]);

        $Admin->notifications("user_story");

        echo json_encode(["status"=>true, "moderation"=>$settings["user_stories_moderation"] ? true : false, "balance"=>true]);

    }

}

?>