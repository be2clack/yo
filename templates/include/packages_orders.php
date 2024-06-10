
<?php
$getCategory = findOne("uni_category_board", "category_board_id=?", [$value["cat_id"]]);
$getPackage = findOne("uni_ads_packages", "id=?", [$value["package_id"]]);

$dateDiff = $Ads->dateDiff($value["completion_date"]);
$progress = ((time() - strtotime($value["create_date"])) / (strtotime($value["completion_date"]) - strtotime($value["create_date"]))) * 100;
$countAds = (int)getOne("select count(*) as total from uni_ads_packages_placements where user_id=? and cat_id=? and order_id=?", [$_SESSION['profile']['id'], $value["cat_id"],$value["id"]])["total"];

?>

<div class="profile-item-packages-orders" >

	<div>
		<h6><?php echo $value["count_ad"]; ?> <?php echo ending($value["count_ad"], $ULang->t('размещение'), $ULang->t('размещения'), $ULang->t('размещений')) ?> / <?php echo $ULang->t( $getCategory["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?></h6>
	</div>

	<div>
		
		<span><?php echo $ULang->t("Оплачен"); ?> <?php echo date("d.m.Y", strtotime($value["create_date"])); ?> <?php echo $ULang->t("Осталось"); ?>: <?php echo $dateDiff["day"]; ?> <?php echo ending($dateDiff["day"], $ULang->t('день'), $ULang->t('дня'), $ULang->t('дней')); ?></span>

		<div class="progress mt10">
		  <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $progress; ?>%" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
		</div>

	</div>

	<div class="row" >
		<div class="col-lg-6" >
			<div class="mt10" ><?php echo $ULang->t("Использовано"); ?> <?php echo $countAds; ?> <?php echo $ULang->t("из"); ?> <?php echo $value["count_ad"]; ?></div>
		</div>
		<div class="col-lg-6 text-right" >
			<div class="btn-custom-mini btn-color-blue-light mt15 open-modal modal-packages-payment-copy" data-id-modal="modal-ad-packages" data-catid="<?php echo $value["cat_id"]; ?>" ><?php echo $ULang->t("Купить похожий пакет"); ?></div>
		</div>
	</div>
	
</div>