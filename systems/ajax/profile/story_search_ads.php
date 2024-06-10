<?php

$query = clearSearchBack($_POST["search"]);

if($query && mb_strlen($query, 'UTF-8') > 2){

    $getAds = $Ads->getAll(["query"=>"ads_status='1' and ads_period_publication > now() and ads_id_user=".$_SESSION["profile"]["id"].' and '.$Filters->explodeSearch($query), "sort"=>"ORDER By ads_datetime_add DESC"]);
    
    if($getAds['count']){
    foreach ($getAds['all'] as $value) {
        ?>
        <div data-id="<?php echo $value["ads_id"]; ?>" data-title="<?php echo $value["ads_title"]; ?>" >
        <div class="modal-user-story-add-footer-ads-list-item-image" ></div>
        <div class="modal-user-story-add-footer-ads-list-item-title" ><?php echo $value["ads_title"]; ?></div>
        </div>
        <?php
    }
    }

}else{

    $getAds = $Ads->getAll(["query"=>"ads_status='1' and ads_period_publication > now() and ads_id_user=".$_SESSION["profile"]["id"], "sort"=>"ORDER By ads_datetime_add DESC limit 10"]);

    if($getAds['count']){
    foreach ($getAds['all'] as $value) {
        ?>
        <div data-id="<?php echo $value["ads_id"]; ?>" data-title="<?php echo $value["ads_title"]; ?>" >
        <div class="modal-user-story-add-footer-ads-list-item-image" ></div>
        <div class="modal-user-story-add-footer-ads-list-item-title" ><?php echo $value["ads_title"]; ?></div>
        </div>
        <?php
    }
    }

}

?>