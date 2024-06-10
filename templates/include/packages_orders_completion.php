
<?php
$getCategory = findOne("uni_category_board", "category_board_id=?", [$value["cat_id"]]);
$getPackage = findOne("uni_ads_packages", "id=?", [$value["package_id"]]);

?>

<div class="profile-item-packages-orders" >

	<div>
		<h6><?php echo $value["count_ad"]; ?> <?php echo ending($value["count_ad"], $ULang->t('размещение'), $ULang->t('размещения'), $ULang->t('размещений')) ?> / <?php echo $ULang->t( $getCategory["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?></h6>
	</div>

	<div>
		
		<span><?php echo $ULang->t("Оплачен"); ?> <?php echo date("d.m.Y", strtotime($value["create_date"])); ?> <span style="color: red;" ><?php echo $ULang->t("Истек срок"); ?></span> </span>

		<div class="progress mt10">
		  <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
		</div>

	</div>

	<div class="row" >
		<div class="col-lg-12 text-right" >
			<div class="btn-custom-mini btn-color-blue-light mt15 open-modal modal-packages-payment-copy" data-id-modal="modal-ad-packages" data-catid="<?php echo $value["cat_id"]; ?>" ><?php echo $ULang->t("Купить похожий пакет"); ?></div>
		</div>
	</div>
	
</div>