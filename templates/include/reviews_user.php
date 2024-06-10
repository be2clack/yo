<?php
$getAd = $Ads->get("ads_id=?", [ $value["clients_reviews_id_ad"] ]);
?>
<div class="user-review-item" >
	<div class="row" >
		<div class="col-lg-8 col-8 col-md-6 col-sm-6" >
			<div class="user-review-item-avatar" >
				<div class="user-review-item-avatar1" ><img src="<?php echo $Profile->userAvatar($value); ?>"></div>
				<div class="user-review-item-avatar2" > <a href="<?php echo _link("user/".$value["clients_id_hash"]); ?>" ><?php echo $Profile->name($value); ?></a> <span><?php echo datetime_format($value["clients_reviews_date"]); ?></span> 

				<?php 
				if($getAd){ 
                   
                   if( $value["clients_reviews_status_result"] == 1 ){

                   	   ?>
                   	   <p class="user-review-item-title" ><?php echo $ULang->t("Сделка состоялась"); ?> «<?php echo $getAd["ads_title"]; ?>»</p>
                   	   <?php

                   }elseif( $value["clients_reviews_status_result"] == 2 ){

                   	   ?>
                   	   <p class="user-review-item-title" ><?php echo $ULang->t("Сделка сорвалась"); ?> «<?php echo $getAd["ads_title"]; ?>»</p>
                   	   <?php

                   }elseif( $value["clients_reviews_status_result"] == 3 ){

                   	   ?>
                   	   <p class="user-review-item-title" ><?php echo $ULang->t("Не договорились"); ?> «<?php echo $getAd["ads_title"]; ?>»</p>
                   	   <?php

                   }elseif( $value["clients_reviews_status_result"] == 4 ){

                   	   ?>
                   	   <p class="user-review-item-title" ><?php echo $ULang->t("Не удалось связаться"); ?> «<?php echo $getAd["ads_title"]; ?>»</p>
                   	   <?php

                   }

			    } 
			    ?>
					
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-4 col-md-6 col-sm-6 text-right" >
			<div class="star-rating" >
			<?php echo $Profile->outRating( 0, $value["clients_reviews_rating"] ); ?>
			</div>
		</div>
	</div>
	<p class="mt15" ><?php echo nl2br($value["clients_reviews_text"]); ?></p>
    
    <?php if($value["clients_reviews_files"]){ ?>
	<div class="user-review-item-attach lightgallery" >
		<?php
          $files = $value["clients_reviews_files"] ? explode(",", $value["clients_reviews_files"]) : [];
          if($files){
          	 foreach ($files as $image) {
          	 	if( file_exists( $config["basePath"] . "/" . $config["media"]["user_attach"] . "/" . $image ) ){
	          	 	?>
	          	 	<a href="<?php echo $config["urlPath"] . "/" . $config["media"]["user_attach"] . "/" . $image; ?>"><img class="image-autofocus" src="<?php echo $config["urlPath"] . "/" . $config["media"]["user_attach"] . "/" . $image; ?>" ></a>
	          	 	<?php
          	    }
          	 }
          }
		?>
	</div>
    <?php } ?>

	<?php if($value["clients_reviews_from_id_user"] == intval($_SESSION["profile"]["id"])){ ?>
	<div class="text-right" >
		<span class="user-review-item-delete open-modal" data-id-modal="modal-confirm-delete-review" data-id="<?php echo $value["clients_reviews_id"]; ?>" ><i class="las la-trash"></i> <?php echo $ULang->t("Удалить"); ?></span>
	</div>
    <?php } ?>
</div>