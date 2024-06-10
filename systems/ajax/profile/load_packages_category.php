<?php 

$array_cats = [];
$totalPayment = 0;

$cat_id = (int)$_POST['cat_id'];

$getPackagesCategories = $Profile->getPackagesCategories();

$cat_id = $CategoryBoard->firstIdsBuild($cat_id, $getPackagesCategories);

$ids_cat = $CategoryBoard->reverseId($getPackagesCategories,$cat_id);

?>

<select name="cat_id" class="form-control">
    <?php
       if( $getPackagesCategories["category_board_id_parent"][0] ){
           foreach ($getPackagesCategories["category_board_id_parent"][0] as $value) {
              ?>
              <option <?php if( in_array($value["category_board_id"], explode(",", $ids_cat)) ){ echo 'selected=""'; } ?> value="<?php echo $value["category_board_id"]; ?>" > <?php echo $ULang->t( $value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ); ?> </option>
              <?php
           }
       }
    ?>          
</select>

<?php

if($cat_id){

	 if($ids_cat){
	   $ids_cat = explode(',', $ids_cat);
	   foreach ($ids_cat as $key => $value) {
	      $array_cats[$value] = $ids_cat[ $key + 1 ];
	   }
	 }

	 foreach ($array_cats as $id_main_cat => $id_sub_cat) {
		  
	      $parent_list = '';

	      if($getPackagesCategories["category_board_id_parent"][$id_main_cat]){

	          foreach ($getPackagesCategories["category_board_id_parent"][$id_main_cat] as $key => $parent_value) {

	            if($parent_value["category_board_id"] == $id_sub_cat){ $active = 'selected=""'; }else{ $active = ''; }
	               
	            $parent_list .= '<option '.$active.' value="'.$parent_value["category_board_id"].'"> '.$ULang->t($parent_value["category_board_name"], [ "table" => "uni_category_board", "field" => "category_board_name" ] ).' </option>';

	          }

	          ?>
			  <select name="cat_id" class="form-control mt10">
	              <?php echo $parent_list; ?>     
	          </select>			          
	          <?php

	      }

	 }


	if(!$getPackagesCategories["category_board_id_parent"][$cat_id]){

		$getPackagesCategory = getAll("select * from uni_ads_packages_categories where cat_id=?", [$cat_id]);

		if($getPackagesCategory){

		?>
			<h5 class="mb20 mt30" > <strong><?php echo $ULang->t("Выберите пакет объявлений"); ?></strong> </h5>

			<?php
			  
			  foreach ($getPackagesCategory as $key => $value) {
			  	$getPackage = findOne("uni_ads_packages", "id=?", [$value["package_id"]]);
			  	if($key == 0){
			  		$totalPayment = $getPackage["price_ad"] * $getPackage["count_ad"];
			  	}
			  	if($getPackage){
				     ?>
				     <div class="modal-ad-packages-item <?php if($key == 0){ echo 'active'; } ?>" data-amount="<?php echo $Main->price($getPackage["price_ad"] * $getPackage["count_ad"]); ?>" data-id="<?php echo $getPackage["id"]; ?>" >
				       <div class="row" >
				          <div class="col-lg-1" >
				                <div class="custom-control custom-radio">
				                    <input type="radio" class="custom-control-input" <?php if($key == 0){ echo 'checked=""'; } ?> name="package" id="package<?php echo $getPackage["id"]; ?>" value="<?php echo $getPackage["id"]; ?>">
				                    <label class="custom-control-label" for="package<?php echo $getPackage["id"]; ?>"></label>
				                </div>
				          </div>                
				          <div class="col-lg-6" >
				                <h6> <strong><?php echo $getPackage["count_ad"]; ?> <?php echo ending($getPackage["count_ad"], $ULang->t('размещение'), $ULang->t('размещения'), $ULang->t('размещений')); ?></strong> </h6>
				                <span><?php echo $ULang->t("Пакет на"); ?> <?php echo $getPackage["period"]; ?> <?php echo ending($getPackage["period"], $ULang->t('день'), $ULang->t('дня'), $ULang->t('дней')); ?></span>
				          </div>
				          <div class="col-lg-5" >
				                <div class="modal-ad-packages-item-price" >
				                  <h6> 
				                  	<?php if($getPackagesCategories["category_board_id"][$cat_id]["category_board_price"]){ ?>
				                  	<span><?php echo $Main->price($getPackagesCategories["category_board_id"][$cat_id]["category_board_price"] * $getPackage["count_ad"]); ?></span>
				                  	<?php } ?>				                  	
				                  	<strong><?php echo $Main->price($getPackage["price_ad"] * $getPackage["count_ad"]); ?></strong>
				                  </h6>
				                </div>
				                <div>

				                	<?php echo $Main->price($getPackage["price_ad"]); ?> <?php echo $ULang->t("за объявление"); ?>
				                		
				                </div>
				          </div>                   
				       </div> 
				     </div>           
				     <?php
			 	}
			  }
			?>

			<h5 class="mb20 mt30" > <strong><?php echo $ULang->t("Выберите способ оплаты"); ?></strong> </h5>

			<div class="modal-ad-packages-payment" >
			  
			  <div data-type="balance" >
			  	<div> <div class="ad-packages-payment-logo" ><img src="<?php echo $settings["path_tpl_image"] . '/wallet.png' ?>" ></div> <div><?php echo $ULang->t("Кошелек"); ?></div></div>
			  </div>

            <?php
            if($settings["payment_variant"]){
            $payments = getAll("select * from uni_payments where id IN(".$settings["payment_variant"].") order by sorting desc");

              if($payments){
                 foreach ($payments as $key => $value) {
                     ?>
                     <div data-type="<?php echo $value["code"]; ?>" >
                     	<div> <div class="ad-packages-payment-logo-payment" ><img src="<?php echo Exists($config["media"]["other"], $value["logo"], $config["media"]["no_image"]); ?>" ></div> <div><?php echo $value["name"]; ?></div></div>
                     </div>
                     <?php
                 }
              }
            }
            ?>

			</div>

			<div class="modal-ad-packages-action-payment-box" >
				<button class="btn-custom btn-color-blue mt25 width100 modal-ad-packages-action-payment"><?php echo $ULang->t("Оплатить"); ?> <span class="modal-ad-packages-amount-payment" ><?php echo $Main->price($totalPayment); ?></span> </button>
			</div>

			<input type="hidden" name="cat_id" value="<?php echo $cat_id; ?>" >

		<?php

		}

	}

}
?>