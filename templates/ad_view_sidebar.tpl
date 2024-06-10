<div class="board-view-right" >

<div class="board-view-sidebar" >
    
    <?php
        echo $Ads->outAdViewPrice( ["data" => $data["ad"]] );
        echo $Ads->adSidebar( $data ); 
    ?>

</div>

<div class="board-view-user" >

      <?php echo $Profile->cardUser($data); ?>

</div>

<div <?php echo $Main->modalAuth( ["attr"=>'class="complain-toggle init-complaint text-center open-modal mb20" data-id-modal="modal-complaint" data-id="'.$data["ad"]["ads_id"].'" data-action="ad"', "class"=>"mb20 complain-toggle text-center"] ); ?> > <span><?php echo $ULang->t("Пожаловаться"); ?></span> </div>

<div class="view-list-status-box" >
<?php
if($data["ad"]["ads_auction"]){
    ?>
    <div class="view-list-status-promo ad-view-promo-status-auction" >

          <h5><?php echo $ULang->t("Аукцион"); ?></h5>

          <?php echo $Ads->adAuctionSidebar( $data ); ?>

    </div>
    <?php
}

if($Ads->getStatusBooking($data["ad"])){
    ?>
    <div class="view-list-status-promo ad-view-promo-status-booking" >

        <div class="row" >
            <div class="col-lg-3 col-3" >
               
               <span class="view-list-status-promo-icon" ></span>

            </div>
            <div class="col-lg-9 col-9" >
              
              <h5><?php echo $ULang->t("Онлайн-аренда"); ?></h5>

              <?php if($data["ad"]["category_board_booking_variant"] == 0){ ?>
                <p><?php echo $ULang->t("Можно забронировать онлайн"); ?></p>
              <?php }else{ ?>
                <p><?php echo $ULang->t("Можно взять в аренду онлайн"); ?></p>
              <?php } ?>

            </div>
        </div>

    </div>
    <?php
}

if($data["ad"]["ads_online_view"]){
    ?>
    <div class="view-list-status-promo ad-view-promo-status-online" >

        <div class="row" >
            <div class="col-lg-3 col-3" >
               
               <span class="view-list-status-promo-icon" ></span>

            </div>
            <div class="col-lg-9 col-9" >
              
              <h5><?php echo $ULang->t("Онлайн-показ"); ?></h5>

              <p><?php echo $ULang->t("Можно посмотреть по видеосвязи"); ?></p>

              <span class="view-list-status-promo-button open-modal" data-id-modal="modal-ad-online-view" ><?php echo $ULang->t("Подробнее"); ?> <i class="las la-arrow-right"></i></span>

            </div>
        </div>

    </div>
    <?php
}  

?>
</div>

<?php if( $data["ad"]["ads_status"] != 0 ){ ?>

    <?php if($_SESSION["profile"]["id"] == $data["ad"]["ads_id_user"]){ ?>
    <div class="board-view-sidebar-box-stimulate" >
     
     <p class="box-stimulate-title" ><?php echo $ULang->t("Кол-во показов"); ?></p>

     <p class="box-stimulate-count" ><?php echo $Ads->getDisplayView($data["ad"]["ads_id"], date("Y-m-d")); ?></p>
     
        <?php if( !$data["order_service_ids"] && $data["ad"]["ads_status"] == 1 && strtotime($data["ad"]["ads_period_publication"]) > time() ){ ?>
            <span class="btn-custom-mini btn-color-blue mt10 open-modal" data-id-modal="modal-top-views" ><?php echo $ULang->t("Как повысить?"); ?></span> 
        <?php } ?>

    </div>
    <?php } ?>

<?php } ?>

<?php
    echo $Banners->out( ["position_name"=>"ad_view_sidebar", "current_id_cat"=>$data["ad"]["category_board_id"], "categories"=>$getCategoryBoard] ); 
?>

</div>