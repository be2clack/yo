<div class="sidebar-shop-filter" >

<?php
if($data["shop"]["clients_shops_status"] == 0){
    ?>
    <div class="sidebar-shop-info-box" >
        <?php echo $ULang->t("Магазин на модерации"); ?>
    </div>        
    <?php
}elseif($data["shop"]["clients_shops_status"] == 2){
    ?>
    <div class="sidebar-shop-info-box" >
        <?php echo $ULang->t("Магазин отклонен по причине: "); ?> <?php echo $data["shop"]["clients_shops_status_note"]; ?>
    </div>        
    <?php
}
?>

<div class="sidebar-filter" >
<form class="form-filter" >

<?php echo $Filters->outFormFilters('shop',['data'=>$data, 'categories'=>$getCategoryBoard]); ?>

</form>
</div>


<div class="sidebar-style-link mt20" >

    <?php if( $data["shop"]["clients_shops_id_user"] != $_SESSION["profile"]["id"] ){ ?>

        <?php if(!$data["locked"]){ ?>
        <div <?php echo $Main->modalAuth( ["attr"=>'class="open-modal" data-id-modal="modal-confirm-block"', "class"=>""] ); ?> ><?php echo $ULang->t("Заблокировать"); ?></div>
        <?php }else{ ?>
        <div <?php echo $Main->modalAuth( ["attr"=>'class="profile-user-block" data-id="'.$data["user"]["clients_id"].'" ', "class"=>"profile-user-block"] ); ?> ><?php echo $ULang->t("Разблокировать"); ?></div>
        <?php } ?>

        <div <?php echo $Main->modalAuth(["attr"=>'class="init-complaint mt10 open-modal" data-id-modal="modal-complaint" data-id="'.$data["user"]["clients_id"].'" data-action="user" ', "class"=>"mt10"]); ?> ><?php echo $ULang->t("Пожаловаться"); ?></div>

    <?php } ?>

    <p class="small-title mt15 mb10" ><?php echo $ULang->t("Поделиться"); ?></p>
    <?php echo $data["share"]; ?>

</div>

</div>

