
<h3 class="mb35 user-title" > <strong><?php echo $data["page_name"]; ?></strong> </h3>

<div class="user-block-promo" >
 
 <div class="row no-gutters" >
    <div class="col-lg-8" >
      <div class="user-block-promo-content" >
        <p>
           <?php echo $ULang->t("Превратите свой профиль в полноценный онлайн-магазин с рекламной обложкой, удобными фильтрами, персональными страницами и поиском"); ?>
        </p>

        <?php

        if($_SESSION["profile"]["tariff"]){
            if(isset($_SESSION["profile"]["tariff"]["services"]["shop"])){
                $getUserShop = findOne("uni_clients_shops", "clients_shops_id_user=?", [$_SESSION["profile"]["id"]]);
                if(!$getUserShop){
                    ?>
                    <div class="btn-custom btn-color-blue mt15 display-inline profile-open-shop-user" ><?php echo $ULang->t("Открыть магазин"); ?></div>
                    <?php
                }
            }
        }else{
            ?>
            <a class="btn-custom btn-color-blue mt15 display-inline" href="<?php echo _link('tariffs'); ?>" ><?php echo $ULang->t("Подключить тариф"); ?></a>
            <?php                                
        }

        ?>

      </div>
    </div>
    <div class="col-lg-4 d-none d-lg-block" style="text-align: center;" >
        <img src="<?php echo $settings["path_tpl_image"]; ?>/shop_3345733.png" height="230px" >
    </div>
 </div>

</div>

<?