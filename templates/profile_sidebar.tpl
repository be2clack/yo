<div class="user-avatar" >
 <div class="user-avatar-img" >
    <?php if($data["advanced"]){ ?>
    <span class="user-avatar-replace" > <i class="las la-sync-alt"></i> </span>
    <?php } ?>
    <img src="<?php echo $Profile->userAvatar($user, false); ?>" />
 </div>  
 <h4> <?php echo $Profile->name($user, false); ?> </h4>  
 <?php
    if($user["clients_type_person"] == 'company'){
        ?>
        <div class="user-card-company-name" ><?php echo $user["clients_name_company"]; ?></div>
        <?php
    }
    if($user["clients_verification_status"]){
        ?>
        <div class="user-card-verification-box">
            <span class="user-card-verification-status" ><?php echo $ULang->t("Профиль подтвержден"); ?></span>
            <div><i class="las la-check"></i> <?php echo $ULang->t("Телефон подтверждён"); ?></div> 
            <div><i class="las la-check"></i> <?php echo $ULang->t("Email подтверждён"); ?></div> 
            <div><i class="las la-check"></i> <?php echo $ULang->t("Документы и фото проверены"); ?></div>                              
        </div>
        <?php
    }else{
        if($data["advanced"]){
            $getUserVerifications = findOne("uni_clients_verifications", "user_id=?", [$_SESSION["profile"]["id"]]);
            if(!$getUserVerifications){
                ?>
                <span class="btn-custom-mini btn-color-green mb15 mt5 width100 open-modal" data-id-modal="modal-user-verification" ><?php echo $ULang->t("Подтвердить профиль"); ?></span>
                <?php
            }elseif($getUserVerifications["status"] == 2){
                ?>
                <span class="btn-custom-mini btn-color-green mb15 mt5 width100 open-modal" data-id-modal="modal-user-verification" ><?php echo $ULang->t("Подтвердить профиль"); ?></span>
                <?php
            }
        }
    }                        
 ?>
 <p><?php echo $ULang->t("На"); ?> <?php echo $ULang->t($settings["site_name"]); ?> <?php echo $ULang->t("с"); ?> <?php echo date("d.m.Y", strtotime($user["clients_datetime_add"])); ?></p>  

 <div class="board-view-stars">
     
   <?php
    $countReviews = (int)getOne("select count(*) as total from uni_clients_reviews where clients_reviews_id_user=? and clients_reviews_status=?", [$user["clients_id"],1])["total"];
   ?>
     
   <?php echo $data["ratings"]; ?>

   <a href="<?php echo _link( "user/" . $user["clients_id_hash"] . "/reviews" ); ?>" >(<?php echo $countReviews; ?>)</a>

   <div class="clr"></div>   

 </div>

</div>

<?php if($data["advanced"]){ ?>

<div class="user-menu d-none d-lg-block" >

   <?php
        echo $Profile->outUserMenu($data,$user["clients_balance"]);                       
   ?>

</div>

<div class="d-none d-lg-block" >
<hr>
<p class="small-title mt0" ><?php echo $ULang->t("Поделиться профилем"); ?></p>
<?php echo $data["share"]; ?>
</div>

<form id="user-form-avatar" ><input type="file" name="image" /></form>
<?php }else{

?>
<div class="mt15" ></div>
<?php

if( $action != "reviews" ){
    ?>
    <a class="button-style-custom color-blue mb5" href="<?php echo _link("user/".$user["clients_id_hash"]."/reviews"); ?>"> <span><?php echo $ULang->t("Отзывы"); ?>(<?php echo count($data["reviews"]); ?>)</span> </a>
    <?php
}else{
    ?>
    <span <?php echo $Main->modalAuth( ["attr"=>'class="button-style-custom color-green open-modal mt15 mb5" data-id-modal="modal-user-add-review"', "class"=>"button-style-custom color-green mt15 mb5"] ); ?> ><?php echo $ULang->t("Добавить отзыв"); ?></span>
    <?php
}

?>
<div class="sidebar-style-link mt20" >
<?php

if(!$data["locked"]){ ?>

<div <?php echo $Main->modalAuth(["attr"=>'class="open-modal" data-id-modal="modal-confirm-block"']); ?> ><?php echo $ULang->t("Заблокировать"); ?></div>

<?php }else{ ?>

<div <?php echo $Main->modalAuth( ["attr"=>'class="profile-user-block" data-id="'.$user["clients_id"].'" ', "class"=>"profile-user-block"] ); ?> ><?php echo $ULang->t("Разблокировать"); ?></div>

<?php } ?>

<div <?php echo $Main->modalAuth( ["attr"=>'class="init-complaint open-modal mt10" data-id-modal="modal-complaint" data-id="'.$user["clients_id"].'" data-action="user" ', "class"=>"mt10"] ); ?> ><?php echo $ULang->t("Пожаловаться"); ?></div>

</div>

<?php

} ?>