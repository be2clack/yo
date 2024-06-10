<?php

$index = (int)$_POST["index"];
$category_id = (int)$_POST["cat_id"];

$queryLocation = "";
$getUserStories = $Profile->getUserStories($category_id);

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
    $queryCategory = "and (clients_stories_media_cat_id IN(".$ids_cat.") or clients_stories_media_cat_id=0)";            
}

if(isset($getUserStories[$index])){

    if($getUserStories[$index]["user_id"] == $_SESSION['profile']['id']){
        if(!$category_id){
            $getStories = getAll('select * from uni_clients_stories_media where clients_stories_media_user_id=? and clients_stories_media_loaded=? order by clients_stories_media_timestamp desc', [$getUserStories[$index]["user_id"],1]);
        }else{
            $getStories = getAll('select * from uni_clients_stories_media where clients_stories_media_user_id=? and clients_stories_media_loaded=? and clients_stories_media_status=? '.$queryCategory.' order by clients_stories_media_timestamp desc', [$getUserStories[$index]["user_id"],1,1]);
        }
    }else{
        $getStories = getAll('select * from uni_clients_stories_media where clients_stories_media_user_id=? and clients_stories_media_loaded=? and clients_stories_media_status=? '.$queryLocation.' '.$queryCategory.' order by clients_stories_media_timestamp desc', [$getUserStories[$index]["user_id"],1,1]);
    }
    
    if($getStories){

        ?>
        <div class="modal-view-user-stories-progress-header" >

        <div class="text-right" ><span class="modal-view-user-stories-close" ><?php echo $ULang->t("Закрыть"); ?></span></div>

        <div class="modal-view-user-stories-progress" >
        <?php
        foreach ($getStories as $key => $value) {
            ?>
            <span data-index="<?php echo $key+1; ?>" ></span>
            <?php
        }            
        ?>
        </div>
        </div>
        <?php

        foreach ($getStories as $key => $value) {

            $getUser = findOne('uni_clients', 'clients_id=?', [$value['clients_stories_media_user_id']]);
            $getShop = $Shop->getUserShop($value['clients_stories_media_user_id']);
            if($value['clients_stories_media_ad_id']) $getAd = $Ads->get("ads_id=?", [$value['clients_stories_media_ad_id']]);
                
            ?>
            <div class="modal-view-user-stories-item <?php if($key == 0){ echo 'active'; } ?>" data-id="<?php echo $value['clients_stories_media_id']; ?>" data-index="<?php echo $key+1; ?>" data-duration="<?php echo $value['clients_stories_media_duration']; ?>" data-type="<?php echo $value['clients_stories_media_type']; ?>" >
                <div class="modal-view-user-stories-item-header" >
                    <a class="modal-view-user-stories-item-header-user" href="<?php echo $Profile->userLink($getUser); ?>" >
                        <div class="modal-view-user-stories-item-header-user-avatar" ><img src="<?php echo $Profile->userAvatar($getUser); ?>" /></div>
                        <div class="modal-view-user-stories-item-header-user-name" >
                            <span><strong><?php echo $Profile->name($getUser); ?></strong></span>
                            <?php if($value['clients_stories_media_status']){ ?>
                            <span class="modal-view-user-stories-item-header-user-count" ><?php echo $Profile->countViewStories($value['clients_stories_media_id']).' '.ending($Profile->countViewStories($value['clients_stories_media_id']), $ULang->t("просмотр"), $ULang->t("просмотра"), $ULang->t("просмотров")); ?></span> 
                            <?php } ?> 
                            <?php if(!$value['clients_stories_media_status']){ ?> <div class="modal-view-user-stories-item-header-user-status" ><?php echo $ULang->t("На модерации"); ?></div> <?php } ?>  
                        </div>
                    </a>
                    <?php if($value['clients_stories_media_user_id'] == $_SESSION["profile"]["id"]){ ?>
                    <div class="modal-view-user-stories-right-menu" >
                        <i class="las la-ellipsis-v"></i>

                        <div class="modal-view-user-stories-right-menu-list" >
                        <span class="modal-view-user-stories-right-menu-delete open-modal" data-story-id="<?php echo $value['clients_stories_media_id']; ?>" data-id-modal="modal-user-story-confirm-delete" ><?php echo $ULang->t("Удалить"); ?></span>
                        </div>

                    </div>
                    <?php } ?>
                </div>
                <?php
                    if($value['clients_stories_media_type'] == 'image'){
                        ?>
                        <div class="modal-view-user-stories-item-content" ><img src="<?php echo $config['urlPath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_name']; ?>"></div>
                        <?php
                    }else{
                        ?>
                        <div class="modal-view-user-stories-item-content" ><video class="story-video" name="media"><source src="<?php echo $config['urlPath'].'/'.$config['media']['user_stories'].'/'.$value['clients_stories_media_name']; ?>" type="video/mp4"></video></div>
                        <?php
                    }
                ?>
                <div class="modal-view-user-stories-item-footer" >
                    
                    <?php if($value['clients_stories_media_ad_id'] != 0){ ?>
                        <div class="modal-view-user-stories-item-footer-ads" >
                            <span><?php echo $getAd['ads_title']; ?></span>
                            <span><?php echo $Ads->outPrice( [ "data"=>$getAd,"class_price"=>"item-grid-price-now","class_price_old"=>"item-grid-price-old", "shop"=>$getShop, "abbreviation_million" => true ] ); ?></span>
                        </div>
                        <a href="<?php echo $Ads->alias($getAd); ?>" class="btn-custom btn-color-light width100" ><?php echo $ULang->t("Открыть объявление"); ?></a>
                    <?php }else{ ?>
                        <a href="<?php echo $Profile->userLink($getUser); ?>" class="btn-custom btn-color-light width100" ><?php echo $ULang->t("Открыть профиль"); ?></a>
                    <?php } ?>

                </div>
                <button class="modal-view-user-stories-item-control-left" ></button>
                <button class="modal-view-user-stories-item-control-right" ></button>
            </div>
            <?php

        }

    }

}

?>