<?php

$videoPreview = '';
$extensions_image = array('jpeg', 'jpg', 'png');
$extensions_video = array('mp4', 'avi', 'mov');

$getUser = findOne("uni_clients", "clients_id=?", [$_SESSION["profile"]["id"]]);

if(!empty($_FILES["story_media"]['name'])){
        
    $ext = strtolower(pathinfo($_FILES["story_media"]['name'], PATHINFO_EXTENSION));
    $name = md5($_SESSION['profile']['id'].uniqid()) . '.' . $ext;
    
    if(in_array($ext, $extensions_image)){

        $filePath = $config["basePath"] . "/" . $config["media"]["temp_images"]. "/" . $name;

        if(move_uploaded_file($_FILES["story_media"]['tmp_name'], $filePath)){

            resize($filePath, $filePath, 1024, 0);

            ?>

            <div class="modal-user-story-add-header-maker-close" ><i class="las la-times"></i></div>

            <div class="modal-user-story-add-content-maker" >
                <img src="<?php echo $config["urlPath"] . "/" . $config["media"]["temp_images"]. "/" . $name; ?>" />
            </div>

            <div class="modal-user-story-add-footer-maker" >

                <div class="modal-user-story-add-footer-actions-maker" >
                    <strong><?php echo $ULang->t("Буду продвигать"); ?></strong>
                    <span class="modal-user-story-add-change-promovere modal-user-story-add-footer-actions-title" > <span><?php echo $ULang->t("Свой профиль"); ?></span> <i class="las la-angle-down"></i></span>

                    <strong class="mt10" ><?php echo $ULang->t("Локация"); ?></strong>
                    <span class="modal-user-story-add-change-location modal-user-story-add-footer-actions-title" > <span><?php echo $ULang->t("Все города"); ?></span> <i class="las la-angle-down"></i></span>

                    <strong class="mt10" ><?php echo $ULang->t("Категория"); ?></strong>
                    <span class="modal-user-story-add-change-category modal-user-story-add-footer-actions-title" > <span><?php echo $ULang->t("Все категории"); ?></span> <i class="las la-angle-down"></i></span>

                    <div class="modal-user-story-add-footer-promovere-list" >
                        <div class="button-style-custom btn-color-green" data-type="profile" ><?php echo $ULang->t("Свой профиль"); ?></div>
                        <div class="button-style-custom btn-color-green" data-type="ad" ><?php echo $ULang->t("Объявление"); ?></div>
                    </div>

                    <div class="modal-user-story-add-footer-ads-list" >
                        <input type="text" class="form-control" placeholder="<?php echo $ULang->t("Поиск объявлений"); ?>">
                        <div class="modal-user-story-add-footer-ads-list-search" >
                            <?php
                                $getAds = $Ads->getAll(["query"=>"ads_status='1' and ads_period_publication > now() and ads_id_user=".$_SESSION["profile"]["id"], "sort"=>"ORDER By ads_datetime_add DESC limit 10"]);
                                if($getAds['count']){
                                foreach ($getAds['all'] as $value) {
                                    ?>
                                    <div data-id="<?php echo $value["ads_id"]; ?>" data-title="<?php echo $value["ads_title"]; ?>" >
                                    <div class="modal-user-story-add-footer-ads-list-item-title" ><?php echo $value["ads_title"]; ?></div>
                                    </div>
                                    <?php
                                }
                                }
                            ?>
                        </div>
                    </div>

                    <div class="modal-user-story-add-footer-location-list" >
                        <input type="text" class="form-control" placeholder="<?php echo $ULang->t("Укажите название города"); ?>">
                        <div class="modal-user-story-add-footer-location-list-search" >
                            <div data-name="<?php echo $ULang->t("Все города"); ?>" id-country="0" id-region="0" id-city="0" ><?php echo $ULang->t("Все города"); ?></div>
                            <?php
                              if(!$settings["region_id"]){
                                $get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_city`.city_default = '1' and `uni_country`.country_status = '1' order by city_count_view desc limit 15");
                                if(!count($get)){
                                   $get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_country`.country_status = '1' order by city_count_view desc limit 15");
                                }
                              }else{
                                $get = getAll("SELECT * FROM uni_city WHERE region_id='{$settings["region_id"]}' order by city_count_view desc limit 15");
                              }

                              if($get){
                                foreach ($get as $key => $value) {
                                    ?>
                                    <div data-name="<?php echo $value["city_name"]; ?>" id-country="0" id-region="0" id-city="<?php echo $value["city_id"]; ?>" ><?php echo $value["city_name"]; ?></div>
                                    <?php
                                }
                              }
                            ?>
                        </div>
                    </div>

                    <div class="modal-user-story-add-footer-category-list" >
                        <input type="text" class="form-control" placeholder="<?php echo $ULang->t("Укажите название категории"); ?>">
                        <div class="modal-user-story-add-footer-category-list-search" >
                            <?php
                              $getCategories = $CategoryBoard->getCategories("where category_board_visible=1");
                              if($getCategories){
                                foreach ($getCategories["category_board_id_parent"][0] as $value) {
                                    ?>
                                    <div data-name="<?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?>" id-cat="<?php echo $value["category_board_id"]; ?>" ><?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?></div>
                                    <?php
                                }
                              }
                            ?>
                        </div>
                    </div>

                </div>

                <?php
                    if($settings["user_stories_paid_add"] && $settings["user_stories_price_add"] && !isset($_SESSION['profile']['tariff']['services']['stories'])){

                    if($settings["user_stories_free_add"]){

                        if($getUser['clients_count_story_publication'] >= $settings["user_stories_free_add"]){
                        ?>
                        <button class="button-style-custom btn-color-blue schema-color-button user-story-publication mt5" data-type="image" data-name="<?php echo $name; ?>" ><?php echo $ULang->t("Оплатить"); ?> <?php echo $Main->price($settings["user_stories_price_add"]); ?> <?php echo $ULang->t("и опубликовать"); ?></button>
                        <?php                                
                        }else{
                        ?>
                        <button class="button-style-custom btn-color-blue schema-color-button user-story-publication mt5" data-type="image" data-name="<?php echo $name; ?>" ><?php echo $ULang->t("Опубликовать"); ?></button>
                        <?php                                
                        }

                    }else{
                        ?>
                        <button class="button-style-custom btn-color-blue schema-color-button user-story-publication mt5" data-type="image" data-name="<?php echo $name; ?>" ><?php echo $ULang->t("Оплатить"); ?> <?php echo $Main->price($settings["user_stories_price_add"]); ?> <?php echo $ULang->t("и опубликовать"); ?></button>
                        <?php                              
                    }

                    }else{
                    ?>
                    <button class="button-style-custom btn-color-blue schema-color-button user-story-publication mt5" data-type="image" data-name="<?php echo $name; ?>" ><?php echo $ULang->t("Опубликовать"); ?></button>
                    <?php
                    }
                ?>
            </div>

            <?php

        }

    }elseif(in_array($ext, $extensions_video)){

        $filePath = $config["basePath"] . "/" . $config["media"]["temp_video"]. "/" . $name;

        if(move_uploaded_file($_FILES["story_media"]['tmp_name'], $filePath)){

            if($settings["user_stories_video_length"] && checkAvailableFfmpeg()){
                $name = md5($_SESSION['profile']['id'].uniqid()) . '.mp4';
                exec('ffmpeg -i '.$filePath.' -c:v libx264 -t '.$settings["user_stories_video_length"].' '.$config["basePath"] . "/" . $config["media"]["temp_video"]. "/".$name);
                unlink($filePath);
            }

            ?>

            <div class="modal-user-story-add-header-maker-close" ><i class="las la-times"></i></div>

            <div class="modal-user-story-add-content-maker" >
                <video  name="media" controls><source src="<?php echo $config["urlPath"] . "/" . $config["media"]["temp_video"]. "/" . $name; ?>" type="video/mp4"></video>
            </div>

            <div class="modal-user-story-add-footer-maker" >

                <div class="modal-user-story-add-footer-actions-maker" >
                    <strong><?php echo $ULang->t("Буду продвигать"); ?></strong>
                    <span class="modal-user-story-add-change-promovere modal-user-story-add-footer-actions-title" > <span><?php echo $ULang->t("Свой профиль"); ?></span> <i class="las la-angle-down"></i></span>

                    <strong class="mt10" ><?php echo $ULang->t("Локация"); ?></strong>
                    <span class="modal-user-story-add-change-location modal-user-story-add-footer-actions-title" > <span><?php echo $ULang->t("Все города"); ?></span> <i class="las la-angle-down"></i></span>

                    <strong class="mt10" ><?php echo $ULang->t("Категория"); ?></strong>
                    <span class="modal-user-story-add-change-category modal-user-story-add-footer-actions-title" > <span><?php echo $ULang->t("Все категории"); ?></span> <i class="las la-angle-down"></i></span>

                    <div class="modal-user-story-add-footer-promovere-list" >
                        <div class="button-style-custom btn-color-green" data-type="profile" ><?php echo $ULang->t("Свой профиль"); ?></div>
                        <div class="button-style-custom btn-color-green" data-type="ad" ><?php echo $ULang->t("Объявление"); ?></div>
                    </div>

                    <div class="modal-user-story-add-footer-ads-list" >
                        <input type="text" class="form-control" placeholder="<?php echo $ULang->t("Поиск объявлений"); ?>">
                        <div class="modal-user-story-add-footer-ads-list-search" >
                            <?php
                                $getAds = $Ads->getAll(["query"=>"ads_status='1' and ads_period_publication > now() and ads_id_user=".$_SESSION["profile"]["id"], "sort"=>"ORDER By ads_datetime_add DESC limit 10"]);
                                if($getAds['count']){
                                foreach ($getAds['all'] as $value) {
                                    ?>
                                    <div data-id="<?php echo $value["ads_id"]; ?>" data-title="<?php echo $value["ads_title"]; ?>" >
                                    <div class="modal-user-story-add-footer-ads-list-item-title" ><?php echo $value["ads_title"]; ?></div>
                                    </div>
                                    <?php
                                }
                                }
                            ?>
                        </div>
                    </div>

                    <div class="modal-user-story-add-footer-location-list" >
                        <input type="text" class="form-control" placeholder="<?php echo $ULang->t("Укажите название города"); ?>">
                        <div class="modal-user-story-add-footer-location-list-search" >
                            <div data-name="<?php echo $ULang->t("Все города"); ?>" id-country="0" id-region="0" id-city="0" ><?php echo $ULang->t("Все города"); ?></div>
                            <?php
                              if(!$settings["region_id"]){
                                $get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_city`.city_default = '1' and `uni_country`.country_status = '1' order by city_count_view desc limit 15");
                                if(!count($get)){
                                   $get = getAll("SELECT * FROM uni_city INNER JOIN `uni_country` ON `uni_country`.country_id = `uni_city`.country_id WHERE `uni_country`.country_status = '1' order by city_count_view desc limit 15");
                                }
                              }else{
                                $get = getAll("SELECT * FROM uni_city WHERE region_id='{$settings["region_id"]}' order by city_count_view desc limit 15");
                              }

                              if($get){
                                foreach ($get as $key => $value) {
                                    ?>
                                    <div data-name="<?php echo $value["city_name"]; ?>" id-country="0" id-region="0" id-city="<?php echo $value["city_id"]; ?>" ><?php echo $value["city_name"]; ?></div>
                                    <?php
                                }
                              }
                            ?>
                        </div>
                    </div>

                    <div class="modal-user-story-add-footer-category-list" >
                        <input type="text" class="form-control" placeholder="<?php echo $ULang->t("Укажите название категории"); ?>">
                        <div class="modal-user-story-add-footer-category-list-search" >
                            <?php
                              $getCategories = $CategoryBoard->getCategories("where category_board_visible=1");
                              if($getCategories){
                                foreach ($getCategories["category_board_id_parent"][0] as $value) {
                                    ?>
                                    <div data-name="<?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?>" id-cat="<?php echo $value["category_board_id"]; ?>" ><?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?></div>
                                    <?php
                                }
                              }
                            ?>
                        </div>
                    </div>

                </div>

                <?php
                    if($settings["user_stories_paid_add"] && $settings["user_stories_price_add"] && !isset($_SESSION['profile']['tariff']['services']['stories'])){

                    if($settings["user_stories_free_add"]){

                        if($getUser['clients_count_story_publication'] >= $settings["user_stories_free_add"]){
                        ?>
                        <button class="button-style-custom btn-color-blue schema-color-button user-story-publication mt5" data-type="video" data-name="<?php echo $name; ?>" ><?php echo $ULang->t("Оплатить"); ?> <?php echo $Main->price($settings["user_stories_price_add"]); ?> <?php echo $ULang->t("и опубликовать"); ?></button>
                        <?php                                
                        }else{
                        ?>
                        <button class="button-style-custom btn-color-blue schema-color-button user-story-publication mt5" data-type="video" data-name="<?php echo $name; ?>" ><?php echo $ULang->t("Опубликовать"); ?></button>
                        <?php                                
                        }

                    }else{
                        ?>
                        <button class="button-style-custom btn-color-blue schema-color-button user-story-publication mt5" data-type="video" data-name="<?php echo $name; ?>" ><?php echo $ULang->t("Оплатить"); ?> <?php echo $Main->price($settings["user_stories_price_add"]); ?> <?php echo $ULang->t("и опубликовать"); ?></button>
                        <?php                              
                    }

                    }else{
                    ?>
                    <button class="button-style-custom btn-color-blue schema-color-button user-story-publication mt5" data-type="video" data-name="<?php echo $name; ?>" ><?php echo $ULang->t("Опубликовать"); ?></button>
                    <?php
                    }
                ?>
            </div>

            <?php

        }

    }

}

?>