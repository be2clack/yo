<div class="catalog-list-options toggle-list-options catalog-list-options-active" >
    <span class="catalog-list-options-single" >
        
        <?php if( $getCategoryBoard["category_board_id"][ $data["current_category"]["category_board_id"] ]["category_board_secure"] && $settings["secure_status"] ){ ?>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="filter[secure]" <?php if($data["param_filter"]["filter"]["secure"]){ echo 'checked=""'; } ?> id="flsecure" value="1" >
            <label class="custom-control-label" for="flsecure"><?php echo $ULang->t("Безопасная сделка"); ?></label>
        </div>
        <?php } ?>
        
        <?php if( $getCategoryBoard["category_board_id"][ $data["current_category"]["category_board_id"] ]["category_board_auction"] ){ ?>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="filter[auction]" <?php if($data["param_filter"]["filter"]["auction"]){ echo 'checked=""'; } ?> id="flauction" value="1" >
            <label class="custom-control-label" for="flauction"><?php echo $ULang->t("Аукцион"); ?></label>
        </div>
        <?php } ?>
        
        <?php if( $getCategoryBoard["category_board_id"][ $data["current_category"]["category_board_id"] ]["category_board_online_view"] ){ ?>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="filter[online_view]" <?php if($data["param_filter"]["filter"]["online_view"]){ echo 'checked=""'; } ?> id="online_view" value="1" >
            <label class="custom-control-label" for="online_view"><?php echo $ULang->t("Онлайн-показ"); ?></label>
        </div>
        <?php } ?>

        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="filter[vip]" <?php if($data["param_filter"]["filter"]["vip"]){ echo 'checked=""'; } ?> id="flvip" value="1" >
            <label class="custom-control-label" for="flvip"><?php echo $ULang->t("VIP"); ?></label>
        </div>        

        <?php if( $getCategoryBoard["category_board_id"][ $data["current_category"]["category_board_id"] ]["category_board_booking"] ){ ?>

            <?php
            if( $getCategoryBoard["category_board_id"][ $data["current_category"]["category_board_id"] ]["category_board_booking_variant"] == 0 ){
            ?>

                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="filter[booking]" <?php if($data["param_filter"]["filter"]["booking"]){ echo 'checked=""'; } ?> id="booking_variant" value="1" >
                    <label class="custom-control-label" for="booking_variant"><?php echo $ULang->t("Онлайн-бронирование"); ?></label>
                </div>

                <div class="catalog-list-options toggle-list-options catalog-list-options-active" >

                    <span class="catalog-list-options-name" >
                    <?php echo $ULang->t("Даты"); ?>  
                    <i class="las la-angle-down"></i>
                    </span>
                    
                    <div class="catalog-list-options-content" >

                        <div class="catalog-list-options-content" >
                            <div class="filter-input" >
                              <div><span><?php echo $ULang->t("с"); ?></span><input type="text" class="catalog-change-date-from" name="filter[date][start]" value="<?php if($data["param_filter"]["filter"]["date"]["start"]) echo date("d.m.Y", strtotime($data["param_filter"]["filter"]["date"]["start"])); ?>" /></div>
                              <div><span><?php echo $ULang->t("по"); ?></span><input type="text" class="catalog-change-date-to" name="filter[date][end]" value="<?php if($data["param_filter"]["filter"]["date"]["end"]) echo date("d.m.Y", strtotime($data["param_filter"]["filter"]["date"]["end"])); ?>" /></div>
                            </div>                    
                        </div>

                    </div>

                </div>

            <?php }elseif( $getCategoryBoard["category_board_id"][ $data["current_category"]["category_board_id"] ]["category_board_booking_variant"] == 1 ){ ?>

                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="filter[booking]" <?php if($data["param_filter"]["filter"]["booking"]){ echo 'checked=""'; } ?> id="booking_variant" value="1" >
                    <label class="custom-control-label" for="booking_variant"><?php echo $ULang->t("Онлайн-аренда"); ?></label>
                </div>

                <div class="catalog-list-options toggle-list-options catalog-list-options-active" >

                    <span class="catalog-list-options-name" >
                    <?php echo $ULang->t("Даты"); ?>  
                    <i class="las la-angle-down"></i>
                    </span>
                    
                    <div class="catalog-list-options-content" >

                        <div class="catalog-list-options-content" >
                            <div class="filter-input" >
                              <div><span><?php echo $ULang->t("с"); ?></span><input type="text" class="catalog-change-date-from" name="filter[date][start]" value="<?php if($data["param_filter"]["filter"]["date"]["start"]) echo date("d.m.Y", strtotime($data["param_filter"]["filter"]["date"]["start"])); ?>" /></div>
                              <div><span><?php echo $ULang->t("по"); ?></span><input type="text" class="catalog-change-date-to" name="filter[date][end]" value="<?php if($data["param_filter"]["filter"]["date"]["end"]) echo date("d.m.Y", strtotime($data["param_filter"]["filter"]["date"]["end"])); ?>" /></div>
                            </div>                    
                        </div>

                    </div>

                </div>

            <?php } ?>

        <?php } ?>

    </span>
</div>

<?php 
if($data["current_category"]["category_board_id"]){
    if( $getCategoryBoard["category_board_id"][ $data["current_category"]["category_board_id"] ]["category_board_display_price"] ){ 
    ?>
    <div class="catalog-list-options toggle-list-options <?php if( $data["param_filter"]["filter"]["price"]["from"] || $data["param_filter"]["filter"]["price"]["to"] ){ echo 'catalog-list-options-active'; } ?>" >

        <span class="catalog-list-options-name" >
        <?php
            echo $Main->nameInputPrice($getCategoryBoard["category_board_id"][ $data["current_category"]["category_board_id"] ]["category_board_variant_price_id"]);
        ?> 
        <i class="las la-angle-down"></i>
        </span>
        
        <div class="catalog-list-options-content" >
        <div class="filter-input" >
          <div><span><?php echo $ULang->t("от"); ?></span><input type="text" class="inputNumber" name="filter[price][from]" value="<?php if($data["param_filter"]["filter"]["price"]["from"]) echo round($data["param_filter"]["filter"]["price"]["from"],2); ?>" /></div>
          <div><span><?php echo $ULang->t("до"); ?></span><input type="text" class="inputNumber" name="filter[price][to]" value="<?php if($data["param_filter"]["filter"]["price"]["to"]) echo round($data["param_filter"]["filter"]["price"]["to"],2); ?>" /></div>
        </div>
        </div>

    </div>
    <?php 
    } 
}else{
    ?>
    <div class="catalog-list-options toggle-list-options <?php if( $data["param_filter"]["filter"]["price"]["from"] || $data["param_filter"]["filter"]["price"]["to"] ){ echo 'catalog-list-options-active'; } ?>" >

        <span class="catalog-list-options-name" >
        <?php echo $ULang->t("Цена"); ?>  
        <i class="las la-angle-down"></i>
        </span>
        
        <div class="catalog-list-options-content" >
        <div class="filter-input" >
          <div><span><?php echo $ULang->t("от"); ?></span><input type="text" class="inputNumber" name="filter[price][from]" value="<?php if($data["param_filter"]["filter"]["price"]["from"]) echo round($data["param_filter"]["filter"]["price"]["from"],2); ?>" /></div>
          <div><span><?php echo $ULang->t("до"); ?></span><input type="text" class="inputNumber" name="filter[price][to]" value="<?php if($data["param_filter"]["filter"]["price"]["to"]) echo round($data["param_filter"]["filter"]["price"]["to"],2); ?>" /></div>
        </div>
        </div>

    </div>    
    <?php
}

?>

<div class="catalog-list-options toggle-list-options <?php if($data["param_filter"]["filter"]["period"]){ echo 'catalog-list-options-active'; } ?>" >
    <span class="catalog-list-options-name" ><?php echo $ULang->t("Срок размещения"); ?> <i class="las la-angle-down"></i></span>
    
    <div class="catalog-list-options-content" >
      
        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" name="filter[period]" <?php if($data["param_filter"]["filter"]["period"] == 1){ echo 'checked=""'; } ?> id="flPeriod1" value="1" >
            <label class="custom-control-label" for="flPeriod1"><?php echo $ULang->t("За 24 часа"); ?></label>
        </div>                        

        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" name="filter[period]" <?php if($data["param_filter"]["filter"]["period"] == 7){ echo 'checked=""'; } ?> id="flPeriod2" value="7" >
            <label class="custom-control-label" for="flPeriod2"><?php echo $ULang->t("За 7 дней"); ?></label>
        </div>

        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" name="filter[period]" <?php if(!$data["param_filter"]["filter"]["period"]){ echo 'checked=""'; } ?> id="flPeriod3" value="" >
            <label class="custom-control-label" for="flPeriod3"><?php echo $ULang->t("За все время"); ?></label>
        </div>

    </div>

</div>

<div class="catalog-more-filter <?php if($data["count_filters"] > 4){ ?>catalog-more-filter-action <?php } ?>" >
<?php echo $Filters->load_filters_catalog( $data["current_category"]["category_board_id"] , $data["param_filter"], "filters_catalog" ); ?>

<?php if($data["count_filters"] > 4){ ?>
<div class="catalog-more-filter-show" > <?php echo $ULang->t("Все фильтры"); ?> <i class="las la-angle-down"></i> </div>
<div class="catalog-more-filter-hide" > <?php echo $ULang->t("Скрыть"); ?> <i class="las la-angle-up"></i> </div>
<?php } ?>

</div>

<div class="form-filter-submit" >
    <button class="btn-custom btn-color-blue submit-filter-form"  > <?php echo $ULang->t("Применить"); ?> </button>
    <?php if($data["param_filter"]["filter"] && !$data["filter"]){ ?>
    <button class="btn-custom action-clear-filter btn-color-light"> <?php echo $ULang->t("Сбросить фильтры"); ?> </button>
    <?php } ?>
</div>

<input type="hidden" name="id_c" value="<?php echo $data["current_category"]["category_board_id"]; ?>" >
<input type="hidden" name="filter[sort]" value="<?php echo clear($data["param_filter"]["filter"]["sort"]); ?>" >
<input type="hidden" name="id_u" value="<?php echo $data["shop"]["clients_shops_id_user"]; ?>" >

<?php if($data["param_filter"]["search"]){ ?>
<input type="hidden" name="search" value="<?php echo clear($data["param_filter"]["search"]); ?>" >
<?php } ?>